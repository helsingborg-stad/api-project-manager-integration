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
                    <span class="post-header__meta">{{__('Challenge', 'project-manager-integration')}}</span>
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
    $relatedPosts = get_posts([
        'post_type' => get_post_type(),
        'posts_per_page' => 4,
        'exclude' => array(get_queried_object_id()),
        'orderby' => 'rand'
    ]);
@endphp

    @if (!empty($relatedPosts))
        <div class="section related-posts u-py-6 u-py-8@lg u-py-8@xl">
            <div class="container">
                <div class="grid u-align-items-center u-mb-3">
                    <div class="grid-xs-auto">
                        <h2 class="related-posts__title">Fler Utmaningar</h2>
                    </div>
                    <div class="grid-xs-fit-content">
                        <a class="related-posts__archive_link" href="{{get_post_type_archive_link(get_post_type())}}">Visa alla <i class="pricon pricon-right-fat-arrow u-ml-1"></i></a>
                    </div>
                </div>
                <div>
                </div>
                <div class="grid">
                    @foreach($relatedPosts as $post)
                    @php
                        $category = !empty(get_the_terms($post->ID, 'challenge_category')) 
                            ? get_the_terms($post->ID, 'challenge_category')[0]->name 
                            : false; 
                    @endphp
                        <div class="grid-xs-12 grid-sm-6 grid-md-3">
                            <a href="{{ get_permalink($post->ID) }}" class="box box--project">
                                <div class="box__container" data-equal-item>
                                    <div class="box__image ratio-12-16 u-radius-8" style="background-image:url('{{ municipio_get_thumbnail_source($post->ID,array(636,846), '12:16') }}');">
                                    </div>
                                    <div class="box__content">
                                        <div class="box__meta">
                                            @if ($category)
                                                <span class="box__organisation">{{$category}}</span>
                                            @endif
                                        </div>
                                        <h3 class="box__title">{{ get_the_title($post->ID) }}</h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@stop