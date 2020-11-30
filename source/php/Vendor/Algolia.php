<?php

namespace ProjectManagerIntegration\Vendor;

use \AlgoliaIndex\Helper\Index as Instance;

class Algolia
{
    public $taxRecords = array();
    public $postMetaRecords = array();


    public function __construct()
    {
        add_filter('AlgoliaIndex/Record', array($this, 'addMetaRecords'), 10, 2);
        add_filter('AlgoliaIndex/Record', array($this, 'addTaxRecords'), 10, 2);
        add_filter('AlgoliaIndex/SearchableAttributes', array($this, 'addSearchableAttributes'), 10, 1);
        add_action('pre_get_posts', array($this, 'doAlgoliaQuery'));
    }

    public function addTaxRecords($result, $postId)
    {
        $taxonomiesToIndex = array(
            'project_organisation',
            'challenge_category',
            'project_technology',
            'project_sector',
        );

        if (!empty($taxonomiesToIndex)) {
            foreach ($taxonomiesToIndex as $tax) {
                $this->taxRecords[] = $tax;
                $result[$tax] = array_map(function (\WP_Term $term) {
                    return $term->name;
                }, wp_get_post_terms($postId, $tax));
            }
        }

        return $result;
    }

    public function addMetaRecords($result, $postId)
    {
        $postMetaKeysToIndex = array(
            'project_what',
            'project_why',
            'project_how'
        );

        if (!empty($postMetaKeysToIndex)) {
            foreach ($postMetaKeysToIndex as $metaKey) {
                $this->postMetaRecords[] = $metaKey;
                $result[$metaKey] = strip_tags(apply_filters('the_content', get_post_meta($postId, $metaKey, true)));
            }
        }

        return $result;
    }
    
    public function addSearchableAttributes($searchableAttributes)
    {
        return array_merge($searchableAttributes, $this->postMetaRecords, $this->taxRecords);
    }

    /**
     * Do algolia query
     *
     * @param $query
     * @return void
     */
    public function doAlgoliaQuery($query)
    {
        if (!is_admin() && $query->is_main_query() && self::isSearchPage()) {
            //Check if backend search should run or not
            if (self::backendSearchActive()) {
                $query->query_vars['post__in'] = self::getPostIdArray(
                    Instance::getIndex()->search(
                        $query->query['s']
                    )['hits']
                );

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
