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
    <div class="o-grid u-padding__top--8">
        @typography([
            'variant' => 'h2',
            'element' => 'h3'
        ])
            {{ $challenge['relatedProjects']['title'] }}
        @endtypography
    </div>
    @includeWhen(!empty($challenge['relatedProjects']), 'partials.post.project-cards', [
                'posts' => $challenge['relatedProjects']['posts'],
    ])
    @includeWhen(!empty($challenge['relatedPosts']), 'partials.challenge.related-posts')
@stop
