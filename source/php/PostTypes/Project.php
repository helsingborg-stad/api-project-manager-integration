<?php

namespace ProjectManagerIntegration\PostTypes;

use ProjectManagerIntegration\Helper\WP;

class Project
{
    public static $postType = 'project';

    public function __construct()
    {
        add_action('init', array($this, 'registerPostType'), 9);
    }

    public function registerPostType()
    {
        $customPostType = new \ProjectManagerIntegration\Helper\PostType(
            self::$postType,
            __('Project', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            __('Projects', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            [
                'menu_icon'          => 'dashicons-portfolio',
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                'supports'           => ['title', 'editor', 'thumbnail'],
                'show_in_rest'       => true,
            ],
            [],
            ['exclude_keys' => ['author', 'acf', 'guid', 'link', 'template', 'meta', 'taxonomy', 'menu_order']]
        );

        foreach (self::taxonomies() as $taxonomy) {
            $customPostType->addTaxonomy(
                $taxonomy['slug'],
                $taxonomy['singular'],
                $taxonomy['plural'],
                $taxonomy['args'],
            );
        }
    }

    private static function taxonomies()
    {
        return [
            [
                'slug'      =>  'project_status',
                'singular'  =>  __('Status', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'plural'    =>  __('Statuses', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'args'      =>  ['hierarchical' => false, 'show_ui' => false]
            ],
            [
                'slug'      =>  'project_technology',
                'singular'  =>  __('Technology', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'plural'    =>  __('Technologies', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'args'      =>  ['hierarchical' => true, 'show_ui' => false]
            ],
            [
                'slug'      =>  'project_sector',
                'singular'  =>  __('Sector', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'plural'    =>  __('Sectors', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'args'      =>  ['hierarchical' => true, 'show_ui' => false]
            ],
            [
                'slug'      =>  'project_organisation',
                'singular'  =>  __('Organisation', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'plural'    =>  __('Organisations', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'args'      =>  ['hierarchical' => true, 'show_ui' => false]
            ],
            [
                'slug'      =>  'project_global_goal',
                'singular'  =>  __('Global goal', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'plural'    =>  __('Global goals', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'args'      =>  ['hierarchical' => true, 'show_ui' => false]
            ],
            [
                'slug'      =>  'project_category',
                'singular'  =>  __('Category', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'plural'    =>  __('Categories', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'args'      =>  ['hierarchical' => true, 'show_ui' => false]
            ],
            [
                'slug'      =>  'project_partner',
                'singular'  =>  __('Partner', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'plural'    =>  __('Partners', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'args'      =>  ['hierarchical' => true, 'show_ui' => false]
            ],
            [
                'slug'      =>  'challenge_category',
                'singular'  =>  __('Category', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'plural'    =>  __('Categories', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'args'      =>  ['hierarchical' => false, 'show_ui' => true]
            ],
        ];
    }
}
