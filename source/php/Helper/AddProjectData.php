<?php

namespace ProjectManagerIntegration\Helper;

class AddProjectData
{
    public static function addPostTags($post, $postId) {

        $postTags = [];
                
        if (!empty(get_the_terms($postId, 'project_sector'))) {
            $postTags = array_merge($postTags, get_the_terms($postId, 'project_sector'));
        }
                
        if (!empty(get_the_terms($postId, 'project_technology'))) {
                    $postTags = array_merge($postTags, get_the_terms($postId, 'project_technology'));
        }
                
        if (empty(!$postTags)) {
            $postTags = array_reduce(
                $postTags,
                function ($accumilator, $term) {
                    if (empty($accumilator)) {
                        $accumilator = '<span>' . $term->name . '</span>';
                    } else {
                        $accumilator .= ' / ' . '<span>' . $term->name . '</span>';
                    }
                
                    return $accumilator;
                },
                '',
            );
        }
        $post->statusBar = \ProjectManagerIntegration\UI\ProjectStatus::create($postId);

        $post->taxonomies = $postTags;        

        return $post;
    }

    public static function addPostData($post, $postId) {
        $post->category = !empty(get_the_terms($postId, 'challenge_category')) 
        ? get_the_terms($postId, 'challenge_category')[0]->name : false; 

        if(!$post->thumbnail){
            $post->thumbnail = municipio_get_thumbnail_source($postId, "sm", '12:16');
        }

        if(!$post->permalink) {
            $post->permalink = get_permalink( $postId);
        }

        return $post;
    }
}
