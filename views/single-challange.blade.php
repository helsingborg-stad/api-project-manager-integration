@extends('templates.master')

@section('content')

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
                    @include('partials.blog.type.post-single-challange')
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
            @if ($project && !empty($project['impact_goals']))
                @foreach ($project['impact_goals'] as $item)
                    <div class="box box-filled box-filled-1 box-project box-project-contact">
                        @if ($item['impact_goal_completed'])
                            <h4 class="box-title"><small>Impact Goal - Completed</small><br><strike>{{$item['impact_goal']}}</strike></h4>
                            @if (!empty($item['impact_goal_comment']))
                                <div class="box-content">
                                    {{$item['impact_goal_comment']}}
                                </div>
                            @endif
                        @else
                            <h4 class="box-title"><small>Impact Goal</small><br>{{$item['impact_goal']}}</h4>
                        @endif
                    </div>
                @endforeach
            @endif

            {{-- Project meta --}}
            @if ($project && !empty($project['meta']))
                <div class="box box-filled box-filled-1 box-project box-project-meta">
                    <div class="box-content">
                        <ul class="box-project-meta__list">
                            @foreach ($project['meta'] as $meta)
                                <li>
                                    <h4>{{$meta['title']}}</h4>
                                    <p>{{$meta['content']}}</p>
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
                    <div class="box-content">
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

        </div>
        @include('partials.sidebar-right')
    </div>
</div>

@stop

