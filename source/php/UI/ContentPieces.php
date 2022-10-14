<?php

namespace ProjectManagerIntegration\UI;

class ContentPieces
{
    public static function create(array $items): array
    {
        return array_filter(
            $items,
            fn ($i) => !empty($i['content']) && !empty($i['title'])
        );
    }
}
