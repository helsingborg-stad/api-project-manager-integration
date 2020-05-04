<?php

namespace ProjectManagerIntegration;

use ProjectManagerIntegration\Helper\CacheBust as CacheBust;

class App
{
    public function __construct()
    {
        new PostTypes\Project();
        new Import\Setup();
        new Options();
        new UI\Theme();

        // Add view paths
        add_filter('Municipio/blade/view_paths', array($this, 'addViewPaths'), 2, 1);

        add_action('wp_enqueue_scripts', array($this, 'enqueueStyles'));
        // add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));
    }

    /**
     * Enqueue required style
     * @return void
     */
    public function enqueueStyles()
    {
        wp_enqueue_style(
            'project-manager-integration-css',
            PROJECTMANAGERINTEGRATION_URL .
                        '/dist/' .
                        CacheBust::name('css/project-manager-integration.css'),
            array(),
            ''
        );
    }

    /**
     * Enqueue required scripts
     * @return void
     */
    public function enqueueScripts()
    {
        wp_register_script('project-manager-integration-js', PROJECTMANAGERINTEGRATION_URL . '/dist/' . \ProjectManagerIntegration\Helper\CacheBust::name('js/project-manager-integration.js'));
    }

    /**
     * Add searchable blade template paths
     * @param array  $array Template paths
     * @return array        Modified template paths
     */
    public function addViewPaths($array)
    {
        // If child theme is active, insert plugin view path after child views path.
        if (is_child_theme()) {
            array_splice($array, 2, 0, array(PROJECTMANAGERINTEGRATION_VIEW_PATH));
        } else {
            // Add view path first in the list if child theme is not active.
            array_unshift($array, PROJECTMANAGERINTEGRATION_VIEW_PATH);
        }

        return $array;
    }
}
