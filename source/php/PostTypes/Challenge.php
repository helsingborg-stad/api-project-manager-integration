<?php

namespace ProjectManagerIntegration\PostTypes;

class Challenge
{
    public $postType = 'challenge';

    public function __construct()
    {
        add_action('init', array($this, 'registerPostType'), 9);
        add_filter('Municipio/theme/key', array($this, 'setThemeColorBasedOnMeta'));
        add_filter('Municipio/viewData', array($this, 'singleViewController'));
    }

    public function setThemeColorBasedOnMeta($color)
    {
        if (is_singular('challenge')) {
            $theme = get_post_meta(get_the_id(), 'theme_color', true);
            $color = !empty($theme) ? $theme : 'purple';
        }

        return $color;
    }

    public function singleViewController($data)
    {
        if (!is_singular('challenge')) {
            return $data;
        }


        $data['contacts'] = get_post_meta(get_the_id(), 'contacts', true);

        $data['preamble'] = get_post_meta(get_the_id(), 'preamble', true);

        $featuredImagePosX = get_post_meta(get_the_id(), 'featured_image_position_x', true);
        $featuredImagePosY = get_post_meta(get_the_id(), 'featured_image_position_y', true);
        $data['featuredImagePosition'] = array();
        $data['featuredImagePosition']['x'] = !empty($featuredImagePosX) ? $featuredImagePosX : 'center';
        $data['featuredImagePosition']['y'] = !empty($featuredImagePosY) ? $featuredImagePosY : 'center';


        $theme = get_post_meta(get_the_id(), 'theme_color', true);
        $data['themeColor'] = !empty($theme) ? $theme : 'purple';

        $globalGoals = get_the_terms(get_queried_object_id(), 'project_global_goal');
        if (!empty($globalGoals)) {
            $globalGoals = array_map(function ($item) {
                $item = (array) $item;
                $featuredImage = get_term_meta($item['term_id'], 'featured_image', true);
                $item['featuredImageUrl'] = !empty($featuredImage) ? $featuredImage : '';

                return $item;
            }, $globalGoals);

            $globalGoals = array_filter($globalGoals, function ($item) {
                return !empty($item);
            });
        }

        $data['globalGoals'] = !empty($globalGoals) ? $globalGoals : array();


        return $data;
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
            __('Challenge', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            __('Challenges', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            $args,
            array(),
            $restArgs
        );

        // Enable archive modules
        $postType->enableArchiveModules();

        // Global goals
        $postType->addTaxonomy(
            'project'. '_global_goal',
            __('Global goal', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            __('Global goals', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            array('hierarchical' => true, 'show_ui' => true)
        );

        $postType->addTaxonomy(
            'challenge_category',
            __('Category', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            __('Categories', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            array(
              'hierarchical' => false,
              'show_ui' => true,
            )
        );
    }
}
