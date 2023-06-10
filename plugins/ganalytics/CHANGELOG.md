# v2.0.0
## 06/09/2023

1. [](#new)
    * BREAKING CHANGE! The plugin now uses Google Analytics 4 API, which is not compatible with an old Universal Analytics functionality. You must migrate your existing Universal Analytics properties to Google Analytics 4 data streams before performing the upgrade. Please, read upgrade section in the [README](https://github.com/escopecz/grav-ganalytics/blob/master/README.md#upgrading-from-1x) file for the detailed steps.

# v1.5.2
## 03/10/2023

1. [](#bugfix)
    * Fixed changelog formating

# v1.5.1
## 03/10/2023

1. [](#bugfix)
    * Fix previously broken 1.5.0 release

# v1.5.0
## 09/22/2019

1. [](#new)
    * Added support for environment variables
    * Add capabilities to block address ranges and use a blocking cookie
    * Opt out code added
2. [](#improved)
    * Changes for General Data Protection Regulation (GDPR)

# v1.4.0
## 01/04/2017

1. [](#new)
    * Preload the Google Analytics script asynchronously
    * Choose the code position in the HTML document (head or body)
    * Custom Cookie Configuration. Name, domain and expiration time are configurable.
    * Force SSL - Send all data using SSL, even from insecure (HTTP) pages
2. [](#improved)
    * Improve plugin configuration with tab views.
    * Better use and configuration of the global object name. Please use `objectName` instead of `renameGa`.

# v1.3.0
## 12/21/2016

1. [](#new)
    * Block IP addresses (_Google Analytics code will not be embedded_)
2. [](#improved)
    * Added german translation
3. [](#bugfix)
    * Fixed the date format in the changelog 

# v1.2.0
## 08/11/2016

1. [](#new)
    * Rename the global (ga) variable of the Google Analytics object
    * Enable the debug version of the analytics.js library + Trace Debugging
      
# v1.1.0
## 08/02/2016

1. [](#new)
    * Anonymize the IP address sent to Google Analytics

# v1.0.0
## 11/08/2015

1. [](#new)
    * GA Plugin started
