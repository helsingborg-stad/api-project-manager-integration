<?php

namespace ProjectManagerIntegration\UI;

class ScrollSpyMenu
{
    public static function create(array $items): array
    {
        return array_filter(
            $items,
            fn ($i) => empty($i['disabled'])
                && !empty($i['label'])
                && !empty($i['anchor'])
                && strpos($i['anchor'], '#') === 0
        );
    }
}
