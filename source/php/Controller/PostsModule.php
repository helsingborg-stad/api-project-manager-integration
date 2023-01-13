<?php

namespace ProjectManagerIntegration\Controller;

use ProjectManagerIntegration\Helper\Municipio;

class PostsModule
{
    public function __construct()
    {
        add_filter("Modularity/Display/mod-posts/viewData", [$this, 'mapPostData']);
    }

    public function mapPostData($data)
    {
        $postTypes = ['project', 'challenge', 'platform'];

        if (
            !empty($data['posts_data_post_type'])
            && !empty($data['posts'])
            && in_array($data['posts_data_post_type'], $postTypes)
        ) {
            $mergeObjectsProperties = fn (array $array1, array $array2) => array_map(
                fn (object $a, object $b): object => (object) array_merge((array) $a, (array) $b),
                $array1,
                $array2
            );

            $postsWithMixCase = $mergeObjectsProperties(
                $data['posts'],
                Municipio::mapPosts($data['posts'])
            );

            $data['posts'] = $postsWithMixCase;
        }

        return $data;
    }
}
