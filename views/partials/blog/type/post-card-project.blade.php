@php
    global $post;

    // Organisation
    $organisation = !empty(get_the_terms($post->ID, 'project_organisation')) 
    ? get_the_terms($post->ID, 'project_organisation')[0]->name 
    : false; 

    // Status
    $status = !empty(get_the_terms($post->ID, 'project_status')) 
    ? get_the_terms($post->ID, 'project_status')[0]->name 
    : false; 

    $postTags = array();

    if (!empty(get_the_terms(get_queried_object_id(), 'project_sector'))) {
        $postTags = array_merge($postTags, get_the_terms(get_queried_object_id(), 'project_sector'));
    }

    if (!empty(get_the_terms(get_queried_object_id(), 'project_technology'))) {
        $postTags = array_merge($postTags, get_the_terms(get_queried_object_id(), 'project_technology'));
    }

    if (empty(!$postTags)) {
        $postTags = array_reduce($postTags, function($accumilator, $term) {
            if (empty($accumilator)) {
                $accumilator = $term->name;
            } else {
                $accumilator .= ' / ' . $term->name;
            }

            return $accumilator;
        }, '');
    }
@endphp

<div class="{{ $grid_size }}">
    <a href="{{ the_permalink() }}" class="box box--project">
        <div class="box__container" data-equal-item>
            <div class="box__image ratio-1-1" style="background-image:url('{{ municipio_get_thumbnail_source(null,array(600,600), '1:1') }}');">
            </div>
            <div class="box__content">
                <div class="box__meta">
                    <span class="box__organisation">{{$organisation}}</span>
                    <span class="box__status box__status--{{sanitize_title($status)}}">{{$status}}</span>
                </div>
                <h3 class="box__title">{{ the_title() }}</h3>
                <span class="box__tags">{{$postTags}}</span>
            </div>
        </div>
    </a>
</div>
