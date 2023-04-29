<?php
namespace Grav\Plugin;

use Composer\Autoload\ClassLoader;
use Grav\Common\Page\Interfaces\PageInterface;
use Grav\Common\Page\Pages;
use Grav\Common\Plugin;
use Grav\Common\Page\Collection;
use Grav\Common\Taxonomy;
use Grav\Common\Uri;
use Grav\Common\Utils;
use Grav\Common\Yaml;
use RocketTheme\Toolbox\Event\Event;

class ArchivesPlugin extends Plugin
{
    /**
     * @var string
     */
    protected $month_taxonomy_value;

    /**
     * @var string
     */
    protected $year_taxonomy_value;

    /**
     * @var string
     */
    protected $month_taxonomy_name;

    /**
     * @var string
     */
    protected $year_taxonomy_name;

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => [
                ['autoload', 100001],
                ['onPluginsInitialized', 0]
            ]
        ];
    }

    /**
     * [onPluginsInitialized:100000] Composer autoload.
     *
     * @return ClassLoader
     */
    public function autoload()
    {
        return require __DIR__ . '/vendor/autoload.php';
    }

    /**
     * Initialize configuration
     */
    public function onPluginsInitialized()
    {
        if ($this->isAdmin()) {
            $this->active = false;
            return;
        }

        $this->month_taxonomy_value = $this->config->get('plugins.archives.taxonomy_values.month');
        $this->year_taxonomy_value = $this->config->get('plugins.archives.taxonomy_values.year');

        $this->month_taxonomy_name = $this->config->get('plugins.archives.taxonomy_names.month');
        $this->year_taxonomy_name = $this->config->get('plugins.archives.taxonomy_names.year');

        // Dynamically add the needed taxonomy types to the taxonomies config
        $taxonomy_config = array_merge((array)$this->config->get('site.taxonomies'), [$this->month_taxonomy_name, $this->year_taxonomy_name]);
        $this->config->set('site.taxonomies', $taxonomy_config);

        $this->enable([
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
            'onPageProcessed' => ['onPageProcessed', 0],
            'onTwigSiteVariables' => ['onTwigSiteVariables', 0]
        ]);
    }

    /**
     * Add current directory to twig lookup paths.
     */
    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

    /**
     * Add
     *
     * @param Event $event
     */
    public function onPageProcessed(Event $event)
    {
        /** @var PageInterface $page */
        $page = $event['page'];
        if (!$page->isPage()) {
            return;
        }

        $taxonomy = $page->taxonomy();

        // track month taxonomy using month_taxonomy_value format:
        if (!isset($taxonomy[$this->month_taxonomy_name])) {
            $taxonomy[$this->month_taxonomy_name] = array(strtolower(date($this->month_taxonomy_value, $page->date())));
        }

        // track year taxonomy using year_taxonomy_value format:
        if (!isset($taxonomy[$this->year_taxonomy_name])) {
            $taxonomy[$this->year_taxonomy_name] = array(date($this->year_taxonomy_value, $page->date()));
        }

        // set the modified taxonomy back on the page object
        $page->taxonomy($taxonomy);
    }

    /**
     * Set needed variables to display archives.
     */
    public function onTwigSiteVariables()
    {
        /** @var PageInterface $page */
        $page = $this->grav['page'];

        // If a page exists merge the archive config if set
        if ($page) {
            $this->config->set('plugins.archives', $this->mergeConfig($page));
        }

        // See if there is page-specific configuration set (new in Archives 2.0)
        $page_specific_config = $this->config->get('plugins.archives.page_specific_config');
        $archives = $archives_url = null;

        if ($page && is_array($page_specific_config)) {
            foreach ($page_specific_config as $page_config) {
                // Does the page config match route of this current page
                if (isset($page_config['route']) && $this->isValidPageRoute($page, $page_config['route'])) {
                    $filters = $page_config['filters'] ?? (array) $this->config->get('plugins.archives.filters');

                    // get around limitation of no YAML filtering support in list field
                    if (is_string($filters)) {
                        $filters = Yaml::parse($filters);
                    }

                    $operator = $page_config['filter_combinator'] ?? $this->config->get('plugins.archives.filter_combinator');
                    $order = [
                        'by' => $page_config['order_by'] ?? $this->config->get('plugins.archives.order.by'),
                        'dir' => $page_config['order_dir'] ?? $this->config->get('plugins.archives.order.dir')
                    ];
                    $archives = $this->getArchives((array)$filters, $operator, $order);
                    $archives_url = $this->grav['base_url_absolute'] . $page_config['route'];
                    break;
                }
            }
        } else {
            // get the plugin filters setting
            $filters = (array) $this->config->get('plugins.archives.filters');
            $operator = $this->config->get('plugins.archives.filter_combinator');
            $order = $this->config->get('plugins.archives.order');
            $archives = $this->getArchives((array)$filters, $operator, $order);
        }

        // add the archives_start date to the twig variables
        $this->grav['twig']->twig_vars['archives_show_count'] = $this->config->get('plugins.archives.show_count');
        $this->grav['twig']->twig_vars['archives_data'] = $archives;
        $this->grav['twig']->twig_vars['archives_url'] = $archives_url;
    }

    /** Something like this should be in Page object in future */
    protected function isValidPageRoute(PageInterface $page, $route)
    {
        $uri = $this->grav['uri'];
        $page_routes = [$page->route(), $page->rawRoute()];
        $page_routes[] = str_replace($page->canonical(false), $uri->rootUrl(true), '');
        $page_routes = array_merge($page_routes, $page->routeAliases());

        foreach ($page_routes as $proute) {
            if (Utils::startsWith($proute, $route, false)) {
                return true;
            }
        }
        return false;
    }

    protected function getArchives($filters, $operator, $order)
    {
        $order_by = $order['by'] ?? 'date';
        $order_dir = $order['dir'] ?? 'desc';

        /** @var Pages $pages */
        $pages = $this->grav['pages'];

        /** @var PageInterface $page */
        $page = $this->grav['page'];

        /** @var Taxonomy $taxonomy_map */
        $taxonomy_map = $this->grav['taxonomy'];
        $taxonomies = [];
        $find_taxonomy = [];
        $archives = [];
        $start_date = time();


        $new_approach = false;
        $collection = null;
        $page_filter = null;

        if (!$filters || (count($filters) === 1 && !reset($filters))){
            $collection = $pages->all();
        } else {
            foreach ($filters as $key => $filter) {
                // flatten item if it's wrapped in an array
                if (is_int($key)) {
                    if (is_array($filter)) {
                        $key = key($filter);
                        $filter = $filter[$key];
                    } else {
                        $key = $filter;
                    }
                }
                // see if the filter uses the new 'items-type' syntax
                if ($key === '@self' || $key === 'self@') {
                    $new_approach = true;
                } elseif ($key === '@page' || $key === 'page@') {
                    $page_filter = $filter;
                } elseif ($key === '@taxonomy' || $key === 'taxonomy@') {
                    $taxonomies = $filter === false ? false : array_merge($taxonomies, (array) $filter);
                } else {
                    $find_taxonomy[$key] = $filter;
                }
            }
            if ($new_approach) {
                $collection = $page->children();
            } elseif ($page_filter) {
                $collection = $pages->find($page_filter)->children();
            } else {
                $collection = new Collection();
                $collection->append($taxonomy_map->findTaxonomy($find_taxonomy, $operator)->toArray());
            }
        }

        // reorder the collection based on settings
        $collection = $collection->order($order_by, $order_dir)->published();
        $date_format = $this->config->get('plugins.archives.date_display_format');
        // drop unpublished and un-routable pages
        $collection->published()->routable();

        // loop over new collection of pages that match filters
        foreach ($collection as $page) {
            // update the start date if the page date is older
            $start_date = $page->date() < $start_date ? $page->date() : $start_date;
            $archives[date($date_format, $page->date())][] = $page;
        }

        // slice the array to the limit you want
        $archives = array_slice($archives, 0, (int)$this->config->get('plugins.archives.limit'), is_string(reset($archives)) ? false : true );

        return $archives;
    }
}
