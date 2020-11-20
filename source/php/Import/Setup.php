<?php

namespace ProjectManagerIntegration\Import;

class Setup
{
    public static $urlProjectSufix = '/project';

    public function __construct()
    {
        //Add manual import button(s)
        add_action('restrict_manage_posts', array($this, 'addImportButton'), 100);

        add_action('admin_init', array($this, 'importPosts'));

        /* Register cron action */
        add_action('import_projects_daily', array($this, 'projectEventsCron'));

        /* WP WebHooks */
        add_filter('wpwhpro/run/actions/custom_action/return_args', array($this, 'triggerImportProject'), 10, 3);
    }

    public function triggerImportProject($response, $identifier, $payload)
    {
        if ($identifier !== 'importProject') {
            return $response;
        }

        if (!isset($payload['content']) || !isset($payload['content']->post)) {
            $response['msg'] = 'Project data is missing';
            return $response;
        }

        if ($payload['content']->post->post_type !== 'project') {
            $response['msg'] = 'Can only import posts with post type "project"';
            return $response;
        }
        
        $url = get_field('project_api_url', 'option') . self::urlProjectSufix;
        $importer = new \ProjectManagerIntegration\Import\Importer($url, $payload['content']->post->ID);
        
        $response['msg'] = 'Updated post ' . $payload['content']->post->ID;

        if (empty($importer->addedPostsId)) {
            $response['msg'] = 'Project ID does not exists' . $payload['content']->post->ID;
        }

        return $response;
    }

    public function importPosts()
    {
        $avalibleImporters = [
            'project'   => "\ProjectManagerIntegration\Import\Importer",
            'challange' => "\ProjectManagerIntegration\Import\Challange",
        ];

        if (!isset($_GET['import_projects'])
            || empty($_GET['post_type'])
            || !in_array($_GET['post_type'], array_keys($avalibleImporters))) {
            return;
        }

        $ImporterClass = $avalibleImporters[$_GET['post_type']];

        if (!class_exists($ImporterClass)) {
            die;
            return;
        }
        
        $baseUrl = get_field('project_api_url', 'option');
        $url = $baseUrl . '/' . $_GET['post_type'];

        new $ImporterClass($url);
    }

    /**
    * Add manual import button
    * @return bool|null
    */
    public function addImportButton()
    {
        global $wp;
        $allowedPostTypes = array('project', 'challange');

        if (isset(get_current_screen()->post_type) && in_array(get_current_screen()->post_type, $allowedPostTypes)) {
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
        $url = get_field('project_api_url', 'option') . self::urlProjectSufix;

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
