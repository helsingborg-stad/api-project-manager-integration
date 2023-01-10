<?php

namespace ProjectManagerIntegration\UI;

use ProjectManagerIntegration\Helper\Municipio;

class RelatedPosts
{
    public static function create(
        int $postId = 0,
        string $postType = '',
        string $relatedPostTitle = '',
        array $wpQueryArgs = []
    ): array {
        $post = $postId > 0 ? $postId : get_queried_object_id();

        $type = !empty($postType) ? $postType : get_post_type();

        $postTypeObject = get_post_type_object($type);

        $title = !empty($relatedPostTitle) ? $relatedPostTitle : __(
            'Fler',
            PROJECTMANAGERINTEGRATION_TEXTDOMAIN
        ) . ' ' . $postTypeObject->labels->all_items;

        $posts = Municipio::getPosts(array_merge([
            'post_type' => $type,
            'posts_per_page' => 4,
            'exclude' => array($post),
            'orderby' => 'rand',
        ], $wpQueryArgs)) ?? [];

        return count($posts) > 0 ? [
            'title'         => $title,
            'posts'         => $posts
        ] : [];
    }
}
