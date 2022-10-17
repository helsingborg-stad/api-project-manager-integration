<?php

// Public functions

if (!function_exists('in_array_any')) {
    function in_array_any($needles, $haystack)
    {
        return (bool)array_intersect($needles, $haystack);
    }
}


if (!function_exists('build_youtube_url')) {
    function build_youtube_url($videoUrl)
    {
        if (empty($videoUrl)) {
            return false;
        }

        parse_str(parse_url($videoUrl, PHP_URL_QUERY), $parsedUrl);
        $videoID = $parsedUrl['v'] ?? false;

        if (!$videoID) {
            return false;
        }

        $youtubeParams = [
            'iv_load_policy' => '3',
            'rel' => '0',
            'modestbranding' => '1',
            'controls' => '0',
            'origin' => get_home_url(),
        ];

        return 'https://www.youtube.com/embed/' . $videoID . '?' . build_query($youtubeParams);
    }
}
