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
        $company_and_title = $data['company_and_title'] ?? '';
        $object = $data['object'] ?? 'other';
        $testimonial = $data['testimonial'] ?? '';
        $social = $data['social'] ?? '';

        if (trim($testimonial) === '') {
            $this->grav['log']->info("Skipping review creation for {$name}");
            return;
        }

        // Generate the slug (e.g., 20250117-1213-rodrigo-serr-o)
        $date = (new \DateTime())->format('Ymd-Hi');
        $slug = preg_replace('/[^a-z0-9]+/', '-', strtolower($name));
        $directory = "testimonials/{$date}-{$slug}";

        // Render the template
        $twig = $this->grav['twig'];
        $content = $twig->processTemplate('forms/review.md.twig', [
            'name' => $name,
            'company_and_title' => $company_and_title,
            'object' => $object,
            'testimonial' => $testimonial,
            'social' => $social,
        ]);

        // Create the new page
        $path = USER_DIR . "pages/{$directory}";
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $file = "{$path}/default.md";
        file_put_contents($file, $content);

        // Handle the headshot upload
        $this->moveUploadedFiles($form, $path);

        $this->grav['log']->info("New review page created: {$directory}");
    }

    private function moveUploadedFiles($form, $destination): void
    {
        // Access the uploaded file data from form values
        $headshotData = $form->value()['headshot'] ?? null;
    
        if ($headshotData && is_array($headshotData)) {
            foreach ($headshotData as $fileInfo) {
                $sourcePath = $fileInfo['path'] ?? null;
                $fileName = $fileInfo['name'] ?? null;
    
                if ($sourcePath && $fileName && file_exists($sourcePath)) {
                    $destinationPath = "{$destination}/{$fileName}";
    
                    // Move the file
                    if (rename($sourcePath, $destinationPath)) {
                        $this->grav['log']->info("File moved to: {$destinationPath}");
                    } else {
                        $this->grav['log']->error("Failed to move file from {$sourcePath} to {$destinationPath}");
                    }
                } else {
                    $this->grav['log']->error("Invalid file data or file does not exist: " . json_encode($fileInfo));
                }
            }
        } else {
            $this->grav['log']->error("No headshot files found in form values.");
        }
    }
    
}


