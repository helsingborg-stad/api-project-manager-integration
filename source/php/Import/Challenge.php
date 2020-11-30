<?php

namespace ProjectManagerIntegration\Import;

class Challenge extends Importer
{
    public $postType = 'challenge';

    public function init()
    {
        add_filter('ProjectManagerIntegration/Import/Importer/metaKeys', array($this, 'setTermMetaKeys'), 10, 2);
    }

    public function setTermMetaKeys($metaKeys, $term)
    {
        extract($term);

        if ($taxonomy === 'global_goal') {
            $statusMetaKeys = array(
                'featured_image' => $featured_image ?? '',
            );

            $metaKeys = array_merge($metaKeys, $statusMetaKeys);
        }

        return $metaKeys;
    }
    public function mapTaxonomies($post)
    {
        extract($post);

        $data = array(
            'challenge_category' => $challenge_category ?? null,
            'project_global_goal' => $global_goal ?? null,
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
            'contacts' => $contacts ?? null,
            'theme_color' => $theme_color ?? null,
            'featured_image_position_x' => $featured_image_position_x ?? null,
            'featured_image_position_y' => $featured_image_position_y ?? null,
        );

        return $data;
    }
}
