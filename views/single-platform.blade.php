@extends('templates.single')

@section('article.content.before')

@stop

@section('below')
    @includeWhen(!empty($platform['relatedProjects']), 'partials.platform.related-projects')
@stop
