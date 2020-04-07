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
    }
}