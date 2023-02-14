@extends('templates.single')

@section('sidebar.top-sidebar.after')
@includeWhen(!empty($platform['hero']['image']), 'partials.platform.hero')
    @anchorMenu([
        'menuItems' => $scrollSpyMenuItems,
    ])
    @endanchorMenu
@stop

@section('sidebar.right-sidebar.after')
    <div class="o-grid">
        {{-- Meta --}}
        @includeWhen($platform && !empty($platform['meta']), 'partials.platform.meta')

        {{-- Contacts --}}
        @includeWhen($platform && !empty($platform['contacts']), 'partials.platform.contacts')

        {{-- Files --}}
        @includeWhen($platform && !empty($platform['files']), 'partials.platform.files')

        {{-- Links --}}
        @includeWhen($platform && !empty($platform['links']), 'partials.platform.links')
    </div>
@stop

@section('below')
    @includeWhen(!empty($platform['features']), 'partials.platform.features')
    @includeWhen(!empty($platform['getStartedContent']), 'partials.platform.get-started')

    {{-- TODO: Implement styling for roadmap  --}}
    {{-- @includeWhen(!empty($platform['roadmap']) && !empty($platform['roadmap']['items']),
    'partials.platform.roadmap') --}}

    {{-- TODO: Implement styling for platform meta --}}
    {{-- TODO: PO/editors acknowledgement before release of platform meta since content might need to get updated --}}
    {{-- @includeWhen(!empty($platform['files']) || !empty($platform['links']) || !empty($platform['contacts']),
        'partials.platform.meta') --}}

    @if (!empty($platform['relatedProjects']))
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
                        {{ $platform['relatedProjects']['title'] }}
                    @endtypography
                @endgroup
                @include('partials.post.project-cards', [
                    'posts' => $platform['relatedProjects']['posts'],
                ])
            </div>
        @endsegment
    @endif
@stop
