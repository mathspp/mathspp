<?php
namespace Grav\Plugin\Shortcodes;

use Grav\Common\Inflector;
use Grav\Plugin\PageToc\UniqueSlugify;
use Grav\Plugin\PageTOCPlugin;
use Thunder\Shortcode\Shortcode\ProcessedShortcode;

class AnchorShortcode extends Shortcode
{
  public function init()
  {
    $this->shortcode->getRawHandlers()->add('anchor', function(ProcessedShortcode $sc) {

      $id = $this->cleanParam($sc->getParameter('id', $sc->getBbCode()));
      $tag = $this->cleanParam($sc->getParameter('tag'));
      $prefix = $this->cleanParam($sc->getParameter('prefix', PageTOCPlugin::configVar('anchors.slug_prefix')));
      $class = $this->cleanParam($sc->getParameter('class', 'inline-anchor'));
      $aria = PageTOCPlugin::configVar('anchors.aria');
      $content = $sc->getContent();

      $slugger = new UniqueSlugify();

      if (is_null($id)) {
          $id = $slugger->slugify(strip_tags($content));
      }

      if (isset($prefix)) {
          $id = $prefix . $id;
      }

      if ($tag) {
        $output = "<$tag id=\"$id\" class=\"$class\">$content</$tag>";
      } else {
        $output = "<a id=\"$id\" href=\"#$id\" class=\"$class\" aria-label=\"$aria\">$content</a>";
      }

      return $output;
    });
    $this->shortcode->getRawHandlers()->addAlias('#', 'anchor');
  }

  /**
   * @param $param
   * @return string
   */
  protected function cleanParam($param)
  {
    return trim(html_entity_decode($param), '"');
  }
}