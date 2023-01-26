<?php

namespace ProjectManagerIntegration\UI;

class Gallery
{
    public static function create(array $items): array
    {
        $list = [];
        foreach($items as $item) {
            $list[] = [
                'largeImage' => $item['sizes']['large'], 
                'smallImage' => $item['sizes']['medium'], 
                'alt' => $item['alt'], 
                'caption' => $item['caption']];
        }
        return $list;
    }
}
