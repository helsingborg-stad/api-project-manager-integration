<?php
    $selected = isset($_GET['filter'][$taxKey]) && $_GET['filter'][$taxKey] !== '-1' ? $_GET['filter'][$taxKey] : null;

    wp_dropdown_categories(array(
        'taxonomy'          => $taxKey,
        'hide_empty'        => false,
        'hierarchical'      => true,
        'name'              => 'filter[' . $taxKey . ']',
        'show_option_none'  => ucwords($tax->label),
        'value_field'       => 'slug',
        'selected'          => $selected,
        'orderby'           => 'name'
    ));