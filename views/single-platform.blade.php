@extends('templates.single')

@section('article.content.before')

@stop

@section('below')
    @includeWhen(!empty($platform['getStartedContent']), 'partials.platform.get-started')
    @includeWhen(!empty($platform['relatedProjects']), 'partials.platform.related-projects')
@stop
