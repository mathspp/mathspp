# Grav Random Plugin

`Random` is a [Grav][grav] Plugin which allows a random article to load based on its taxonomy filters.

# Installation

Installing the Random plugin can be done in one of two ways. Our GPM (Grav Package Manager) installation method enables you to quickly and easily install the plugin with a simple terminal command, while the manual method enables you to do so via a zip file. 

## GPM Installation (Preferred)

The simplest way to install this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm) through your system's Terminal (also called the command line).  From the root of your Grav install type:

    bin/gpm install random

This will install the Random plugin into your `/user/plugins` directory within Grav. Its files can be found under `/your/site/grav/user/plugins/random`.

## Manual Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `random`. You can find these files either on [GitHub](https://github.com/getgrav/grav-plugin-random) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/random

>> NOTE: This plugin is a modular component for Grav which requires [Grav](http://github.com/getgrav/grav), the [Error](https://github.com/getgrav/grav-plugin-error) and [Problems](https://github.com/getgrav/grav-plugin-problems) plugins, and a theme to be installed in order to operate.

# Usage

`Random` creates a **route** that you define. Based on the **taxonomy** filters, it picks a random item.

By default `Random` looks for all page that are have taxonomy that match those in the plugin's `filter` settings.  For this content to find _anything_ you need to define at
least one page with a matching taxonomy.  As the default value of the filter is `category: blog` you must have at least one page with this taxonomy. For example:

    ---
    title: Home
    taxonomy:
        category: blog
    ---

    # Grav is Running!
    ## You have installed **Grav** successfully

    Congratulations! You have installed the **Base Grav Package** that provides a **simple page** and the default **antimatter** theme to get you started.


# Settings Defaults

    route: /random
    filters:
        category: blog

# Customizing the Settings

To customize the plugin, you first need to create an override config. To do so, create the folder `user/config/plugins` (if it doesn't exist already) and copy the [random.yaml](random.yaml) config file in there.

Now you can edit it and tweak it however you prefer.

For further help with the `filters` settings, please refer to our [Taxonomy][taxonomy] and [Headers][headers] documentation.

# Creating a "Random Article" Button

![Random](assets/readme_1.png)

In our [Blog Skeleton](http://demo.getgrav.org/blog-skeleton/) we placed a button in the sidebar that pulls up a random blog post. Here is the code we used in the `sidebar.html.twig` template file to create this button.

    <a class="button" href="{{ base_url_relative }}/random"><i class="fa fa-retweet"></i> I'm Feeling Lucky!</a>

This button forwards the user to `/random` which is configured as the route for the random plugin. The `random.yaml` configuration file contains the following:

    enabled: true
    route: /random
    filters: { category: blog}
    filter_combinator: and    

# Updating

As development for the Random plugin continues, new versions may become available that add additional features and functionality, improve compatibility with newer Grav releases, and generally provide a better user experience. Updating Random is easy, and can be done through Grav's GPM system, as well as manually.

## GPM Update (Preferred)

The simplest way to update this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm). You can do this with this by navigating to the root directory of your Grav install using your system's Terminal (also called command line) and typing the following:

    bin/gpm update random

This command will check your Grav install to see if your Random plugin is due for an update. If a newer release is found, you will be asked whether or not you wish to update. To continue, type `y` and hit enter. The plugin will automatically update and clear Grav's cache.

## Manual Update

Manually updating Random is pretty simple. Here is what you will need to do to get this done:

* Delete the `your/site/user/plugins/random` directory.
* Download the new version of the Random plugin from either [GitHub](https://github.com/getgrav/grav-plugin-random) or [GetGrav.org](http://getgrav.org/downloads/plugins#extras).
* Unzip the zip file in `your/site/user/plugins` and rename the resulting folder to `random`.
* Clear the Grav cache. The simplest way to do this is by going to the root Grav directory in terminal and typing `bin/grav clear-cache`.

> Note: Any changes you have made to any of the files listed under this directory will also be removed and replaced by the new set. Any files located elsewhere (for example a YAML settings file placed in `user/config/plugins`) will remain intact.

[taxonomy]: http://learn.getgrav.org/content/taxonomy
[headers]: http://learn.getgrav.org/content/headers
[grav]: http://github.com/getgrav/grav
