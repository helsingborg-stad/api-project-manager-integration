@extends('templates.single')

@section('article.content.before')

@stop

@section('below')

    @includeWhen(!empty($platform['getStartedContent']), 'partials.platform.get-started')
    @includeWhen(!empty($platform['roadmap']) && !empty($platform['roadmap']['items']),
        'partials.platform.roadmap')

    @if (!empty($platform['files']) || !empty($platform['links']) || !empty($platform['contacts']))
        <div class="section u-py-7 u-bt-1">
            <div class="o-container">
                <div class="o-grid">
                    @includeWhen(!empty($platform['contacts']), 'partials.platform.contacts')
                    @includeWhen(!empty($platform['files']), 'partials.platform.files')
                    @includeWhen(!empty($platform['links']), 'partials.platform.links')
                </div>
            </div>
        </div>
    @endif

    @includeWhen(!empty($platform['relatedProjects']), 'partials.platform.related-projects')
@stop
