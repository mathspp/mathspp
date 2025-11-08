<?php
namespace Grav\Theme;

use Grav\Theme\Myquark\Twig\PythonVaultTwigExtension;

class Myquark extends Quark
{
    public static function getSubscribedEvents()
    {
        return parent::getSubscribedEvents() + [
            'onTwigExtensions' => ['onTwigExtensions', 0],
        ];
    }

    public function onTwigExtensions(): void
    {
        require_once __DIR__ . '/twig/PythonVaultTwigExtension.php';

        $this->grav['twig']->twig()->addExtension(new PythonVaultTwigExtension());
    }
}
?>