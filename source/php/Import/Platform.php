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
        error_log(var_dump($term));
        extract($term);

        if ($taxonomy === 'status') {
            $statusMetaKeys = array(
                'progress_value' => (int) $progress_value ?? null,
            );

            $metaKeys = array_merge($metaKeys, $statusMetaKeys);
        }

        return $metaKeys;
    }

    public function mapTaxonomies($post)
    {
        extract($post);

        $data = array(
          $this->postType . '_status' => $status ?? null,
          $this->postType . '_files' => $files ?? null,
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
          'files' => $files
        );

        return $data;
    }
}
