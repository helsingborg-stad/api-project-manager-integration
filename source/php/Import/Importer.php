<?php

namespace ProjectManagerIntegration\Import;

class Importer
{
    public $url;
    public $postType = 'project';

    public function __construct($url)
    {
        ini_set('max_execution_time', 300);

        $this->url = $url;
        $this->startImport();
    }

    public function startImport()
    {
        if (function_exists('kses_remove_filters')) {
            kses_remove_filters();
        }

        $totalPages = 1;

        for ($i = 1; $i <= $totalPages; $i++) {
            error_log("Do run: " . $i);

            $url = add_query_arg(
                array(
                  'page' => $i,
                  'per_page' => 1,
                  ),
                $this->url
            );

            error_log(print_r($url, true));

            $requestResponse = $this->requestApi($url);

            if (is_wp_error($requestResponse)) {
                break;
            }

            $totalPages = $requestResponse['headers']['x-wp-totalpages'] ?? $totalPages;

            error_log(print_r($totalPages, true));
            //error_log(print_r($requestResponse['headers'], true));
            //error_log(print_r($requestResponse['body'], true));

            $this->savePosts($requestResponse['body']);
        }
    }

    public function savePosts($posts)
    {
        foreach ($posts as $key => $post) {
            $this->savePost($post);
        }
    }

    public function savePost($post)
    {
        //error_log(print_r($post, true));

        extract($post);

        $postData = array(
          'post_title' => $title['rendered'] ?? '',
          'post_content' => $content['rendered'] ?? '',
          'post_type' => $this->postType,
          'post_status' => 'publish',
        );

        error_log(print_r($postData, true));


        $postId = wp_insert_post($postData);

        error_log("Created post: " . $postId);
    }

    /**
     * Request to Api
     * @param  string $url Request Url
     * @return array|bool|\WP_Error
     */
    public function requestApi($url)
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
