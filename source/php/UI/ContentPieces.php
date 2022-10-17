<?php

namespace ProjectManagerIntegration\UI;

class ContentPieces
{
    public static function create(array $items): array
    {
        $replaceH2Tags = fn ($content) => preg_replace('/<h2(.*?)<\/h(.*?)>/', '<h3$1</h3>', $content);

        return array_map(
            fn ($piece) => [
                'content' => $replaceH2Tags($piece['content']),
                'title' => $piece['title']
            ],
            array_filter(
                $items,
                fn ($i) => !empty($i['content']) && !empty($i['title'])
            )
        );
    }
}
