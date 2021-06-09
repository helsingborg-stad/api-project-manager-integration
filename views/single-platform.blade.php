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
                    <div class="grid-sm-12  u-mb-2">
                        {!! the_post() !!}
                        @include('partials.blog.type.post-single-challenge')
                    </div>
                    {{-- Links --}}
                    <div class="grid-xs-12 u-mb-2">
                        <h2>Links</h2>
                        @if (!empty($platform['links']))
                            <ul>
                                @foreach($platform['links'] as $link)
                                    <li>
                                        <a target="_blank" href="{{$link['url']}}">{{$link['title']}}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    {{-- Files --}}
                    <div class="grid-xs-12 u-mb-2">
                        <h2>Files</h2>
                        @if (!empty($platform['files']))
                            <ul>
                                @foreach($platform['files'] as $file)
                                    <li>
                                        <a target="_blank" href="{{$file['attachment']}}">{{$file['title']}}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    {{-- Contacts --}}
                    <div class="grid-xs-12 u-mb-2">
                        <h2>Contacts</h2>
                        @if (!empty($platform['contacts']))
                            <ul>
                                @foreach($platform['contacts'] as $contact)
                                    <li>
                                        <h4>
                                            {{$contact['name']}} 
                                            @if (!empty($contact['role']))
                                                <br><small>{{$contact['role']}}</small>
                                            @endif
                                        </h4>
                                        <a href="mailto: {{$contact['mail']}}">{{$contact['mail']}}</a>
                                        <br>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    {{-- Roadmap --}}
                    <div class="grid-xs-12 u-mb-2">
                        <h2>Roadmap</h2>
                        @if (!empty($platform['roadmap']))
                            <ul>
                                @foreach($platform['roadmap'] as $roadmapItem)
                                    <li>
                                            <h4>
                                                {{$roadmapItem['title']}} 
                                                @if (!empty($roadmapItem['category']))
                                                    <br><small>{{$roadmapItem['category']}}</small>
                                                @endif
                                            </h4>
                                            <p>{{$roadmapItem['date']}}</p>
                                            <p>{{$roadmapItem['content']}}</p>
                                        <br>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    {{-- Features --}}
                    <div class="grid-xs-12 u-mb-2">
                        <h2>Features</h2>
                        @if (!empty($platform['features']))
                            <ul>
                                @foreach($platform['features'] as $feature)
                                    <li>
                                            <h4>
                                                {{$feature['title']}} 
                                                @if (!empty($feature['category']))
                                                    <br><small>{{$feature['category']}}</small>
                                                @endif
                                            </h4>
                                            <p>{{$feature['content']}}</p>
                                        <br>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    {{-- Video --}}
                    <div class="grid-xs-12 u-mb-2">
                        <h2>Video</h2>
                        @if (!empty($platform['videoUrl']))
                        <a target="_blank" href="{{$platform['videoUrl']}}">{{$platform['videoUrl']}}</a>
                
                        @endif
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