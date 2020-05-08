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

        // TODO: For testing, move import of taxonomies!
        $this->importTaxonomies();
        // return;

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
        }

        // Update post meta data
        $this->updatePostMeta($postId, $postMeta);
        // Update taxonomies
        $this->updateTaxonomies($postId, $postTaxonomies);

        $this->updateFeatureImage($post, $postId);

        // $this->updateTerms($postTaxonomies);
    }

    /**
     * Update if any feature image found.
     */
    public function updateFeatureImage($post, $idOfCopyPost)
    {
        extract($post);
        
        // TODO: Fix naive fetching of JSON elemetns.
        if (!isset($_links['wp:featuredmedia'])
            || !is_array($_links['wp:featuredmedia'])
            || !isset($_links['wp:featuredmedia'][0])
            || !isset($_links['wp:featuredmedia'][0]['href'])) {
            return;
        }

        $fimg_api_url = $_links['wp:featuredmedia'][0]['href'];

        if (!isset($fimg_api_url) || strlen($fimg_api_url) === 0 || !filter_var($fimg_api_url, FILTER_VALIDATE_URL)) {
            // Did not find valid href for feature image,
            return;
        }

        $fimg_api_res = $this->requestApi($fimg_api_url);

        if (is_wp_error($fimg_api_res)) {
            return;
        }

        $fimg_url = $fimg_api_res['body']['source_url'];

        if (is_string($fimg_url)) {
            $this->setFeaturedImageFromUrl($fimg_url, $idOfCopyPost);
        }
    }

    /**
     * Uploads an image from a specified url and sets it as the current post's featured image
     * @param string $url Image url
     * @return bool|void
     */
    public function setFeaturedImageFromUrl($url, $id)
    {
        // Fix for get_headers SSL errors (https://stackoverflow.com/questions/40830265/php-errors-with-get-headers-and-ssl)
        stream_context_set_default( [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);

        $headers = get_headers($url, 1);

        if (!isset($url) || strlen($url) === 0 || !wp_http_validate_url($url) || preg_match('/200 OK/', $headers[0]) === 0) {
            return false;
        }

        // TODO: Set correct uploadDir.
        // Upload paths
        $uploadDir = wp_upload_dir();
        $uploadDir = $uploadDir['basedir'];
        $uploadDir = $uploadDir . '/project_manager';

        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0776)) {
                error_log('could not crate folder');
                return new WP_Error('event', __(
                    'Could not create folder',
                    'event-manager'
                ) . ' "' . $uploadDir . '", ' . __(
                    'please go ahead and create it manually and rerun the import.',
                    'event-manager'
                ));
            }
        }

        $filename = sanitize_file_name(basename($url));
        if (stripos(basename($url), '.aspx')) {
            $filename = md5($filename) . '.jpg';
        }

        // Bail if image already exists in library
        if ($attachmentId = $this->attachmentExists($uploadDir . '/' . basename($filename))) {
            set_post_thumbnail((int)$id, (int)$attachmentId);

            return;
        }

        // Save file to server
        $contents = file_get_contents($url);
        $save = fopen($uploadDir . '/' . $filename, 'w');
        fwrite($save, $contents);
        fclose($save);

        // Detect file type
        $filetype = wp_check_filetype($filename, null);

        // Insert the file to media library
        $attachmentId = wp_insert_attachment(array(
            'guid' => $uploadDir . '/' . basename($filename),
            'post_mime_type' => $filetype['type'],
            'post_title' => $filename,
            'post_content' => '',
            'post_status' => 'inherit',
            'post_parent' => $id
        ), $uploadDir . '/' . $filename, $id);

        // Generate attachment meta
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attachData = wp_generate_attachment_metadata($attachmentId, $uploadDir . '/' . $filename);
        wp_update_attachment_metadata($attachmentId, $attachData);

        set_post_thumbnail($id, $attachmentId);
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
                    error_log($taxonomyKey);
                    $localTerm = wp_insert_term(
                        $term['name'],
                        $taxonomyKey,
                        array(
                            'description' => $term['description'],
                            'slug' => $term['slug'],
                            'parent' => $this->getParentByRemoteId($term['parent'], $term['taxonomy'])
                        )
                    );
                }

                if (is_array($localTerm) && isset($localTerm['term_id'])) {
                    $termList[] = (int) $localTerm['term_id'];
                }
            }

            // Connecting term to post
            wp_set_post_terms($postId, $termList, $taxonomyKey, true);
        }
    }

    public function importTaxonomies() 
    {
        $insertAndUpdateId = array();
        // TODO: Add all taxonomies, only added two for testing.
        // Bug: Taxonomy 'status' returns 'Undefined index'.
        $taxonomies = array('status', 'technology', 'sector', 'organisation', 'global_goal', 'category', 'partner');

        foreach ($taxonomies as $taxonomie) {
            $url = str_replace('project', $taxonomie, $this->url); 

            // Fetch taxonomy from API.
            $totalPages = 1;
            for ($i = 1; $i <= $totalPages; $i++) {
                $url = add_query_arg(
                    array(
                        'page' => 1,
                        'per_page' => 50,
                    ),
                    $url
                );

                // error_log(print_r($url, true));

                $requestResponse = $this->requestApi($url);

                if (is_wp_error($requestResponse)) {
                    break;
                }

                $totalPages = $requestResponse['headers']['x-wp-totalpages'] ?? $totalPages;

                if (!$requestResponse['body']) {
                    return;
                } 
    
                // Start import of taxonomies.
                $terms = $requestResponse['body'];
    
                // Crate term if it does not exist or update existing
                if (!empty($terms)) {
                    foreach ($terms as $term) {
                        $localTerm = term_exists($term['slug'] , 'project_' . $term['taxonomy']);
    
                        // Keep track of newly inserted and updated querys. Will be used to delte old entries.
                        $wpInsertUpdateResp = null;

                        // Construct arguments for insert and update querys.
                        $wpInsertUpdateArgs = array(
                            'description' => $term['description'],
                            'slug' => $term['slug']
                        );

                        if (isset($term['parent'])) {
                            $wpInsertUpdateArgs['parent'] = $term['parent'];
                        }
    
                        if (!$localTerm) {
                            // Crate term, could not find any existing.
                            $wpInsertUpdateResp = wp_insert_term($term['name'], 'project_' . $term['taxonomy'], $wpInsertUpdateArgs);

                            // TODO: Log errors.
                            if (!is_wp_error($wpInsertUpdateResp)) {
                                $insertAndUpdateId[] = $wpInsertUpdateResp['term_id'];
                            }
    
                            continue;
                        }

                        $wpInsertUpdateArgs['name'] = $term['name'];
    
                        // Update term, did find existing.
                        $wpInsertUpdateResp = wp_update_term($localTerm['term_id'], 'project_' . $term['taxonomy'], $wpInsertUpdateArgs);
    
                        if (!is_wp_error($wpInsertUpdateResp)) {
                            $insertAndUpdateId[] = $wpInsertUpdateResp['term_id'];
                        }
                    }
                }
            }            
        }

        $removeEntries = get_terms(array(
            'hide_empty' => false,
            'exclude' => $insertAndUpdateId
        ));
        

        foreach ($removeEntries as $entries) {
            // TODO: Should we skip root antry?
            if ($entries->term_id === 1 && $entries->taxonomy === 'category') {
                continue;
            }
            
            wp_delete_term($entries->term_id, $entries->taxonomy);            
        }

    }

    public function getParentByRemoteId($remoteId, $remoteTaxonomy)
    {
        if ($remoteId === 0) {
            return $remoteId;
        }

        $url = str_replace('project', $remoteTaxonomy, $this->url) . '/' . $remoteId;
        $requestResponse = $this->requestApi($url);
        $remoteParentTerm = $requestResponse['body'];
        $localParentTerm = get_term_by('slug', $remoteParentTerm['slug'], 'project_' . $remoteTaxonomy, ARRAY_A);

        if (empty($localParentTerm)) {
            $localParentTerm = wp_insert_term(
                $remoteParentTerm['name'],
                'project_' . $remoteParentTerm['taxonomy'],
                array(
                    'description' => $remoteParentTerm['description'],
                    'slug' => $remoteParentTerm['slug'],
                    'parent' => $this->getParentByRemoteId($remoteParentTerm['parent'], $remoteParentTerm['taxonomy'])
                )
            );
        }

        return $localParentTerm['term_id'];
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

    /**
     * Checks if a attachment src already exists in media library
     * @param  string $src Media url
     * @return mixed
     */
    private function attachmentExists($src)
    {
        global $wpdb;
        $query = "SELECT ID FROM {$wpdb->posts} WHERE guid = '$src'";
        $id = $wpdb->get_var($query);

        if (!empty($id) && $id > 0) {
            return $id;
        }

        return false;
    }
}
