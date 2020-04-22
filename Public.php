<?php

// Public functions

if (!function_exists('in_array_any')) {
    function in_array_any($needles, $haystack)
    {
        return (bool)array_intersect($needles, $haystack);
    }
}
