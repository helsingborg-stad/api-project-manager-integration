@php
    global $post;

    // Category
    $category = !empty(get_the_terms($post->ID, 'challenge_category')) 
    ? get_the_terms($post->ID, 'challenge_category')[0]->name 
    : false; 

    
    $permalink = get_permalink($post->ID);
    
    if (isset($_GET) && !empty($_GET)) {
        $permalink .= '?' . http_build_query($_GET);
    }

@endphp

<div class="grid-xs-6 {{ $grid_size }}">
    <a href="{{ $permalink }}" class="box box--project">
        <div class="box__container" data-equal-item>
            <div class="box__image ratio-1-1" style="background-image:url('{{ municipio_get_thumbnail_source(null,array(500,500), '1:1') }}');">
            </div>
            <div class="box__content">
                <div class="box__meta">
                    @if ($category)
                        <span class="box__organisation">{{$category}}</span>
                    @endif
                </div>
                <h3 class="box__title">{{ the_title() }}</h3>
            </div>
        </div>
    </a>
</div>
