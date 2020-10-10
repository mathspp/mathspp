<?php
namespace Grav\Theme;

use Grav\Common\Theme;

class Myquark extends Quark
{
    // cf. https://learn.getgrav.org/16/cookbook/twig-recipes#custom-twig-filter-function
    public function onTwigInitialized(Event $e)
    {
        $this->grav['twig']->twig()->addFilter(
            new \Twig_SimpleFilter('to_problem', [$this, 'toProblem'])
        );
    }

    // custom function that takes the URL of a blog solution and turns it into the problem URL.
    public function toProblem($solution_url) {
        $parts = explode("/", problem_url);
        $key = array_search("s", $parts);
        unset($parts[$key]);
        return implode("/", $parts);
    }
}
?>