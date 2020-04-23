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
        $this->CACHE_PATH = WP_CONTENT_DIR . '/uploads/cache/blade-cache';

        // TODO: Should not be running mapPlotData action during loop_start
        add_action('loop_start', array($this, 'mapPlotData'));
    }

    public function mapPlotData($query)
    {
        if (is_object($query) && $query->is_main_query() && isset($query->query['post_type'])) {
            
            if (is_single()) {
                return false;
            }

            if(isset($_GET['filter'])) {
                $allPosts = $query->posts;
            } else {
                 $allPosts = get_posts(array(
                    'numberposts' => -1,
                    'post_type' => $query->query['post_type']
                ));
            }

            if (isset($allPosts) && is_array($allPosts) && !empty($allPosts)) {
                
                $result = array();

                foreach ($allPosts as $postItem) {
                    if ($postItem->post_status != "publish") {                    
                        continue;
                    }

                    $map = get_field('map', $postItem->ID);
                    if (!isset($map)) {
                        continue;
                    }

                    $result[] = array(
                        'location' => $postItem->post_title,
                        'excerpt' => wp_trim_words($postItem->post_content, 20),
                        'geo' => get_field('map', $postItem->ID)
                    );
                }
            }
        }

        $center = null;

        $blade = new Blade(PROJECTMANAGERINTEGRATION_VIEW_PATH, $this->CACHE_PATH);
        echo $blade->view()->make('partials.area.map', array('data' => $result, 'center' => $center))->render();
    }
}