<?php

namespace ProjectManagerIntegration\Controller;

class Challenge
{
    public $postType = 'challenge';

    public function __construct()
    {
        add_filter('Municipio/theme/key', array($this, 'setThemeColorBasedOnMeta'));
        add_filter('Municipio/viewData', array($this, 'singleViewController'));
    }

    public function setThemeColorBasedOnMeta($color)
    {
        if (is_singular('challenge')) {
            $theme = get_post_meta(get_the_id(), 'theme_color', true);
            $color = !empty($theme) ? $theme : 'purple';
        }

        return $color;
    }

    public function singleViewController($data)
    {
        if (!is_singular('challenge')) {
            return $data;
        }

        $category = get_the_terms(get_queried_object_id(), 'challenge_category');

        if (!empty($category)) {
            $data['category'] = $category[0]->name;
        }

        $data['contacts'] = get_post_meta(get_the_id(), 'contacts', true);

        $data['preamble'] = get_post_meta(get_the_id(), 'preamble', true);

        $featuredImagePosX = get_post_meta(get_the_id(), 'featured_image_position_x', true);
        $featuredImagePosY = get_post_meta(get_the_id(), 'featured_image_position_y', true);
        $data['featuredImagePosition'] = array();
        $data['featuredImagePosition']['x'] = !empty($featuredImagePosX) ? $featuredImagePosX : 'center';
        $data['featuredImagePosition']['y'] = !empty($featuredImagePosY) ? $featuredImagePosY : 'center';
        $theme = get_post_meta(get_the_id(), 'theme_color', true);
        $data['themeColor'] = !empty($theme) ? $theme : 'purple';


        $data['globalGoalsTitle'] = get_field('global_goal_title', 'options') ?? __('Global Goals', PROJECTMANAGERINTEGRATION_TEXTDOMAIN);
        $data['globalGoalsDescription'] = get_field('global_goal_description', 'options');
        $data['globalGoals'] = $this->mapTerms('project_global_goal', ['featured_image'], ['featured_image']);

        $data['focalPointTitle'] = get_field('focal_point_title', 'options') ?? __('Focal Points', PROJECTMANAGERINTEGRATION_TEXTDOMAIN);
        $data['focalPointDescription'] = get_field('focal_point_description', 'options');
        $data['focalPoints'] = $this->mapTerms('challenge_focal_point', ['url'], ['url']);



        $data['relatedProjects'] = get_posts([
            'post_type' => 'project',
            'posts_per_page' => -1,
            'meta_key' => 'challenge',
            'meta_value' => get_queried_object_id()

        ]);

        $data['relatedPosts'] = get_posts([
            'post_type' => get_post_type(),
            'posts_per_page' => 4,
            'exclude' => array(get_queried_object_id()),
            'orderby' => 'rand'
        ]);

        if(!empty($data['relatedProjects'])) {
            foreach($data['relatedProjects'] as $post) {
                \ProjectManagerIntegration\Helper\AddProjectData::addPostTags($post, $post->ID);
                \ProjectManagerIntegration\Helper\AddProjectData::addPostData($post, $post->ID);
            }
        };

        if(!empty($data['relatedPosts'])) {
            foreach($data['relatedPosts'] as $post) {
                \ProjectManagerIntegration\Helper\AddProjectData::addPostData($post, $post->ID);
            }
        };

        $data['postTypeObject'] = get_post_type_object(get_post_type());
        $data['archive'] = get_post_type_archive_link(get_post_type());

        $data['lang'] = [
            'moreChallenges' => __('More Challenges', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            'showAll' => __('Show all', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            'relatedProjects' => __('Innovation initiatives linked to the challenge', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            'contact' => __('Contact', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
        ];

        return $data;
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
