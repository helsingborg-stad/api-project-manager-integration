<?php

namespace ProjectManagerIntegration;

use ProjectManagerIntegration\Helper\CacheBust as CacheBust;

class App
{
    public function __construct()
    {
        new PostTypes\Project();
        new PostTypes\Challenge();
        new Import\Setup();
        new Options();
        new UI\Theme();

        // Add view paths
        add_filter('Municipio/blade/view_paths', array($this, 'addViewPaths'), 2, 1);

        add_action('wp_enqueue_scripts', array($this, 'enqueueStyles'));

        add_action('wp_enqueue_scripts', array($this, 'enqueueStyles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueueScripts'));
        add_filter('language_attributes', array($this, 'wpBodyClasses'), 999);
    }

    public function wpBodyClasses($output)
    {
        if (is_singular('project')) {
            $output .= ' data-header-offset="158"';
        } else {
            $output .= ' data-header-offset="80"';
        }


        return $output;
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
        wp_register_script(
            'project-manager-integration-js',
            PROJECTMANAGERINTEGRATION_URL .
                                '/dist/' .
                                CacheBust::name('js/project-manager-integration.js'),
            array('jquery'),
            false,
            true
        );

        wp_enqueue_script('project-manager-integration-js');
    }

    /**
     * Add searchable blade template paths
     * @param array  $array Template paths
     * @return array        Modified template paths
     */
    public function addViewPaths($array)
    {
        // Add view path first in the list
        array_unshift($array, PROJECTMANAGERINTEGRATION_VIEW_PATH);

        return $array;
    }
}
