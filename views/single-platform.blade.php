@extends('page')

@section('content-header')
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
                        <span class="post-header__meta">{{get_post_type_object(get_post_type())->labels->singular_name}}</span>
                        <h1 class="post-title post-title--{{get_post_type()}}">{{ the_title() }}</h1>   
                    </header>
                </div>
            </div>
        </div>
    </div>
@stop

@section('after-article')

@stop


@section('content-bottom')
    <div class="section u-p-5">
        <div class="container">
            <div class="grid">
                {{-- Features --}}
                <div class="grid-xs-12">
                    @if (!empty($platform['features']))
                        <ul class="grid">
                            @foreach($platform['features'] as $feature)
                                <li class="grid-md-4 u-mb-4">
                                    <div class="box box-filled box-project u-h-100">
                                        <div class="box-content">
                                            <h4 class="u-mb-2">
                                                {{$feature['title']}} 
                                                @if (!empty($feature['category']))
                                                    <br><small>{{$feature['category']}}</small><br>
                                                @endif
                                            </h4>
                                            <p>{{$feature['content']}}</p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>


    @if (!empty($platform['roadmap']) && !empty($platform['roadmap']['items']))
        <style>
            .c-status-dot {
                border-radius: 100%; 
                display: block; 
                height: 16px; 
                width: 16px;
            }
        </style>
    
        <div class="section u-py-7 t-section-gray">
            <div class="container">
                <div class="grid">
                    <div class="grid-xs-12 u-mb-4">
                        <h2>Roadmap</h2>
                    </div>
                    {{-- Roadmap --}}
                    <div class="grid-xs-12 u-mb-2">
                        @if (!empty($platform['roadmap']['items']))
                        <div class="js-post-slider u-w-100">
                            <div id="flickity-mod-posts-platform" class="grid post-slider__flickity js-post-slider__flickity" data-flickity-options='{!! json_encode($platform['roadmap']['flickityOptions'])!!}'  data-equal-container>
                                @foreach($platform['roadmap']['items'] as $item)
                                <div class="post-slider__item flickity-item u-flex u-align-items-center {{implode(' ', $item['classes'])}} @if (isset($columnsPerRow) && $loop->iteration > $columnsPerRow) u-flickity-init-hidden @endif">
                                    <div class="box box-filled box--outline">
                                        <div class="box-content">
                                            @if (!empty($item['category']))
                                                <span>{{$item['category']}}</span>
                                            @endif

                                            <h3 class="u-mb-2">
                                                {{$item['title']}} 
                                            </h3>

                                            <p>{{$item['content']}}</p>

                                            <div class="grid u-mt-3 u-align-items-center">
                                                    @if (!empty($item['status']) 
                                                            && !empty($platform['roadmap']['statusColors']) 
                                                            && isset($platform['roadmap']['statusColors'][$item['status']]))
                                                        <div class="grid-xs-1">
                                                            <span class="c-status-dot u-display-inline-block" style="background-color:{{$platform['roadmap']['statusColors'][$item['status']]}};"></span>
                                                        </div>
                                                    @endif
                                                <div class="grid-xs-auto">
                                                    <p><b>{{$item['date']}}</b></p>
                                                </div>
                                            </div>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (!empty($platform['files']) || !empty($platform['links']))
        <div class="section u-p-5">
            <div class="container">
                <div class="grid">
                    {{-- Files --}}
                    @if (!empty($platform['files']))
                        <div class="grid-xs-12 grid-md-auto">
                            <div class="box box--outline box-filled">
                                <h3>Documents</h3>
                                <ul class="u-pt-2">
                                    @foreach($platform['files'] as $file)
                                        <li>
                                            <a target="_blank" href="{{$file['attachment']}}">{{$file['title']}}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    {{-- Links --}}
                    @if (!empty($platform['links']))
                        <div class="grid-xs-12 grid-md-auto u-mb-4">
                            <div class="box box--outline box-filled">
                                <h3>Links</h3>
                                <ul class="u-pt-2">
                                    @foreach($platform['links'] as $link)
                                        <li>
                                            <a target="_blank" href="{{$link['url']}}">{{$link['title']}}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
    
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

    <div class="section u-p-7 t-blue-section">
        <div class="container">
            <div class="grid">
                {{-- Contacts --}}
                <div class="grid-xs-12 u-mb-2">
                    <h2 class="u-mb-2">Contacts</h2>
                    @if (!empty($platform['contacts']))
                        <ul class="grid">
                            @foreach($platform['contacts'] as $contact)
                                <li class="grid-xs-4">
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
            </div>
        </div>
    </div>

    {{-- @php
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
    @endif --}}
@stop
