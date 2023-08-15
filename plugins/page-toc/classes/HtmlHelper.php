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

use ArrayIterator;
use DOMDocument;
use DomElement;
use DOMXPath;

/**
 * Trait that helps with HTML-related operations
 *
 * @package TOC
 */
trait HtmlHelper
{
    protected function getHTMLParser($markup)
    {
        libxml_use_internal_errors(true);
        $domDocument = new \DOMDocument();
        $html = "<page-toc>$markup</page-toc>";
        $domDocument->loadHTML(mb_encode_numericentity($html, [0x80, 0x10FFFF, 0, ~0], 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $domDocument->preserveWhiteSpace = true;
        return $domDocument;
    }

    /**
     * Convert a topLevel and depth to H1..H6 tags array
     *
     * @param int $topLevel
     * @param int $depth
     * @return array|string[]  Array of header tags; ex: ['h1', 'h2', 'h3']
     */
    protected function determineHeaderTags(int $topLevel, int $depth): array
    {
        $desired = range((int) $topLevel, (int) $topLevel + ((int) $depth - 1));
        $allowed = [1, 2, 3, 4, 5, 6];

        return array_map(function ($val) {
            return 'h' . $val;
        }, array_intersect($desired, $allowed));
    }



    /**
     * Traverse Header Tags in DOM Document
     *
     * @param DOMDocument $domDocument
     * @param int          $topLevel
     * @param int          $depth
     * @return ArrayIterator<int,DomElement>
     */
    protected function traverseHeaderTags(DOMDocument $domDocument, int $topLevel, int $depth): ArrayIterator
    {
        $xQueryResults = new DOMXPath($domDocument);

        $xpathQuery = sprintf(
            "//*[%s]",
            implode(' or ', array_map(function ($v) {
                return sprintf('local-name() = "%s"', $v);
            }, $this->determineHeaderTags($topLevel, $depth)))
        );

        $nodes = [];
        $xQueryResults = $xQueryResults->query($xpathQuery);

        if ($xQueryResults) {
            foreach ($xQueryResults as $node) {
                $nodes[] = $node;
            }
            return new ArrayIterator($nodes);
        } else {
            return new ArrayIterator([]);
        }
    }
}
