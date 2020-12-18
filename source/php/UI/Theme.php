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
        add_action('municipio/view/after_hero', array($this, 'mapPlotData'));
        add_action('loop_start', array($this, 'outputQueryInfo'));
    }

    public function outputQueryInfo($query)
    {
        if (!is_object($query)) {
            return;
        }

        // Query info should only be displayed for post project.
        if (!is_archive() || get_post_type() !== 'project') {
            return;
        }

        $output = null;    // Output for return value.
        $postCount = \Municipio\Helper\Query::getPaginationData()['postCount'];
        $postTotal = \Municipio\Helper\Query::getPaginationData()['postTotal'];

        // If no posts.
        // TODO: Will not post any output but placeholder exists for feature improvements.
//        if (!isset($postCount) || !$postCount || $postCount == 0) {
//            $output = __('Could not find any', 'import_projects');
//        }‚

        // If posts.
        if (isset($postCount) && !$postCount || $postCount > 0) {
            $output = __('Showing', PROJECTMANAGERINTEGRATION_TEXTDOMAIN) . ' ' . $postCount . ' ' . strtolower(__('Of', PROJECTMANAGERINTEGRATION_TEXTDOMAIN)) . ' ' . $postTotal . ' ' . strtolower(__('Results', PROJECTMANAGERINTEGRATION_TEXTDOMAIN));
        }

        // Return if no output could be crated.
        if (!isset($output) || !$output || !is_string($output)) {
            return;
        }

        echo '<div class="grid-xs-12 u-element"><p>' . $output . '</p></div>';
    }

    public function mapPlotData($query)
    {
        global $wp_query;
        $query = $wp_query;
        if (!is_archive() || $wp_query->query['post_type'] !== 'project') {
            return;
        }


        // Set map default center position to Helsingborg.
        // Center position should be recalculated if any markers gets added to map.
        $center = array(
            'lat' => 56.05,
            'lng' => 12.716667
        );


        if (is_object($query) && $query->is_main_query() && isset($query->query['post_type'])) {
            if (is_single()) {
                return false;
            }

            if (isset($_GET['filter'])) {
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
                        'geo' => get_field('map', $postItem->ID),
                        'permalink' => get_permalink($postItem->ID)
                    );
                }
            }

            // Get center of all points
            if (is_array($result) && !empty($result)) {
                $lat = (float) 0;
                $lng = (float) 0;

                //Sum all lat lng
                foreach ($result as $latLngItem) {
                    $lat = $lat + (float) $latLngItem['geo']['lat'];
                    $lng = $lng + (float) $latLngItem['geo']['lng'];
                }

                //Calc center position
                $center = array(
                    'lat' => $lat/count($result),
                    'lng' => $lng/count($result)
                );
            }
        }

        $blade = new Blade(PROJECTMANAGERINTEGRATION_VIEW_PATH, $this->CACHE_PATH);
        echo $blade->view()->make('partials.area.map', array('data' => $result, 'center' => $center))->render();
    }
}
