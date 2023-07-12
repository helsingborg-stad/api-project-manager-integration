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

@section('article.content.after')
    @includeWhen(!empty($project['gallery']), 'partials.project.gallery')
    @include('partials.project.dates')
@stop

@php
@endphp
@section('sidebar.right-sidebar.after')
    <div class="o-grid">
        
        {{-- Searching collaboration --}}
        @includeWhen($project && !empty($project['searching_collaborator']) && !empty($project['seekingCollaboration']), 'partials.project.collaborator')

        {{-- Impact goals --}}
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
                    'classList' => ['related_posts', 'u-margin__bottom--3']
                ])
                    @typography([
                        'element' => 'h2',
                        'variant' => 'h2',
                        'classList' => ['u-margin__bottom--3']
                    ])
                        {{ $project['relatedPosts']['title'] }}
                    @endtypography
                    @link([
                        'href' => $project['archive'],
                        'classList' => ['related_posts-link', 'u-no-decoration', 'u-color__text--darkest']
                    ])
                        @group([
                            'alignItems' => 'center'
                        ])
                            {{ $project['labels']['showAll'] }}
                            @icon([
                                'icon' => 'arrow_forward',
                                'classList' => ['related_posts-icon'],
                                'size' => 'lg'
                            ])
                            @endicon
                        @endgroup
                    @endlink
                @endgroup
                @include('partials.post.project-cards', [
                    'posts' => $project['relatedPosts']['posts'],
                    'gridColumnClass' => !empty($gridSize) ? $gridSize : 'grid-xs-12 grid-md-3',
                ])
            </div>
        @endsegment
    @endif
@stop
