<?php
namespace Grav\Plugin\Console;

use Grav\Console\ConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class LogCommand
 *
 * @package Grav\Plugin\Console
 */
class LogCommand extends ConsoleCommand
{
    /**
     * @var string
     */
    protected $logfile;
    /**
     * @var array
     */
    protected $options = [];
    /**
     * @var array
     */
    protected $colors = [
        'DEBUG'     => 'green',
        'INFO'      => 'cyan',
        'NOTICE'    => 'yellow',
        'WARNING'   => 'yellow',
        'ERROR'     => 'red',
        'CRITICAL'  => 'red',
        'ALERT'     => 'red',
        'EMERGENCY' => 'magenta'
    ];

    /**
     *
     */
    protected function configure()
    {
        $this->logfile = LOG_DIR . 'grav.log';
        $this
            ->setName("log")
            ->setDescription("Outputs the Error Log")
            ->addOption(
                'trace',
                't',
                InputOption::VALUE_NONE,
                'Include the errors stack trace in the output'
            )
            ->addOption(
                'limit',
                'l',
                InputArgument::OPTIONAL,
                'Outputs only the last X amount of errors. Use as --limit 10 / -l 10 [default 5]',
                5
            )
            ->setHelp('The <info>log</info> outputs the Errors Log in Console')
        ;
    }

    /**
     * @return int|null|void
     */
    protected function serve()
    {
        $this->options = [
            'trace' => $this->input->getOption('trace'),
            'limit' => $this->input->getOption('limit')
        ];

        if (!file_exists($this->logfile)) {
            $this->output->writeln("\n" . "Log file not found." . "\n");
            exit;
        }

        $log   = file_get_contents($this->logfile);
        $lines = explode("\n", $log);

        if (!is_numeric($this->options['limit'])) {
            $this->options['limit'] = 5;
        }

        $lines = array_slice($lines, -($this->options['limit'] + 1));

        foreach ($lines as $line) {
            $parsed = $this->parseLine($line);
            if ($parsed !== null) {
                $this->output->writeln($parsed);
            }
        }
    }

    /**
     * @param string $line
     *
     * @return null|string
     */
    protected function parseLine($line)
    {
        // Skip empty lines
        if (empty(trim($line))) {
            return null;
        }

        $bit   = explode(': ', $line);
        
        // Check if we have at least the basic structure
        if (count($bit) < 2) {
            return null;
        }
        
        $line1 = explode('] ', $bit[0]);

        if (!isset($line1[0]) || !$line1[0]) {
            return null;
        }
        
        // Check if we have the log type
        if (!isset($line1[1])) {
            return null;
        }

        // Handle both formats: "Message - Trace" and just "Message"
        $line2 = explode(' - ', $bit[1]);

        $date  = $line1[0] . ']';
        $type  = str_replace('grav.', '', $line1[1]);
        
        // Check if the log type has a color defined
        if (!isset($this->colors[$type])) {
            $color = 'white';  // Default color for unknown types
        } else {
            $color = $this->colors[$type];
        }
        
        // Get the full message (everything after the log level)
        // Join back with ': ' in case the message itself contains colons
        $fullMessage = implode(': ', array_slice($bit, 1));
        
        // If there's a dash separator, use the part before it, otherwise use the full message
        if (count($line2) > 1) {
            $error = $line2[0];
        } else {
            $error = $fullMessage;
        }
        $trace = implode(': ', array_slice($bit, 2));

        $output = [];

        $output[] = '';
        $output[] = '<cyan>' . $date . '</cyan>';
        $output[] = sprintf('  <%s>%s</%s> <white>' . $error . '</white>', $color, $type, $color);

        if ($this->options['trace']) {
            $output[] = '  <white>TRACE:</white> ';
            $output[] = '  ' . $trace;
        }

        $output[] = '<cyan>' . str_repeat('-', strlen($date)) . '</cyan>';

        return implode("\n", $output);
    }
}

