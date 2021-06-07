<?php


if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_c7e040ca43',
    'title' => __('platform settings', 'project-manager-integration'),
    'fields' => array(
        0 => array(
            'key' => 'field_d1b776cb52',
            'label' => __('API URL', 'project-manager-integration'),
            'name' => 'platform_api_url',
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
            'key' => 'field_13572b14778',
            'label' => __('Daily import', 'project-manager-integration'),
            'name' => 'platform_daily_import',
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
                'value' => 'platform-options',
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
));
}
