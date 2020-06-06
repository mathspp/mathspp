# v3.0.0
## 07/13/2018

1. [](#new)
    * Empty route is now coded as the 404 route (default `/error/404`), thanks to @Kage.
    * Removing the deprecated interface. Use the `viewcounts` array.
    * Added some concrete examples to the README and linked to the demo page.

# v2.0.0
##  11/17/2016

1. [](#new)
    * Now dumping the count data into the special Twig variable `viewcounts`.
    * DEPRECATED: The data is still passed via `config.plugins.view-count.counts` as well, *but this is officially deprecated*. This functionality will be removed in a later major release.
1. [](#improved)
	* BACKWARDS INCOMPATIBLE: Removed the `datadir` config parameter. Just provide any folder structure with the `datafile`.
	* `datafile` is now properly sanitized.
	* Moved the data dump to `onPagesInitialized` so that the data is also visible when twig is processed in the page. This means, though, that the data file gets loaded twice. Let me know if performance becomes a problem.

# v1.0.0
##  10/18/2016

1. [](#new)
    * ChangeLog started...
