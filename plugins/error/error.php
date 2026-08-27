<?php
namespace Grav\Plugin;

use Composer\Autoload\ClassLoader;
use Grav\Common\Page\Interfaces\PageInterface;
use Grav\Common\Plugin;
use Grav\Common\Page\Page;
use Grav\Common\Page\Pages;
use Grav\Common\Page\Types;
use RocketTheme\Toolbox\Event\Event;

class ErrorPlugin extends Plugin
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onCliInitialize' => [
                ['autoload', 100000],
            ],
            'onPageNotFound' => [
                ['onPageNotFound', 0]
            ],
            'onGetPageTemplates' => [
                ['onGetPageTemplates', 0]
            ],
            'onTwigTemplatePaths' => [
                ['onTwigTemplatePaths', -10]
            ],
            'onDisplayErrorPage.404'=> [
                ['onDisplayErrorPage404', -1]
            ]
        ];
    }

    /**
     * [onPluginsInitialized:100000] Composer autoload.
     *
     * @return ClassLoader
     */
    public function autoload(): ClassLoader
    {
        return require __DIR__ . '/vendor/autoload.php';
    }

    /**
     * @param Event $event
     */
    public function onDisplayErrorPage404(Event $event): void
    {
        if ($this->isAdmin()) {
            return;
        }

        $event['page'] = $this->getErrorPage();
        $event->stopPropagation();
    }

    /**
     * Display error page if no page was found for the current route.
     *
     * @param Event $event
     */
    public function onPageNotFound(Event $event): void
    {
        $event->page = $this->getErrorPage();
        $event->stopPropagation();
    }

    /**
     * @return PageInterface
     * @throws \Exception
     */
    public function getErrorPage(): PageInterface
    {
        /** @var Pages $pages */
        $pages = $this->grav['pages'];

        // Try to load user error page. Passing `$all = true` is deliberate: a
        // custom error page is normally `routable: false`, so the routable check
        // has to be skipped. The trade-off is that a folder holding no page file
        // at all (say it only contains a stray `500.html.php`) still comes back
        // as a contentless stub, which would then win over the built-in page
        // below and render blank -- with a 200 status, since the stub has no
        // frontmatter to set one. `isPage()` is what separates a real page from a
        // bare directory in both the regular and the Flex page engines;
        // `exists()` additionally catches a file deleted after indexing. (#49)
        $page = $pages->dispatch($this->config->get('plugins.error.routes.404', '/error'), true);
        if (!$page || !$page->isPage() || !$page->exists()) {
            // If none provided use built in error page.
            $language = $this->grav['language'];
            $page = new Page;
            $page->init(new \SplFileInfo(__DIR__ . '/pages/error.md'));
            $page->title($language->translate('PLUGIN_ERROR.ERROR') . ' ' . $page->header()->http_response_code);

            // The page body uses a [translate] shortcode for the message, the
            // safe in-content replacement for Twig (Grav 2 disables Twig in
            // content by default — #47). Shortcode Core normally renders it, but
            // the 404 is the last line of defense and must never show a raw tag,
            // so if Shortcode Core isn't available we supply the translated
            // message directly. Custom page content still takes precedence.
            $shortcodesAvailable = $this->config->get('plugins.shortcode-core.enabled');
            $isDefaultBody = strpos((string) $page->rawMarkdown(), '[translate]') !== false;
            if (!$shortcodesAvailable && $isDefaultBody) {
                $page->content($language->translate('PLUGIN_ERROR.ERROR_MESSAGE'));
            }
        }

        // Login page may not have the correct Cache-Control header set, force no-store for the proxies.
        $cacheControl = $page->cacheControl();
        if (!$cacheControl) {
            $page->cacheControl('private, no-cache, must-revalidate');
        }

        return $page;
    }

    /**
     * Add page template types.
     */
    public function onGetPageTemplates(Event $event): void
    {
        /** @var Types $types */
        $types = $event->types;
        $types->register('error');
    }

    /**
     * Add current directory to twig lookup paths.
     */
    public function onTwigTemplatePaths(): void
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }
}
