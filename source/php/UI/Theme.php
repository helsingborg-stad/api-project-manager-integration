<?php

namespace ProjectManagerIntegration\UI;

use Philo\Blade\Blade as Blade;

class Theme
{
    private $VIEWS_PATHS;
    // private $CONTROLLER_PATH;
    private $CACHE_PATH;
    
    public function __construct()
    {
        // $this->VIEWS_PATHS = apply_filters('Municipio/blade/view_paths', array(
        //     // get_stylesheet_directory() . '/views',
        //     // get_template_directory() . '/views'
            

        // ));
        
        // $this->VIEWS_PATHS = array_unique($this->VIEWS_PATHS);
        $this->CACHE_PATH = WP_CONTENT_DIR . '/uploads/cache/blade-cache';

        add_action('loop_start', array($this, 'mapPlotData'));
    }

    public function mapPlotData($query)
    {
        // Add view
        // if (isset($center) ) {
        //     $blade = new Blade($this->VIEWS_PATHS, $this->CACHE_PATH);
        //     echo $blade->view()->make('partials.area.map')->render();
        // }

        $result = null;
        $center = null;
        
        $blade = new Blade(PROJECTMANAGERINTEGRATION_VIEW_PATH, $this->CACHE_PATH);
        echo $blade->view()->make('partials.area.map', array('data' => $result, 'center' => $center))->render();
    }
}