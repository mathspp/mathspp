# Social & SEO Meta-Tags Plugin

The **Social & SEO Meta-Tags** Plugin is for [Grav CMS](http://github.com/getgrav/grav).

## Description

Add and manage the SEO meta-tags:
- Description
- Robots
- Keywords

Add all Meta-Tags that need Facebook Open Graph, and Twitter Cards.

Note: `keyword` meta-tag is deprecated for the most bots but this plugin allow management of it for the other bots.


# Features

* [Open Graph](http://ogp.me/) support.
* [Facebook Insights](https://developers.facebook.com/docs/sharing/referral-insights) support.
* [Twitter Cards](https://dev.twitter.com/cards/overview) support. You can select between Summary and Large cards.


# Installation

## Using GPM (preferred):

The simplest way to install this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm) through your system's Terminal (also called the command line). From the root of your Grav install type:

```shell
$ bin/gpm install social-seo-metatags
```

This will install the Social & SEO Meta-Tags plugin into your `/user/plugins` directory within Grav. Its files can be found under `/user/plugins/social-seo-metatags`.

## Manual Installation:

In `/user/plugins` folder, apply the following command:
```shell
git clone https://github.com/clemdesign/grav-plugin-social-seo-metatags social-seo-metatags
```

This will clone this repository into the _social-seo-metatags_ folder.


# Usage

After enabling plugin and options, 2 solutions:
  - Nothing to do: Items are determined from your page (Description, Keywords...)
  - Overwrite meta-tags from your frontmatter header page configuration.

You have possibility to customize meta-tag from your [page header metadata](https://learn.getgrav.org/content/headers#standard-metatag-examples):

```yaml

---
metadata:
    description: 'Your page description'
    robots: noindex, nofollow, ...
    keywords: key1, key2, key3, ...
    'og:title': 'The title for OpenGraph'
    'twitter:description': 'The description for Twitter'
---
Page content
```

To define a global description for all meta-tags, configure your page header as follow:

```yaml

---
description: 'Global description'
metadata:
    robots: noindex, nofollow, ...
    keywords: key1, key2, key3, ...
    'og:title': 'The title for OpenGraph'
---
Page content
```

> This configuration set up description for tag `description`, `og:description` and `twitter:description`.

## Strategy for Description set-up

Description is defined as follow:

1. Define description from Summary.
2. If Summary not defined, define description from site metadata configuration.
3. If description from frontmatter header is defined, use it.
4. If description from frontmatter header metadata (for each meta-tag) is defined, use it.


## Antimatter theme

This plugin support the field `header_image_file` of [Antimatter](https://github.com/getgrav/grav-theme-antimatter) theme.  
This configuration is choose first, then first media image and last default image from plugin configuration.


```yaml
header_image_file: 'Relative image path'
```

# Configuration

## Common SEO

For SEO, you have the following default configuration:

```yaml
seo:
  robots: without
  length: 20
  taxonomy:
    enabled: true
  page_content:
    enabled: false
  breadcrumb: false
```

`robots` e.g. "Default Robots Meta-Tag" allow you to define default rules for bots. You have the following possibilities:
- noindex: prevents the page from being indexed
- nofollow: don't follow links from this page when looking for new pages to crawl
- nosnippet: don't show a snippet of this page when displaying it in the search results
- noodp: don't use text from ODP (The Open Directory Project a.k.a. dmoz.org) to generate a title or snippet for this page
- noarchive: don't display a "Cached" link for this page in the search results

`length` e.g. "Number of keywords" allow you to define maximum number of keywords to display in meta-tag.

`taxonomy.enabled` e.g. "Use Taxonomy Keywords" allow you to use Keywords of Taxonomy for Meta-Tags.

`page_content.enabled` e.g. "Use Page Content Keywords" allow you to use Keywords of page content (`<strong>` and `<em>`) for Meta-Tags.

`breadcrumb` e.g. "Use SEO Breadcrumb" allow you to integrate JSON-LD script SEO tag in your page.

## Associate Twitter account


For Twitter, you have the following default configuration:

```yaml
social_pages:
  pages:
    twitter:
      enabled: true
      type: summary
      username: ''
```

`enabled` e.g. "Twitter Cards active" enable integration of [Twitter Cards](https://dev.twitter.com/cards/overview) meta-tags.

`type` e.g. "Card Type" define the typê of card used by meta-tag. Accepted values are: `summary`, `summary_large_image`.

`username` e.g. "Twitter Username" is your twitter account reachable by https://twitter/username.

## Associate Open Graph and Facebook

Open Graph can work without a strong integration to Facebook. This plugin gives you the opportunity to use both and add an app_id to use Facebook Statistics with your website.

### Namespace configuration

[OpenGraph](http://ogp.me/) requires to use a namespace on the `<html>` tag.

In your base template, add the following line in the `html` tag:

```twig
<html
    {{- (
            config.plugins['social-seo-metatags'].enabled
            and
            config.plugins['social-seo-metatags'].social_pages.pages.facebook.opengraph.enabled
        )
        ? 'xmlns:og="http://ogp.me/ns#"'
        : ''
    -}}
>
```

### Plugin configuration

For Facebook, you have the following default configuration:

```yaml
social_pages:
  pages:
    facebook:
      opengraph:
        enabled: true
      insights:
        enabled: false
        appid: '1234567890'
```

`facebook.opengraph.enabled` toggles the integration of [Facebook Open Graph](https://ogp.me/) meta-tags.

`facebook.insights.enabled` toggles the integration of [Facebook Insights](https://developers.facebook.com/docs/sharing/referral-insights) meta-tag, which needs an app_id to identify you as the owner of the document. You can find this app_id from the [Facebook App Dashboard](https://developers.facebook.com/apps/redirect/dashboard).

### Gzip activation

It could be that content sharing on facebook require the _Gzip activation_. The [Facebook debugger](https://developers.facebook.com/tools/debug/sharing/) will display no data or incomprehensible errors in this case.

To resolve it, set the `cache.gzip` to `true` in `system.yaml` configuration file.

This behavior is discussed [here](https://github.com/getgrav/grav/issues/1955).

## Other configuration

### Single quote escaping

With the following configuration, the plugin allows the conversion of simple quote (`'`), which can be an issue for Twitter sharing, into html entity (`#039;`):

```yaml
quote:
  convert_simple: true
```

### Images Cache

This plugin can use cache to render images, instead to access directly to resource.

With this option, you can hide the path to access to your resource in page source code.

```yaml
image:
  use_cache: true
```

### Default image

The following defines a default image to use if no image is present in the current Page.

This avoids having to set an image for each Page; useful with repetitive Pages like events or such.

```yaml
default:
  image:
    user/assets/images/ZOqdm0x1rzP6iLp.png:
      name: ZOqdm0x1rzP6iLp.png
      type: image/png
      size: 27269
      path: user/assets/images/ZOqdm0x1rzP6iLp.png
```

# Demo

Check source of the following link: [http://www.clemdesign.fr/blog/grav-un-nouveau-plugin-pour-gerer-les-meta-tags](view-source:http://www.clemdesign.fr/blog/grav-un-nouveau-plugin-pour-gerer-les-meta-tags)

# Contributing

If you think any implementation are just not the best, feel free to submit ideas and pull requests. All your comments and suggestion are welcome.

# Credits

Some features based on [Social Meta Tags Plugin](https://github.com/tucho235/grav-plugin-social-meta-tags)

