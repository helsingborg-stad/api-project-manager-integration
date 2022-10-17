<?php

namespace ProjectManagerIntegration\UI;

class ProjectStatus
{
    public static function create(int $projectId = 0): array
    {
        $project = $projectId > 0 ? $projectId : get_queried_object_id();
        if (!empty(get_the_terms($project, 'project_status'))) {
            $currentStatus = get_the_terms($project, 'project_status')[0];
            $progress = get_term_meta($currentStatus->term_id, 'progress_value', true);
            $isCancelled = 0 > (int) $progress;

            if ($isCancelled) {
                $previousProgress = get_post_meta($project, 'previous_status_progress_value', true);
                $progress = (int) $previousProgress >= 0 ? $previousProgress : 0;
            }

            return array(
                'label' => $currentStatus->name,
                'value' => (int) $progress ?? 0,
                'explainer' => $currentStatus->description ?? '',
                'explainer_html' => term_description($currentStatus->term_id) ?? '',
                'isCancelled' => $isCancelled,
            );
        }

        return [];
    }
}
