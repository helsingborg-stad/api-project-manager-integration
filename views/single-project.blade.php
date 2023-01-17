@extends('templates.single')

@php
    $statusBar = \ProjectManagerIntegration\UI\ProjectStatus::create($post->id);
@endphp

@section('sidebar.top-sidebar.after')
    @include('partials.project.hero')
    
    @anchorMenu([
        'menuItems' => $scrollSpyMenuItems,
        'classList' => ['u-display--none@lg', 'u-display--none@xl']
    ])
    @endanchorMenu
@stop

@section('article.title.before')
    <div class="u-display--none">
@stop
@section('article.title.after')
    </div>
@stop

@section('sidebar.right-sidebar.after')
    <div class="o-grid">
        @includeWhen($project && !empty($project['impactGoals']), 'partials.project.goals')

        {{-- Resident Involvement --}}
        @includeWhen ($project && !empty($project['residentInvolvement']), 'partials.project.involvement')
            
        {{-- Project meta --}}
        @includeWhen($project && !empty($project['meta']), 'partials.project.meta')

        {{-- Contacts --}}
        @includeWhen($project && !empty($project['contacts']), 'partials.project.contacts')

        {{-- Files --}}
        @includeWhen($project && !empty($project['files']), 'partials.project.files')

        {{-- Links --}}
        @includeWhen($project && !empty($project['links']), 'partials.project.links')
    </div>
@stop

@section('below')
    @if (!empty($project['relatedPosts']['posts']))
        @segment([
            'stretch' => true,
            'paddingTop' => false,
            'paddingBottom' => false,
            'background' => 'lightest',
            'layout' => 'full-width'
        ])
            <div class="u-padding__bottom--8">
                @group([
                    'justifyContent' => 'space-between',
                    'classList' => ['challenge__related', 'u-margin__bottom--3']
                ])
                    @typography([
                        'element' => 'h2',
                        'variant' => 'h2',
                        'classList' => ['u-margin__bottom--3']
                    ])
                        {{ $project['relatedPosts']['title'] }}
                    @endtypography
                    {{-- @link([
                        'href' => $challenge['archive'],
                        'classList' => ['challenge__related-link']
                    ])
                        @group([
                            'alignItems' => 'center'
                        ])
                            {{ $challenge['labels']['showAll'] }}
                            @icon([
                                'icon' => 'arrow_forward',
                                'classList' => ['challenge__related-icon'],
                                'size' => 'lg'
                            ])
                            @endicon
                        @endgroup
                    @endlink --}}
                @endgroup
                @include('partials.post.project-cards', [
                    'posts' => $project['relatedPosts']['posts'],
                    'gridColumnClass' => !empty($gridSize) ? $gridSize : 'grid-xs-12 grid-md-3',
                ])
            </div>
        @endsegment
    @endif
@stop
