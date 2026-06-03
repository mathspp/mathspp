<?php
namespace Grav\Plugin;

use Composer\Autoload\ClassLoader;
use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class MarkdownDetailsPlugin
 * @package Grav\Plugin
 */
class MarkdownDetailsPlugin extends Plugin
{

    protected $base_class;
    protected $title_class;
    protected $trigger_class;
    protected $content_class;
    protected $icon;
    protected $a11y;

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
                ['autoload', 100000], // TODO: Remove when plugin requires Grav >=1.7
                ['onPluginsInitialized', 0]
            ],
        ];
    }

    /**
    * Composer autoload.
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
        if ($this->isAdmin())
        {
            return;
        }

        // Enable the main events we are interested in
        $this->enable([
            // Put your main events here
            'onTwigTemplatePaths'   => ['onTwigTemplatePaths', 0],
            'onPageContentRaw'      => ['onPageContentRaw', 0],
        ]);
    }

    /**
     * Process Content before Grav's processing
     *
     * @param Event $event
     */
    public function onPageContentRaw(Event $event)
    {
        /** @var Page $page */
        $page = $event['page'];
        /** @var Twig $twig */
        $twig = $this->grav['twig'];
        /** @var Config $config */
        $config = $this->config->get( 'plugins.'.$this->name );

        // is there any content to process?
        $raw = $page->getRawContent();
        if ( $raw && preg_match_all( '/!>[\S\s]*?!@/', $raw, $instances ) )
        {
            $this->loadAssets();

            foreach ( $instances[0] as $instance )
            {
                $options = [];

                // sort out the title line
                preg_match( '/^!>(\s?\[(\w*)\]?)?\s*(.*)/', $instance, $titeMatch );
                $options['titleTag'] = ( $titeMatch[2] ) ? $titeMatch[2] : $config['default_title_tag'];
                $options['titleText'] = $titeMatch[3];

                // get the content part
                preg_match( '/!>.*\n([\S\s]*?)!@/', $instance, $contentMatch );
                $options['content'] = $contentMatch[1];

                // process the template
                $markup = $twig->processTemplate('partials/md-details.html.twig', $options );

                // replace the old content with the processed one
                $newRaw = str_replace( $instance, $markup, $page->getRawContent() );
                // save the new content
                $page->setRawContent( $newRaw );
                // $this->grav["debugger"]->addMessage($options['titleText'] );
            }
        }
    }

    /**
     * Add our assets
     */
    public function loadAssets()
    {
        /** @var Config $config */
        $config = $this->config->get( 'plugins.'.$this->name );

        // add CSS only if demanded
        if ( $config['built_in_css'] )
        {
            $this->grav['assets']->add('plugin://markdown-details/css/details.css');
        }
        // add JS only if a11y markup and built in JS is demanded
        if ( $config['a11y'] && $config['built_in_js'] )
        {
            $this->grav['assets']->add('plugin://markdown-details/js/details.js', ['group' => 'bottom']);
        }
    }

    /**
     * Add current directory to twig lookup paths.
     */
    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }
}
