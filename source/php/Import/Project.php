<?php

namespace ProjectManagerIntegration\Import;

class Project extends Importer
{
    public $postType = 'project';

    public function init()
    {
        add_filter('ProjectManagerIntegration/Import/Importer/metaKeys', array($this, 'setTermMetaKeys'), 10, 2);
    }

    public function setTermMetaKeys($metaKeys, $term)
    {
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
          $this->postType . '_technology' => $technology ?? null,
          $this->postType . '_sector' => $sector ?? null,
          $this->postType . '_organisation' => array_merge($organisation ?? [], $participants ?? []) ?? null,
          $this->postType . '_global_goal' => $global_goal ?? null,
          $this->postType . '_partner' => $partner ?? null,
                            'challenge_category' => $challenge_category ?? null
        );

        $this->taxonomies = array_keys($data);

        return $data;
    }

    public function mapMetaKeys($post)
    {
        extract($post);

        if (!empty($challenge) && isset($challenge['ID'])) {
            $challengePostObject = $this->getPost(
                array(
                    'key' => 'uuid',
                    'value' => $challenge['ID']
                ),
                'challenge'
            );

            if ($challengePostObject) {
                $challenge = $challengePostObject->ID;
            }
        }

        $data = array(
          'uuid' => $id,
          'last_modified' => $modified,
          'internal_project' => $internal_project ?? null,
          'address' => $address ?? null,
          'contacts' => $contacts ?? null,
          'links' => $links ?? null,
          'files' => $files ?? null,
          'gallery' => $gallery ?? null,
          'video' => $video ?? null,
          'map' => $map ?? null,
          'project_what' => $project_what ?? null,
          'project_why' => $project_why ?? null,
          'project_how' => $project_how ?? null,
          'impact_goals' => $impact_goals ?? null,
          'estimated_budget' => $estimated_budget ?? null,
          'cost_so_far' => $cost_so_far ?? null,
          'challenge' => $challenge ?? null,
          'previous_status_progress_value' => $previous_status_progress_value ?? null,
          'previous_status' => $previous_status ?? null,
          'resident_involvement' => $resident_involvement ?? null,
          'platforms' => $platforms ?? null,
        );

        return $data;
    }
}
