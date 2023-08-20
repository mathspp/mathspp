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

use Knp\Menu\ItemInterface;
use Knp\Menu\Renderer\ListRenderer;

use function str_repeat;

/**
 * Class OrderedListRenderer
 *
 * @package TOC
 */
class OrderedListRenderer extends ListRenderer
{
    /**
     * @param ItemInterface $item
     * @param array<string> $attributes
     * @param array<mixed> $options
     * @return string
     */
    protected function renderList(ItemInterface $item, array $attributes, array $options): string
    {
        if (!$item->hasChildren() || 0 === $options['depth'] || !$item->getDisplayChildren()) {
            return '';
        }

        $html = $this->format(
            '<ol' . $this->renderHtmlAttributes($attributes) . '>',
            'ol',
            $item->getLevel(),
            $options
        );

        $html .= $this->renderChildren($item, $options);
        $html .= $this->format('</ol>', 'ol', $item->getLevel(), $options);

        return $html;
    }

    /**
     * @param string $html
     * @param string $type
     * @param int $level
     * @param array<string, mixed> $options
     * @return string
     */
    protected function format(string $html, string $type, int $level, array $options): string
    {
        return $type === 'ol'
            ? str_repeat(' ', $level * 4) . $html . "\n"
            : parent::format($html, $type, $level, $options);
    }
}
