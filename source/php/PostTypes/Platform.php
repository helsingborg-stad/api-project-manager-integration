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
        if (!is_singular('platform')) {
            return $data;
        }

        $featuredImagePosX = get_post_meta(get_the_id(), 'featured_image_position_x', true);
        $featuredImagePosY = get_post_meta(get_the_id(), 'featured_image_position_y', true);
        $data['featuredImagePosition'] = array();
        $data['featuredImagePosition']['x'] = !empty($featuredImagePosX) ? $featuredImagePosX : 'center';
        $data['featuredImagePosition']['y'] = !empty($featuredImagePosY) ? $featuredImagePosY : 'center';
        $theme = get_post_meta(get_the_id(), 'theme_color', true);
        $data['themeColor'] = !empty($theme) ? $theme : 'purple';

        return $data;
    }


    public function mapTerms(string $taxonomy, array $termMetaKeys, array $requiredTermMetaKeys)
    {
        $terms = get_the_terms(get_queried_object_id(), $taxonomy);

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

        // Enable archive modules
        $postType->enableArchiveModules();
    }
}
