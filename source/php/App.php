<?php

namespace ProjectManagerIntegration;

use ProjectManagerIntegration\Helper\CacheBust as CacheBust;

class App
{
    public function __construct()
    {
        new PostTypes\Project();
        new PostTypes\Challenge();
        new PostTypes\Platform();
        new Controller\Project();
        new Controller\Challenge();
        new Controller\Platform();
        new Controller\PostsModule();
        new Import\Setup();
        new Options();
        new UI\Theme();
        new Vendor\Algolia();
        new Shortcodes\ExtendedQuote\ExtendedQuote();
        new Shortcodes\PostTypeLink\PostTypeLink();

        add_action('template_redirect', array($this, 'addViewPaths'));
        add_action('wp_enqueue_scripts', array($this, 'enqueueStyles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueueStyles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueueScripts'));
        add_filter('language_attributes', array($this, 'wpBodyClasses'), 999);
        add_filter('register_post_type_args', array($this, 'modifyPostTypes'), 10, 2);
        add_filter('ComponentLibrary/Component/Card/Data', array($this, 'applyCardModifiersByContext'), 10, 1);
    }

    public function applyCardModifiersByContext($componentProps)
    {
        $modifiers = [
            [
                'context' => [
                    'module.text.box',
                    'platform.feature',
                    'platform.contacts',
                    'platform.links',
                    'platform.meta',
                    'project.links',
                    'project.contacts',
                    'project.goals',
                    'project.meta',
                    'project.resident-involvement',
                    'shortcode.post-type-link'
                ],
                'classList' => ['u-color__bg--lighter']
            ],
            [
                'context' => ['archive', 'module.posts.index'],
                'classList' => ['c-card--flat']
            ]
        ];

        foreach ($modifiers as $modifier) {
            $matches = array_intersect($modifier['context'], $componentProps['context']);
            if (!empty($matches)) {
                $componentProps['classList'] = array_merge($componentProps['classList'], $modifier['classList']);
            }
        }


        return $componentProps;
    }

    public function wpBodyClasses($output)
    {
        if (is_singular('project') || is_singular('platform')) {
            $output .= ' data-header-offset="158"';
        } else {
            $output .= ' data-header-offset="80"';
        }


        return $output;
    }


    /**
     * Enqueue required style
     * @return void
     */
    public function enqueueStyles()
    {
        wp_enqueue_style(
            'project-manager-integration-css',
            PROJECTMANAGERINTEGRATION_URL .
                '/dist/' .
                CacheBust::name('css/project-manager-integration.css'),
            array(),
            ''
        );
    }

    /**
     * Enqueue required scripts
     * @return void
     */
    public function enqueueScripts()
    {
        wp_register_script(
            'project-manager-integration-js',
            PROJECTMANAGERINTEGRATION_URL .
                '/dist/' .
                CacheBust::name('js/project-manager-integration.js'),
            array('jquery'),
            false,
            true
        );

        wp_enqueue_script('project-manager-integration-js');
    }

    /**
     * Add searchable blade template paths
     * 
     * @return void
     */
    public function addViewPaths(): void
    {
        $postTypes = [
            \ProjectManagerIntegration\PostTypes\Challenge::$postType,
            \ProjectManagerIntegration\PostTypes\Platform::$postType,
            \ProjectManagerIntegration\PostTypes\Project::$postType
        ];

        if (in_array(get_post_type(), $postTypes) && defined('PROJECTMANAGERINTEGRATION_VIEW_PATH')) {
            add_filter('Municipio/viewPaths', function ($array) {
                array_unshift($array, PROJECTMANAGERINTEGRATION_VIEW_PATH);
                return $array;
            }, 2, 1);
        }
    }

    public function modifyPostTypes($args, $postType)
    {
        if (defined('API_PROJECT_MANAGER_INTEGRATION_DEFAULT_POST_TYPE_NAMES')) {
            return $args;
        }

        $postTypesToModify = [
            'project' => [
                'singular' => __('Initiativ', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'plural' => __('Initiativ', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'slug' => 'initiativ'
            ],
            'challenge' => [
                'singular' => __('Utmaning', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'plural' => __('Utmaningar', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'slug' => 'utmaningar'
            ],
            'platform' => [
                'singular' => __('Plattform', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'plural' => __('Plattformar', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                'slug' => 'plattformar'
            ]
        ];

        if (!in_array($postType, array_keys($postTypesToModify))) {
            return $args;
        }

        // Rewrite slug
        if (!empty($postTypesToModify[$postType]['slug'])) {
            $args['rewrite'] = array(
                'slug' => $postTypesToModify[$postType]['slug']
            );
        }

        // Alter labels
        if (
            !empty($postTypesToModify[$postType]['plural'])
            && !empty($postTypesToModify[$postType]['singular'])
        ) {
            $args['labels'] = array(
                'name'              => $postTypesToModify[$postType]['plural'],
                'singular_name'     => $postTypesToModify[$postType]['singular'],
                'add_new'             => sprintf(
                    __('Add new %s', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    strtolower($postTypesToModify[$postType]['singular'])
                ),
                'add_new_item'        => sprintf(
                    __('Add new %s', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    strtolower($postTypesToModify[$postType]['singular'])
                ),
                'edit_item'           => sprintf(
                    __('Edit %s', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    strtolower($postTypesToModify[$postType]['singular'])
                ),
                'new_item'            => sprintf(
                    __('New %s', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    strtolower($postTypesToModify[$postType]['singular'])
                ),
                'view_item'           => sprintf(
                    __('View %s', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    strtolower($postTypesToModify[$postType]['singular'])
                ),
                'search_items'        => sprintf(
                    __('Search %s', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    strtolower($postTypesToModify[$postType]['plural'])
                ),
                'not_found'           => sprintf(
                    __('No %s found', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    strtolower($postTypesToModify[$postType]['plural'])
                ),
                'not_found_in_trash'  => sprintf(
                    __('No %s found in trash', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    strtolower($postTypesToModify[$postType]['plural'])
                ),
                'parent_item_colon'   => sprintf(
                    __('Parent %s:', PROJECTMANAGERINTEGRATION_TEXTDOMAIN),
                    strtolower($postTypesToModify[$postType]['singular'])
                ),
                'menu_name'           => $postTypesToModify[$postType]['plural'],
            );
        }

        return $args;
    }
}
