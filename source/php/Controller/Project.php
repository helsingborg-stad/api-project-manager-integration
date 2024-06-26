<?php

namespace ProjectManagerIntegration\Controller;

use ProjectManagerIntegration\Helper\Municipio;
use ProjectManagerIntegration\Helper\WP;
use ProjectManagerIntegration\UI\ProjectStatus;
use ProjectManagerIntegration\UI\RelatedPosts;
use ProjectManagerIntegration\UI\Gallery;
use ProjectManagerIntegration\UI\FeaturedImage;

class Project
{
    public function __construct()
    {
        $postType = \ProjectManagerIntegration\PostTypes\Project::$postType;
        add_filter("ProjectManagerIntegration/Helper/Municipio/{$postType}/mapPost", [$this, 'mapProjectPostData']);
        add_filter("Municipio/Template/{$postType}/single/viewData", [$this, 'singleViewController']);
        add_filter("Municipio/Template/{$postType}/archive/viewData", [$this, 'archiveViewController']);
        add_filter('the_content', [$this, 'replaceContentWithContentPieces'], 10, 1);
    }

    public function singleViewController($data)
    {
        $data['project'] = array_merge(
            WP::getPostMeta(),
            [
                'meta'                      =>  $this->meta(),
                'statusBar'                 =>  ProjectStatus::create(),
                'files'                     =>  WP::getPostMeta('files', []),
                'contacts'                  =>  WP::getPostMeta('contacts', []),
                'links'                     =>  WP::getPostMeta('links', []),
                'residentInvolvement'       =>  WP::getPostMeta('resident_involvement', []),
                'impactGoals'               =>  WP::getPostMeta('impact_goals', []),
                'projectStatusDescription'  =>  WP::getPostMeta('project_status_description', []),
                'relatedPosts'              =>  RelatedPosts::create(),
                'gallery'                   =>  Gallery::create(WP::getPostMeta('gallery', [])),
                'labels'                    => [
                    'contact'   => __('Contact', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    'email'     => __('E-mail', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    'name'      => __('Name', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    'files'     => __('Files', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    'links'     => __('Links', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    'showAll'   => __('Show all', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    'published' => __('Published', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    'updated'   => __('Last updated', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                ],
                 'archive' => get_post_type_archive_link(get_post_type()),
                 'image' => FeaturedImage::getFeaturedImage(),

            ]
        );

        $data['scrollSpyMenuItems'] = $this->scrollSpyMenuItems($data);

        return $data;
    }

    public function mapProjectPostData(object $post): object
    {
        $post->project = (object) [
            'statusBar'     => ProjectStatus::create($post->id),
            'category'      => WP::getPostTermsJoined(['challenge_category'], $post->id) ?? '',
            'taxonomies'    => WP::getPostTermsJoined(['project_sector', 'project_technology'], $post->id)
        ];

        return $post;
    }

    protected static function createProjectCardPosts(array $posts): array
    {
        return array_map(function ($post) {
            $post->project = (object) [
                'statusBar'     => ProjectStatus::create($post->id),
                'category'      => WP::getPostTermsJoined(['challenge_category'], $post->id) ?? '',
                'taxonomies'    => WP::getPostTermsJoined(['project_sector', 'project_technology'], $post->id)
            ];

            if (!$post->thumbnail) {
                $post->thumbnail = FeaturedImage::getFeaturedImage($post->id);
            }

            if (!$post->permalink) {
                $post->permalink = get_permalink($post->id);
            }

            return $post;
        }, $posts);
    }

    public function archiveViewController($data)
    {
        $data['posts'] = Municipio::mapPosts($data['posts']);

        $data['noResultLabels'][0] = __('We found no results for your search', PROJECTMANAGERINTEGRATION_TEXTDOMAIN);
        $data['noResultLabels'][1] = __('Try to refine your search.', PROJECTMANAGERINTEGRATION_TEXTDOMAIN);
        $data['lang']->searchFor = __('Search', PROJECTMANAGERINTEGRATION_TEXTDOMAIN);

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
                'title' => __('Lessons learned', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'content' => WP::getPostMeta('project_lessons_learned', null),
            ]
        ]);
    }

    private function meta(): array
    {
        return \ProjectManagerIntegration\UI\MetaBoxes::create([
            [
                'title'     => __('Powered by', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'content'   => WP::getPostTermsJoined(['project_organisation']) ?? null,
                'url'       => null,
            ],
            [
                'title'     => __('Challenge', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'content'   => WP::getPostMeta('challenge', false) ? get_the_title(WP::getPostMeta('challenge')) : null,
                'url'       => WP::getPostMeta('challenge', false) ? get_permalink(WP::getPostMeta('challenge')) : null,
            ],
            [
                'title'     => __('Category', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'content'   => WP::getPostTermsJoined(['challenge_category']) ?? null,
                'url'       => null,
            ],
            [
                'title'     => __('Technologies', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'content'   => WP::getPostTermsJoined(['project_technology']) ?? null,
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
                'content'   => WP::getPostTermsJoined(['project_sector']) ?? null,
                'url'       => null,
            ],
            [
                'title'     => __('Partners', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'content'    => WP::getPostTermsJoined(['project_partner']) ?? null,
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
                fn ($piece) => "<h2 class='c-typography c-typography__variant--h2'>{$piece['title']}</h2>" . PHP_EOL . $piece['content'],
                $this->contentPieces()
            )
        );

        return "<div>{$controlledContent}</div>";
    }
}
