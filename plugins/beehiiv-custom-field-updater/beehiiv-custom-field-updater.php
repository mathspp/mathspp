<?php

namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class BeehiivCustomFieldUpdaterPlugin
 * @package Grav\Plugin
 */
class BeehiivCustomFieldUpdaterPlugin extends Plugin
{
    public static function getSubscribedEvents(): array
    {
        return [
            'onPluginsInitialized' => [
                ['onPluginsInitialized', 0],
            ],
        ];
    }

    public function onPluginsInitialized(): void
    {
        if ($this->isAdmin()) {
            return;
        }

        $this->enable([
            'onFormProcessed' => ['onFormProcessed', 0],
        ]);
    }

    public function onFormProcessed(Event $event): void
    {
        $action = $event['action'] ?? null;
        if ($action !== 'beehiiv-custom-field-updater') {
            return;
        }

        $form = $event['form'];

        $publicationId = $form->value('publication_id');
        $token = $this->config->get('plugins.beehiiv-custom-field-updater.token');
        $email = $this->grav['session']->__get('newsletter_subscriber.email');

        if (!$publicationId || !$token || !$email) {
            $this->grav['log']->error("BeehiivCustomFieldUpdater: bad publication ID ({$pub_id}), token, or subscriber email ({$email}).");
            return;
        }

        $customFields = $this->extractCustomFields($form->fields(), $form);

        if (!$customFields) {
            return;
        }

        $url = sprintf(
            'https://api.beehiiv.com/v2/publications/%s/subscriptions/by_email/%s',
            rawurlencode($publicationId),
            rawurlencode($email)
        );

        $payload = json_encode([
            'custom_fields' => $customFields,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        if ($payload === false) {
            $this->grav['log']->error('BeehiivCustomFieldUpdater: failed to JSON-encode payload.');
            return;
        }

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json',
                'Accept: application/json',
                'Content-Length: ' . strlen($payload),
            ],
            CURLOPT_TIMEOUT => 15,
        ]);

        $responseBody = curl_exec($ch);
        $curlError = curl_error($ch);
        $statusCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($responseBody === false) {
            $this->grav['log']->error(
                sprintf('BeehiivCustomFieldUpdater: cURL error while updating subscriber "%s": %s', $email, $curlError)
            );
            return;
        }

        if ($statusCode < 200 || $statusCode >= 300) {
            $this->grav['log']->error(
                sprintf(
                    'BeehiivCustomFieldUpdater: Beehiiv API returned HTTP %d for subscriber "%s". Response: %s',
                    $statusCode,
                    $email,
                    $responseBody
                )
            );
        }
    }

    /**
     * Recursively scans form field definitions and returns Beehiiv custom fields.
     *
     * Each item matches Beehiiv's expected custom_fields object format:
     * [
     *   ['name' => 'field_name', 'value' => 'field_value'],
     *   ...
     * ]
     */
    private function extractCustomFields(array $fields, $form): array
    {
        $customFields = [];

        foreach ($fields as $fieldName => $definition) {
            if (!is_array($definition)) {
                continue;
            }

            $beehiivCustomFieldName = $definition['attributes']['custom_field'] ?? null;

            if ($beehiivCustomFieldName) {
                $value = $form->value($fieldName);

                if ($value !== null) {
                    $customFields[] = [
                        'name' => $beehiivCustomFieldName,
                        'value' => $value,
                    ];
                }
            }

            if (isset($definition['fields']) && is_array($definition['fields'])) {
                $customFields = array_merge(
                    $customFields,
                    $this->extractCustomFields($definition['fields'], $form)
                );
            }
        }

        return $customFields;
    }
}
