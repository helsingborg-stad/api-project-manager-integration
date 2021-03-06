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


    @if (!empty($scrollSpyMenuItems) && count($scrollSpyMenuItems) > 1)
        <div class="sticky-bar sticky-bar--content u-mb-2">
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
@stop



@section('before-article')
    <div id="background" class="js-scroll-spy-section"></div>
@stop

@section('after-article')
    @if ($youtubeUrl)
        <div class="u-mt-7">
            <div class="u-yt-wrapper">
                <iframe 
                    frameborder="0" 
                    scrolling="no" 
                    marginheight="0" 
                    marginwidth="0" 
                    width="788.54" 
                    height="443" 
                    type="text/html"
                    src="{{$youtubeUrl}}">
                </iframe>
            </div>
        </div>
    @endif
@stop

@section('content-bottom')
    @if (!empty($features))
        <div id="features" class="section u-py-5 js-scroll-spy-section">
            <div class="container">
                <div class="grid">
                    {{-- Features --}}
                    <div class="grid-xs-12">
                        @if (!empty($features))
                        <ul class="grid">
                            @foreach($features as $feature)
                            <li class="grid-md-4 u-mb-4">
                                <div class="box box-filled box--lightblue box-project u-h-100">
                                    <div class="box-content">
                                        <h3 class="u-mb-2">
                                            {{$feature['title']}} 
                                            @if (!empty($feature['category']))
                                            <br><small>{{$feature['category']}}</small><br>
                                            @endif
                                        </h3>
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
    @endif

    @if (!empty($getStartedContent))
        <div id="get-started" class="section u-py-5 js-scroll-spy-section">
            <div class="container">
                <div class="grid">
                    <div class="grid-xs-12">
                        <article id="article" class="c-article clearfix full u-mb-4">
                            <h2>{{$getStartedHeading}}</h2>
                            {!! $getStartedContent !!}
                        </article>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (!empty($roadmap) && !empty($roadmap['items']))
        <style>
            .c-status-dot {
                border-radius: 100%; 
                display: block; 
                height: 16px; 
                width: 16px;
            }
        </style>
    
        <div id="roadmap" class="section u-py-7 t-blue-section js-scroll-spy-section">
            <div class="container">
                <div class="grid">
                    <div class="grid-xs-12 u-mb-4">
                        <h2>{{$labels['roadmap']}}</h2>
                    </div>
                    {{-- Roadmap --}}
                    <div class="grid-xs-12 u-mb-2">
                        @if (!empty($roadmap['items']))
                        <div class="js-post-slider u-w-100">
                            <div id="flickity-mod-posts-platform" class="grid post-slider__flickity js-post-slider__flickity" data-flickity-options='{!! json_encode($roadmap['flickityOptions'])!!}'  data-equal-container>
                                @foreach($roadmap['items'] as $item)
                                <div class="post-slider__item flickity-item u-flex u-align-items-center {{implode(' ', $item['classes'])}} @if (isset($columnsPerRow) && $loop->iteration > $columnsPerRow) u-flickity-init-hidden @endif">
                                    <div class="box box-filled">
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
                                                            && !empty($roadmap['statusColors']) 
                                                            && isset($roadmap['statusColors'][$item['status']]))
                                                        <div class="grid-xs-1">
                                                            <span class="c-status-dot u-display-inline-block" style="background-color:{{$roadmap['statusColors'][$item['status']]}};"></span>
                                                        </div>
                                                    @endif
                                                <div class="grid-xs-auto">
                                                    <p><b>{{$item['date']}}</b></p>
                                                </div>
                                            </div>
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


    @if (!empty($files) || !empty($links))
        <div class="section u-py-7 u-bt-1">
            <div class="container">
                <div class="grid grid--columns">
                    {{-- Contacts --}}
                    @if (!empty($contacts))
                        <div class="grid-xs-12 grid-md-auto">
                            <div class="box box-filled box--lightblue">
                                <div class="box-content">
                                    <h3>{{$labels['contacts']}}</h3>
                                    <ul class="unordered-list">
                                        @foreach($contacts as $contact)
                                            <li>
                                                <h4>
                                                    {{$contact['name']}} 
                                                    @if (!empty($contact['role']))
                                                        <br><small>{{$contact['role']}}</small>
                                                    @endif
                                                </h4>
                                                <a href="mailto: {{$contact['mail']}}">{{$contact['mail']}}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Files --}}
                    @if (!empty($files))
                        <div class="grid-xs-12 grid-md-auto">
                            <div class="box box--outline box-filled">
                                <div class="box-content">
                                    <h3>{{$labels['documents']}}</h3>
                                    <ul class="pricon-list">
                                        @foreach($files as $file)
                                        <li class="pricon-list__item {{implode(' ', $file['classNames'])}}">
                                            <a target="_blank" href="{{$file['attachment']}}">{{$file['title']}}</a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Links --}}
                    @if (!empty($links))
                        <div class="grid-xs-12 grid-md-auto u-mb-4">
                            <div class="box box--outline box-filled">
                                <div class="box-content">
                                <h3>{{$labels['links']}}</h3>
                                <ul class="pricon-list">
                                    @foreach($links as $link)
                                        <li class="pricon-list__item pricon pricon-external-link">
                                            <a target="_blank" href="{{$link['url']}}">{{$link['title']}}</a>
                                        </li>
                                    @endforeach
                                </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if (!empty($projects))
        <div class="section u-pt-7 u-bt-1">
            <div class="container">
                <h2 class="u-mb-4">{{$labels['relatedProjects']}}</h2>
                <div class="grid grid--columns">
                    @foreach ($projects as $post)
                        @include('partials.blog.type.post-card-project', array('post' => $post, 'grid_size' => 'grid-xs-12 grid-sm-6 grid-md-3'))
                    @endforeach
                </div>
            </div>
        </div>
    @endif  
@stop
