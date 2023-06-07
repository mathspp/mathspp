<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Messenger\Bridge\Doctrine\Transport;

use Doctrine\DBAL\Connection as DBALConnection;
use Doctrine\DBAL\Driver\Exception as DriverException;
use Doctrine\DBAL\Driver\Result as DriverResult;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Doctrine\DBAL\LockMode;
use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Platforms\OraclePlatform;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Result;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Comparator;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaDiff;
use Doctrine\DBAL\Schema\Synchronizer\SchemaSynchronizer;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Messenger\Exception\InvalidArgumentException;
use Symfony\Component\Messenger\Exception\TransportException;
use Symfony\Contracts\Service\ResetInterface;

/**
 * @internal since Symfony 5.1
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class Connection implements ResetInterface
{
    protected const TABLE_OPTION_NAME = '_symfony_messenger_table_name';

    protected const DEFAULT_OPTIONS = [
        'table_name' => 'messenger_messages',
        'queue_name' => 'default',
        'redeliver_timeout' => 3600,
        'auto_setup' => true,
    ];

    /**
     * Configuration of the connection.
     *
     * Available options:
     *
     * * table_name: name of the table
     * * connection: name of the Doctrine's entity manager
     * * queue_name: name of the queue
     * * redeliver_timeout: Timeout before redeliver messages still in handling state (i.e: delivered_at is not null and message is still in table). Default: 3600
     * * auto_setup: Whether the table should be created automatically during send / get. Default: true
     */
    protected $configuration = [];
    protected $driverConnection;
    protected $queueEmptiedAt;
    private $schemaSynchronizer;
    private $autoSetup;

    public function __construct(array $configuration, DBALConnection $driverConnection, SchemaSynchronizer $schemaSynchronizer = null)
    {
        $this->configuration = array_replace_recursive(static::DEFAULT_OPTIONS, $configuration);
        $this->driverConnection = $driverConnection;
        $this->schemaSynchronizer = $schemaSynchronizer;
        $this->autoSetup = $this->configuration['auto_setup'];
    }

    public function reset()
    {
        $this->queueEmptiedAt = null;
    }

    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    public static function buildConfiguration(string $dsn, array $options = []): array
    {
        if (false === $components = parse_url($dsn)) {
            throw new InvalidArgumentException(sprintf('The given Doctrine Messenger DSN "%s" is invalid.', $dsn));
        }

        $query = [];
        if (isset($components['query'])) {
            parse_str($components['query'], $query);
        }

        $configuration = ['connection' => $components['host']];
        $configuration += $query + $options + static::DEFAULT_OPTIONS;

        $configuration['auto_setup'] = filter_var($configuration['auto_setup'], \FILTER_VALIDATE_BOOLEAN);

        // check for extra keys in options
        $optionsExtraKeys = array_diff(array_keys($options), array_keys(static::DEFAULT_OPTIONS));
        if (0 < \count($optionsExtraKeys)) {
            throw new InvalidArgumentException(sprintf('Unknown option found: [%s]. Allowed options are [%s].', implode(', ', $optionsExtraKeys), implode(', ', array_keys(static::DEFAULT_OPTIONS))));
        }

        // check for extra keys in options
        $queryExtraKeys = array_diff(array_keys($query), array_keys(static::DEFAULT_OPTIONS));
        if (0 < \count($queryExtraKeys)) {
            throw new InvalidArgumentException(sprintf('Unknown option found in DSN: [%s]. Allowed options are [%s].', implode(', ', $queryExtraKeys), implode(', ', array_keys(static::DEFAULT_OPTIONS))));
        }

        return $configuration;
    }

    /**
     * @param int $delay The delay in milliseconds
     *
     * @return string The inserted id
     *
     * @throws DBALException
     */
    public function send(string $body, array $headers, int $delay = 0): string
    {
        $now = new \DateTime();
        $availableAt = (clone $now)->modify(sprintf('+%d seconds', $delay / 1000));

        $queryBuilder = $this->driverConnection->createQueryBuilder()
            ->insert($this->configuration['table_name'])
            ->values([
                'body' => '?',
                'headers' => '?',
                'queue_name' => '?',
                'created_at' => '?',
                'available_at' => '?',
            ]);

        $this->executeStatement($queryBuilder->getSQL(), [
            $body,
            json_encode($headers),
            $this->configuration['queue_name'],
            $now,
            $availableAt,
        ], [
            null,
            null,
            null,
            Types::DATETIME_MUTABLE,
            Types::DATETIME_MUTABLE,
        ]);

        return $this->driverConnection->lastInsertId();
    }

    public function get(): ?array
    {
        if ($this->driverConnection->getDatabasePlatform() instanceof MySQLPlatform) {
            try {
                $this->driverConnection->delete($this->configuration['table_name'], ['delivered_at' => '9999-12-31 23:59:59']);
            } catch (DriverException $e) {
                // Ignore the exception
            }
        }

        get:
        $this->driverConnection->beginTransaction();
        try {
            $query = $this->createAvailableMessagesQueryBuilder()
                ->orderBy('available_at', 'ASC')
                ->setMaxResults(1);

            // Append pessimistic write lock to FROM clause if db platform supports it
            $sql = $query->getSQL();
            if (($fromPart = $query->getQueryPart('from')) &&
                ($table = $fromPart[0]['table'] ?? null) &&
                ($alias = $fromPart[0]['alias'] ?? null)
            ) {
                $fromClause = sprintf('%s %s', $table, $alias);
                $sql = str_replace(
                    sprintf('FROM %s WHERE', $fromClause),
                    sprintf('FROM %s WHERE', $this->driverConnection->getDatabasePlatform()->appendLockHint($fromClause, LockMode::PESSIMISTIC_WRITE)),
                    $sql
                );
            }

            // Wrap the rownum query in a sub-query to allow writelocks without ORA-02014 error
            if ($this->driverConnection->getDatabasePlatform() instanceof OraclePlatform) {
                $sql = str_replace('SELECT a.* FROM', 'SELECT a.id FROM', $sql);

                $wrappedQuery = $this->driverConnection->createQueryBuilder()
                    ->select(
                        'w.id AS "id", w.body AS "body", w.headers AS "headers", w.queue_name AS "queue_name", '.
                        'w.created_at AS "created_at", w.available_at AS "available_at", '.
                        'w.delivered_at AS "delivered_at"'
                    )
                    ->from($this->configuration['table_name'], 'w')
                    ->where('w.id IN('.$sql.')');

                $sql = $wrappedQuery->getSQL();
            }

            // use SELECT ... FOR UPDATE to lock table
            $stmt = $this->executeQuery(
                $sql.' '.$this->driverConnection->getDatabasePlatform()->getWriteLockSQL(),
                $query->getParameters(),
                $query->getParameterTypes()
            );
            $doctrineEnvelope = $stmt instanceof Result || $stmt instanceof DriverResult ? $stmt->fetchAssociative() : $stmt->fetch();

            if (false === $doctrineEnvelope) {
                $this->driverConnection->commit();
                $this->queueEmptiedAt = microtime(true) * 1000;

                return null;
            }
            // Postgres can "group" notifications having the same channel and payload
            // We need to be sure to empty the queue before blocking again
            $this->queueEmptiedAt = null;

            $doctrineEnvelope = $this->decodeEnvelopeHeaders($doctrineEnvelope);

            $queryBuilder = $this->driverConnection->createQueryBuilder()
                ->update($this->configuration['table_name'])
                ->set('delivered_at', '?')
                ->where('id = ?');
            $now = new \DateTime();
            $this->executeStatement($queryBuilder->getSQL(), [
                $now,
                $doctrineEnvelope['id'],
            ], [
                Types::DATETIME_MUTABLE,
            ]);

            $this->driverConnection->commit();

            return $doctrineEnvelope;
        } catch (\Throwable $e) {
            $this->driverConnection->rollBack();

            if ($this->autoSetup && $e instanceof TableNotFoundException) {
                $this->setup();
                goto get;
            }

            throw $e;
        }
    }

    public function ack(string $id): bool
    {
        try {
            if ($this->driverConnection->getDatabasePlatform() instanceof MySQLPlatform) {
                return $this->driverConnection->update($this->configuration['table_name'], ['delivered_at' => '9999-12-31 23:59:59'], ['id' => $id]) > 0;
            }

            return $this->driverConnection->delete($this->configuration['table_name'], ['id' => $id]) > 0;
        } catch (DBALException $exception) {
            throw new TransportException($exception->getMessage(), 0, $exception);
        }
    }

    public function reject(string $id): bool
    {
        try {
            if ($this->driverConnection->getDatabasePlatform() instanceof MySQLPlatform) {
                return $this->driverConnection->update($this->configuration['table_name'], ['delivered_at' => '9999-12-31 23:59:59'], ['id' => $id]) > 0;
            }

            return $this->driverConnection->delete($this->configuration['table_name'], ['id' => $id]) > 0;
        } catch (DBALException $exception) {
            throw new TransportException($exception->getMessage(), 0, $exception);
        }
    }

    public function setup(): void
    {
        $configuration = $this->driverConnection->getConfiguration();
        $assetFilter = $configuration->getSchemaAssetsFilter();
        $configuration->setSchemaAssetsFilter(null);
        $this->updateSchema();
        $configuration->setSchemaAssetsFilter($assetFilter);
        $this->autoSetup = false;
    }

    public function getMessageCount(): int
    {
        $queryBuilder = $this->createAvailableMessagesQueryBuilder()
            ->select('COUNT(m.id) as message_count')
            ->setMaxResults(1);

        $stmt = $this->executeQuery($queryBuilder->getSQL(), $queryBuilder->getParameters(), $queryBuilder->getParameterTypes());

        return $stmt instanceof Result || $stmt instanceof DriverResult ? $stmt->fetchOne() : $stmt->fetchColumn();
    }

    public function findAll(int $limit = null): array
    {
        $queryBuilder = $this->createAvailableMessagesQueryBuilder();
        if (null !== $limit) {
            $queryBuilder->setMaxResults($limit);
        }

        $stmt = $this->executeQuery($queryBuilder->getSQL(), $queryBuilder->getParameters(), $queryBuilder->getParameterTypes());
        $data = $stmt instanceof Result || $stmt instanceof DriverResult ? $stmt->fetchAllAssociative() : $stmt->fetchAll();

        return array_map(function ($doctrineEnvelope) {
            return $this->decodeEnvelopeHeaders($doctrineEnvelope);
        }, $data);
    }

    public function find($id): ?array
    {
        $queryBuilder = $this->createQueryBuilder()
            ->where('m.id = ? and m.queue_name = ?');

        $stmt = $this->executeQuery($queryBuilder->getSQL(), [$id, $this->configuration['queue_name']]);
        $data = $stmt instanceof Result || $stmt instanceof DriverResult ? $stmt->fetchAssociative() : $stmt->fetch();

        return false === $data ? null : $this->decodeEnvelopeHeaders($data);
    }

    /**
     * @internal
     */
    public function configureSchema(Schema $schema, DBALConnection $forConnection): void
    {
        // only update the schema for this connection
        if ($forConnection !== $this->driverConnection) {
            return;
        }

        if ($schema->hasTable($this->configuration['table_name'])) {
            return;
        }

        $this->addTableToSchema($schema);
    }

    /**
     * @internal
     */
    public function getExtraSetupSqlForTable(Table $createdTable): array
    {
        return [];
    }

    private function createAvailableMessagesQueryBuilder(): QueryBuilder
    {
        $now = new \DateTime();
        $redeliverLimit = (clone $now)->modify(sprintf('-%d seconds', $this->configuration['redeliver_timeout']));

        return $this->createQueryBuilder()
            ->where('m.delivered_at is null OR m.delivered_at < ?')
            ->andWhere('m.available_at <= ?')
            ->andWhere('m.queue_name = ?')
            ->setParameters([
                $redeliverLimit,
                $now,
                $this->configuration['queue_name'],
            ], [
                Types::DATETIME_MUTABLE,
                Types::DATETIME_MUTABLE,
            ]);
    }

    private function createQueryBuilder(): QueryBuilder
    {
        return $this->driverConnection->createQueryBuilder()
            ->select('m.*')
            ->from($this->configuration['table_name'], 'm');
    }

    private function executeQuery(string $sql, array $parameters = [], array $types = [])
    {
        try {
            $stmt = $this->driverConnection->executeQuery($sql, $parameters, $types);
        } catch (TableNotFoundException $e) {
            if ($this->driverConnection->isTransactionActive()) {
                throw $e;
            }

            // create table
            if ($this->autoSetup) {
                $this->setup();
            }
            $stmt = $this->driverConnection->executeQuery($sql, $parameters, $types);
        }

        return $stmt;
    }

    protected function executeStatement(string $sql, array $parameters = [], array $types = [])
    {
        try {
            if (method_exists($this->driverConnection, 'executeStatement')) {
                $stmt = $this->driverConnection->executeStatement($sql, $parameters, $types);
            } else {
                $stmt = $this->driverConnection->executeUpdate($sql, $parameters, $types);
            }
        } catch (TableNotFoundException $e) {
            if ($this->driverConnection->isTransactionActive()) {
                throw $e;
            }

            // create table
            if ($this->autoSetup) {
                $this->setup();
            }
            if (method_exists($this->driverConnection, 'executeStatement')) {
                $stmt = $this->driverConnection->executeStatement($sql, $parameters, $types);
            } else {
                $stmt = $this->driverConnection->executeUpdate($sql, $parameters, $types);
            }
        }

        return $stmt;
    }

    private function getSchema(): Schema
    {
        $schema = new Schema([], [], $this->createSchemaManager()->createSchemaConfig());
        $this->addTableToSchema($schema);

        return $schema;
    }

    private function addTableToSchema(Schema $schema): void
    {
        $table = $schema->createTable($this->configuration['table_name']);
        // add an internal option to mark that we created this & the non-namespaced table name
        $table->addOption(self::TABLE_OPTION_NAME, $this->configuration['table_name']);
        $table->addColumn('id', Types::BIGINT)
            ->setAutoincrement(true)
            ->setNotnull(true);
        $table->addColumn('body', Types::TEXT)
            ->setNotnull(true);
        $table->addColumn('headers', Types::TEXT)
            ->setNotnull(true);
        $table->addColumn('queue_name', Types::STRING)
            ->setLength(190) // MySQL 5.6 only supports 191 characters on an indexed column in utf8mb4 mode
            ->setNotnull(true);
        $table->addColumn('created_at', Types::DATETIME_MUTABLE)
            ->setNotnull(true);
        $table->addColumn('available_at', Types::DATETIME_MUTABLE)
            ->setNotnull(true);
        $table->addColumn('delivered_at', Types::DATETIME_MUTABLE)
            ->setNotnull(false);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['queue_name']);
        $table->addIndex(['available_at']);
        $table->addIndex(['delivered_at']);
    }

    private function decodeEnvelopeHeaders(array $doctrineEnvelope): array
    {
        $doctrineEnvelope['headers'] = json_decode($doctrineEnvelope['headers'], true);

        return $doctrineEnvelope;
    }

    private function updateSchema(): void
    {
        if (null !== $this->schemaSynchronizer) {
            $this->schemaSynchronizer->updateSchema($this->getSchema(), true);

            return;
        }

        $schemaManager = $this->createSchemaManager();
        $comparator = $this->createComparator($schemaManager);
        $schemaDiff = $this->compareSchemas($comparator, $schemaManager->createSchema(), $this->getSchema());

        foreach ($schemaDiff->toSaveSql($this->driverConnection->getDatabasePlatform()) as $sql) {
            if (method_exists($this->driverConnection, 'executeStatement')) {
                $this->driverConnection->executeStatement($sql);
            } else {
                $this->driverConnection->exec($sql);
            }
        }
    }

    private function createSchemaManager(): AbstractSchemaManager
    {
        return method_exists($this->driverConnection, 'createSchemaManager')
            ? $this->driverConnection->createSchemaManager()
            : $this->driverConnection->getSchemaManager();
    }

    private function createComparator(AbstractSchemaManager $schemaManager): Comparator
    {
        return method_exists($schemaManager, 'createComparator')
            ? $schemaManager->createComparator()
            : new Comparator();
    }

    private function compareSchemas(Comparator $comparator, Schema $from, Schema $to): SchemaDiff
    {
        return method_exists($comparator, 'compareSchemas')
            ? $comparator->compareSchemas($from, $to)
            : $comparator->compare($from, $to);
    }
}

if (!class_exists(\Symfony\Component\Messenger\Transport\Doctrine\Connection::class, false)) {
    class_alias(Connection::class, \Symfony\Component\Messenger\Transport\Doctrine\Connection::class);
}
