<?php

namespace ProjectManagerIntegration\PostTypes;

class Project
{
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
            'supports'           => array('title', 'author', 'revisions', 'editor', 'thumbnail'),
            'show_in_rest'       => true,
        );

        $restArgs = array(
          'exclude_keys' => array('author', 'acf', 'guid', 'link', 'template', 'meta', 'taxonomy', 'menu_order')
        );

        $postType = new \ProjectManagerIntegration\Helper\PostType(
            'project',
            __('Project', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            __('Projects', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            $args,
            array(),
            $restArgs
        );

        // // Statuses
        // $postType->addTaxonomy(
        //     'status',
        //     __('Status', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
        //     __('Statuses', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
        //     array('hierarchical' => true)
        // );

        // // Technologies
        // $postType->addTaxonomy(
        //     'technology',
        //     __('Technology', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
        //     __('Technologies', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
        //     array('hierarchical' => true)
        // );

        // // Sectors
        // $postType->addTaxonomy(
        //     'sector',
        //     __('Sector', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
        //     __('Sectors', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
        //     array('hierarchical' => true)
        // );

        // // Organisations
        // $postType->addTaxonomy(
        //     'organisation',
        //     __('Organisation', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
        //     __('Organisations', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
        //     array('hierarchical' => true)
        // );

        // // Global goals
        // $postType->addTaxonomy(
        //     'global_goal',
        //     __('Global goal', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
        //     __('Global goals', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
        //     array('hierarchical' => true)
        // );

        // // Categories
        // $postType->addTaxonomy(
        //     'category',
        //     __('Category', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
        //     __('Categories', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
        //     array('hierarchical' => true)
        // );
    }
}
