<?php

namespace ProjectManagerIntegration\PostTypes;

class Project
{
    public $postType = 'project';

    public function __construct()
    {
        add_action('init', array($this, 'registerPostType'), 9);
        add_filter('Municipio/viewData', array($this, 'singleViewController'));
        add_filter('Municipio/viewData', array($this, 'archiveViewController'));
    }

    public function archiveViewController($data)
    {
        global $wp_query;
        if (!is_archive() || $wp_query->query['post_type'] !== 'project') {
            return $data;
        }

        $data['noResultLabels'][0] = __('We found no results for your search', PROJECTMANAGERINTEGRATION_TEXTDOMAIN);
        $data['noResultLabels'][1] = __('Try to refine your search.', PROJECTMANAGERINTEGRATION_TEXTDOMAIN);

        return $data;
    }

    public function singleViewController($data)
    {
        if (!is_singular('project')) {
            return $data;
        }

        $data['project'] = array();

        $impactGoalsMeta = get_post_meta(get_the_id(), 'impact_goals', true);
        if (!empty($impactGoalsMeta)) {
            $data['project']['impact_goals'] = $impactGoalsMeta;
        }
    
        // Contacts
        $contactsMeta = get_post_meta(get_the_id(), 'contacts', false);
        if (!empty($contactsMeta) && !empty($contactsMeta[0])) {
            $data['project']['contacts'] = $contactsMeta[0];
        }

        // Global Goals
        if (!empty(get_the_terms(get_queried_object_id(), 'project_global_goal'))) {
            $data['project']['globalGoals'] = get_the_terms(get_queried_object_id(), 'project_global_goal');
        }

        //Meta
        $data['project']['meta'] = array();

        // Organisation
        if (!empty(get_the_terms(get_queried_object_id(), 'project_organisation'))) {
            $data['project']['meta'][] = array(
                'title' => __('Organisation', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'content' => get_the_terms(get_queried_object_id(), 'project_organisation')[0]->name
            );
        }

        // Partners
        if (!empty(get_the_terms(get_queried_object_id(), 'project_partner'))) {
            $data['project']['meta'][] = array(
                'title' => __('Partners', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'content' => array_reduce(get_the_terms(get_queried_object_id(), 'project_partner'), array($this, 'reduceTermsToString'), '')
            );
        }

        // Status
        if (!empty(get_the_terms(get_queried_object_id(), 'project_status'))) {
            $data['project']['meta'][] = array(
                'title' => __('Status', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'content' => get_the_terms(get_queried_object_id(), 'project_status')[0]->name
            );
        }

        // Sector
        if (!empty(get_the_terms(get_queried_object_id(), 'project_sector'))) {
            $data['project']['meta'][] = array(
                'title' => __('Sector', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'content' => array_reduce(get_the_terms(get_queried_object_id(), 'project_sector'), array($this, 'reduceTermsToString'), '')
            );
        }

        // Technologies
        if (!empty(get_the_terms(get_queried_object_id(), 'project_technology'))) {
            $data['project']['meta'][] = array(
                'title' => __('Technologies', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'content' => array_reduce(get_the_terms(get_queried_object_id(), 'project_technology'), array($this, 'reduceTermsToString'), '')
            );
        }

        /**
         * Add header based on project key name
         */
        $objectId = get_queried_object_id();
        array_map(function ($item) use ($objectId, &$data) {

            // Split ie 'project_what' in two
            $itemParts = explode('_', $item);

            // Create header using last part in splitted array -> 'What'
            $header = ucfirst($itemParts[1]);

            // Create key - camelCase, using first part in splitted array -> projectWhat
            $key = $itemParts[0] . $header;

            // Array ie. 'projectWhat' used in blade template 'post-single-project.blade.php'
            // Inject header translation and content body to the created projectWhat array
            $data[$key]['header'] = __($header . '?', PROJECTMANAGERINTEGRATION_TEXTDOMAIN);
            $postMeta = get_post_meta($objectId, $item, true);
            $data[$key]['content'] = !empty($postMeta) ? $postMeta : null;
        }, ['project_what', 'project_why', 'project_how']);

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
