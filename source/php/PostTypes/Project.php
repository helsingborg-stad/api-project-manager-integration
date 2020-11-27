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

            // Order completed goals first
            $impactGoalsMeta = array_reduce($impactGoalsMeta, function ($accumilator, $item) {
                if ($item['impact_goal_completed']) {
                    $accumilator['completed'][] = $item;
                    return $accumilator;
                }
                
                $accumilator['notCompleted'][] = $item;
                return $accumilator;
            }, array(
                'completed' => array(),
                'notCompleted' => array(),
            ));

            $data['project']['impact_goals'] = array_merge($impactGoalsMeta['completed'], $impactGoalsMeta['notCompleted']);
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


        // Challenge
        $challengeId = get_post_meta(get_the_id(), 'challenge', true);
        $challengeObject = !empty($challengeId) ? get_post($challengeId) : false;
        if ($challengeObject && $challengeObject->post_type === 'challenge') {
            $data['project']['meta'][] = array(
                'title' => __('Challenge', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'content' => $challengeObject->post_title
            );
        }

        // Category
        $categories = get_the_terms(get_queried_object_id(), 'challenge_category');
        if (!empty($categories)) {
            $categories = array_map(function ($item) {
                return $item->name;
            }, $categories);

            $data['project']['meta'][] = array(
                'title' => __('Category', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'content' => implode(', ', $categories),
            );
        }


        // Status
        if (!empty(get_the_terms(get_queried_object_id(), 'project_status'))) {
            $statusTerm = get_the_terms(get_queried_object_id(), 'project_status')[0];
            $statusMeta = get_term_meta($statusTerm->term_id, 'progress_value', true);

            $data['statusBar'] = array(
                'label' => $statusTerm->name,
                'value' => $statusMeta ?? 0,
                'explainer' => $statusTerm->description ?? '',
                'explainer_html' => term_description($statusTerm->term_id) ?? '',
            );

            $data['project']['meta'][] = array(
                'title' => __('Status', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'content' => get_the_terms(get_queried_object_id(), 'project_status')[0]->name
            );
        }

        // Technologies
        if (!empty(get_the_terms(get_queried_object_id(), 'project_technology'))) {
            $data['project']['meta'][] = array(
                'title' => __('Technologies', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'content' => array_reduce(get_the_terms(get_queried_object_id(), 'project_technology'), array($this, 'reduceTermsToString'), '')
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
                    'unit' => $type === 'amount' ? ' kr' : ' ' . __('hours', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
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
                    'title' => __('Investment', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    'content' => $investmentString
                );
            }
        }

        // Sector
        if (!empty(get_the_terms(get_queried_object_id(), 'project_sector'))) {
            $data['project']['meta'][] = array(
                'title' => __('Sector', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'content' => array_reduce(get_the_terms(get_queried_object_id(), 'project_sector'), array($this, 'reduceTermsToString'), '')
            );
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
            array('hierarchical' => false, 'show_ui' => true)
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
