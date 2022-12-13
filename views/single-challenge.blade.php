@extends('templates.single')

@section('article.content.before') 
    @if (!empty($preamble)) 
        <p class="lead">{{$preamble}}</p>
    @endif
@stop

@section('article.content.after')
    @includeWhen(!empty($globalGoals), 'partials.challenge.global-goals')
@stop

@section('below')
    @includeWhen($relatedPosts, 'partials.challenge.related-posts')
@stop