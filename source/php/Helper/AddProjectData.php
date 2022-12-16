<?php

namespace ProjectManagerIntegration\Helper;

class AddProjectData
{
    public function addPostData($post, $postId) {

        $post->category = !empty(get_the_terms($postId, 'challenge_category')) 
                ? get_the_terms($postId, 'challenge_category')[0]->name 
                : false; 

        $post->thumbnail = municipio_get_thumbnail_source($postId,array(634,846), '12:16');

        $post->url = get_permalink( $postId);

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
}
