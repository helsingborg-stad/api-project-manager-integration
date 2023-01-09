<?php

namespace ProjectManagerIntegration\Controller;

use ProjectManagerIntegration\Helper\WP;
use ProjectManagerIntegration\UI\ProjectStatus;
use ProjectManagerIntegration\UI\RelatedPosts;

class Project
{
    public function __construct()
    {
        add_filter('Municipio/viewData', array($this, 'viewController'));
        add_filter('the_content', array($this, 'replaceContentWithContentPieces'), 10, 1);
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
                'statusBar'             =>  ProjectStatus::create(),
                'files'                 =>  WP::getPostMeta('files', []),
                'contacts'              =>  WP::getPostMeta('contacts', []),
                'links'                 =>  WP::getPostMeta('links', []),
                'residentInvolvement'   =>  WP::getPostMeta('resident_involvement', []),
                'impactGoals'           =>  WP::getPostMeta('impact_goals', []),
                'relatedPosts'          =>  RelatedPosts::create(),
                'labels'                => [
                    'contact'   => __('Contact', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    'email'     => __('E-mail', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    'name'      => __('Name', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    'files'     => __('Files', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    'links'     => __('Links', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                ]
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
            ],
            [
                'title' => '<span class="u-sr__only">' . __('Video', PROJECTMANAGERINTEGRATION_TEXTDOMAIN) . '</span>',
                'content' => WP::embed(WP::getPostMeta('video', [['url' => false]])[0]['url'] ?: ''),
            ],
            [
                'title' => __('Lessons learned', PROJECTMANAGERINTEGRATION_TEXTDOMAIN) . '?',
                'content' => WP::getPostMeta('project_lessons_learned', null),
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

    public function replaceContentWithContentPieces($content)
    {
        if (!is_singular('project')) {
            return $content;
        }

        $controlledContent = implode(
            PHP_EOL,
            array_map(
                fn ($piece) => "<h2>{$piece['title']}</h2>" . PHP_EOL . $piece['content'],
                apply_filters('Municipio/viewData', [])['project']['contentPieces'] ?? []
            )
        );

        return "<div>{$controlledContent}</div>";
    }
}
