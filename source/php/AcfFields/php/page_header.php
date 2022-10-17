<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_5fd1e418be4a8',
    'title' => __('Page Header', 'municipio-innovation-theme'),
    'fields' => array(
        0 => array(
            'key' => 'field_5fd7689affe85',
            'label' => __('Select template', 'municipio-innovation-theme'),
            'name' => 'page_header_template',
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
                'center' => __('Center', 'municipio-innovation-theme'),
                'two-column' => __('Two Column', 'municipio-innovation-theme'),
                'none' => __('None (disable)', 'municipio-innovation-theme'),
            ),
            'default_value' => array(
                0 => __('center', 'municipio-innovation-theme'),
            ),
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 0,
            'return_format' => 'value',
            'ajax' => 0,
            'placeholder' => '',
        ),
        1 => array(
            'key' => 'field_5fd1e51bde7fd',
            'label' => __('Content (optional)', 'municipio-innovation-theme'),
            'name' => 'page_header_content',
            'type' => 'textarea',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_5fd7689affe85',
                        'operator' => '!=',
                        'value' => 'none',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'maxlength' => '',
            'rows' => 2,
            'new_lines' => '',
        ),
        2 => array(
            'key' => 'field_5fd1e524de7fe',
            'label' => __('Meta (optional)', 'municipio-innovation-theme'),
            'name' => 'page_header_meta',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_5fd7689affe85',
                        'operator' => '!=',
                        'value' => 'none',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
        ),
        3 => array(
            'key' => 'field_5fd76a55ffe86',
            'label' => __('Video URL (optional)', 'municipio-innovation-theme'),
            'name' => 'page_header_video_url',
            'type' => 'url',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                0 => array(
                    0 => array(
                        'field' => 'field_5fd7689affe85',
                        'operator' => '!=',
                        'value' => 'none',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
        ),
    ),
    'location' => array(
        0 => array(
            0 => array(
                'param' => 'post_template',
                'operator' => '==',
                'value' => 'default',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'side',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));
}