<?php

namespace ProjectManagerIntegration\Import;

class Challenge extends Importer
{
    public $postType = 'challenge';

    public function mapTaxonomies($post)
    {
        extract($post);

        $data = array(
            'challenge_category' => $challenge_category ?? null
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
        );

        return $data;
    }
}
