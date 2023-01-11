@extends('templates.single')

@section('article.content.before')
    @if (!empty($challenge['preamble']))
        <p class="lead">{{ $challenge['preamble'] }}</p>
    @endif
@stop

@section('article.content.after')
    @includeWhen(!empty($challenge['contacts']), 'partials.challenge.contacts')
    @includeWhen(!empty($challenge['globalGoals']), 'partials.challenge.global-goals')
@stop

@section('below')
    @includeWhen(!empty($challenge['relatedProjects']), 'partials.challenge.related-projects')
    @includeWhen(!empty($challenge['relatedPosts']), 'partials.challenge.related-posts')
@stop
