<?php

namespace ProjectManagerIntegration\Controller;

use ProjectManagerIntegration\Helper\WP;

class Project
{
    public function __construct()
    {
        add_filter('Municipio/viewData', array($this, 'viewController'));
    }

    public function viewController($data)
    {
        global $wp_query;

        if (is_singular(\ProjectManagerIntegration\PostTypes\Project::$postType)) {
            return $this->singleViewController($data);
        }

        if (is_archive() && $wp_query->query['post_type'] === \ProjectManagerIntegration\PostTypes\Project::$postType) {
            return $this->archiveViewController($data);
        }

        return $data;
    }

    public function singleViewController($data)
    {
        $data['project'] = array_merge(
            WP::getPostMeta(),
            [
                'contentPieces'         =>  $this->contentPieces(),
                'meta'                  =>  $this->meta(),
                'statusBar'             =>  $this->statusBar(),
                'files'                 =>  WP::getPostMeta('files', []),
                'contacts'              =>  WP::getPostMeta('contacts', []),
                'links'                 =>  WP::getPostMeta('links', []),
                'address'               =>  WP::getPostMeta('address', []),
                'residentInvolvement'   =>  WP::getPostMeta('resident_involvement', []),
                'impactGoals'           =>  WP::getPostMeta('impact_goals', []),
            ]
        );

        $data['scrollSpyMenuItems'] = $this->scrollSpyMenuItems($data);

        return $data;
    }

    public function archiveViewController($data)
    {
        $data['noResultLabels'][0] = __('We found no results for your search', PROJECTMANAGERINTEGRATION_TEXTDOMAIN);
        $data['noResultLabels'][1] = __('Try to refine your search.', PROJECTMANAGERINTEGRATION_TEXTDOMAIN);

        return $data;
    }

    private function contentPieces(): array
    {
        return \ProjectManagerIntegration\UI\ContentPieces::create([
            [
                'title' => __('What', PROJECTMANAGERINTEGRATION_TEXTDOMAIN) . '?',
                'content' => WP::getPostMeta('project_what', null),
            ],
            [
                'title' => __('Why', PROJECTMANAGERINTEGRATION_TEXTDOMAIN) . '?',
                'content' => WP::getPostMeta('project_why', null),
            ],
            [
                'title' => __('How', PROJECTMANAGERINTEGRATION_TEXTDOMAIN) . '?',
                'content' => WP::getPostMeta('project_how', null),
            ]
        ]);
    }

    private function meta(): array
    {
        return \ProjectManagerIntegration\UI\MetaBoxes::create([
            [
                'title'     => __('Powered by', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'content'   => WP::getPostTermsJoined('project_organisation') ?? null,
                'url'       => null,
            ],
            [
                'title'     => __('Challenge', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'content'   => WP::getPostMeta('challenge', false) ? get_the_title(WP::getPostMeta('challenge')) : null,
                'url'       => WP::getPostMeta('challenge', false) ? get_permalink(WP::getPostMeta('challenge')) : null,
            ],
            [
                'title'     => __('Category', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'content'   => WP::getPostTermsJoined('challenge_category') ?? null,
                'url'       => null,
            ],
            [
                'title'     => __('Technologies', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'content'   => WP::getPostTermsJoined('project_technology') ?? null,
                'url'       => null,
            ],
            [
                'title'     => __('Estimated Budget', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'content'   => WP::getPostMeta('estimated_budget', false)
                    ? WP::getPostMeta('estimated_budget') . ' kr' : null,
                'url'       => null,
            ],
            [
                'title'     => __('Cost so far', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'content'   => WP::getPostMeta('funds_used', false)
                    ? array_reduce(
                        WP::getPostMeta('funds_used', false),
                        fn ($a, $i) => $a + (int) $i['amount'],
                        0
                    ) . ' kr'
                    : null,
                'url'       => null,
            ],
            [
                'title'     => __('Investment', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'content'   => '$investmentString',
                'url'       => null,
            ],
            [
                'title'     => __('Sector', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'content'   => WP::getPostTermsJoined('project_sector') ?? null,
                'url'       => null,
            ],
            [
                'title'     => __('Partners', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'content'    => WP::getPostTermsJoined('project_partner') ?? null,
                'url'       => null,
            ],
        ]);
    }

    private function scrollSpyMenuItems($data): array
    {
        return \ProjectManagerIntegration\UI\ScrollSpyMenu::create([
            [
                'label'     =>  __('Background', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'anchor'    => '#article',
                'disabled'  =>  false,
            ],
            [
                'label'     => __('Impact goals', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'anchor'    => '#impactgoals',
                'disabled'  =>  empty(WP::getPostMeta('impact_goals', [])),
            ],
            [
                'label'     => __('Resident involvement', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'anchor'    => '#residentInvolvement',
                'disabled'  =>  empty(WP::getPostMeta('resident_involvement', null)),
            ],
            [
                'label'     => __('About', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'anchor'    => '#about',
                'disabled'  =>  empty($data['project']['meta']),
            ],
        ]);
    }

    private function statusBar()
    {
        if (!empty(get_the_terms(get_queried_object_id(), 'project_status'))) {
            $statusTerm = get_the_terms(get_queried_object_id(), 'project_status')[0];
            $statusMeta = get_term_meta($statusTerm->term_id, 'progress_value', true);
            $prevStatusMeta = get_post_meta(get_the_id(), 'previous_status_progress_value', true);
            $isCancelled = 0 > (int) $statusMeta;

            if ($isCancelled) {
                $statusMeta = (int) $prevStatusMeta >= 0 ? $prevStatusMeta : 0;
            }

            return array(
                'label' => $statusTerm->name,
                'value' => (int) $statusMeta ?? 0,
                'explainer' => $statusTerm->description ?? '',
                'explainer_html' => term_description($statusTerm->term_id) ?? '',
                'isCancelled' => $isCancelled,
            );
        }

        return null;
    }
}
