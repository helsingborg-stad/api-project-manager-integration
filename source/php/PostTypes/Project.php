<?php

namespace ProjectManagerIntegration\PostTypes;

class Project
{
    public $postType = 'project';

    public function __construct()
    {
        add_action('init', array($this, 'registerPostType'), 9);
        add_filter('Municipio/viewData', array($this, 'singleViewController'));
    }

    public function singleViewController($data)
    {
        if (!is_singular('project')) {
            return $data;
        }

        $data['project'] = array();

        // Contacts
        $contactsMeta = get_post_meta(get_the_id(), 'contacts', false);
        if (!empty($contactsMeta) && !empty($contactsMeta[0])) {
            $data['project']['contacts'] = $contactsMeta[0];
        }

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
            'supports'           => array('title', 'editor', 'thumbnail'),
            'show_in_rest'       => true,
        );

        $restArgs = array(
          'exclude_keys' => array('author', 'acf', 'guid', 'link', 'template', 'meta', 'taxonomy', 'menu_order')
        );

        $postType = new \ProjectManagerIntegration\Helper\PostType(
            $this->postType,
            __('Project', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            __('Projects', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            $args,
            array(),
            $restArgs
        );

        // Statuses
        $postType->addTaxonomy(
            $this->postType . '_status',
            __('Status', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            __('Statuses', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            array('hierarchical' => false, 'show_ui' => false)
        );

        // Technologies
        $postType->addTaxonomy(
            $this->postType . '_technology',
            __('Technology', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            __('Technologies', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            array('hierarchical' => true, 'show_ui' => false)
        );

        // Sectors
        $postType->addTaxonomy(
            $this->postType . '_sector',
            __('Sector', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            __('Sectors', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            array('hierarchical' => true, 'show_ui' => false)
        );

        // Organisations
        $postType->addTaxonomy(
            $this->postType . '_organisation',
            __('Organisation', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            __('Organisations', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            array('hierarchical' => true, 'show_ui' => false)
        );

        // Global goals
        $postType->addTaxonomy(
            $this->postType . '_global_goal',
            __('Global goal', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            __('Global goals', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            array('hierarchical' => true, 'show_ui' => false)
        );

        // Categories
        $postType->addTaxonomy(
            $this->postType . '_category',
            __('Category', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            __('Categories', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            array('hierarchical' => true, 'show_ui' => false)
        );

        // Partners
        $postType->addTaxonomy(
            $this->postType . '_partner',
            __('Partner', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            __('Partners', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            array('hierarchical' => true, 'show_ui' => false)
        );
    }
}
