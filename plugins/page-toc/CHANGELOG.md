# v3.2.4
## 05/16/2024

1. [](#improved)
   * Updated Spanish translation [#40](https://github.com/trilbymedia/grav-plugin-page-toc/pulls/40) 
1. [](#bugfix)
   * Reverted to use `mb_encode_nuericentity()` instead of `htmlspecialchars` / `iconv` approach to fix breaking UTF-8 characters

# v3.2.3
## 05/06/2024

1. [](#new)
   * Added french translation [#28](https://github.com/trilbymedia/grav-plugin-page-toc/pulls/28) 
   * Added option to whitelist HTML tags in TOC [#36](https://github.com/trilbymedia/grav-plugin-page-toc/pulls/36)
   * Added option to set user templates in which anchors are generated [#37](https://github.com/trilbymedia/grav-plugin-page-toc/pulls/37)
1. [](#bugfix)
   * Revert Twig macro to use `_self` as it was breaking the recursion.  Deprecated message remains but can't be helped. [#38](https://github.com/trilbymedia/grav-plugin-page-toc/issues/38)

# v3.2.2
## 05/10/2023

1. [](#bugfix)
   * Use `mb_encode_nuericentity()` instead of `htmlspecialchars` / `iconv` approach to fix breaking UTF-8 characters
   * Fix a deprecated message in the Twig macro

# v3.2.1
## 05/08/2023

1. [](#improved)
   * Fixed a "Deprecated: mb_convert_encoding()" error

# v3.2.0
## 02/23/2022

1. [](#new)
   * Support for HTML or Shortcode based headers with custom `id` attributes to specify an anchor
   * Added German translation

# v3.1.3
## 01/03/2022

1. [](#new)
   * Require Grav `v1.7.26` to make use of built in `Plugin::inheritedConfigOption()`
   * NOTE: `page-toc v3.1.2` was released prior to `Grav v1.7.26` and has been removed
2. [](#improved)
   * Don't force inclusion of `<html>` or `<body>` tags to reduce chance of invalid HTML
   * Improved `README.md`

# v3.1.1
## 12/16/2021

1. [](#bugfix)
   * Fixed some blueprint errors that caused errors on save
   * Force `start` and `depth` to be integers [#17](https://github.com/trilbymedia/grav-plugin-page-toc/issues/17)

# v3.1.0
## 12/09/2021

1. [](#new)
   * **NEW** Added option to automatically copying to clipboard an anchor URL when clicking on it

# v3.0.0
## 12/03/2021

1. [](#new)
   * **NEW** Support built-in `anchors` with customization of icon/classes/css etc.
   * **NEW** `[anchor]` shortcode for creating manual anchors for easy linking to page content
   * Moved the vendor-based TOC functionality in-plugin to provide more flexibility and additional features
   * Added several more Twig functions for increased flexibility
   * Ability to limit the length of a fragment link
   * Ability to set a custom prefix for anchor links
   * Added `languages.yaml` file for text translations
2. [](#improved)
   * Independent control over the levels of anchors that should be built and the TOC displayed
   * `page-toc:` page-level configuration can be set in parent pages and trickles down to child pages
   * Removed dependency on HTML5 library and use the faster PHP `DOMDocument` class
   * Translated text for the "Table of Contents" in the `page-toc.html.twig` template

# v2.0.0
## 11/24/2021

1. [](#new)
   * Added new `components/page-toc.html.twig` that can be extended and the HTML output modified
   * Updated core TOC library to latest `3.0.2` version
   * Requires PHP `7.3.6`
   * Requires Grav `1.7+`
   * Added Shortcode-like in-page syntax support. e.g. `[toc]`

# v1.1.2
## 06/01/2021

1. [](#new)
    * Added page-toc blueprints under "Advanced" tab for admin
1. [](#improved)
    * Updated to latest `knplabs/knp-menu` library
1. [](#bugfix)
    * Added `|raw` filter to twig output in README.md

# v1.1.1
## 12/02/2020

1. [](#improved)
    * Updated to latest `masterminds/html5` and `knplabs/knp-menu` libraries

# v1.1.0
## 04/01/2019

1. [](#improved)
    * Updated to latest `caseyamcl/toc` library
1. [](#bugfix)
    * Fixes relative levels [#6](https://github.com/trilbymedia/grav-plugin-page-toc/pull/9)
    * Fixes incorrect reference to `end` when it should be `depth` [#7](https://github.com/trilbymedia/grav-plugin-page-toc/pull/7)

# v1.0.1
## 03/19/2017

1. [](#improved)
    * Fixed issue with `end` not being valid, should be `depth`. Updated README

# v1.0.0
## 08/01/2017

1. [](#new)
    * ChangeLog started...
