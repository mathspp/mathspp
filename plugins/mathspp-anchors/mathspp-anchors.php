<?php
namespace Grav\Plugin;

use Grav\Common\Assets;
use Grav\Common\Data;
use Grav\Common\Grav;
use Grav\Common\Page\Interfaces\PageInterface;
use Grav\Common\Plugin;
use Grav\Common\Utils;


/**
 * Class PageTOCPlugin
 * @package Grav\Plugin
 */
class MathsppAnchorsPlugin extends Plugin
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
    public function onPluginsInitialized()
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }

        // Enable the main event we are interested in
        $this->enable([
            'onPageContentProcessed'    => ['onPageContentProcessed', -20],
        ]);
    }

    public function onPageContentProcessed(Event $event)
    {
        /** @var PageInterface $page */
        $page = $event['page'];

        if ($this->grav['config']->get('plugins.mathspp-anchors.include_css')) {
            $this->grav['assets']->addCss('plugin://mathspp-anchors/assets/anchors.css');
        }

        $this->grav['assets']->addJs('plugin://mathspp-anchors/assets/anchors.js', ['group' => 'bottom', 'defer' => 'defer']);
    }
}
