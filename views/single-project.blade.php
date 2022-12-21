@extends('templates.single')

@section('sidebar.top-sidebar.after')
    @include('partials.project.hero')
    {{-- Might wanna change location. Can not be placed within o-container since it has  --}}
    @includeWhen(!empty($scrollSpyMenuItems) && count($scrollSpyMenuItems) > 1, 'partials.project.anchorMenu')
@stop



@section('article.title.before')
    <div class="u-display--none">
@stop
@section('article.title.after')
    </div>
@stop

@php
    $statusBar = \ProjectManagerIntegration\UI\ProjectStatus::create($post->id);
@endphp

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
        <div class="u-margin__bottom--6">
            @typography([
                'element' => 'h2',
                'variant' => 'h2',
                'classList' => ['u-margin__bottom--3']
            ])
                {{ $project['relatedPosts']['title'] }}
            @endtypography

            @include('partials.post.project-cards', [
                'posts' => $project['relatedPosts']['posts'],
                'gridColumnClass' => !empty($gridSize) ? $gridSize : 'grid-xs-12 grid-md-3',
            ])
        </div>
    @endif
@stop
