@extends('templates.single')

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
