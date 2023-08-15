<?php

/**
 * PageTOC
 *
 * This plugin allows creation of Table of Contents + Link Anchors
 *
 * Based on the original version https://github.com/caseyamcl/toc
 * by Casey McLaughlin <caseyamcl@gmail.com>
 *
 * Licensed under MIT, see LICENSE.
 */

declare(strict_types=1);

namespace Grav\Plugin\PageToc;

use Cocur\Slugify\Slugify;
use Cocur\Slugify\SlugifyInterface;

/**
 * UniqueSluggify creates slugs from text without repeating the same slug twice per instance
 */
class UniqueSlugify implements SlugifyInterface
{
    protected $slugify;
    protected $used;

    protected $options;

    /**
     * Constructor
     *
     * @param SlugifyInterface|null $slugify
     */
    public function __construct()
    {
        $this->used = array();
        $this->slugify = new Slugify();
    }

    /**
     * Slugify
     *
     * @param string $text
     * @param array|null $options
     * @return string
     */
    public function slugify($text, $options = null): string
    {
        $slugged = $this->slugify->slugify($text, $options);

        $maxlen = $options['maxlen'] ?? null;
        $prefix = $options['prefix'] ?? null;

        if (is_int($maxlen) && strlen($slugged) > $maxlen) {
            $slugged = substr($slugged, 0, $maxlen);
        }

        if (isset($prefix)) {
            $slugged = $prefix . $slugged;
        }

        $count = 1;
        $orig = $slugged;
        while (in_array($slugged, $this->used)) {
            $slugged = $orig . '-' . $count;
            $count++;
        }

        $this->used[] = $slugged;
        return $slugged;
    }
}
