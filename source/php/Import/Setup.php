<?php

namespace ProjectManagerIntegration\Import;

class Setup
{
    public function __construct()
    {
        //Add manual import button(s)
        add_action('restrict_manage_posts', array($this, 'addImportButton'), 100);

        add_action('admin_init', array($this, 'importPosts'));

        /* Register cron action */
        add_action('import_projects_daily', array($this, 'projectEventsCron'));
    }

    public function importPosts()
    {
        if (!isset($_GET['import_projects'])) {
            return;
        }

        $url = get_field('project_api_url', 'option');

        new \ProjectManagerIntegration\Import\Importer($url);
    }

     /**
     * Add manual import button
     * @return bool|null
     */
    public function addImportButton()
    {
        global $wp;

        if(isset(get_current_screen()->post_type) && get_current_screen()->post_type == "project") {
            $queryArgs = array_merge($wp->query_vars, array('import_projects' => 'true')); 
            echo '<a href="' . add_query_arg('import_projects', 'true', $_SERVER['REQUEST_URI']) . '" class="button-primary extraspace" style="float: right; margin-right: 10px;">'. __("Import projects", PROJECTMANAGERINTEGRATION_TEXTDOMAIN) .'</a>'; 
        }
    }

    /**
     * Start cron jobs
     * @return void
     */
    public function projectEventsCron()
    {
        $url = get_field('project_api_url', 'option');

        new \ProjectManagerIntegration\Import\Importer($url);
    }

    public static function addCronJob()
    {
        wp_schedule_event(time(), 'hourly', 'import_projects_daily');
    }

    public static function removeCronJob()
    {
        wp_clear_scheduled_hook('import_projects_daily');
    }
}
