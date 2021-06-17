<?php

namespace ProjectManagerIntegration\PostTypes;

class Platform
{
    public $postType = 'platform';

    public function __construct()
    {
        add_action('init', array($this, 'registerPostType'), 9);
        add_filter('Municipio/viewData', array($this, 'singleViewController'));
    }

    public function singleViewController($data)
    {
        if (!is_singular('platform')) {
            return $data;
        }

        $data['platform'] = array();

        $featuredImagePosX = get_post_meta(get_the_id(), 'cover_image_position_x', true);
        $featuredImagePosY = get_post_meta(get_the_id(), 'cover_image_position_y', true);
        $data['featuredImagePosition'] = array();
        $data['featuredImagePosition']['x'] = !empty($featuredImagePosX) ? $featuredImagePosX : 'center';
        $data['featuredImagePosition']['y'] = !empty($featuredImagePosY) ? $featuredImagePosY : 'center';

        $data['platform']['files'] = get_post_meta(get_the_id(), 'files', true) ?? [];
        $data['platform']['videoUrl'] = get_post_meta(get_the_id(), 'video_url', true) ?? '';
        $data['platform']['features'] = get_post_meta(get_the_id(), 'platform_features', true) ?? [];
        $data['platform']['roadmap'] = self::buildRoadmap(get_post_meta(get_the_id(), 'platform_roadmap', true) ?? []);
        $data['platform']['contacts'] = get_post_meta(get_the_id(), 'contacts', true) ?? [];
        $data['platform']['links'] = get_post_meta(get_the_id(), 'links', true) ?? [];

        //Meta
        $data['platform']['meta'] = array();

        // Partners
        if (!empty(get_the_terms(get_queried_object_id(), 'platform_partner'))) {
            $data['platform']['meta'][] = array(
                'title' => __('Partners', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'content' => array_reduce(get_the_terms(get_queried_object_id(), 'platform_partner'), array($this, 'reduceTermsToString'), '')
            );
        }

        return $data;
    }


    public static function buildRoadmap($items)
    {
        $itemsPerRow = 3;
        $flickityOptions = array(
            'groupCells' => true,
            'cellAlign' => 'left',
            'draggable' => true,
            'wrapAround' => false,
            "pageDots" => false,
            'prevNextButtons' => false,
            'contain' => false,
            'adaptiveHeight' => false,
            'freeScroll' => true,
            'initialIndex' => '.is-initial-select'
        );

        $roadmap = [
            'items' => [],
            'pastCount' => 0,
            'totalCount' => count($items),
            'hasInitialSelect' => false,
            'itemsPerRow' => $itemsPerRow,
            'flickityOptions' => $flickityOptions,
            'gridClasses' => [
                3 => array('grid-xs-12', 'grid-md-4'),
                4 => array('grid-xs-12', 'grid-md-3'),
            ][$itemsPerRow],
        ];

        if (empty($items)) {
            return $roadmap;
        };
        
        // Sort roadmap items based on date
        uasort($items, function ($a, $b) {
            if (strtotime($a['date']) == strtotime($b['date'])) {
                return 0;
            }
            
            return (strtotime($a['date']) < strtotime($b['date'])) ? -1 : 1;
        });

        // Build roadmap and add logic for inital offset based on date
        return array_reduce($items, function ($acc, $item) {
            $item['past'] =  time() > strtotime($item['date']);
            $item['classes'] = array_merge([], $acc['gridClasses']);
            
            $pastCount = $item['past']
                ? $acc['pastCount'] + 1
                : $acc['pastCount'];

            if ($pastCount >= $acc['itemsPerRow']
                && $acc['totalCount'] >= $acc['itemsPerRow'] * 2
                && !$item['past']
                && !$acc['hasInitialSelect']) {
                $item['classes'][] = 'is-initial-select';
            }

            $acc['items'][] = $item;
            $acc['pastCount'] = $pastCount;
            $acc['hasInitialSelect'] = $acc['hasInitialSelect'] || in_array('is-initial-select', $item['classes']) ?? false;
            return $acc;
        }, $roadmap);
    }
    
    public static function reduceTermsToString($accumilator, $item)
    {
        if (empty($accumilator)) {
            $accumilator = $item->name;
        } else {
            $accumilator .= ', ' . $item->name;
        }

        return $accumilator;
    }


    public function mapTerms(string $taxonomy, array $termMetaKeys, array $requiredTermMetaKeys)
    {
        error_log(get_queried_object_id());
        $terms = get_the_terms(get_queried_object_id(), $taxonomy);
        
        error_log($terms);

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

    public function registerPostType()
    {
        $args = array(
            'menu_icon'          => 'dashicons-portfolio',
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'supports'           => array('title', 'editor', 'thumbnail', 'content', 'excerpt'),
            'show_in_rest'       => true,
        );

        $restArgs = array(
          'exclude_keys' => array('author', 'acf', 'guid', 'link', 'template', 'meta', 'taxonomy', 'menu_order')
        );

        $postType = new \ProjectManagerIntegration\Helper\PostType(
            $this->postType,
            __('Platform', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            __('Platforms', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            $args,
            array(),
            $restArgs
        );

        // Partners
        $postType->addTaxonomy(
            'project_partner',
            __('Partner', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            __('Partners', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            array('hierarchical' => true, 'show_ui' => false)
        );

        // Enable archive modules
        $postType->enableArchiveModules();
    }
}
