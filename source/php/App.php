<?php

namespace ProjectManagerIntegration;

class App
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueueStyles'));
        add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));
    }

    /**
     * Enqueue required style
     * @return void
     */
    public function enqueueStyles()
    {
        wp_register_style('api-project-manager-integration-css', PROJECTMANAGERINTEGRATION_URL . '/dist/' . \ProjectManagerIntegration\Helper\CacheBust::name('css/api-project-manager-integration.css'));
    }

    /**
     * Enqueue required scripts
     * @return void
     */
    public function enqueueScripts()
    {
        wp_register_script('api-project-manager-integration-js', PROJECTMANAGERINTEGRATION_URL . '/dist/' . \ProjectManagerIntegration\Helper\CacheBust::name('js/api-project-manager-integration.js'));
    }
}
