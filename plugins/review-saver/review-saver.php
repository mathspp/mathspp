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

        // Handle file uploads
        $this->moveUploadedFiles($form, $path);

        $this->grav['log']->info("New review page created: {$directory}");
    }

        private function moveUploadedFiles($form, $destination): void
        {
            // Access uploaded files
            $files = $form->filesByElement;
        
            if (isset($files['data.image'])) {
                foreach ($files['data.image'] as $file) {
                    $uploadedFile = $file['file'];
                    $filename = $uploadedFile['name'];
                    $tempPath = $uploadedFile['tmp_name'];
        
                    // Move the file to the destination directory
                    $destinationPath = "{$destination}/{$filename}";
                    if (move_uploaded_file($tempPath, $destinationPath)) {
                        $this->grav['log']->info("File moved to: {$destinationPath}");
                    } else {
                        $this->grav['log']->error("Failed to move file: {$filename}");
                    }
                }
            } else {
                $this->grav['log']->info("No images found...");
            }
        }
}


