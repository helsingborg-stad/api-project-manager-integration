@extends('templates.master')

@section('content')

<div class="c-cover c-cover--{{get_post_type()}} c-cover--overlay s-invert-section">
    <div class="c-cover__container">
        <style scoped>
            .u-object-fit {
                object-position: {{$featuredImagePosition['x'] . ' ' . $featuredImagePosition['y']}};
            }
        </style>
        <img class="c-cover__image u-object-fit" src="{{ municipio_get_thumbnail_source(null, array(1440,416)) }}" alt="{{ the_title() }}">
        <div class="container">
            <div class="c-cover__content">
                <header class="post-header">
                    <span class="post-header__meta">{{$category ?? __('Challenge', 'project-manager-integration')}}</span>
                    <h1 class="post-title post-title--{{get_post_type()}}">{{ the_title() }}</h1>   
                </header>
            </div>
        </div>
    </div>
    <div class="stripe stripe--right stripe--force-show stripe--offset">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>


<div class="t-{{$themeColor}}-section">
    <div class="container main-container">
        <div class="grid u-py-6 u-py-8@lg u-py-8@xl">
            <div class="grid-sm-12">
                @if (is_single() && is_active_sidebar('content-area-top'))
                    <div class="grid grid--columns sidebar-content-area sidebar-content-area-top">
                        <?php dynamic_sidebar('content-area-top'); ?>
                    </div>
                @endif

                <div class="grid" id="readspeaker-read">
                    <div class="grid-sm-12">
                        {!! the_post() !!}
                        @include('partials.blog.type.post-single-challenge')
                    </div>
                </div>

                @if (is_single() && comments_open() && get_option('comment_registration') == 0 || is_single() && comments_open() && is_user_logged_in())
                    @if(get_option('comment_order') == 'desc')
                        <div class="grid">
                            <div class="grid-sm-12">
                                @include('partials.blog.comments-form')
                            </div>
                        </div>
                        @if(isset($comments) && ! empty($comments))
                            <div class="grid">
                                <div class="grid-sm-12">
                                    @include('partials.blog.comments')
                                </div>
                            </div>
                        @endif
                    @else
                        @if(isset($comments) && ! empty($comments))
                            <div class="grid">
                                <div class="grid-sm-12">
                                    @include('partials.blog.comments')
                                </div>
                            </div>
                        @endif
                        <div class="grid">
                            <div class="grid-sm-12">
                                @include('partials.blog.comments-form')
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@php
    $projects = get_posts([
        'post_type' => 'project',
        'posts_per_page' => -1,
        'meta_key' => 'platform',
        'meta_value' => get_queried_object_id()
    ]);
@endphp


@if (!empty($projects))
<div class="section u-pt-7">
    <div class="container">
        <h2 class="u-mb-4">Några innovationsinnatitiv relaterade till plattformen</h2>
        <div class="grid grid--columns">
            @foreach ($projects as $post)
                @include('partials.blog.type.post-card-project', array('post' => $post, 'grid_size' => 'grid-xs-12 grid-sm-6 grid-md-3'))
            @endforeach
        </div>
    </div>
</div>
@endif
{{-- 
@php
    $statusTerms = get_terms([
        'taxonomy' => 'project_status',
        'hide_empty' => false,
        'order'     => 'ASC',
        'meta_key' => 'progress_value',
        'orderby'   => 'meta_value',
    ]);

    $statusTerms = array_map(function(\WP_Term $term)  {
        $challengeId = get_queried_object_id();
        $posts = get_posts([
            'post_type' => 'project',
            'posts_per_page' => -1,
            'meta_key' => 'challenge',
            'meta_value' => $challengeId,
            'tax_query' => array(
                array(
                    'taxonomy' => 'project_status', 
                    'field' => 'slug', 
                    'terms' => $term->slug, 
                ),
            )
        ]);

        $term->items = $posts;


        return $term;
    }, $statusTerms);

    $statusTermsWithPosts = array_filter($statusTerms, function($term) {
        return !empty($term->items);
    });
@endphp --}}

@if (!empty($statusTerms) && !empty($statusTermsWithPosts))
    <div class="section u-pt-7">
        <div class="container">
            <div class="grid grid--columns">
                @foreach($statusTerms as $term)
                    <div class="grid-xs-12 grid-md-auto">
                        <h2 class="u-mb-4">{{$term->name}}</h2>
                        <div class="grid grid--columns">
                            @foreach ($term->items as $post)
                                @include('partials.blog.type.post-card-vertical', array('post' => $post, 'grid_size' => 'grid-xs-12'))
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
@endif

@php
    $relatedPosts = get_posts([
        'post_type' => get_post_type(),
        'posts_per_page' => 4,
        'exclude' => array(get_queried_object_id()),
        'orderby' => 'rand'
    ]);
    $postTypeObject = get_post_type_object(get_post_type());
@endphp

    @if (!empty($relatedPosts))
        <div class="section related-posts u-py-6 u-py-8@lg u-py-8@xl t-section-gray">
            <div class="container">
                <div class="grid u-align-items-center u-mb-3">
                    <div class="grid-xs-auto">
                        <h2 class="related-posts__title">Fler {{$postTypeObject->labels->all_items}}</h2>
                    </div>
                    <div class="grid-xs-fit-content">
                        <a class="related-posts__archive_link" href="{{get_post_type_archive_link(get_post_type())}}">Visa alla <i class="pricon pricon-right-fat-arrow u-ml-1"></i></a>
                    </div>
                </div>
                <div>
                </div>
                <div class="grid grid--columns">
                    @foreach ($relatedPosts as $post)
                        @include('partials.blog.type.post-card-challenge', array('post' => $post, 'grid_size' => 'grid-xs-12 grid-sm-6 grid-md-3'))
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@stop