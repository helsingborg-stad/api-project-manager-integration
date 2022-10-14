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
        $postMeta = self::queryPostMeta($postId);

        $isNull = fn () => !in_array($metaKey, array_keys($postMeta)) || $postMeta[$metaKey] === null;
        $isEmptyString = fn () => is_string($postMeta[$metaKey]) && empty($postMeta[$metaKey]);
        $isEmptyArray = fn () => is_array($postMeta[$metaKey]) && empty($postMeta[$metaKey]);

        $caseEmptyArray = fn () => $isEmptyArray() ? $defaultValue : $postMeta[$metaKey];
        $caseEmptyString = fn () => $isEmptyString() ? $defaultValue : $caseEmptyArray();
        $caseNull = fn () => $isNull() ? $defaultValue : $caseEmptyString();

        return !empty($metaKey) ? $caseNull() : $postMeta;
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
