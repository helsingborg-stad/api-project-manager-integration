<?php

namespace ProjectManagerIntegration\Controller;

use ProjectManagerIntegration\Helper\Municipio;
use ProjectManagerIntegration\Helper\WP;
use ProjectManagerIntegration\UI\RelatedPosts;
use ProjectManagerIntegration\UI\FeaturedImage;

class Challenge
{
    public function __construct()
    {
        $postType = \ProjectManagerIntegration\PostTypes\Challenge::$postType;
        add_filter("Municipio/Template/{$postType}/single/viewData", [$this, 'singleViewController']);
        add_filter("Municipio/Template/{$postType}/archive/viewData", [$this, 'archiveViewController']);
        add_filter("ProjectManagerIntegration/Helper/Municipio/{$postType}/mapPost", [$this, 'mapChallengePostData']);
        add_filter('Municipio/theme/key', array($this, 'setThemeColorBasedOnMeta'));
    }

    public function mapChallengePostData(object $post): object
    {
        $post->challenge = (object) [
            'category'      => WP::getPostTermsJoined(['challenge_category'], $post->id) ?? '',
        ];

        return $post;
    }

    public function setThemeColorBasedOnMeta($color)
    {
        if (is_singular('challenge')) {
            $theme = get_post_meta(get_the_id(), 'theme_color', true);
            $color = !empty($theme) ? $theme : 'purple';
        }

        return $color;
    }
    public function archiveViewController($data)
    {
        $data['posts'] = Municipio::mapPosts($data['posts']);
        return $data;
    }

    public function singleViewController($data)
    {
        $data['challenge'] = [
            'hero' => [
                'image' => FeaturedImage::getFeaturedImage(),
                'meta' => get_post_type_object(get_post_type())->labels->singular_name,
                'title' => get_the_title(),
            ],
            'relatedPosts'          =>  RelatedPosts::create(),
            'relatedProjects'       =>  self::createRelatedProjects(),
            'labels' => [
                'showAll'           => __('Show all', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'contact'           => __('Contact', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            ],
            'category' => array_map(
                fn ($t) => !isset($t->name) ? false : $t->name,
                (array) get_the_terms(get_queried_object_id(), 'challenge_category')
            )[0] ?? '',
            'contacts' => get_post_meta(get_the_id(), 'contacts', true),
            'preamble' => get_post_meta(get_the_id(), 'preamble', true),
            'featuredImagePosition' => [
                'x' => get_post_meta(get_the_id(), 'featured_image_position_x', true) ?: 'center',
                'y' => get_post_meta(get_the_id(), 'featured_image_position_y', true) ?: 'center',
            ],
            'themeColor' => get_post_meta(get_the_id(), 'theme_color', true) ?: 'purple',
            'globalGoalsTitle' => get_field('global_goal_title', 'options') ?? __('Global Goals', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            'globalGoalsDescription' => get_field('global_goal_description', 'options'),
            'globalGoals' => WP::getPostTerms(['project_global_goal'], get_queried_object_id(), ['meta_key' => 'featured_image', 'fields' => 'ids']),
            'focalPointTitle' => get_field('focal_point_title', 'options') ?? __('Focal Points', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            'focalPointDescription' => get_field('focal_point_description', 'options'),
            'focalPoints' => $this->mapTerms('challenge_focal_point', ['url'], ['url']),
            'postTypeObject' => get_post_type_object(get_post_type()),
            'archive' => get_post_type_archive_link(get_post_type()),
        ];

        return $data;
    }


    protected static function createRelatedProjects()
    {
        return RelatedPosts::create(
            0,
            'project',
            __('Innovation initiatives linked to the challenge', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            [
                'post_type' => 'project',
                'posts_per_page' => -1,
                'meta_key' => 'challenge',
                'meta_value' => get_queried_object_id()
            ]
        );
    }

    public function mapTerms(string $taxonomy, array $termMetaKeys, array $requiredTermMetaKeys)
    {
        $terms = get_the_terms(get_queried_object_id(), $taxonomy);

        if (empty($terms) || !is_array($terms)) {
            return array();
        }

        $terms = array_map(function ($term) use ($termMetaKeys, $requiredTermMetaKeys) {
            $term = (array) $term;

            if (!empty($termMetaKeys)) {
                foreach ($termMetaKeys as $key) {
                    $meta = get_term_meta($term['term_id'], $key, true);
                    if ($meta) {
                        $term[$key] = $meta;
                    }
                }
            }

            return $term;
        }, $terms);


        if (!empty($requiredTermMetaKeys) && is_array($requiredTermMetaKeys)) {
            foreach ($requiredTermMetaKeys as $requiredKey) {
                $terms = array_filter($terms, function ($term) use ($requiredKey) {
                    return !empty($term[$requiredKey]);
                });
            }
        }

        return !empty($terms) ? $terms : array();
    }
}
