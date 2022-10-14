<?php

namespace ProjectManagerIntegration\Controller;

class Platform
{
    public $postType = 'platform';

    public function __construct()
    {
        add_filter('Municipio/viewData', array($this, 'singleViewController'));
    }

    public function singleViewController($data)
    {
        if (!is_singular('platform')) {
            return $data;
        }

        $data = is_array($data) ? $data : [];

        $featuredImagePosX = get_post_meta(get_the_id(), 'cover_image_position_x', true);
        $featuredImagePosY = get_post_meta(get_the_id(), 'cover_image_position_y', true);

        $data['labels'] = [
            'contacts' => __('Contacts', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            'documents' => __('Documents', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            'links' => __('Links', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            'relatedProjects' => __('Initiatives related to the platform', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            'roadmap' => __('Roadmap', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
        ];

        $data['featuredImagePosition'] = array();
        $data['featuredImagePosition']['x'] = !empty($featuredImagePosX) ? $featuredImagePosX : 'center';
        $data['featuredImagePosition']['y'] = !empty($featuredImagePosY) ? $featuredImagePosY : 'center';

        $data['features'] = get_post_meta(get_the_id(), 'platform_features', true) ?? [];
        $data['links'] = get_post_meta(get_the_id(), 'links', true) ?? [];
        $data['contacts'] = get_post_meta(get_the_id(), 'contacts', true) ?? [];

        $data['files'] = self::buildFiles(get_post_meta(get_the_id(), 'files', true) ?? []);
        $data['roadmap'] = self::buildRoadmap(get_post_meta(get_the_id(), 'platform_roadmap', true) ?? []);
        $data['youtubeUrl'] = \build_youtube_url(get_post_meta(get_the_id(), 'video_url', true) ?? '');

        $data['getStartedHeading'] = get_post_meta(get_the_id(), 'get_started_heading', true) ?? __('Get started', PROJECTMANAGERINTEGRATION_TEXTDOMAIN);
        $data['getStartedContent'] = get_post_meta(get_the_id(), 'get_started_content', true) ?? '';

        $data['projects'] = get_posts([
            'post_type' => 'project',
            'posts_per_page' => -1,
            'meta_key' => 'platforms',
            'meta_value' => get_post_meta(get_queried_object_id(), 'uuid', true),
            'meta_compare' => 'LIKE'
        ]);

        $data['scrollSpyMenuItems'] =  array();
        $data['scrollSpyMenuItems'][] = array(
            'label' => __('Background', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            'anchor' => '#background',
        );

        if (!empty($data['features'])) {
            $data['scrollSpyMenuItems'][] = array(
                'label' => __('Features', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'anchor' => '#features',
            );
        }

        if (!empty($data['getStartedContent'])) {
            $data['scrollSpyMenuItems'][] = array(
                'label' => __('Get started', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'anchor' => '#get-started',
            );
        }

        if (!empty($data['roadmap']) && !empty($data['roadmap']['items'])) {
            $data['scrollSpyMenuItems'][] = array(
                'label' => __('Roadmap', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'anchor' => '#roadmap',
            );
        }


        return $data;
    }

    public static function buildFiles($items)
    {
        if (empty($items)) {
            return $items;
        }

        $fileClasses = [
            'image' => 'pricon-file-image',
            'video' => 'pricon-file-video',
            'audio' => 'pricon-file-audio',
            'pdf' => 'pricon-file-pdf',
            'text' => 'pricon-file-text',
            'presentation' => 'pricon-presentation',
            'archive' => 'pricon-file-archive',
            'file' => 'pricon-file',
        ];

        $fileTypes = [
            'image' => [
                'png',
                'tif',
                'tiff',
                'bmp',
                'eps',
                'jpg',
                'jpeg',
                'gif',
            ],
            'video' => [
                'mp4',
                'mov',
                'wmv',
                'avi',
                'flv',
            ],
            'audio' => [
                'mp3',
                'wav',
            ],
            'pdf' => [
                'pdf'
            ],
            'text' => [
                'docx',
                'txt',
                'md',
                'avi',
                'flv',
            ],
            'presentation' => [
                'pptx',
                'ppfm',
                'ppt',
            ],
            'archive' => [
                'zip',
                'rar',
                'gz',
            ],
        ];

        return array_map(function ($item) use ($fileTypes, $fileClasses) {
            $item['classNames'] = [];
            $pathParts = pathinfo($item['attachment']);
            if ($pathParts && $pathParts['extension']) {
                $fileType = array_filter($fileTypes, function ($item) use ($pathParts) {
                    return in_array(strtolower($pathParts['extension']), $item);
                });

                $fileType = count($fileType) === 1 ? array_keys($fileType)[0] : false;
            }

            $fileType = $fileType ?? 'file';

            $item['classNames'][] = 'pricon';
            $item['classNames'][] = $fileClasses[$fileType];

            return $item;
        }, $items);
    }

    public static function buildRoadmap($items)
    {
        $itemsPerRow = count($items) > (4 * 2) ? 4 : 3;
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
                3 => array('grid-xs-8','grid-md-5', 'grid-lg-4'),
                4 => array('grid-xs-8','grid-md-5', 'grid-lg-3'),
            ][$itemsPerRow],
            'statusColors' => [
                'not-started' => '#ffe200',
                'in-progress' => '#1992a6',
                'done' => '#19a636',
            ]
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
            $item['timestamp'] =  strtotime($item['date']);
            $item['date'] = ucfirst(date_i18n('F Y', $item['timestamp']));
            $item['past'] =  time() > $item['timestamp'];
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
