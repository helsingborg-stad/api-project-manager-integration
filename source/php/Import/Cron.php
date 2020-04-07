<?php

namespace ProjectManagerIntegration\Import;

class Cron
{
    public function __construct()
    {
        add_action('admin_init', array($this, 'importPosts'));
    }

    public function importPosts()
    {
        if (!isset($_GET['import_projects'])) {
            return;
        }

        $url = 'https://multi.local/inovations-api/wp-json/wp/v2/project/';

        error_log("importPosts");
        new \ProjectManagerIntegration\Import\Importer($url);
    }
}
