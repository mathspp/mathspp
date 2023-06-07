<?php

namespace Grav\Plugin;

use Grav\Common\Plugin;

/**
 * Class GanalyticsPlugin
 * @package Grav\Plugin
 */
class GanalyticsPlugin extends Plugin
{
    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0],
            'onAssetsInitialized' => ['onAssetsInitialized', 0]
        ];
    }

    /**
     * Returns the Google Analytics cookie configuration.
     * @return string
     */
    private function getCookieConfiguration(){
        $cookie_config = $this->config->get('plugins.ganalytics.cookieConfig', false);
        if (!$cookie_config) return "'auto'";

        $cookie_config = [
          'cookieName'    => $this->config->get('plugins.ganalytics.cookieName', '_ga'),
          'cookieExpires' => $this->config->get('plugins.ganalytics.cookieExpires', 63072000),
        ];

        // cookie domain
        $cookie_domain = trim($this->config->get('plugins.ganalytics.cookieDomain'));
        if (!empty($cookie_domain)) $cookie_config['cookieDomain'] = $cookie_domain;

        return json_encode($cookie_config);
    }

    /**
     * Returns the Google Analytics opt out configuration.
     * @return array
     */
    private function getOptOutConfiguration(){
        $optout_config = $this->config->get('plugins.ganalytics.optOutEnabled', false);
        if (!$optout_config) return [];

        $optout_config = [
          'optoutMessage' => trim($this->config->get('plugins.ganalytics.optOutMessage', 'Google tracking is now disabled.')),
          'cookieExpires' => gmdate ("D, d-M-Y H:i:s \U\T\C", $this->config->get('plugins.ganalytics.cookieExpires', 63072000) + time()),
        ];

        return $optout_config;
    }

    /**
     * Return the Google Analytics Tracking Code
     * @param string $scriptName Name of the GA script library
     * @param string $objectName Global variable name for the GA object
     * @param bool $async Determine if the GA script should be loaded and executed asynchronously
     * @return string
     */
    private function getTrackingCode($scriptName, $objectName, $async=false)
    {
        if ($async) {
            $code =
              "window.GoogleAnalyticsObject = '{$objectName}';\n".
              "window.{$objectName}=window.{$objectName}||function(){({$objectName}.q={$objectName}.q||[]).push(arguments)};{$objectName}.l=+new Date;\n"
            ;
        } else {
            $code =
                "(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){\n".
                "(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),\n".
                "m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)\n".
                "})(window,document,'script','https://www.google-analytics.com/{$scriptName}.js','{$objectName}');\n"
            ;
        }
        return $code;
    }

    /**
     * Return the Google Analytics Opt Out Code
     * @param string $trackingId Google Analytics Tracking ID
     * @param array $config Out Out settings
     * @return string
     */
    private function getOptOutCode($trackingId, $config)
    {
        $code = <<<JSCODE

            var disableStr = 'ga-disable-$trackingId'; 
            if (document.cookie.indexOf(disableStr + '=true') > -1) { 
                window[disableStr] = true;
            } 
            function gaOptout() { 
                document.cookie = disableStr + '=true; expires={$config['cookieExpires']}; path=/'; 
                window[disableStr] = true; 
                alert('{$config['optoutMessage']}'); 
            } 

JSCODE;
        return $code;
    }

    /**
     * Return all personalized GA settings
     * @param string $trackingId Google Analytics Tracking ID
     * @param string $objectName Global variable name for the GA object
     * @return array
     */
    private function getTrackingSettings($trackingId, $objectName)
    {
        $cookie_config = $this->getCookieConfiguration();

        $settings = [
          'trace-debug' =>  "window.ga_debug = {trace: true};",
          'create'      => "{$objectName}('create', '{$trackingId}', {$cookie_config});",
          'anonymize'   => "{$objectName}('set', 'anonymizeIp', true);",
          'force-ssl'   => "{$objectName}('set', 'forceSSL', true);",
          'send'        => "{$objectName}('send', 'pageview');"
        ];

        if (!$this->config->get('plugins.ganalytics.debugTrace', false)) unset ($settings['trace-debug']);
        if (!$this->config->get('plugins.ganalytics.anonymizeIp', false)) unset ($settings['anonymize']);
        if (!$this->config->get('plugins.ganalytics.forceSsl', false)) unset ($settings['force-ssl']);

        return $settings;
    }

    /**
     * Do something with deprecated settings
     */
    private function processDeprecatedSettings()
    {
        // 1.3.0 => 1.4.0
        // Field: "renameGa" => "objectName"
        $renameGa = trim($this->config->get('plugins.ganalytics.renameGa', ''));
        if (!empty($renameGa)) {
            $settings = $this->config->get('plugins.ganalytics', []);
            $settings['objectName'] = $renameGa;
            unset($settings['renameGa']);
            $this->config->set('plugins.ganalytics', $settings);
            Plugin::saveConfig('ganalytics');
        }
    }

    // Handle deprecated stuff when the plugin is initialized
    public function onPluginsInitialized()
    {
        $this->processDeprecatedSettings();
    }

    /**
     * Returns a packed IP address which can be directly compared to another packed IP address
     * @param string $humanReadableIPAddress IPv4 or IPv6 adress in human readable notation
     * @return string (16 byte packed representation)
     */
    private function packedIPAddress($humanReadableIPAddress)
    {
        $result = inet_pton($humanReadableIPAddress);

        if ($result == FALSE)
            return $this->packedIPAddress('::0');
        elseif (strlen($result) == 16)
            return $result;  // IPv6 native
        else
            return "\0\0\0\0\0\0\0\0\0\0\0\0" . $result;  // IPv4, expanded to IPv6 compatible length
    }

    /**
     * Returns TRUE, if a packed IP address is within the specified address range
     * @param string $packedAddress
     * @param string $range
     * @return boolean
     */
    private function inIPAdressRange($packedAddress, $range)
    {
        if ($range === 'private') {  // RFC 6890, RFC 4193
            return ($this->inIPAdressRange($packedAddress, "10.0.0.0-10.255.255.255")
                || $this->inIPAdressRange($packedAddress, "172.16.0.0-172.31.255.255")
                || $this->inIPAdressRange($packedAddress, "192.168.0.0-192.168.255.255")
                || $this->inIPAdressRange($packedAddress, "fc00::-fdff:ffff:ffff:ffff:ffff:ffff:ffff:ffff"));
        } elseif ($range === 'loopback') {  // RFC 6890
            return ($this->inIPAdressRange($packedAddress, "127.0.0.1-127.255.255.255")
                || $this->inIPAdressRange($packedAddress, "::1-::1"));
        } elseif ($range === 'link-local') {  // RFC 6890, RFC 4291
            return ($this->inIPAdressRange($packedAddress, "169.254.0.0-169.254.255.255")
                || $this->inIPAdressRange($packedAddress, "fe80::-febf:ffff:ffff:ffff:ffff:ffff:ffff:ffff"));
        } else {
            $rangeLimits = explode('-', $range);
            if (count($rangeLimits) == 2) {
                $lowerLimit = $this->packedIPAddress($rangeLimits[0]);
                $upperLimit = $this->packedIPAddress($rangeLimits[1]);
                return $lowerLimit <= $packedAddress && $packedAddress <= $upperLimit;
            }
        }

        return FALSE;
    }

    /**
     * Documents the reason for blocking GA tracking in a JavaScript comment
     * @param string $reason
     */
    private function documentBlockingReason($reason)
    {
        $this->grav['assets']->addInlineJs("/* GA tracking blocked, reason: $reason */");
    }

    /**
     * Add GA tracking JS when the assets are initialized
     */
    public function onAssetsInitialized()
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            $this->documentBlockingReason('admin plugin active');
            return;
        }

        // Don't proceed if there is no GA Tracking ID
        $trackingId = trim($this->config->get('plugins.ganalytics.trackingId', ''));

        // Add support for environment variables:
        if (preg_match('/env:(.*)/', $trackingId, $match)) {
            $trackingId = getenv($match[1]);
        }

        if (empty($trackingId)) {
            $this->documentBlockingReason('trackingId not configured');
            return;
        }

        // Don't proceed if a blocking cookie is set
        $blockingCookieName = $this->config->get('plugins.ganalytics.blockingCookie', '');
        if (!empty($blockingCookieName) && !empty($_COOKIE[$blockingCookieName])) {
            $this->documentBlockingReason("blocking cookie \"$blockingCookieName\" is set");
            return;
        }

        // Don't proceed if the IP address is blocked
        $blockedIps = $this->config->get('plugins.ganalytics.blockedIps', []);
        if (in_array($_SERVER['REMOTE_ADDR'], $blockedIps)) {
            $this->documentBlockingReason("client ip " . $_SERVER['REMOTE_ADDR'] . " is in blockedIps");
            return;
        }

        // Don't proceed if the IP address is within a blocked range
        $packedClientIpAddress = $this->packedIPAddress($_SERVER['REMOTE_ADDR']);
        $blockedIpRanges = $this->config->get('plugins.ganalytics.blockedIpRanges', []);
        foreach ($blockedIpRanges as $blockedIpRange) {
            if ($this->inIPAdressRange($packedClientIpAddress, $blockedIpRange)) {
                $this->documentBlockingReason("client ip " . $_SERVER['REMOTE_ADDR'] . " is in range \"" . $blockedIpRange . "\"");
                return;
            }
        }

        // Parameters
        $scriptName = $this->config->get('plugins.ganalytics.debugStatus', false) ? 'analytics_debug' : 'analytics';
        $objectName = trim($this->config->get('plugins.ganalytics.objectName', 'ga'));
        $async      = $this->config->get('plugins.ganalytics.async', false);
        $position   = trim($this->config->get('plugins.ganalytics.position', 'head'));

        // Opt Out and Tracking Code and settings
        $code = ''; // init
        $optout_config = $this->getOptOutConfiguration();
        if (!empty($optout_config)) {
            $code .= $this->getOptOutCode($trackingId, $optout_config);
        }
        $settings = $this->getTrackingSettings($trackingId, $objectName);
        $code .= $this->getTrackingCode($scriptName, $objectName, $async);
        $code.= join(PHP_EOL, $settings);

        // Embed Google Analytics script
        $group = ($position == 'body') ? 'bottom' : null;

        $this->grav['assets']->addInlineJs($code, null, $group);
        if ($async) $this->grav['assets']->addJs("//www.google-analytics.com/{$scriptName}.js", 9 , true /*pipeline*/, 'async', $group);
    }
}
