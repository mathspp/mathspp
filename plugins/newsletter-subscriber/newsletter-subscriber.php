<?php
namespace Grav\Plugin;

use Composer\Autoload\ClassLoader;
use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;


/**
 * Class NewsletterSubscriberPlugin
 * @package Grav\Plugin
 */
class NewsletterSubscriberPlugin extends Plugin
{
    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onPluginsInitialized' => [
                ['onPluginsInitialized', 0]
            ]
        ];
    }
    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized(): void
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }

        // Enable the main events we are interested in
        $this->enable([
            'onFormProcessed' => ['onFormProcessed', 0]
        ]);
    }

    public function onFormProcessed(Event $event): void
    {
        $form = $event['form'];

        $email = $form->value('email');
        $this->grav['log']->info("Triggered with {$email}");

        // Load the bearer token from the plugin config
        $token = $this->config->get('plugins.newsletter-subscriber.token');

        $payload = json_encode(['email' => $email]);

        $ch = curl_init('https://api.beehiiv.com/v2/publications/pub_fe58688a-209b-4a1b-b7c1-83c0c0e8fee5/subscriptions');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                "Authorization: Bearer $token"
            ],
            CURLOPT_POSTFIELDS => $payload,
        ]);

        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($response === false || $status >= 400) {
            // Log or handle the error
            $this->grav['log']->error("API submission failed: " . curl_error($ch));
        }

        curl_close($ch);
    }
}
