# Markdown Details Plugin

The **markdown-details plugin** for [Grav](http://github.com/getgrav/grav) allows generation of collapsible blocks of text via markdown.

This plugin is not intended to generate accordions but rather for independent collapsable section.

## Usage

> The HTML5 element ``<details>`` has some unexpected [accessability issues](https://daverupert.com/2019/12/why-details-is-not-an-accordion/), so there are two ways to generate markup for collapsible section. You decide which method you want to use by setting it in the [configuration](#configuration).
> You may ask why we are not using the Checkbox Hack? Well it has even more a11y implications than using ``<details>``.

A collapsible section starts with the headline (trigger/summary). The content of the brackets after the delimiter ``!>`` defines the tag to wrap the title in. The default tag is ``h2``.
Then you notice your content as usual and end it with the closing delimiter ``!@``.

````markdown
!> [h3] About Charles Darwin
Charles Robert Darwin, FRS FRGS FLS FZS (/ˈdɑːrwɪn/; 12 February 1809 – 19 April 1882) was an English naturalist, geologist and biologist, best known for his contributions to the science of evolution. His proposition that all species of life have descended over time from common ancestors is now widely accepted, and considered a foundational concept in science.

See his [Wikipedia page](https://en.wikipedia.org/wiki/Charles_Darwin) for more information.
!@
````

If ``a11y: false`` is set in config, the markup will be based on the ``<details>`` tag:

````html
<details class="details">
    <summary class="details__trigger">
        <h3 class="details__title">About Charles Darwin</h3>
    </summary>
    <div class="details__content textflow">
        <p>Charles Robert Darwin, FRS FRGS FLS FZS (/ˈdɑːrwɪn/; 12 February 1809 – 19 April 1882) was an English naturalist, geologist and biologist, best known for his contributions to the science of evolution. His proposition that all species of life have descended over time from common ancestors is now widely accepted, and considered a foundational concept in science.</p>
        <p>See his <a href="https://en.wikipedia.org/wiki/Charles_Darwin">Wikipedia page</a> for more information.</p>
    </div>
</details>
````

If ``a11y: true`` is set in config, the markup will be based on the [collapsible pattern from Inclusive Components](https://inclusive-components.design/collapsible-sections/) and adds a bit of progressively enhancing JavaScript to the page:

````html
<div class="details js-collapsible">
    <h3 class="details__title" data-expanded="true">
        <button class="details__trigger" aria-expanded="true">
            About Charles Darwin
        </button>
    </h3>
    <div class="details__content textflow">
        <p>Charles Robert Darwin, FRS FRGS FLS FZS (/ˈdɑːrwɪn/; 12 February 1809 – 19 April 1882) was an English naturalist, geologist and biologist, best known for his contributions to the science of evolution. His proposition that all species of life have descended over time from common ancestors is now widely accepted, and considered a foundational concept in science.</p>
        <p>See his <a href="https://en.wikipedia.org/wiki/Charles_Darwin">Wikipedia page</a> for more information.</p>
    </div>
</div>
````

> Until the JavaScript runs, all collapsible sections are expanded. In case it never runs (fails, is blocked, etc.), the user will not miss any information.

## Installation

Installing the Markdown Details plugin can be done in one of three ways: The GPM (Grav Package Manager) installation method lets you quickly install the plugin with a simple terminal command, the manual method lets you do so via a zip file, and the admin method lets you do so via the Admin Plugin.

### GPM Installation (Preferred)

To install the plugin via the [GPM](http://learn.getgrav.org/advanced/grav-gpm), through your system's terminal (also called the command line), navigate to the root of your Grav-installation, and enter:

    bin/gpm install markdown-details

This will install the Markdown Details plugin into your `/user/plugins`-directory within Grav. Its files can be found under `/your/site/grav/user/plugins/markdown-details`.

### Manual Installation

To install the plugin manually, download the zip-version of this repository and unzip it under `/your/site/grav/user/plugins`. Then rename the folder to `markdown-details`. You can find these files on [GitHub](https://github.com/bitstarr/grav-plugin-markdown-details) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/markdown-details

> NOTE: This plugin is a modular component for Grav which may require other plugins to operate, please see its [blueprints.yaml-file on GitHub](https://github.com/bitstarr/grav-plugin-markdown-details/blob/master/blueprints.yaml).

### Admin Plugin

If you use the Admin Plugin, you can install the plugin directly by browsing the `Plugins`-menu and clicking on the `Add` button.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/markdown-details/markdown-details.yaml` to `user/config/plugins/markdown-details.yaml` and edit this copy.

Here is the default configuration and an explanation of available options:

```yaml
enabled: true
built_in_css: true                  # include the default CSS file
built_in_js: true                   # include the default JS file
a11y: false                         # use a more accessable markup instead of details/summary, see #usage
base_class: details                 # CSS classes for the containing element
title_class: details__title         # CSS classes for the title element (summary text)
trigger_class: details__trigger     # CSS classes for the trigger element (button)
content_class: details__content     # CSS classes for the content container
default_title_tag: h2               # Sets the default HTML tag to be used as title
```

Note that if you use the Admin Plugin, a file with your configuration named markdown-details.yaml will be saved in the `user/config/plugins/`-folder once the configuration is saved in the Admin.

If wonder about the overkill of CSS classes options: Not every frontend developer uses the BEM naming schema. At the same time there is no need to overwrite the template because of this preference if you can alter those to you linking via the configuration.

## Customization

Since version 2.0.0 the icons are created with CSS pseudo elements instead of a hardcoded SVG image. In order to use different aesthetics you will need to use a custom template along with custom CSS styles.

Copy `user/plugins/markdown-details/templates/partials/md-details.html.twig` to your theme. There you can use the markup you prefer. If you like to create custom CSS delivered with your theme, don't forget to disbale the built in CSS option.


## Contributing

Please set your pull request to the `develop` branch so the version number can be iterated before final merging into main branch.