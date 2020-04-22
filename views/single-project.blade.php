@extends('templates.master')

@section('content')

<div class="container main-container">
    @include('partials.breadcrumbs')

    <div class="grid grid--columns">
        <div class="grid-md-12 grid-lg-8">
            @if (is_single() && is_active_sidebar('content-area-top'))
                <div class="grid grid--columns sidebar-content-area sidebar-content-area-top">
                    <?php dynamic_sidebar('content-area-top'); ?>
                </div>
            @endif

            <div class="grid" id="readspeaker-read">
                <div class="grid-sm-12">
                        {!! the_post() !!}
                        @include('partials.blog.type.post-single')
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

        <div class="grid-md-12 grid-lg-4">
            
            {{-- Project meta --}}
            @if (in_array_any([
                'organisation', 
                'status', 
                'sector', 
                'technologies', 
                'partners'], array_keys($project)))
                <div class="box box-filled box-filled-1">
                    <div class="box-content">
                        {{-- Organisation --}}
                        @if (!empty($project['organisation']))
                            <h4>Organisation</h4>
                            <p>{{$project['organisation']}}</p>
                            <br/>
                        @endif
                        
                        {{-- Status --}}
                        @if (!empty($project['status']))
                            <h4>Status</h4>
                            <p>{{$project['status']}}</p>
                            <br/>
                        @endif

                        {{-- Sector --}}
                        @if (!empty($project['sector']))
                            <h4>Sector</h4>
                            <p>{{$project['sector']}}</p>
                            <br/>
                        @endif

                        {{-- Technologies --}}
                        @if (!empty($project['technologies']))
                            <h4>Teknologier</h4>
                            <p>{{$project['technologies']}}</p>
                            <br/>
                        @endif

                        {{-- Partners --}}
                        @if (!empty($project['partners']))
                            <h4>Partners</h4>
                            <p>{{$project['partners']}}</p>
                        @endif
                    </div>
                </div>
            @endif
            
            {{-- Contacts --}}
            @if ($project && $project['contacts'])
                @php
                    $contacts = get_post_meta(get_the_id(), 'contacts', false )[0];
                @endphp

                {{-- TODO: Translate labels --}}
                <div class="box box-filled box-filled-1">
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

