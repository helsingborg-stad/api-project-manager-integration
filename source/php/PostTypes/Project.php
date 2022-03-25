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

        $impactGoalsMeta = array_filter(get_post_meta(get_the_id(), 'impact_goals', true), function ($item) {
            return !empty($item['impact_goal']);
        });

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

        // Resident Involvement
        $data['project']['resident_involvement'] = array_map(function ($item) {
            return $item['description'];
        }, get_post_meta(get_the_id(), 'resident_involvement', true) ?? []);

        // Resident Involvement - add to scrollspy menu
        if (!empty($data['project']['resident_involvement'])) {
            $data['scrollSpyMenuItems'][] = array(
                'label' => __('Resident involvement', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'anchor' => '#residentInvolvement',
            );
        }

        // Contacts
        $data['project']['contacts'] = get_post_meta(get_the_id(), 'contacts', true) ?? null;

        // Media
        $data['project']['files'] = get_post_meta(get_the_id(), 'files', true) ?? null;
        $data['project']['links'] = get_post_meta(get_the_id(), 'links', true) ?? null;
        $data['project']['video'] = get_post_meta(get_the_id(), 'video', true) ?? null;

        // Global Goals
        if (!empty(get_the_terms(get_queried_object_id(), 'project_global_goal'))) {
            $data['project']['globalGoals'] = get_the_terms(get_queried_object_id(), 'project_global_goal');
        }

        //Meta
        $data['project']['meta'] = array();

        // Powered by
        if (!empty(get_the_terms(get_queried_object_id(), 'project_organisation'))) {
            $data['project']['meta'][] = array(
                'title' => __('Powered by', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'content' => array_reduce(get_the_terms(get_queried_object_id(), 'project_organisation'), array($this, 'reduceTermsToString'), '')
            );
        }

        // Challenge
        $challengeId = get_post_meta(get_the_id(), 'challenge', true);
        $challengeObject = !empty($challengeId) ? get_post($challengeId) : false;
        if ($challengeObject && $challengeObject->post_type === 'challenge') {
            $data['project']['meta'][] = array(
                'title' => __('Challenge', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'content' => $challengeObject->post_title,
                'url'  => get_permalink($challengeObject->ID)
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
            $prevStatusMeta = get_post_meta(get_the_id(), 'previous_status_progress_value', true);
            $isCancelled = false;

            if (0 > (int) $statusMeta) {
                $statusMeta = (int) $prevStatusMeta >= 0 ? $prevStatusMeta : 0;
                $isCancelled = true;
            }

            $data['statusBar'] = array(
                'label' => $statusTerm->name,
                'value' => (int) $statusMeta ?? 0,
                'explainer' => $statusTerm->description ?? '',
                'explainer_html' => term_description($statusTerm->term_id) ?? '',
                'isCancelled' => $isCancelled,
            );
        }

        // Technologies
        if (!empty(get_the_terms(get_queried_object_id(), 'project_technology'))) {
            $data['project']['meta'][] = array(
                'title' => __('Technologies', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'content' => array_reduce(get_the_terms(get_queried_object_id(), 'project_technology'), array($this, 'reduceTermsToString'), '')
            );
        }

        // estimatedBudget
        $estimatedBudget = get_post_meta(get_the_id(), 'estimated_budget', true);
        if (!empty($estimatedBudget)) {
            $data['project']['meta'][] = array(
                'title' => __('Estimated Budget', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'content' => $estimatedBudget . ' kr'
            );
        }

        // spentSoFar
        $fundsUsed = get_post_meta(get_the_id(), 'funds_used', true);
        if (!empty($fundsUsed)) {
            $costSoFar = array_reduce($fundsUsed, function ($total, $item) {
                return $total + (int)$item['amount'];
            }, 0);

            $data['project']['meta'][] = array(
                'title' => __('Cost so far', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'content' => $costSoFar . ' kr'
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
                'content' => get_post_meta(get_the_id(), 'project_why', true),
            ),
            array(
                'title' => __('How', PROJECTMANAGERINTEGRATION_TEXTDOMAIN) . '?',
                'content' => get_post_meta(get_the_id(), 'project_how', true),
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
            $accumilator = '<span>' . $item->name . '</span>';
        } else {
            $accumilator .= ', ' . '<span>' . $item->name . '</span>';
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
