<?php

namespace ProjectManagerIntegration;

class Options
{
    public function __construct()
    {
        if (function_exists('acf_add_options_sub_page')) {
            acf_add_options_sub_page(array(
                'page_title' => _x('Project Manager Integration settings', 'ACF', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'menu_title' => _x('Options', 'Project Manager Integration settings', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'menu_slug' => 'project-options',
                'parent_slug' => 'edit.php?post_type=project',
                'capability' => 'manage_options'
            ));
        }

        add_filter('acf/update_value/name=project_daily_import', array($this, 'registerCronjob'), 10, 1);
    }

    public function registerCronjob($value) 
    {
        if($value) {
            \ProjectManagerIntegration\Import\Setup::addCronJob();
        } else {
            \ProjectManagerIntegration\Import\Setup::removeCronJob();
        }

        return $value;
    }
}
