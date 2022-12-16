<?php

namespace ProjectManagerIntegration\Shortcodes\PostTypeLink;

use ProjectManagerIntegration\Helper\Blade;

class PostTypeLink
{
    public static $shortcode = 'post_type_link';

    public function __construct()
    {
        add_filter('init', array($this, 'addShortcode'), 10, 1);
    }

    public function addShortcode()
    {
        add_shortcode(self::$shortcode, array($this, 'shortcodeController'));
    }

    public function shortcodeController($atts)
    {

        $data = array();
        $post = get_post($atts['id']);

        if ($post) {
            $atts = shortcode_atts(array(
                'id' => 0,
                'title' => $post->post_title,
                'meta' => get_post_type_object($post->post_type)->labels->singular_name,
                'url' => get_permalink($post->ID),
                'imageUrl' => municipio_get_thumbnail_source($post->ID, array(75, 56), '3:2'),
                'buttonText' => __('Open', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'blank' => 0
            ), $atts);

            $data = array_merge($atts, array());
        }

        // Show error to logged in users
        if (empty($post) && empty($atts) && is_user_logged_in()) {
            return __('Please define a valid post id or overrride fields', PROJECTMANAGERINTEGRATION_TEXTDOMAIN);
        }

        return Blade::render('source/php/Shortcodes/PostTypeLink/post-type-link.blade.php', $data);
    }
}
