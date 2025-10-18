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
            // We use onPageInitialized to merge per-page config _before_ we decide to enable hooks.
            'onPageInitialized'    => ['onPageInitialized', 0],
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
    }

    public function onPageInitialized(): void
    {
        if ($this->isAdmin()) {
            return;
        }

        $page = $this->grav['page'];
        $route = $page ? $page->route() : '(no page)';

        // Merge global + per-page config; result goes into plugins.use-bricklayer.*
        // This enables per-page frontmatter overrides under:
        // plugins:
        //   use-bricklayer:
        //     active: true
        $merged = $this->mergeConfig($page);
        $this->config->set('plugins.use-bricklayer', $merged);

        $active = (bool) $merged['active'] ?? false;

        $this->grav['log']->info(sprintf(
            '[use-bricklayer] onPageInitialized route="%s" active=%s',
            $route,
            $active ? 'true' : 'false'
        ));

        if (!$active) {
            // Don’t register site-variable hook if we’re not enabled for this request.
            return;
        }

        // Now that we know it's enabled for this page, register the assets hook.
        $this->enable([
            'onTwigSiteVariables' => ['onTwigSiteVariables', 0],
        ]);
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
