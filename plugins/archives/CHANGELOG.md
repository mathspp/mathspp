# v2.0.2
## 12/02/2020

1. [](#improved)
    * Add taxonomy values date format customization [#29](https://github.com/getgrav/grav-plugin-archives/pull/29)  

# v2.0.1
## 07/09/2020

1. [](#bugfix)
    * Fix for archives on homepage [#26](https://github.com/getgrav/grav-plugin-archives/issues/26)  

# v2.0.0
## 04/06/2020

1. [](#new)
    * New per-page configuration to allow for multiple 'archives' in a single site
    * Added new `page@` filter support to allow configuration from page collection [#20](https://github.com/getgrav/grav-plugin-archives/pull/20)
1. [](#improved)
    * Added more sort-by options

# v1.6.1
## 02/24/2020

1. [](#new)
    * Pass phpstan level 1 tests
    * Require Grav v.1.6
1. [](#bugfix)
    * Exclude empty folders from archive
    * Fixed issue in 1.7 due to `validation: strict` and missing `taxonomy_names` blueprint item

# v1.6.0
## 04/17/2019

1. [](#improved)
    * Only included published pages in collection [#24](https://github.com/getgrav/grav-plugin-archives/issues/24)
    * Translate the date to the format specified [#9](https://github.com/getgrav/grav-plugin-archives/pull/9)

# v1.5.1
## 05/16/2017

1. [](#improved)
    * Added another date option to blueprints [#7](https://github.com/getgrav/grav-plugin-archives/pull/7)

# v1.5.0
## 07/14/2016

1. [](#improved)
    * Allow to configure the taxonomy names that form the URL, instead of hardcoding `archives_month` and `archives_year`
    * Allow to use @self in the filters, useful when adding the archives into a blog posts listing page

# v1.4.1
## 05/03/2016

1. [](#bugfix)
    * Fixed translated months

# v1.4.0
## 01/06/2016

1. [](#improved)
    * Allow for translated months
1. [](#bugfix)
    * Fix blueprints by adding the category to filters

# v1.3.0
## 08/25/2015

1. [](#improved)
    * Added blueprints for Grav Admin plugin

# v1.2.1
## 02/19/2015

2. [](#improved)
    * Implemented new `param_sep` variable from Grav 0.9.18

# v1.2.0
## 01/08/2015

1. [](#new)
    * Added new `archives_year` automatic taxonomy type
2. [](#improved)
    * Automatically adds taxonomy types (`archives_month`, `archives_year`) rather than requiring you to manually edit `site.yaml`

# v1.10
## 11/30/2014

1. [](#new)
    * ChangeLog started...
