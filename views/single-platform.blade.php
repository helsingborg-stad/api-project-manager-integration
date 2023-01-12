@extends('templates.single')

@section('below')
    @includeWhen(!empty($platform['getStartedContent']), 'partials.platform.get-started')
    @includeWhen(!empty($platform['roadmap']) && !empty($platform['roadmap']['items']),
        'partials.platform.roadmap')
    @includeWhen(!empty($platform['files']) || !empty($platform['links']) || !empty($platform['contacts']),
        'partials.platform.meta')
    @includeWhen(!empty($platform['relatedProjects']), 'partials.platform.related-projects')
@stop
