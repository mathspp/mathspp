<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;

class CookiesNoticePlugin extends Plugin
{
    public static function getSubscribedEvents() {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0],
        ];
    }

    public function onPluginsInitialized()
    {
        if ($this->isAdmin()) {
            $this->active = false;
            return;
        }

        $this->enable([
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
            'onTwigSiteVariables' => ['onTwigSiteVariables', 0],
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
     * if enabled on this page, load the JS + CSS theme.
     */
    public function onTwigSiteVariables()
    {
        if ($this->config->get('plugins.cookiesnotice.jqcookie')==true){
            $this->grav['assets']->addJs('plugin://cookiesnotice/assets/js/jquery.cookie.js');
        }

        $this->grav['assets']->addJs('plugin://cookiesnotice/assets/js/cookiesnotice.js');
        $this->grav['assets']->addCss('plugin://cookiesnotice/assets/css/cookiesnotice.css');

        if ($this->config->get('plugins.cookiesnotice.customcss')==true){
            $this->grav['assets']->addCss($this->config->get('plugins.cookiesnotice.urlcss'));
        }

        $twig = $this->grav['twig'];
        $twig->twig_vars['cookiesnotice_markup'] = $twig->twig->render('partials/cookiesnotice.html.twig', array(
            'cookiesnotice_position' => strtolower($this->config->get('plugins.cookiesnotice.position')),
            'cookiesnotice_url' => $this->config->get('plugins.cookiesnotice.url')
        ));
    }
}
