<?php

/**
 * @deprecated in favor if Municipio\Helper\WP.
 */

namespace ProjectManagerIntegration\Helper;

use \Municipio\Helper\FormatObject;
use \Municipio\Helper\Post;

class Municipio
{
    public static function getPosts(array $wpQueryArgs = null): array
    {
        return self::mapPosts(get_posts($wpQueryArgs));
    }

    public static function mapPosts($posts): array
    {
        $reduceToOneCallback = fn ($callbacks) => fn ($item) =>
        array_reduce($callbacks, function ($carry, $closure) {
            return $closure($carry);
        }, $item);

        $mapGeneralPostData = function (object $post): object {
            $post->permalink = get_permalink($post->id);
            $post->thumbnail =
                Post::getFeaturedImage($post->id, [800, 800]);

            return $post;
        };

        return array_map(
            $reduceToOneCallback([
                fn ($p) => (object) $p,
                fn ($p) => !isset($p->postType) ? FormatObject::camelCase((object) $p) : $p,
                fn ($p) => $mapGeneralPostData($p),
                fn ($p) => apply_filters('ProjectManagerIntegration/Helper/Municipio/mapPost', $p, $p->postType),
                fn ($p) => apply_filters("ProjectManagerIntegration/Helper/Municipio/{$p->postType}/mapPost", $p),
            ]),
            $posts
        );
    }
}
