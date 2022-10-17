@extends('templates.master')

@section('content')

@if (!empty($scrollSpyMenuItems) && count($scrollSpyMenuItems) > 1)
    <div class="sticky-bar sticky-bar--content hidden-md hidden-lg hidden-xl u-mt-3 u-mb-2">
        <div class="container">
            <ul id="scroll-spy-menu" class="js-scroll-spy content-navbar">
                @foreach($scrollSpyMenuItems as $item)
                    <li class="content-navbar__item" data-spy-target="{{$item['anchor']}}">
                        <a href="{{$item['anchor']}}">
                            <span class="content-navbar__inner" tabindex="-1">
                                {{$item['label']}}
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="container main-container">
    <div class="grid grid--columns">
        <div class="grid-sm-12 grid-md-7 grid-lg-8">
            @if (is_single() && is_active_sidebar('content-area-top'))
                <div class="grid grid--columns sidebar-content-area sidebar-content-area-top">
                    <?php dynamic_sidebar('content-area-top'); ?>
                </div>
            @endif

            <div class="grid" id="readspeaker-read">
                <div class="grid-sm-12">
                    {!! the_post() !!}
                    @include('partials.blog.type.post-single-project')
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

        <div class="grid-sm-12 grid-md-5 grid-lg-4">
            {{-- Inline scoped styles --}}
            <style scoped>
                .box-project-meta .box-project-meta__list > li:not(:last-child) {
                    margin-bottom: 16px;
                }
            </style>
            
            {{-- Impact goals --}}
            @if ($project && !empty($project['impactGoals']))
                @foreach ($project['impactGoals'] as $item)
                    <div id="impactgoals" class="box box--outline box-filled box-filled-1 box-project box-project-contact js-scroll-spy-section">
                        <div class="box-content u-py-4">
                            @if ($item['impact_goal_completed'])
                                <h4>
                                    <small class="secondary-color tiny">{{__('Impact goals', 'project-manager-integration')}}</small>
                                </h4>
                                <p class="u-p-0">
                                    <b>
                                        {{$item['impact_goal']}}
                                    </b>
                                </p>
                                @if (!empty($item['impact_goal_comment']))
                                    <h4 class="u-mt-2">
                                        <small class="secondary-color tiny">{{__('Results', 'project-manager-integration')}}</small>
                                    </h4>
                                    <p>{{$item['impact_goal_comment']}}</p>
                                @endif
                            @else
                                <h4>
                                    <small class="secondary-color tiny">{{__('Impact goals', 'project-manager-integration')}}</small>
                                </h4>
                                <p class="u-p-0">
                                    <b>
                                        {{$item['impact_goal']}}
                                    </b>
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif

            
            {{-- Resident Involvement --}}
            @if ($project && !empty($project['residentInvolvement']))
                @foreach ($project['residentInvolvement'] as $residentInvolement)
                    <div id="residentInvolvement" class="box box--outline box-filled box-filled-1 box-project box-project-contact js-scroll-spy-section">
                        <div class="box-content u-py-4">
                                <h4>
                                    <small class="secondary-color tiny">{{__('Resident involvement', 'project-manager-integration')}}</small>
                                </h4>
                                <p class="u-p-0">
                                    <b>
                                        {{$residentInvolement['description']}}
                                    </b>
                                </p>
                        </div>
                    </div>
                @endforeach
            @endif

            {{-- Project meta --}}
            @if ($project && !empty($project['meta']))
                <div id="about" class="box box-filled box-filled-1 box-project box-project-meta js-scroll-spy-section">
                    <div class="box-content">
                        <ul class="box-project-meta__list">
                            @foreach ($project['meta'] as $meta)
                                <li>
                                    <h4>{{$meta['title']}}</h4>
                                    @if (!empty($meta['url']))
                                        <a href="{{$meta['url']}}">
                                            <p>{!!$meta['content']!!}</p>
                                        </a>
                                    @else
                                        <p>{!!$meta['content']!!}</p> 
                                    @endif
                                </li>
                            @endforeach                            
                        </ul>
                    </div>
                </div>
            @endif
            
            {{-- Contacts --}}
            @if ($project && !empty($project['contacts']))
                {{-- TODO: Translate labels --}}
                <div class="box box-filled box-filled-1 box-project box-project-contact">
                    <h4 class="box-title">Kontakt</h4>
                    <div class="box-content u-pt-1">
                        @foreach ($project['contacts'] as $contact)
                            <p><b>Namn:</b> {{$contact['name']}}
                                @if($contact['email'])
                                    </br> <b>E-post: </b><a href="mailto:{{$contact['email']}}">{{$contact['email']}}</a>
                                @endif
                            </p>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Files --}}
            @if ($project && !empty($project['files']))
                {{-- TODO: Translate labels --}}
                <div class="box box-filled box-filled-1 box-project box-project-contact">
                    <h4 class="box-title">Filer</h4>
                    <div class="box-content u-pt-1">
                        <ul>              
                            @foreach ($project['files'] as $file)
                                <li>
                                    <a href="{{$file['file']['url']}}">{{$file['title']}}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- Links --}}
            @if ($project && !empty($project['links']))
                {{-- TODO: Translate labels --}}
                <div class="box box-filled box-filled-1 box-project box-project-contact">
                    <h4 class="box-title">Länkar</h4>
                    <div class="box-content u-pt-1">
                        <ul>              
                            @foreach ($project['links'] as $link)
                                <li>
                                    <a target="_blank" href="{{$link['url']}}">{{$link['title']}}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

        </div>
        @include('partials.sidebar-right')
    </div>
</div>

@php
    $relatedPosts = get_posts([
        'post_type' => get_post_type(),
        'posts_per_page' => 4,
        'exclude' => array(get_queried_object_id()),
        'orderby' => 'rand'
    ]);
    $postTypeObject = get_post_type_object(get_post_type());

    $gridSize = get_field('archive_' . sanitize_title(get_post_type()) . '_grid_columns', 'option');
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
                        @include('partials.blog.type.post-card-project', array(
                                'post' => $post,
                                'grid_size' => !empty($gridSize) ? $gridSize : 'grid-xs-12 grid-md-4'
                            ))
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@stop

