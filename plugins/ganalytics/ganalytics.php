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
     * that the plugin wants to listen to. The key of each
     * array section is the event that the plugin listens to
     * and the value (in the form of an array) contains the
     * callable (or function) as well as the priority. The
     * higher the number the higher the priority.
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
     * @return array
     */
    private function getCookieConfiguration(){
        $cookie_config = $this->config->get('plugins.ganalytics.cookieConfig', false);
        if (!$cookie_config) return [];

        $cookie_config = [
          'cookie_prefix'    => $this->config->get('plugins.ganalytics.cookiePrefix', ''),
          'cookie_expires' => $this->config->get('plugins.ganalytics.cookieExpires', 63072000),
        ];

        // cookie domain
        $cookie_domain = trim($this->config->get('plugins.ganalytics.cookieDomain'));
        if (!empty($cookie_domain)) $cookie_config['cookie_domain'] = $cookie_domain;

        return $cookie_config;
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
     * @param string $objectName Global variable name for the GA object
     * @return string
     */
    private function getTrackingCode($objectName)
    {
        $code =
            "window.dataLayer = window.dataLayer || [];\n".
            "function {$objectName}(){dataLayer.push(arguments);}\n".
            "{$objectName}('js', new Date());\n"
        ;

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
     * @return string
     */
    private function getTrackingSettings($trackingId, $objectName)
    {
        if ($this->config->get('plugins.ganalytics.debugMode', false)) {
            $settings['debug_mode'] = true;
        } else {
            $settings = [];
        }

        $settings = array_merge($settings, $this->getCookieConfiguration());
        if (!empty($settings)) {
            try {
                $settings = json_encode($settings, JSON_THROW_ON_ERROR);
                return "{$objectName}('config', '{$trackingId}', {$settings});";
            } catch (\JsonException $e) {
                $this->grav['log']->error("plugin.{$this->name}: Invalid cookie settings - {$e->getMessage()}");
            }
        }

        return "{$objectName}('config', '{$trackingId}');";
    }

    /**
     * Do something with deprecated settings
     *
     * Due to https://github.com/getgrav/grav/issues/3697
     * this method doesn't store modified settings back to
     * YAML file. One needs to migrate deprecated configuration
     * options manually.
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
//            Plugin::saveConfig('ganalytics');
        }

        // 1.x => 2.0
        // Remove unused fields
        $fields = ['async', 'anonymizeIp', 'forceSsl', 'cookieName', 'debugTrace'];
        $settings = $this->config();
        if (array_intersect_key(array_flip($fields), $settings)) {
            unset(
              $settings['async'], $settings['anonymizeIp'], $settings['forceSsl'],
              $settings['cookieName'], $settings['debugTrace']
            );
            $this->config->set('plugins.ganalytics', $settings);
//            $this->saveConfig('ganalytics');
        }
        // Rename default "objectName" value from "ga" to "gtag"
        $objectName = $this->config->get('plugins.ganalytics.objectName');
        if ($objectName == 'ga') {
            $this->config->set('plugins.ganalytics.objectName', 'gtag');
//            Plugin::saveConfig('ganalytics');
        }
        // Rename "debugStatus" field to "debugMode"
        $debugStatus = $this->config->get('plugins.ganalytics.debugStatus');
        if (!is_null($debugStatus)) {
            $settings = $this->config->get('plugins.ganalytics', []);
            $settings['debugMode'] = $debugStatus;
            unset($settings['debugStatus']);
            $this->config->set('plugins.ganalytics', $settings);
//            Plugin::saveConfig('ganalytics');
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
        $objectName = trim($this->config->get('plugins.ganalytics.objectName', 'gtag'));
        $position   = trim($this->config->get('plugins.ganalytics.position', 'head'));

        // Opt Out and Tracking Code and settings
        $code = ''; // init
        $optout_config = $this->getOptOutConfiguration();
        if (!empty($optout_config)) {
            $code .= $this->getOptOutCode($trackingId, $optout_config);
        }
        $settings = $this->getTrackingSettings($trackingId, $objectName);
        $code .= $this->getTrackingCode($objectName);
        $code .= $settings;

        // Embed Google Analytics script
        $group = ($position == 'body') ? 'bottom' : null;

        $this->grav['assets']->addJs("https://www.googletagmanager.com/gtag/js?id={$trackingId}", 9, true, 'async', $group);
        $this->grav['assets']->addInlineJs($code, null, $group);
    }
}
