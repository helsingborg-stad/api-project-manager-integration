<?php

namespace ProjectManagerIntegration\PostTypes;

class Challenge
{
    public static $postType = 'challenge';

    public function __construct()
    {
        add_action('init', array($this, 'registerPostType'), 9);
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
            self::$postType,
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
            'project' . '_global_goal',
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

        $postType->addTaxonomy(
            'challenge_focal_point',
            __('Focal Point', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            __('Focal Points', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            array(
                'hierarchical' => false,
                'show_ui' => true,
            )
        );
    }
}
