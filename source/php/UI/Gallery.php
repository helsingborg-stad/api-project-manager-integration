<?php

namespace ProjectManagerIntegration\UI;

class Gallery
{
    public static function create(array $items): array
    {
        return array_map(function($item) {
            return [
                'largeImage' => $item['sizes']['large'], 
                'smallImage' => $item['sizes']['medium'], 
                'alt' => $item['alt'], 
                'caption' => $item['caption']];
        }, $items);
    }
}
