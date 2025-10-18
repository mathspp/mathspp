<?php
namespace Grav\Plugin;

use Composer\Autoload\ClassLoader;
use Grav\Common\Plugin;

/**
 * Class UseBricklayerPlugin
 * @package Grav\Plugin
 */
class UseBricklayerPlugin extends Plugin
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
            'onPluginsInitialized' => ['onPluginsInitialized', 0],
        ];
    }

    /**
     * Composer autoload
     *
     * @return ClassLoader
     */
    public function autoload(): ClassLoader
    {
        return require __DIR__ . '/vendor/autoload.php';
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

        $this->grav['log']->info('[use-bricklayer] Plugin booted (onPluginsInitialized)');

        // Respect config merged with per-page overrides
        if (!$this->config->get('plugins.use-bricklayer.active')) {
            $this->grav['log']->info('[use-bricklayer] Skipping because not active.');
            return;
        }

        // Only add assets when rendering the site
        $this->enable([
            'onTwigSiteVariables' => ['onTwigSiteVariables', 0],
        ]);

        $this->grav['log']->info('[use-bricklayer] onTwigSiteVariables enabled');
    }

    public function onTwigSiteVariables(): void
    {
        $assets = $this->grav['assets'];

        $this->grav['log']->info('[use-bricklayer] in onTwigSiteVariables.');

        // CSS
        $assets->addCss('theme://css/bricklayer.css');

        // JS (polyfill first, then Bricklayer)
        $assets->addJs('theme://js/scopedQuerySelectorShim.min.js');
        $assets->addJs('theme://js/bricklayer.min.js');
    }
}
