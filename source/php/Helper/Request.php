<?php

namespace ProjectManagerIntegration\Helper;

class Request
{
    /**
     * Request to Api
     * @param  string $url Request Url
     * @return array|bool|\WP_Error
     */
    public static function get($url)
    {
        $args = array(
            'timeout' => 120,
            'sslverify' => defined('DEV_MODE') && DEV_MODE == true ? false : true,
            'headers' => array(
                'PhpVersion' => phpversion(),
                'referrer' => get_home_url(),
            ),
        );
        $request = wp_remote_get($url, $args);
        $responseCode = wp_remote_retrieve_response_code($request);
        $headers = wp_remote_retrieve_headers($request);
        $body = wp_remote_retrieve_body($request);

        // Decode JSON
        $body = json_decode($body, true);

        // Return WP_Error if response code is not 200 OK or result is empty
        if ($responseCode !== 200 || !is_array($body) || empty($body)) {
            return new \WP_Error('error', __('API request failed.', PROJECTMANAGERINTEGRATION_TEXTDOMAIN));
        }

        $returnData = array(
          'body' => $body,
          'headers' => $headers,
        );

        return $returnData;
    }
}
