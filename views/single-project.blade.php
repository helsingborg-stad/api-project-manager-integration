@extends('templates.single')

@section('sidebar.right-sidebar.after')
    <div class="o-grid">
        @if ($project && !empty($project['impactGoals']))
            @foreach ($project['impactGoals'] as $item)
                <div class="o-grid-12">
                    <div id="impactgoals"
                        class="box box--outline box-filled box-filled-1 box-project box-project-contact js-scroll-spy-section">
                        <div class="box-content u-py-4">
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
                    </div>
                </div>
            @endforeach
        @endif

        {{-- Resident Involvement --}}
        @if ($project && !empty($project['residentInvolvement']))
            @foreach ($project['residentInvolvement'] as $residentInvolement)
                <div class="o-grid-12">
                    <div id="residentInvolvement"
                        class="box box--outline box-filled box-filled-1 box-project box-project-contact js-scroll-spy-section">
                        <div class="box-content u-py-4">
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
                    </div>
                </div>
            @endforeach
        @endif


        {{-- Project meta --}}
        @if ($project && !empty($project['meta']))
            <div class="o-grid-12">
                <div id="about" class="box box-filled box-filled-1 box-project box-project-meta js-scroll-spy-section">
                    <div class="box-content">
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
                </div>
            </div>
        @endif

        {{-- Contacts --}}
        @if ($project && !empty($project['contacts']))
            <div class="o-grid-12">
                {{-- TODO: Translate labels --}}
                <div class="box box-filled box-filled-1 box-project box-project-contact">
                    <h4 class="box-title">Kontakt</h4>
                    <div class="box-content u-pt-1">
                        @foreach ($project['contacts'] as $contact)
                            <p><b>Namn:</b> {{ $contact['name'] }}
                                @if ($contact['email'])
                                    </br> <b>E-post: </b><a
                                        href="mailto:{{ $contact['email'] }}">{{ $contact['email'] }}</a>
                                @endif
                            </p>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- Files --}}
        @if ($project && !empty($project['files']))
            <div class="o-grid-12">
                {{-- TODO: Translate labels --}}
                <div class="box box-filled box-filled-1 box-project box-project-contact">
                    <h4 class="box-title">Filer</h4>
                    <div class="box-content u-pt-1">
                        <ul>
                            @foreach ($project['files'] as $file)
                                <li>
                                    <a href="{{ $file['file']['url'] }}">{{ $file['title'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Links --}}
        @if ($project && !empty($project['links']))
            <div class="o-grid-12">
                {{-- TODO: Translate labels --}}
                <div class="box box-filled box-filled-1 box-project box-project-contact">
                    <h4 class="box-title">Länkar</h4>
                    <div class="box-content u-pt-1">
                        <ul>
                            @foreach ($project['links'] as $link)
                                <li>
                                    <a target="_blank" href="{{ $link['url'] }}">{{ $link['title'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

    </div>
@stop
