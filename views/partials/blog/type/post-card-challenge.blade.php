@php
    // Category
    $category = !empty(get_the_terms($post->ID, 'challenge_category')) 
    ? get_the_terms($post->ID, 'challenge_category')[0]->name 
    : false; 

    
    $permalink = get_permalink($post->ID);
    
    if (isset($_GET) && !empty($_GET)) {
        $permalink .= '?' . http_build_query($_GET);
    }

@endphp

<div class="grid-xs-12 grid-sm-6 grid-lg-3">
    <a href="{{ $permalink }}" class="box box--project box--feature u-radius-8">
        <div class="box__container" data-equal-item>
            <div class="box__image ratio-12-16 u-radius-8" style="background-image:url('{{ municipio_get_thumbnail_source($post->ID,array(636,846), '12:16') }}');">
            </div>
            <div class="box__content">
                @if ($category)
                    <div class="box__meta">
                        <b>{{$category}}</b>
                    </div>
                @endif
                <h3 class="box__title u-mb-0">{{ $post->post_title }}</h3>
            </div>
        </div>
    </a>
</div>
