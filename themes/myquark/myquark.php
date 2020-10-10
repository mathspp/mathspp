<?php
namespace Grav\Theme;

use Grav\Common\Theme;

class Myquark extends Quark
{
    // cf. https://learn.getgrav.org/16/cookbook/twig-recipes#custom-twig-filter-function
    public function onTwigInitialized()
    {
        parent::onTwigInitialized(); // cf. https://stackoverflow.com/a/3754969/2828287

        $this->grav['twig']->twig()->addFilter(
            new \Twig_SimpleFilter('to_problem', [$this, 'toProblem'])
        );
        $this->grav['twig']->twig()->addFilter(
            new \Twig_SimpleFilter('to_solution', [$this, 'toSolution'])
        );
    }

    // custom function that takes the URL of a blog solution and turns it into the problem URL.
    public function toProblem($solution_url) {
        $parts = explode("/", $solution_url);
        $key = array_search("s", $parts);
        unset($parts[$key]);
        return implode("/", $parts);
    }

    // custom function that takes the URL of a blog problem and turns it into the solution URL.
    public function toSolution($problem_url) {
        $parts = explode("/", $problem_url);
        $nparts = count($parts);
        array_splice($parts, $nparts-1, 0, array("s"));
        return implode("/", $parts);
    }
}
?>