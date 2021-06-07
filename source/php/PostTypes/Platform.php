<?php

namespace ProjectManagerIntegration\PostTypes;

class Platform
{
    public $postType = 'platform';

    public function __construct()
    {
        add_action('init', array($this, 'registerPostType'), 9);
        add_filter('Municipio/theme/key', array($this, 'setThemeColorBasedOnMeta'));
        add_filter('Municipio/viewData', array($this, 'singleViewController'));
    }

    public function setThemeColorBasedOnMeta($color)
    {
        if (is_singular('platform')) {
            $theme = get_post_meta(get_the_id(), 'theme_color', true);
            $color = !empty($theme) ? $theme : 'purple';
        }

        return $color;
    }

    public function singleViewController($data)
    {
        error_log(print_r(get_post_meta(get_the_id()), true));

        error_log('====[DATA]====');

        error_log(print_r($data, true));

        if (!is_singular('platform')) {
            return $data;
        }

        $data['platform'] = array();

        $featuredImagePosX = get_post_meta(get_the_id(), 'featured_image_position_x', true);
        $featuredImagePosY = get_post_meta(get_the_id(), 'featured_image_position_y', true);
        $data['featuredImagePosition'] = array();
        $data['featuredImagePosition']['x'] = !empty($featuredImagePosX) ? $featuredImagePosX : 'center';
        $data['featuredImagePosition']['y'] = !empty($featuredImagePosY) ? $featuredImagePosY : 'center';
        $theme = get_post_meta(get_the_id(), 'theme_color', true);
        $data['themeColor'] = !empty($theme) ? $theme : 'purple';

        // Files

        $data['platform']['files'] = get_post_meta(get_the_id(), 'files');

        // Contacts
        $contactsMeta = get_post_meta(get_the_id(), 'contacts', false);
        if (!empty($contactsMeta) && !empty($contactsMeta[0])) {
            $data['platform']['contacts'] = $contactsMeta[0];
        }

        // Links

        $data['platform']['links'] = get_post_meta(get_the_id(), 'links');

        //Meta
        $data['platform']['meta'] = array();

        // Partners
        if (!empty(get_the_terms(get_queried_object_id(), 'platform_partner'))) {
            $data['platform']['meta'][] = array(
                'title' => __('Partners', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'content' => array_reduce(get_the_terms(get_queried_object_id(), 'platform_partner'), array($this, 'reduceTermsToString'), '')
            );
        }

        return $data;
    }
    
    public static function reduceTermsToString($accumilator, $item)
    {
        if (empty($accumilator)) {
            $accumilator = $item->name;
        } else {
            $accumilator .= ', ' . $item->name;
        }

        return $accumilator;
    }


    public function mapTerms(string $taxonomy, array $termMetaKeys, array $requiredTermMetaKeys)
    {
        error_log(get_queried_object_id());
        $terms = get_the_terms(get_queried_object_id(), $taxonomy);
        
        error_log($terms);

        if (empty($terms) || !is_array($terms)) {
            return array();
        }

        $terms = array_map(function ($term) use ($termMetaKeys, $requiredTermMetaKeys) {
            $term = (array) $term;

            if (!empty($termMetaKeys)) {
                foreach ($termMetaKeys as $key) {
                    $meta = get_term_meta($term['term_id'], $key, true);
                    if ($meta) {
                        $term[$key] = $meta;
                    }
                }
            }

            return $term;
        }, $terms);

        
        if (!empty($requiredTermMetaKeys) && is_array($requiredTermMetaKeys)) {
            foreach ($requiredTermMetaKeys as $requiredKey) {
                $terms = array_filter($terms, function ($term) use ($requiredKey) {
                    return !empty($term[$requiredKey]);
                });
            }
        }

        return !empty($terms) ? $terms : array();
    }

    public function registerPostType()
    {
        $args = array(
            'menu_icon'          => 'dashicons-portfolio',
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'supports'           => array('title', 'editor', 'thumbnail', 'content', 'excerpt'),
            'show_in_rest'       => true,
        );

        $restArgs = array(
          'exclude_keys' => array('author', 'acf', 'guid', 'link', 'template', 'meta', 'taxonomy', 'menu_order')
        );

        $postType = new \ProjectManagerIntegration\Helper\PostType(
            $this->postType,
            __('Platform', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            __('Platforms', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            $args,
            array(),
            $restArgs
        );

        // Partners
        $postType->addTaxonomy(
            $this->postType . '_partner',
            __('Partner', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            __('Partners', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            array('hierarchical' => true, 'show_ui' => false)
        );

        // Enable archive modules
        $postType->enableArchiveModules();
    }
}
