<?php

namespace ProjectManagerIntegration\Shortcodes\ExtendedQuote;

use ProjectManagerIntegration\Helper\Blade;

class ExtendedQuote
{
    private static $shortcode = 'extended_quote';

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
        $atts = shortcode_atts(array(
            'quote' => '',
            'author' => '',
        ), $atts);

        $data = array_merge($atts, array());

        return Blade::render('source/php/Shortcodes/ExtendedQuote/extended-quote.blade.php', $data);
    }
}
