<?php

namespace Grav\Theme\Myquark\Twig;

use Grav\Common\Page\Page;
use Grav\Common\Page\Medium\Medium;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PythonVaultTwigExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('python_vault_snippet_code', [$this, 'getSnippetCode']),
        ];
    }

    /**
     * @param mixed $source
     */
    public function getSnippetCode($source, string $filename = 'snippet.py'): string
    {
        $path = null;

        if ($source instanceof Medium) {
            if (method_exists($source, 'path')) {
                $path = $source->path();
            } elseif (property_exists($source, 'path')) {
                $path = $source->path;
            } elseif (method_exists($source, 'get')) {
                $path = $source->get('filepath');
            }
        } elseif ($source instanceof Page) {
            if (method_exists($source, 'path')) {
                $base = $source->path();
            } else {
                $base = property_exists($source, 'path') ? $source->path : null;
            }

            if ($base) {
                $path = rtrim($base, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;
            }
        } elseif (is_string($source)) {
            $path = $source;
        }

        if (!$path || !is_file($path) || !is_readable($path)) {
            return '';
        }

        $contents = file_get_contents($path);

        return $contents === false ? '' : $contents;
    }
}
