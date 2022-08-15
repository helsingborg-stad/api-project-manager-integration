<?php

namespace ProjectManagerIntegration\PostTypes;

class Project
{
    public static $postType = 'project';

    public function __construct()
    {
        add_action('init', array($this, 'registerPostType'), 9);
        add_filter('Municipio/viewData', array($this, 'viewController'));
    }

    public function viewController($data)
    {
        global $wp_query;

        if (is_singular(self::$postType)) {
            return $this->singleViewController($data);
        }

        if (is_archive() && $wp_query->query['post_type'] === self::$postType) {
            return $this->archiveViewController($data);
        }

        return $data;
    }

    public function singleViewController($data)
    {
        $data['project'] = array_merge(
            self::getPostMeta(),
            [
                'contentPieces'     =>  $this->contentPieces(),
                'meta'              =>  $this->meta(),
                'statusBar'         =>  $this->statusBar(),
                'files'             =>  self::getPostmeta('files', []),
                'contacts'          =>  self::getPostmeta('contacts', []),
                'links'             =>  self::getPostmeta('links', []),
                'address'           =>  self::getPostmeta('address', []),
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

    public function registerPostType()
    {
        $customPostType = new \ProjectManagerIntegration\Helper\PostType(
            self::$postType,
            __('Project', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            __('Projects', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
            [
                'menu_icon'          => 'dashicons-portfolio',
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                'supports'           => ['title', 'editor', 'thumbnail'],
                'show_in_rest'       => true,
            ],
            [],
            ['exclude_keys' => ['author', 'acf', 'guid', 'link', 'template', 'meta', 'taxonomy', 'menu_order']]
        );

        foreach (self::taxonomies() as $taxonomy) {
            $customPostType->addTaxonomy(
                $taxonomy['slug'],
                $taxonomy['singular'],
                $taxonomy['plural'],
                $taxonomy['args'],
            );
        }
    }

    private static function taxonomies()
    {
        return [
            [
                'slug'      =>  'project_status',
                'singular'  =>  __('Status', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'plural'    =>  __('Statuses', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'args'      =>  ['hierarchical' => false, 'show_ui' => false]
            ],
            [
                'slug'      =>  'project_technology',
                'singular'  =>  __('Technology', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'plural'    =>  __('Technologies', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'args'      =>  ['hierarchical' => true, 'show_ui' => false]
            ],
            [
                'slug'      =>  'project_sector',
                'singular'  =>  __('Sector', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'plural'    =>  __('Sectors', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'args'      =>  ['hierarchical' => true, 'show_ui' => false]
            ],
            [
                'slug'      =>  'project_organisation',
                'singular'  =>  __('Organisation', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'plural'    =>  __('Organisations', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'args'      =>  ['hierarchical' => true, 'show_ui' => false]
            ],
            [
                'slug'      =>  'project_global_goal',
                'singular'  =>  __('Global goal', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'plural'    =>  __('Global goals', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'args'      =>  ['hierarchical' => true, 'show_ui' => false]
            ],
            [
                'slug'      =>  'project_category',
                'singular'  =>  __('Category', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'plural'    =>  __('Categories', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'args'      =>  ['hierarchical' => true, 'show_ui' => false]
            ],
            [
                'slug'      =>  'project_partner',
                'singular'  =>  __('Partner', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'plural'    =>  __('Partners', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'args'      =>  ['hierarchical' => true, 'show_ui' => false]
            ],
            [
                'slug'      =>  'challenge_category',
                'singular'  =>  __('Category', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'plural'    =>  __('Categories', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'args'      =>  [ 'hierarchical' => false, 'show_ui' => true]
            ],
        ];
    }

    private function contentPieces(): array
    {
        return array_filter(
            [
                [
                    'title' => __('What', PROJECTMANAGERINTEGRATION_TEXTDOMAIN) . '?',
                    'content' => self::getPostMeta('project_what', null),
                ],
                [
                    'title' => __('Why', PROJECTMANAGERINTEGRATION_TEXTDOMAIN) . '?',
                    'content' => self::getPostMeta('project_why', null),
                ],
                [
                    'title' => __('How', PROJECTMANAGERINTEGRATION_TEXTDOMAIN) . '?',
                    'content' => self::getPostMeta('project_how', null),
                ]
            ],
            fn ($i) => !empty($i['content'])
        );
    }

    private function meta(): array
    {
        return array_filter(
            [
                [
                    'title'     => __('Powered by', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    'content'   => array_reduce(self::getPostTerms('project_organisation'), array($this, 'reduceTermsToString'), '') ?? null,
                    'url'       => null,
                ],
                [
                    'title'     => __('Challenge', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    'content'   => self::getPostMeta('challenge', false) ? get_the_title(self::getPostMeta('challenge')) : null,
                    'url'       => self::getPostMeta('challenge', false) ? get_permalink(self::getPostMeta('challenge')) : null,
                ],
                [
                    'title'     => __('Category', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    'content'   => array_reduce(self::getPostTerms('challenge_category'), array($this, 'reduceTermsToString'), '') ?? null,
                    'url'       => null,
                ],
                [
                    'title'     => __('Technologies', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    'content'   => array_reduce(self::getPostTerms('project_technology'), array($this, 'reduceTermsToString'), '') ?? null,
                    'url'       => null,
                ],
                [
                    'title'     => __('Estimated Budget', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    'content'   => self::getPostMeta('estimated_budget', false) ? self::getPostMeta('estimated_budget') . ' kr' : null,
                    'url'       => null,
                ],
                [
                    'title'     => __('Cost so far', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    'content'   => self::getPostMeta('funds_used', false) ? array_reduce(self::getPostMeta('funds_used', false), fn ($a, $i) => $a + (int) $i['amount'], 0) . ' kr' : null,
                    'url'       => null,
                ],
                [
                    'title'     => __('Investment', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    'content'   => $investmentString,
                    'url'       => null,
                ],
                [
                    'title'     => __('Sector', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    'content'   => array_reduce(self::getPostTerms('project_sector'), array($this, 'reduceTermsToString'), '') ?? null,
                    'url'       => null,
                ],
                [
                    'title'     => __('Partners', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    'content'    => array_reduce(self::getPostTerms('project_partner'), array($this, 'reduceTermsToString'), '') ?? null,
                    'url'       => null,
                ],
            ],
            fn ($i) => !empty($i['content']) && !empty($i['title'])
        );
    }

    private function scrollSpyMenuItems($data): array
    {
        return array_filter(
            [
                [
                    'label'     =>  __('Background', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    'anchor'    => '#article',
                    'disabled'  =>  false,
                ],
                [
                    'label'     => __('Impact goals', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    'anchor'    => '#impactgoals',
                    'disabled'  =>  empty(self::getPostMeta('impact_goals', [])),
                ],
                [
                    'label'     => __('Resident involvement', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    'anchor'    => '#residentInvolvement',
                    'disabled'  =>  empty(self::getPostMeta('resident_involvement', null)),
                ],
                [
                    'label'     => __('About', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    'anchor'    => '#about',
                    'disabled'  =>  empty($data['project']['meta']),
                ],
            ],
            fn ($i) => empty($i['disabled'])
        );
    }

    private function statusBar()
    {
        if (!empty(get_the_terms(get_queried_object_id(), 'project_status'))) {
            $statusTerm = get_the_terms(get_queried_object_id(), 'project_status')[0];
            $statusMeta = get_term_meta($statusTerm->term_id, 'progress_value', true);
            $prevStatusMeta = get_post_meta(get_the_id(), 'previous_status_progress_value', true);
            $isCancelled = false;

            if (0 > (int) $statusMeta) {
                $statusMeta = (int) $prevStatusMeta >= 0 ? $prevStatusMeta : 0;
                $isCancelled = true;
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

    private static function getPostTerms(string $taxonomy, int $postId = 0): array
    {
        $terms = get_the_terms(
            $postId > 0 ? $postId : get_queried_object_id(),
            $taxonomy
        );

        return !empty($terms) && !is_wp_error($terms) ? $terms : [];
    }

    private static function getPostMeta(string $metaKey = '', $defaultValue = null, int $postId = 0)
    {
        return !empty($metaKey)
                ? in_array($metaKey, array_keys(self::queryPostMeta($postId))) && self::queryPostMeta($postId)[$metaKey] !== null
                    ? is_string(self::queryPostMeta($postId)[$metaKey]) && empty(self::queryPostMeta($postId)[$metaKey])
                        ? $defaultValue
                        : (is_array(self::queryPostMeta($postId)[$metaKey]) && empty(self::queryPostMeta($postId)[$metaKey])
                            ? $defaultValue
                            : (self::queryPostMeta($postId)[$metaKey]))
                    : ($defaultValue)
                : (self::queryPostMeta($postId));
    }

    private static function queryPostMeta(int $postId = 0): array
    {
        return array_merge(
            array_map(
                [__CLASS__, 'mapRemoveNullVaulesFromArrays'],
                array_map(
                    [__CLASS__, 'mapUnserializePostMetaValue'],
                    array_map(
                        [__CLASS__, 'mapFlattenPostMetaValue'],
                        get_post_meta($postId > 0 ? $postId : get_queried_object_id()) ?? []
                    )
                )
            ),
            []
        );
    }

    private static function mapRemoveNullVaulesFromArrays($metaValue)
    {
        return is_array($metaValue) ? array_filter($metaValue, fn ($i) => $i !== null) : $metaValue;
    }

    private static function mapFlattenPostMetaValue(array $metaValue)
    {
        return $metaValue[0] ?? $metaValue;
    }

    private static function mapUnserializePostMetaValue($metaValue)
    {
        return maybe_unserialize($metaValue);
    }

    private static function reduceTermsToString($accumilator, $item)
    {
        if (empty($accumilator)) {
            $accumilator = '<span>' . $item->name . '</span>';
        } else {
            $accumilator .= ', ' . '<span>' . $item->name . '</span>';
        }

        return $accumilator;
    }
}
