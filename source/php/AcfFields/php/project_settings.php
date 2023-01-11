<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_5e8c4a83e611a',
    'title' => __('project settings', 'project-manager-integration'),
    'fields' => array(
        0 => array(
            'key' => 'field_5e8c4ad58b110',
            'label' => __('API URL', 'project-manager-integration'),
            'name' => 'project_api_url',
            'aria-label' => '',
            'type' => 'url',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
        ),
        1 => array(
            'key' => 'field_5ece6ba2ad592',
            'label' => __('Organisation filter', 'project-manager-integration'),
            'name' => 'organisation_filter',
            'aria-label' => '',
            'type' => 'select',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                0 => __('Importera alla organisationer', 'project-manager-integration'),
                20 => __('Organisation 1 (2)', 'project-manager-integration'),
            ),
            'default_value' => false,
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 0,
            'return_format' => 'value',
            'ajax' => 0,
            'placeholder' => '',
            'allow_custom' => 0,
            'search_placeholder' => '',
        ),
        2 => array(
            'key' => 'field_5e8c514c89ee4',
            'label' => __('Daily import', 'project-manager-integration'),
            'name' => 'project_daily_import',
            'aria-label' => '',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'message' => '',
            'default_value' => 0,
            'ui' => 1,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
    ),
    'location' => array(
        0 => array(
            0 => array(
                'param' => 'options_page',
                'operator' => '==',
                'value' => 'project-options',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => '',
    'show_in_rest' => false,
));
}