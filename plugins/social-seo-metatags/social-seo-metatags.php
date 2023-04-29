<?php

namespace Grav\Plugin;

use Grav\Common\Media\Interfaces\MediaObjectInterface;
use Grav\Common\Page\Medium\MediumFactory;
use Grav\Common\Page\Medium\ImageMedium;
use Grav\Common\Page\Page;
use Grav\Common\Plugin;
use Grav\Common\Utils;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class SocialSEOMetaTagsPlugin
 * @package Grav\Plugin
 */
class SocialSEOMetaTagsPlugin extends Plugin
{

  /** ---------------------------
   * Private/protected properties
   * ----------------------------
   */

  /**
   * Instance of SocialSEOMetaTagsPlugin class
   *
   * @var object
   */
  private $desc;
  private $title;
  private $html_flags;

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

    if (!$this->isAdmin() && $this->config->get('plugins.social-seo-metatags.enabled')) {
      // TODO: Check when metadata are set to define this priority: Metadata shall be set before executing this plugin - Works with default configuration => To do later.
      $this->enable([
        'onPageInitialized' => ['onPageInitialized', 0]
      ]);
    }
  }

  /**
   * Function called on Grav Page initilized
   */
  public function onPageInitialized(Event $e)
  {
    /** @var $page Page */
    $page = $this->grav['page'];

    if ($this->config->get('plugins.social-seo-metatags.quote.convert_simple')) {
      $this->html_flags = ENT_QUOTES;
    } else {
      $this->html_flags = ENT_COMPAT;
    }

    //Get values
    $meta = $page->metadata(null);
    $this->desc = $this->sanitizeMarkdowns(strip_tags($page->summary()));
    $this->title = $this->sanitizeMarkdowns($this->grav['page']->title());

    //Pre-treatment fro description
    if (strlen($this->desc) > 160) {
      // Remove last (truncated) word and replace by ...
      $desc_temp = substr($this->desc, 0, 157);
      $this->desc = substr($desc_temp, 0, strrpos($desc_temp, ' ')) . "...";
    }
    if ($this->desc == "") {
      $this->desc = $this->config->get('site.metadata.description');
    }

    //Header description treatment
    if (isset($page->header()->description)) {
      $this->desc = $page->header()->description;
    }

    //Apply change
    $meta = $this->getSEOMetatags($meta);
    $meta = $this->getTwitterCardMetatags($meta);
    $meta = $this->getFacebookMetatags($meta);

    //Set new meta to the page
    $page->metadata($meta);

    // Build breadcrumb
    if ($this->config->get('plugins.social-seo-metatags.seo.breadcrumb')) {
      $this->buildBreadcrumb();
    }
  }

  /**
   * Build JSON-LD breadcrumb
   *
   * @doc https://developers.google.com/search/docs/advanced/structured-data/breadcrumb
   */
  private function buildBreadcrumb() {
    /** @var $page Page */
    $page = $this->grav["page"];
    /** @var @var $assets \Grav\Common\Assets */
    $assets = $this->grav['assets'];

    $inline = '{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [';

    // Current page
    $inline .= '{
    "@type": "ListItem",
    "position": 1,
    "name": "' . $page->title() . '",
    "item": "'. Utils::url($page->url(), true) . '"
  }';

    // Children
    $index = 2;
    foreach ($page->children() as $child) {
      $inline .= '{
    "@type": "ListItem",
    "position": "'. strval($index) .'",
    "name": "' . $child->title() . '",
    "item": "'. Utils::url($child->url(), true) . '"
  }';
      $index++;
    }
    $inline .= ']
}';

    $assets->addInlineJs($inline, null, 'bottom', 'application/ld+json');

  }

  private function getSEOMetatags($meta)
  {
    if ($this->config->get('plugins.social-seo-metatags.enabled')) {
      /** @var $page Page */
      $page = $this->grav["page"];
      $page_header = $page->header();
      $keywords = "";

      /**
       * SEO Description
       *
       * Build process:
       *   1. Define description from Summary.
       *   2. If Summary not defined, define description from site metadata configuration.
       *   3. If description from frontmatter header is defined, use it.
       *   4. If description from frontmatter header metadata (for each metatag) is defined, use it.
       **/
      if (!isset($page_header->metadata['description'])) {
        $meta['description']['name'] = 'description';
        $meta['description']['content'] = $this->desc;
      }

      /**
       *  SEO Keywords
       **/
      if (!isset($meta['keywords'])) {
        $length = $this->config->get('plugins.social-seo-metatags.seo.keywords.length');
        if ($length < 1) $length = 20;
        // From Taxomany
        if ($this->config->get('plugins.social-seo-metatags.seo.keywords.taxonomy.enabled')) {
          if (array_key_exists('category', $page->taxonomy())) {
            $categories = $page->taxonomy()['category'];
          } else {
            $categories = [];
          }
          if (array_key_exists('tag', $page->taxonomy())) {
            $tags = $page->taxonomy()['tag'];
          } else {
            $tags = [];
          }
          $taxonomy = array_merge($categories, $tags);
          $taxonomy = array_unique($taxonomy);
        } else {
          $taxonomy = [];
        }

        // From Page Content
        if ($this->config->get('plugins.social-seo-metatags.seo.keywords.page_content.enabled')) {
          $content = $page->getRawContent();
          $content = str_replace("\n", " ", $content);
          $matches = [];
          if (preg_match_all('|<strong>(.*)</strong>|U', $content, $matches) > 0) {
            $strong_words = $matches[1];
          } else {
            $strong_words = [];
          }
          if (preg_match_all('|<em>(.*)</em>|U', $content, $matches) > 0) {
            $em_words = $matches[1];
          } else {
            $em_words = [];
          }
          $content_words = array_merge($strong_words, $em_words);
          $content_words = $this->cleanKeywords($content_words);
          $content_words = array_unique($content_words);
        } else {
          $content_words = [];
        }

        if ((count($taxonomy) > 0) || (count($content_words) > 0)) {
          $keywords_tab = array_merge($taxonomy, $content_words);
          $keywords_tab = array_unique($keywords_tab);
          $keywords_tab = array_slice($keywords_tab, 0, $length);
          $keywords = join(',', $keywords_tab);
        }

        if ($keywords != "") {
          $meta['keywords']['name'] = 'keywords';
          $meta['keywords']['content'] = strip_tags($keywords);
        }
      }

      /**
       *  SEO Robots
       **/
      if (!isset($meta['robots'])) {
        switch ($this->config->get('plugins.social-seo-metatags.seo.robots')) {
          case "index_follow":
            $meta['robots']['name'] = 'robots';
            $meta['robots']['content'] = 'index, follow';
            break;
          case "noindex_follow":
            $meta['robots']['name'] = 'robots';
            $meta['robots']['content'] = 'noindex, follow';
            break;
          case "index_nofollow":
            $meta['robots']['name'] = 'robots';
            $meta['robots']['content'] = 'index, nofollow';
            break;
          case "noindex_nofollow":
            $meta['robots']['name'] = 'robots';
            $meta['robots']['content'] = 'noindex, nofollow';
            break;
          case "noarchive":
            $meta['robots']['name'] = 'robots';
            $meta['robots']['content'] = 'noarchive';
            break;
          case "noodp":
            $meta['robots']['name'] = 'robots';
            $meta['robots']['content'] = 'noodp';
            break;
          case "nosnippet":
            $meta['robots']['name'] = 'robots';
            $meta['robots']['content'] = 'nosnippet';
            break;
          case "noarchive_noodp":
            $meta['robots']['name'] = 'robots';
            $meta['robots']['content'] = 'noarchive, noodp';
            break;
          default:
            // Without metatag
            break;
        }
      }
    }
    return $meta;
  }

  /**
   * Get the first available image in the Page or its children.
   *
   * @return string|null path of default image if it exists
   */
  private function getFirstImage(): ?string
  {
    $page = $this->grav['page'];
    /* @var $page Page */

    $image = null;

    // Check if Antimatter theme header_image_file field is defined
    if (isset($page->header()->header_image_file)) {
      $antimatterFile = $page->path() . '/' . $page->header()->header_image_file;
      if (is_file($antimatterFile)) {
        $image = MediumFactory::fromFile($antimatterFile);
      }
    }

    // Get first image from medias
    if ($image === null) {
      if (!empty($page->value('media.image'))) {
        // Get images for the current page.
        $images = $page->media()->images();

      } elseif (!empty($page->collection())) {
        // Get images for the children of the current pages.

        foreach ($page->collection() as $child) {
          /* @var $child Page */
          if (!empty($child->value('media.image'))) {
            $images = $child->media()->images();
            break;
          }
        }
      }

      /* @var $images ImageMedium[] */
      $image = isset($images) ? array_shift($images) : null;
    }

    return $this->getImage($image);
  }

  /**
   * Get the image path according context
   *
   * @param ImageMedium|null $image
   * @return false|string|null
   */
  private function getImage(?ImageMedium $image)
  {
    if ($image !== null) {
      $url = $this->config->get('plugins.social-seo-metatags.image.use_cache') ? $image->cache()->url() : $image->url();
      return Utils::url($url, true);
    }

    return null;
  }

  /**
   * Get the default image set in the Plugin config.
   *
   * @return string|null path of default image if it exists
   */
  private function getDefaultImage(): ?string
  {
    $default = $this
      ->grav['config']
      ->get('plugins.social-seo-metatags.default.image');

    $image = null;

    if (is_array($default)) {
      $path = array_key_first($default);
    }

    if (isset($path) && is_file($path)) {
      $image = MediumFactory::fromFile($path);
    }

    return $this->getImage($image);
  }

  private function getTwitterCardMetatags($meta)
  {

    if ($this->grav['config']->get('plugins.social-seo-metatags.social_pages.pages.twitter.enabled')) {

      if (!isset($meta['twitter:card'])) {
        $meta['twitter:card']['name'] = 'twitter:card';
        $meta['twitter:card']['property'] = 'twitter:card';
        $meta['twitter:card']['content'] = $this->grav['config']->get('plugins.social-seo-metatags.social_pages.pages.twitter.type');
      }

      if (!isset($meta['twitter:title'])) {
        $meta['twitter:title']['name'] = 'twitter:title';
        $meta['twitter:title']['property'] = 'twitter:title';
        $meta['twitter:title']['content'] = $this->title;
      }

      if (!isset($meta['twitter:description'])) {
        $meta['twitter:description']['name'] = 'twitter:description';
        $meta['twitter:description']['property'] = 'twitter:description';
        $meta['twitter:description']['content'] = $this->desc;
      }

      if (!isset($meta['twitter:image'])) {
        $imagePath = $this->getFirstImage() ?: $this->getDefaultImage();

        if (isset($imagePath)) {
          $meta['twitter:image']['name'] = 'twitter:image';
          $meta['twitter:image']['property'] = 'twitter:image';
          $meta['twitter:image']['content'] = str_replace(
            ' ',
            '%20',
            $imagePath
          );
        }
      }

      if (!isset($meta['twitter:site'])) {
        //Get Twitter username
        $user = "@" . $this->grav['config']->get('plugins.social-seo-metatags.social_pages.pages.twitter.username');
        //Update data
        $meta['twitter:site']['name'] = 'twitter:site';
        $meta['twitter:site']['property'] = 'twitter:site';
        $meta['twitter:site']['content'] = $user;
      }
    }
    return $meta;
  }

  private function getFacebookMetatags($meta)
  {
    $opengraph_is_active = $this->grav['config']->get('plugins.social-seo-metatags.social_pages.pages.facebook.opengraph.enabled');

    $insights_is_active = $this->grav['config']->get('plugins.social-seo-metatags.social_pages.pages.facebook.insights.enabled');

    if ($opengraph_is_active) {
      //Manually convert locale ll by ll_LL from page or default language
      $default_locale = $this->grav["page"]->language();
      if ($default_locale == null) $default_locale = $this->grav['config']->get('site.default_lang');
      switch ($default_locale) {
        case "fr":
          $locale = "fr_FR";
          break;
        case "en":
          $locale = "en_EN";
          break;
      }

      if (!isset($meta['og:title'])) {
        $meta['og:title']['property'] = 'og:title';
        $meta['og:title']['content'] = $this->title;
      }

      if (!isset($meta['og:description'])) {
        $meta['og:description']['property'] = 'og:description';
        $meta['og:description']['content'] = $this->desc;
      }

      if (!isset($meta['og:type'])) {
        $meta['og:type']['property'] = 'og:type';
        $meta['og:type']['content'] = 'article';
      }

      if (isset($locale) && !isset($meta['og:locale'])) {
        $meta['og:locale']['property'] = 'og:locale';
        $meta['og:locale']['content'] = $locale;
      }

      if (!isset($meta['og:url'])) {
        $meta['og:url']['property'] = 'og:url';
        $meta['og:url']['content'] = $this->grav['uri']->base() . $this->grav['uri']->uri();
      }

      if (!isset($meta['og:image'])) {
        $imagePath = $this->getFirstImage() ?: $this->getDefaultImage();

        if (isset($imagePath)) {
          $meta['og:image']['property'] = 'og:image';
          $meta['og:image']['content'] = str_replace(
            ' ',
            '%20',
            $imagePath
          );
        }
      }

      if ($insights_is_active) {
        if (!isset($meta['fb:app_id'])) {
          $meta['fb:app_id']['property'] = 'fb:app_id';
          $meta['fb:app_id']['content'] = $this->grav['config']->get('plugins.social-seo-metatags.social_pages.pages.facebook.insights.appid');
        }
      }
    }

    return $meta;
  }

  private function sanitizeMarkdowns($text)
  {
    $rules = array(
      '/(#+)(.*)/' => '\2',  // headers
      '/(&lt;|<)!--\n((.*|\n)*)\n--(&gt;|\>)/' => '',    // comments
      '/(\*|-|_){3}/' => '',    // hr
      '/!\[([^\[]+)\]\(([^\)]+)\)/' => '',    // images
      '/\[([^\[]+)\]\(([^\)]+)\)/' => '\1',  // links
      '/(\*\*|__)(.*?)\1/' => '\2',  // bold
      '/(\*|_)(.*?)\1/' => '\2',  // emphasis
      '/\~\~(.*?)\~\~/' => '\1',  // del
      '/\:\"(.*?)\"\:/' => '\1',  // quote
      '/```(.*)\n((.*|\n)+)\n```/' => '\2',  // fence code
      '/`(.*?)`/' => '\1',  // inline code
      '/\n(\*|\+|-)(.*)/' => '\2',  // ul lists
      '/\n[0-9]+\.(.*)/' => '\2',  // ol lists
      '/(&gt;|\>)+(.*)/' => '\2',  // blockquotes
    );

    foreach ($rules as $regex => $replacement) {
      if (is_callable($replacement)) {
        $text = preg_replace_callback($regex, $replacement, $text);
      } else {
        $text = preg_replace($regex, $replacement, $text);
      }
    }

    $text = trim($text);

    return htmlspecialchars($text, $this->html_flags, 'UTF-8');
  }

  private function cleanKeywords($array)
  {
    $new_array = [];
    foreach ($array as $value) {
      $push_val = true;
      if (strlen($value) < 3) $push_val = false;
      if (strlen($value) > 30) $push_val = false;
      if (preg_match("#([/|\\]{1})([0-9a-zA-Z_-]+)([.]{1})([0-9a-zA-Z_-]+)#", $value) > 0) $push_val = false;  //Remove path value
      if (substr_count($value, ' ') > 1) $push_val = false;                                                  //Accept only 1 space in keyword value

      $value = preg_replace("/([^a-zA-Z0-9_éèêëàîïôöùûü' -]+)/", "", $value);                                //Remove all special char

      if ($push_val) array_push($new_array, $value);
    }
    return $new_array;
  }

}
