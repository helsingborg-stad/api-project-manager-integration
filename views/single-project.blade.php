@extends('templates.single')

@section('article.title.before')
    <div class="u-display--none">
    @stop
    @section('article.title.after')
    </div>
@stop

@php
    $statusBar = \ProjectManagerIntegration\UI\ProjectStatus::create($post->id);
@endphp

@section('sidebar.top-sidebar.after')
    <header class="page-header page-header--{{ get_post_type() }}">
        <div class="o-container page-header__container">
            <div
                class="o-grid u-flex-direction--row--reverse@sm u-flex-direction--row--reverse@md u-flex-direction--row--reverse@lg u-flex-direction--row--reverse@xl">
                <div class="grid-xs-12 grid-sm-6 order-2 page-header__image u-mb-2@xs">
                    @if (municipio_get_thumbnail_source($post->ID, [456, 342], '4:3'))
                        <img class="u-max-width-header-image"
                            src="{{ municipio_get_thumbnail_source($post->ID, [456, 342], '4:3') }}">
                    @endif
                </div>
                <div class="grid-xs-12 grid-sm-6 page-header__body">
                    <div class="page-header__content u-max-width-header-content u-ml-auto">
                        @if (get_field('page_header_meta', $post->ID) || !empty(get_the_terms($post->id, 'challenge_category')))
                            <span
                                class="page-header__meta">{{ get_the_terms($post->id, 'challenge_category')[0]->name }}</span>
                        @endif
                        <h1 class="page-header__title u-margin__top--0">{{ get_the_title() }}</h1>



                        @if (get_field('page_header_content', $post->ID) || !empty($statusBar))
                            @if (!empty($statusBar) && $statusBar['value'] > -1 && $statusBar['label'])
                                <div class="statusbar u-margin__top--2">
                                    <div class="statusbar__header u-mb-1 explain">
                                        <b class="statusbar__title">{{ $statusBar['label'] }}</b>

                                        @if (!empty($statusBar['explainer']))
                                            <span class="statusbar__explainer">
                                                <span data-tooltip="{{ $statusBar['explainer'] }}" data-tooltip-bottom>
                                                    <i class="pricon pricon-info-o"></i>
                                                </span>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="statusbar__content">
                                        <div class="c-progressbar">
                                            <div class="c-progressbar__value {{ $statusBar['isCancelled'] ? 'is-disabled' : '' }}"
                                                style="width: {{ $statusBar['value'] }}%;"></div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </header>
@stop


@section('sidebar.right-sidebar.after')
    <div class="o-grid">
        @if ($project && !empty($project['impactGoals']))
            @foreach ($project['impactGoals'] as $item)
                <div class="o-grid-12">
                    @card
                        @if ($item['impact_goal'])
                            <div class="c-card__body">
                                @if ($item['impact_goal_completed'])
                                    <h4>
                                        <small
                                            class="secondary-color tiny">{{ __('Impact goals', 'project-manager-integration') }}</small>
                                    </h4>
                                    <p class="u-p-0">
                                        <b>
                                            {{ $item['impact_goal'] }}
                                        </b>
                                    </p>
                                    @if (!empty($item['impact_goal_comment']))
                                        <h4 class="u-mt-2">
                                            <small
                                                class="secondary-color tiny">{{ __('Results', 'project-manager-integration') }}</small>
                                        </h4>
                                        <p>{{ $item['impact_goal_comment'] }}</p>
                                    @endif
                                @else
                                    <h4>
                                        <small
                                            class="secondary-color tiny">{{ __('Impact goals', 'project-manager-integration') }}</small>
                                    </h4>
                                    <p class="u-p-0">
                                        <b>
                                            {{ $item['impact_goal'] }}
                                        </b>
                                    </p>
                                @endif
                            </div>
                        @endif
                    @endcard
                </div>
            @endforeach
        @endif

        {{-- Resident Involvement --}}
        @if ($project && !empty($project['residentInvolvement']))
            @foreach ($project['residentInvolvement'] as $residentInvolement)
                <div class="o-grid-12">
                    @card
                        <div class="c-card__body">
                            <h4>
                                <small
                                    class="secondary-color tiny">{{ __('Resident involvement', 'project-manager-integration') }}</small>
                            </h4>
                            <p class="u-p-0">
                                <b>
                                    {{ $residentInvolement['description'] }}
                                </b>
                            </p>
                        </div>
                    @endcard
                </div>
            @endforeach
        @endif


        {{-- Project meta --}}
        @if ($project && !empty($project['meta']))
            <div class="o-grid-12">
                @card
                    <div class="c-card__body">
                        <ul class="box-project-meta__list">
                            @foreach ($project['meta'] as $meta)
                                <li>
                                    <h4>{{ $meta['title'] }}</h4>
                                    @if (!empty($meta['url']))
                                        <a href="{{ $meta['url'] }}">
                                            <p>{!! $meta['content'] !!}</p>
                                        </a>
                                    @else
                                        <p>{!! $meta['content'] !!}</p>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endcard
            </div>
        @endif

        {{-- Contacts --}}
        @if ($project && !empty($project['contacts']))
            <div class="o-grid-12">
                {{-- TODO: Translate labels --}}
                @card
                    <div class="c-card__body">
                        <h4>Kontakt</h4>
                        @foreach ($project['contacts'] as $contact)
                            <p><b>Namn:</b> {{ $contact['name'] }}
                                @if ($contact['email'])
                                    </br> <b>E-post: </b><a href="mailto:{{ $contact['email'] }}">{{ $contact['email'] }}</a>
                                @endif
                            </p>
                        @endforeach
                    </div>
                @endcard
            </div>
        @endif

        {{-- Files --}}
        @if ($project && !empty($project['files']))
            <div class="o-grid-12">
                {{-- TODO: Translate labels --}}
                @card
                    <div class="c-card__body u-pt-1">
                        <h4>Filer</h4>
                        <ul>
                            @foreach ($project['files'] as $file)
                                <li>
                                    <a href="{{ $file['file']['url'] }}">{{ $file['title'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endcard
            </div>
        @endif

        {{-- Links --}}
        @if ($project && !empty($project['links']))
            <div class="o-grid-12">
                {{-- TODO: Translate labels --}}
                @card
                    <div class="c-card__body u-pt-1">
                        <h4>Länkar</h4>
                        <ul>
                            @foreach ($project['links'] as $link)
                                <li>
                                    <a target="_blank" href="{{ $link['url'] }}">{{ $link['title'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endcard
            </div>
        @endif
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
