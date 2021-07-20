# Grav Cookies Notice Plugin
Grav plugin displays small banner on the botton or top of screen with informations about EU Cookie Directive.

# Installation
To install this plugin, just download files and copy to `/your/site/grav/user/plugins/cookiesnotice`. Remember you need to c create the folder `cookiesnotice` under `/your/site/grav/user/plugins`.

# Usage

The Cookies Notice plugin contains template `cookiesnotice.html.twig`, when the plugin is initialized template is rendered and is assigned to the variable `cookiesnotice_markup`.

To run the script on your site, just call the variable `{{ cookiesnotice_markup }}` in template before the body close tag. E.g. in `/your/site/grav/user/themes/themename/templates/partials/base.html.twig`.

# Features

* On/Off loading jQuery Cookie plugin for some reasons.
* Ability to load your custom CSS files.
* Multi Language support (currently translations are in English, Polish, Swedish, German, Danish, Croatian, Dutch, French, Spanish).

# Configuration

To adjust the plugin, you have to copy the `cookiesnotice.yaml` file into this direction `/your/site/grav/user/config/plugins`. If this folder doesn't exist, you have to create it manually.
