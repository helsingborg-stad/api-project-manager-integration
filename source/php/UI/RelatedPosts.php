<?php

namespace ProjectManagerIntegration\UI;

use \Municipio\Helper\FormatObject;
use \Municipio\Helper\Post;

class RelatedPosts
{
    public static function create(
        int $postId = 0,
        string $postType = '',
        string $relatedPostTitle = ''
    ): array {
        $post = $postId > 0 ? $postId : get_queried_object_id();
        $type = !empty($postType) ? $postType : get_post_type();

        $posts = get_posts([
            'post_type' => $type,
            'posts_per_page' => 4,
            'exclude' => array($post),
            'orderby' => 'rand'
        ]) ?? [];

        $postTypeObject = get_post_type_object($type);

        $title = !empty($relatedPostTitle) ? $relatedPostTitle : __(
            'Fler',
            PROJECTMANAGERINTEGRATION_TEXTDOMAIN
        ) . ' ' . $postTypeObject->labels->all_items;

        $mapPostDataForMunicipio = fn ($p) => (object) array_merge(
            (array) FormatObject::camelCase($p),
            [
                'permalink' => get_permalink($p->ID),
                'thumbnail' => Post::getFeaturedImage($p->ID, [800, 800]),
            ]
        );

        return count($posts) > 0 ? [
            'title'         => $title,
            'posts'         => array_map(
                $mapPostDataForMunicipio,
                $posts
            )
        ] : [];
    }
}
