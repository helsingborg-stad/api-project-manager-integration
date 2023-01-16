@extends('templates.single')

@section('below')
    @includeWhen(!empty($platform['getStartedContent']), 'partials.platform.get-started')

    {{-- TODO: Implement styling for roadmap  --}}
    {{-- @includeWhen(!empty($platform['roadmap']) && !empty($platform['roadmap']['items']),
    'partials.platform.roadmap') --}}

    {{-- TODO: Implement styling for platform meta --}}
    {{-- TODO: PO/editors acknowledgement before release of platform meta since content might need to get updated --}}
    {{-- @includeWhen(!empty($platform['files']) || !empty($platform['links']) || !empty($platform['contacts']),
        'partials.platform.meta') --}}

    <div class="o-grid u-padding__top--8">
        @typography([
            'variant' => 'h2',
            'element' => 'h3'
        ])
            {{ $platform['relatedProjects']['title'] }}
        @endtypography
    </div>
    @includeWhen(!empty($platform['relatedProjects']), 'partials.post.project-cards', [
        'posts' => $platform['relatedProjects']['posts'],
    ])
@stop
