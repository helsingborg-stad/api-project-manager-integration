<?php

namespace ProjectManagerIntegration\Vendor;

use \AlgoliaIndex\Helper\Index as Instance;

class Algolia
{
    public static $taxonomiesToIndex = array(
        'project_organisation',
        'challenge_category',
        'project_technology',
        'project_partner'
    );

    public static $postMetaKeysToIndex = array(
        'project_what',
        'project_why',
        'project_how'
    );

    public function __construct()
    {
        add_filter('AlgoliaIndex/Record', array($this, 'addMetaRecords'), 10, 2);
        add_filter('AlgoliaIndex/Record', array($this, 'addTaxRecords'), 10, 2);
        add_filter('AlgoliaIndex/SearchableAttributes', array($this, 'addSearchableAttributes'), 10, 1);
        add_action('pre_get_posts', array($this, 'doAlgoliaQuery'));
    }

    public function addTaxRecords($result, $postId)
    {
        if (!empty(self::$taxonomiesToIndex)) {
            foreach (self::$taxonomiesToIndex as $tax) {
                $result[$tax] = array_map(function (\WP_Term $term) {
                    return $term->name;
                }, wp_get_post_terms($postId, $tax));
            }
        }

        return $result;
    }

    public function addMetaRecords($result, $postId)
    {
        if (!empty(self::$postMetaKeysToIndex)) {
            foreach (self::$postMetaKeysToIndex as $metaKey) {
                $result[$metaKey] = strip_tags(apply_filters('the_content', get_post_meta($postId, $metaKey, true)));
            }
        }

        return $result;
    }

    public function addSearchableAttributes($searchableAttributes)
    {
        return array_merge($searchableAttributes, self::$postMetaKeysToIndex, self::$taxonomiesToIndex);
    }

    /**
     * Do algolia query
     *
     * @param $query
     * @return void
     */
    public function doAlgoliaQuery($query)
    {
        if (!class_exists('\AlgoliaIndex\Helper\Index')) {
            return;
        }

        if (!is_admin() && $query->is_main_query() && self::isSearchPage() && is_post_type_archive('project')) {
            //Check if backend search should run or not
            if (self::backendSearchActive()) {
                $foundPosts = self::getPostIdArray(
                    Instance::getIndex()->search(
                        $query->query['s']
                    )['hits']
                );

                $query->query_vars['post__in'] = !empty($foundPosts) ? $foundPosts : array(0);

                //Disable local search
                $query->query_vars['s'] = false;

                //Order by respomse order algolia
                $query->set('orderby', 'post__in');
            }

            //Query (locally) for a post that dosen't exist, if empty response from algolia
            if (!self::backendSearchActive()) {
                $query->query_vars['post__in'] = [PHP_INT_MAX]; //Fake post id
                $query->set('posts_per_page', 1); //Limit to 1 result
            }
        }
    }

    /**
     * Get id's if result array
     *
     * @param   array $response   The full response array
     * @return  array             Array containing results
     */
    private static function getPostIdArray($response)
    {
        $result = array();
        foreach ($response as $item) {
            $result[] = $item['ID'];
        }

        return $result;
    }

    /**
     * Check if search page is active page
     *
     * @return boolean
     */
    private static function isSearchPage()
    {
        if (is_multisite() && (defined('SUBDOMAIN_INSTALL') && SUBDOMAIN_INSTALL === false)) {
            if (trim(strtok($_SERVER["REQUEST_URI"], '?'), "/") == trim(get_blog_details()->path, "/") && is_search()) {
                return true;
            }
        }

        if (is_search()) {
            return true;
        }

        return false;
    }


    /**
     * Check if backend search should run
     *
     * @return boolean
     */
    private static function backendSearchActive()
    {
        //Backend search active
        $backendSearchActive = apply_Filters('AlgoliaIndex/BackendSearchActive', true);

        //Query algolia for search result
        if ($backendSearchActive || is_post_type_archive()) {
            return true;
        }

        return false;
    }
}
