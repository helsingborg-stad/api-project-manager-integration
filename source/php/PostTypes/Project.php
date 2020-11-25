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

        $data['scrollSpyMenuItems'] =  array();
        $data['scrollSpyMenuItems'][] = array(
            'label' => __('Background', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            'anchor' => '#article',
        );

        $impactGoalsMeta = get_post_meta(get_the_id(), 'impact_goals', true);
        if (!empty($impactGoalsMeta)) {
            $data['scrollSpyMenuItems'][] = array(
                'label' => __('Impact goals', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'anchor' => '#impactgoals',
            );

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

        // Status
        if (!empty(get_the_terms(get_queried_object_id(), 'project_status'))) {
            $statusValues = array(
                'implementerat' => 100,
                'skalas' => 75,
                'testas' => 50,
                'utforskas' => 25,
            );

            $statusTerm = get_the_terms(get_queried_object_id(), 'project_status')[0];

            $data['statusBar'] = array(
                'label' => $statusTerm->name,
                'value' => in_array($statusTerm->slug, array_keys($statusValues)) ? $statusValues[$statusTerm->slug] : 0,
            );


            $data['project']['meta'][] = array(
                'title' => __('Status', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'content' => get_the_terms(get_queried_object_id(), 'project_status')[0]->name
            );
        }

        // Investments
        $investmentTypes = get_post_meta(get_the_id(), 'investment_type', true);
        if (!empty($investmentTypes)) {
            $investments = array_filter(array_map(function ($type) {
                $metaValue = get_post_meta(get_the_id(), 'investment_' . $type, true);
    
                if (!is_numeric($metaValue)) {
                    return false;
                }
    
                return array(
                    'unit' => $type === 'amount' ? 'kr' : ' hours',
                    'value' => $metaValue,
                    'type' => $type
                );
            }, $investmentTypes), function ($item) {
                return $item;
            });
            $data['project']['investments'] = $investments;

            if (!empty($investments)) {
                $investmentString = implode(', ', array_map(function ($investment) {
                    return $investment['value'] . $investment['unit'];
                }, $investments));

                $data['project']['meta'][] = array(
                    'title' => 'Investment',
                    'content' => $investmentString
                );
            }
        }

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

        /**
         * Content pieces
         */
        $contentPieces = array(
            array(
                'title' => __('What', PROJECTMANAGERINTEGRATION_TEXTDOMAIN) . '?',
                'content' => get_post_meta(get_the_id(), 'project_what', true),
            ),
            array(
                'title' => __('Why', PROJECTMANAGERINTEGRATION_TEXTDOMAIN) . '?',
                'content' => get_post_meta(get_the_id(), 'project_what', true),
            ),
            array(
                'title' => __('How', PROJECTMANAGERINTEGRATION_TEXTDOMAIN) . '?',
                'content' => get_post_meta(get_the_id(), 'project_what', true),
            ),
        );

        $data['project']['contentPieces'] = array_filter($contentPieces, function ($item) {
            return !empty($item['content']);
        });


        if (!empty($data['project']['meta'])) {
            $data['scrollSpyMenuItems'][] = array(
                'label' => __('About', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'anchor' => '#about',
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
