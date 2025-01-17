<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;
use Grav\Common\Page\Page;

class ReviewSaverPlugin extends Plugin
{
    public static function getSubscribedEvents(): array
    {
        return [
            'onFormProcessed' => ['onFormProcessed', 0],
        ];
    }

    public function onFormProcessed(Event $event): void
    {
        // Get form data
        $form = $event['form'];
        $data = $form->value();

        // Extract fields
        $name = $data['name'] ?? 'anonymous';
        $role = $data['role'] ?? '';
        $company = $data['company'] ?? '';
        $object = $data['object'] ?? 'other';
        $testimonial = $data['testimonial'] ?? '';

        // Generate the slug (e.g., 20250117-1213-rodrigo-serr-o)
        $date = (new \DateTime())->format('Ymd-Hi');
        $slug = preg_replace('/[^a-z0-9]+/', '-', strtolower($name));
        $directory = "reviews/{$date}-{$slug}";

        // Render the template
        $twig = $this->grav['twig'];
        $content = $twig->processTemplate('forms/review.md.twig', [
            'name' => $name,
            'role' => $role,
            'company' => $company,
            'object' => $object,
            'testimonial' => $testimonial,
        ]);

        // Create the new page
        $path = USER_DIR . "pages/{$directory}";
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $file = "{$path}/default.md";
        file_put_contents($file, $content);

        $this->grav['log']->info("New review page created: {$directory}");
    }
}
