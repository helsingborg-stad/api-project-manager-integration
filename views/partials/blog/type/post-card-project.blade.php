@php
    // Category
    $category = !empty(get_the_terms($post->ID, 'challenge_category')) 
    ? get_the_terms($post->ID, 'challenge_category')[0]->name 
    : false; 

    // Organisation
    $organisation = !empty(get_the_terms($post->ID, 'project_organisation')) 
    ? get_the_terms($post->ID, 'project_organisation')[0]->name 
    : false; 

    // Status
    $status = !empty(get_the_terms($post->ID, 'project_status')) 
    ? get_the_terms($post->ID, 'project_status')[0]->name 
    : false; 

    $postTags = array();
    
    if (!empty(get_the_terms($post->ID, 'project_sector'))) {
        $postTags = array_merge($postTags, get_the_terms($post->ID, 'project_sector'));
    }

    if (!empty(get_the_terms($post->ID, 'project_technology'))) {
        $postTags = array_merge($postTags, get_the_terms($post->ID, 'project_technology'));
    }

    if (empty(!$postTags)) {
        $postTags = array_reduce($postTags, function($accumilator, $term) {
            if (empty($accumilator)) {
                $accumilator = '<span>' . $term->name . '</span>';
            } else {
                $accumilator .= ' / ' . '<span>'  . $term->name . '</span>';
            }

            return $accumilator;
        }, '');
    }

    $permalink = get_permalink($post->ID);

    // Status
    if (!empty(get_the_terms($post->ID, 'project_status'))) {
        $statusTerm = get_the_terms($post->ID, 'project_status')[0];
        $statusMeta = get_term_meta($statusTerm->term_id, 'progress_value', true);
        $prevStatusMeta = get_post_meta($post->ID, 'previous_status_progress_value', true);
        $isCancelled = false;

        if (0 > (int) $statusMeta) {
            $statusMeta = (int) $prevStatusMeta >= 0 ? $prevStatusMeta : 0;
            $isCancelled = true;
        }

        $statusBar = array(
            'label' => $statusTerm->name,
            'value' => (int) $statusMeta ?? 0,
            'explainer' => $statusTerm->description ?? '',
            'explainer_html' => term_description($statusTerm->term_id) ?? '',
            'isCancelled' => $isCancelled,
        );
    }


@endphp 

<div class="{{ $grid_size }}">
    <a href="{{ get_the_permalink($post->ID) }}" class="box box--project">
        <div class="box__container" data-equal-item>
            <div class="box__image ratio-3-2" style="background-image:url('{{ municipio_get_thumbnail_source($post->ID,array(504,336), '3:2') }}');">
            </div>
            <div class="box__content">
                <div class="box__meta">
                    <span class="box__organisation">{{$category}}</span>
                </div>
                <h3 class="box__title">{{ $post->post_title }}</h3>
                @if ($postTags)
                    <span class="box__tags">{!!$postTags!!}</span>
                @endif
            </div>
        </div>
        @if (!empty($statusBar) && $statusBar['value'] > -1 && $statusBar['label'])
            <div class="statusbar u-mt-3">
                <div class="statusbar__header u-mb-1 explain">
                    <b class="statusbar__title">{{$statusBar['label']}}</b>

                    @if (!empty($statusBar['explainer'])) 
                        <span class="statusbar__explainer">
                            <span data-tooltip="{{$statusBar['explainer']}}" data-tooltip-bottom>
                                <i class="pricon pricon-info-o"></i>
                            </span>
                        </span>
                    @endif
                </div>
                <div class="statusbar__content">
                <div class="c-progressbar">
                        <div class="c-progressbar__value {{$statusBar['isCancelled'] ? 'is-disabled' : ''}}" style="width: {{$statusBar['value']}}%;"></div>                
                    </div>
                </div>
            </div>
        @endif
    </a>
</div>
