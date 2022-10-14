<?php

namespace ProjectManagerIntegration\Helper;

class WP
{
    public static function getPostTermsJoined(string $taxonomy, int $postId = 0): string
    {
        return array_reduce(WP::getPostTerms($taxonomy, $postId), array(__CLASS__, 'reduceTermsToString'), '');
    }

    public static function getPostTerms(string $taxonomy, int $postId = 0): array
    {
        $terms = get_the_terms(
            $postId > 0 ? $postId : get_queried_object_id(),
            $taxonomy
        );

        return !empty($terms) && !is_wp_error($terms) ? $terms : [];
    }

    private static function reduceTermsToString($accumilator, $item)
    {
        if (empty($accumilator)) {
            $accumilator = '<span>' . $item->name . '</span>';
        } else {
            $accumilator .= ', ' . '<span>' . $item->name . '</span>';
        }

        return $accumilator;
    }

    public static function getPostMeta(string $metaKey = '', $defaultValue = null, int $postId = 0)
    {
        return !empty($metaKey)
                ? in_array($metaKey, array_keys(self::queryPostMeta($postId))) && self::queryPostMeta($postId)[$metaKey] !== null
                    ? is_string(self::queryPostMeta($postId)[$metaKey]) && empty(self::queryPostMeta($postId)[$metaKey])
                        ? $defaultValue
                        : (is_array(self::queryPostMeta($postId)[$metaKey]) && empty(self::queryPostMeta($postId)[$metaKey])
                            ? $defaultValue
                            : (self::queryPostMeta($postId)[$metaKey]))
                    : ($defaultValue)
                : (self::queryPostMeta($postId));
    }

    private static function queryPostMeta(int $postId = 0): array
    {
        return array_merge(
            array_map(
                [__CLASS__, 'mapRemoveNullVaulesFromArrays'],
                array_map(
                    [__CLASS__, 'mapUnserializePostMetaValue'],
                    array_map(
                        [__CLASS__, 'mapFlattenPostMetaValue'],
                        get_post_meta($postId > 0 ? $postId : get_queried_object_id()) ?? []
                    )
                )
            ),
            []
        );
    }

    private static function mapRemoveNullVaulesFromArrays($metaValue)
    {
        return is_array($metaValue) ? array_filter($metaValue, fn ($i) => $i !== null) : $metaValue;
    }

    private static function mapFlattenPostMetaValue(array $metaValue)
    {
        return $metaValue[0] ?? $metaValue;
    }

    private static function mapUnserializePostMetaValue($metaValue)
    {
        return maybe_unserialize($metaValue);
    }
}
