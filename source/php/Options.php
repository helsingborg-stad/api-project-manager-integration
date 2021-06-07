<?php

namespace ProjectManagerIntegration;

class Options
{
    public function __construct()
    {
        add_filter('acf/load_field/name=organisation_filter', array($this, 'setupOrganisationFilters'));

        if (function_exists('acf_add_options_sub_page')) {
            acf_add_options_sub_page(array(
                'page_title' => _x('Project Manager Integration settings', 'ACF', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'menu_title' => _x('Options', 'Project Manager Integration settings', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'menu_slug' => 'project-options',
                'parent_slug' => 'edit.php?post_type=project',
                'capability' => 'manage_options'
            ));
            
            acf_add_options_sub_page(array(
                'page_title' => _x('Challenge options', 'ACF', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'menu_title' => _x('Challenge options', 'Project Manager Integration settings', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'menu_slug' => 'challenge-options',
                'parent_slug' => 'edit.php?post_type=challenge',
                'capability' => 'manage_options'
            ));

            acf_add_options_sub_page(array(
                'page_title' => _x('Platform options', 'ACF', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'menu_title' => _x('Platform options', 'Project Manager Integration settings', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'menu_slug' => 'platform-options',
                'parent_slug' => 'edit.php?post_type=platform',
                'capability' => 'manage_options'
            ));
        }

        add_filter('acf/update_value/name=project_daily_import', array($this, 'registerCronjob'), 10, 1);
    }

    public function registerCronjob($value)
    {
        if ($value) {
            \ProjectManagerIntegration\Import\Setup::addCronJob();
        } else {
            \ProjectManagerIntegration\Import\Setup::removeCronJob();
        }

        return $value;
    }

    public function setupOrganisationFilters($field)
    {
        $organisationApiUrl = get_field('project_api_url', 'option') . '/organisation';
        $fieldValues = array();

        $totalPages = 1;
        for ($i = 1; $i <= $totalPages; $i++) {
            $url = add_query_arg(
                array(
                    'page' => $i,
                    'per_page' => 50,
                ),
                $organisationApiUrl
            );

            $requestResponse = \ProjectManagerIntegration\Helper\Request::get($url);

            if (is_wp_error($requestResponse)) {
                return $field;
            }

            $totalPages = $requestResponse['headers']['x-wp-totalpages'] ?? $totalPages;

            foreach ($requestResponse['body'] as $organisation) {
                if ($organisation['count'] > 0) {
                    $fieldValues[$organisation['id']] = $organisation['name'] . ' (' . $organisation['count']  . ')';
                }
            }
        }

        // Associated sort, retain keys.
        asort($fieldValues, SORT_STRING);
        
        $field['choices'] = array(
                0 => __('Import all organisations', PROJECTMANAGERINTEGRATION_TEXTDOMAIN)
            ) + $fieldValues;

        return $field;
    }
}
