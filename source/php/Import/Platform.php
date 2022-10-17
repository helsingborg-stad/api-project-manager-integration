<?php

namespace ProjectManagerIntegration\Import;

class Platform extends Importer
{
    public $postType = 'platform';

    public function init()
    {
        add_filter('ProjectManagerIntegration/Import/Importer/metaKeys', array($this, 'setTermMetaKeys'), 10, 2);
    }

    public function setTermMetaKeys($metaKeys, $term)
    {
        // error_log(var_dump($term));
        extract($term);

        // if ($taxonomy === 'status') {
        //     $statusMetaKeys = array(
        //         'progress_value' => (int) $progress_value ?? null,
        //     );

        //     $metaKeys = array_merge($metaKeys, $statusMetaKeys);
        // }

        return $metaKeys;
    }

    public function mapTaxonomies($post)
    {
        // error_log("====[ Map Tax ]====");
        // error_log(print_r($post, true));
        extract($post);

        $data = array(
            //   $this->postType . '_status' => $status ?? null,
            //   $this->postType . '_partner' => $partner ?? null,
        );

        $this->taxonomies = array_keys($data);

        return $data;
    }

    public function mapMetaKeys($post)
    {
        extract($post);

        $data = array(
            'uuid' => $id,
            'last_modified' => $modified,
            'files' => $files ?? null,
            'contacts' => $contacts ?? null,
            'links' => $links ?? null,
            'video_url' => $video_url ?? null,
            'platform_features' => $platform_features ?? null,
            'platform_roadmap' => $platform_roadmap ?? null,
            'cover_image' => $cover_image ?? null,
            'cover_image_position_x' => $cover_image_position_x ?? null,
            'cover_image_position_y' => $cover_image_position_y ?? null,
            'get_started_heading' => $get_started_heading ?? null,
            'get_started_content' => $get_started_content ?? null,
        );

        return $data;
    }
}
