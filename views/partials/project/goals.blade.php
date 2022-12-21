<div id="impactgoals">
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
                            <strong>
                                {{ $item['impact_goal'] }}
                            </strong>
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
                            <strong>
                                {{ $item['impact_goal'] }}
                            </strong>
                        </p>
                    @endif
                </div>
            @endif
        @endcard
    </div>
@endforeach
</div>