<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;
use RocketTheme\Toolbox\File\File;
use Symfony\Component\Yaml\Yaml;

/**
 * Class CountViewsPlugin
 * @package Grav\Plugin
 */
class CountViewsPlugin extends Plugin
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
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized()
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }

        // Enable the main event we are interested in
        $this->enable([
            'onPagesInitialized' => ['onPagesInitialized', 0],
            'onPageInitialized' => ['onPageInitialized', 0]
        ]);
    }

    // This loads the existing data into Twig
    public function onPagesInitialized(Event $e)
    {
        // Get count data file
        $config = $this->grav['config'];
        $locator = $this->grav['locator'];
        $path = $locator->findResource('user://data', true);
        $path .= DS.static::sanitize($this->grav['config']->get('plugins.count-views.datafile'));

        // Get page route
        $page = $this->grav['page'];
        $route = $page->route();
        if (empty($route)) $route = $this->config->get('plugins.error.routes.404', '/error');

        // Open data file
        $datafh = File::instance($path);
        $data = Yaml::parse($datafh->content());
        $datafh->free();

        // Load count data into a twig variable
        $this->grav['twig']->twig_vars['viewcounts'] = $data;
    }

    // This increments the counter
    public function onPageInitialized(Event $e)
    {
        // Get count data file
        $config = $this->grav['config'];
        $locator = $this->grav['locator'];
        $path = $locator->findResource('user://data', true);
        $path .= DS.static::sanitize($this->grav['config']->get('plugins.count-views.datafile'));

        // Get page route
        $page = $this->grav['page'];
        $route = $page->route();
        if (empty($route)) $route = $this->config->get('plugins.error.routes.404', '/error');

        // Open data file
        $datafh = File::instance($path);
        $datafh->lock();
        $data = Yaml::parse($datafh->content());
        if ($data === null) {
            $data = array();
        }

        // Record count
        if (array_key_exists($route, $data)) {
            $data[$route]++;
        } else {
            $data[$route] = 1;
        }

        // Save 
        $datafh->save(YAML::dump($data));
        $datafh->free();
    }

    private static function sanitize($fn) {
        $fn = trim($fn);
        $fn = str_replace('..', '', $fn);
        $fn = ltrim($fn, DS);
        $fn = str_replace(DS.DS, DS, $fn);
        return $fn;
    }
}
