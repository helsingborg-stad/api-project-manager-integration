<?php

namespace ProjectManagerIntegration\Import;

class Project extends Importer
{
    public $postType = 'project';

    public function mapTaxonomies($post)
    {
        extract($post);

        $data = array(
          $this->postType . '_status' => $status ?? null,
          $this->postType . '_technology' => $technology ?? null,
          $this->postType . '_sector' => $sector ?? null,
          $this->postType . '_organisation' => $organisation ?? null,
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

        $data = array(
          'uuid' => $id,
          'last_modified' => $modified,
          'internal_project' => $internal_project ?? null,
          'address' => $address ?? null,
          'contacts' => $contacts ?? null,
          'links' => $links ?? null,
          'map' => $map ?? null,
          'project_what' => $project_what ?? null,
          'project_why' => $project_why ?? null,
          'project_how' => $project_how ?? null,
          'impact_goals' => $impact_goals ?? null,
          'investment_type' => $investment_type ?? null,
          'investment_amount' => $investment_amount ?? null,
          'investment_hours' => $investment_hours ?? null,
        );

        return $data;
    }
}
