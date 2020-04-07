<?php

namespace ProjectManagerIntegration;

class App
{
    public function __construct()
    {
        new PostTypes\Project();
        new Import\Setup();
        new Options();

        // add_action('admin_enqueue_scripts', array($this, 'enqueueStyles'));
        // add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));
    }

    /**
     * Enqueue required style
     * @return void
     */
    public function enqueueStyles()
    {
        wp_register_style('project-manager-integration-css', PROJECTMANAGERINTEGRATION_URL . '/dist/' . \ProjectManagerIntegration\Helper\CacheBust::name('css/project-manager-integration.css'));
    }

    /**
     * Enqueue required scripts
     * @return void
     */
    public function enqueueScripts()
    {
        wp_register_script('project-manager-integration-js', PROJECTMANAGERINTEGRATION_URL . '/dist/' . \ProjectManagerIntegration\Helper\CacheBust::name('js/project-manager-integration.js'));
    }
}
