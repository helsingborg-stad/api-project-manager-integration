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
                  'per_page' => 50,
                  ),
                $this->url
            );

            error_log(print_r($url, true));

            $requestResponse = $this->requestApi($url);

            if (is_wp_error($requestResponse)) {
                break;
            }

            $totalPages = $requestResponse['headers']['x-wp-totalpages'] ?? $totalPages;

            $this->savePosts($requestResponse['body']);
        }
    }

    public function savePosts($posts)
    {
        foreach ($posts as $post) {
            $this->savePost($post);
        }
    }

    public function savePost($post)
    {
        extract($post);

        //Get matching post
        $postObject = $this->getPost(
            array(
              'key' => 'uuid',
              'value' => $id
            )
        );

        // Collect post meta
        $postMeta = $this->mapMetaKeys($post);
        // Collect taxonomies
        $postTaxonomies = $this->mapTaxonomies($post);

        // Not existing, create new
        if (!isset($postObject->ID)) {
            $postData = array(
              'post_title' => $title['rendered'] ?? '',
              'post_content' => $content['rendered'] ?? '',
              'post_type' => $this->postType,
              'post_status' => 'publish',
            );
            $postId = wp_insert_post($postData);

            // Update post meta data
            $this->updatePostMeta($postId, $postMeta);
            // Update taxonomies
            $this->updateTaxonomies($postId, $postTaxonomies);
        } else {
            // Post already exist, do updates

            // Get post object id
            $postId = $postObject->ID;

            // Bail if no updates has been made
            if ($modified === get_post_meta($postId, 'last_modified', true)) {
                return;
            }

            $remotePost = array(
                'ID' => $postId,
                'post_title' => $title['rendered'] ?? '',
                'post_content' => $content['rendered'] ?? ''
            );

            $localPost = array(
                'ID' => $postId,
                'post_title' => $postObject->post_title,
                'post_content' => $postObject->post_content,
            );
            // Update if post object is modified
            if ($localPost !== $remotePost) {
                wp_update_post($remotePost);
            }

            // Update post meta data
            $this->updatePostMeta($postId, $postMeta);
            // Update taxonomies
            $this->updateTaxonomies($postId, $postTaxonomies);
        }
    }

    public function updateTaxonomies($postId, $taxonomies)
    {
        foreach ($taxonomies as $taxonomyKey => $taxonomy) {
            // Remove previous connections
            wp_delete_object_term_relationships($postId, $taxonomyKey);

            if (empty($taxonomy) || !is_array($taxonomy)) {
                continue;
            }

            $termList = array();

            foreach ($taxonomy as $key => $term) {
                // Check if term exist
                $localTerm = term_exists($term['slug'], $taxonomyKey);

                if (!$localTerm) {
                    // Create term if not exist
                    $localTerm = wp_insert_term(
                        $term['name'],
                        $taxonomyKey,
                        array(
                        'description' => $term['description'],
                        'slug' => $term['slug'],
                        'parent' => $term['parent']
                        )
                    );
                }

                $termList[] = (int) $localTerm['term_id'];
            }

            // Connecting term to post
            wp_set_post_terms($postId, $termList, $taxonomyKey, true);
        }
    }

    public function mapTaxonomies($post)
    {
        extract($post);

        $data = array(
          $this->postType . '_status' => $status,
          $this->postType . '_technology' => $technology,
          $this->postType . '_sector' => $sector,
          $this->postType . '_organisation' => $organisation,
          $this->postType . '_global_goal' => $global_goal,
          $this->postType . '_partner' => $partner
        );

        return $data;
    }

    public function mapMetaKeys($post)
    {
        extract($post);

        $data = array(
          'uuid' => $id,
          'last_modified' => $modified,
          'internal_project' => $internal_project ?? null,
          'address' => $address ?? null,
          'contacts' => $contacts ?? null,
          'links' => $links ?? null,
          'map' => $map ?? null
        );

        return $data;
    }

    /**
     *  Get posts
     * @param $search
     * @return mixed|null
     */
    public function getPost($search)
    {
        $post = get_posts(
            array(
                'meta_query' => array(
                    array(
                        'key' => $search['key'],
                        'value' => $search['value']
                    )
                ),
                'post_type' => $this->postType,
                'posts_per_page' => 50,
                'post_status' => 'all'
            )
        );

        if (!empty($post) && is_array($post)) {
            $post = array_pop($post);
            if (isset($post->ID) && is_numeric($post->ID)) {
                return $post;
            }
        }

        return null;
    }

    /**
     *  Update post meta
     * @param $postId
     * @param $dataObject
     * @return bool
     */
    public function updatePostMeta($postId, $dataObject)
    {
        if (is_array($dataObject) && !empty($dataObject)) {
            foreach ($dataObject as $metaKey => $metaValue) {
                if ($metaKey == "") {
                    continue;
                }

                if ($metaValue !== get_post_meta($postId, $metaKey, true)) {
                    update_post_meta($postId, $metaKey, $metaValue);
                }
            }

            return true;
        }

        return false;
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
