<div id="impactgoals" class="o-grid">
    @foreach ($project['impactGoals'] as $item)
        <div class="o-grid-12">
            @card(['classList' => ['u-color__bg--lighter']])
                @if ($item['impact_goal'])
                    <div class="c-card__body">
                        @if ($item['impact_goal_completed'])
                            @typography([
                                'element' => 'h2',
                                'variant' => 'h4'
                            ])
                                {{ __('Impact goals', 'project-manager-integration') }}
                            @endtypography
                            @typography([
                                'element' => 'p'
                            ])
                                {{ $item['impact_goal'] }}
                            @endtypography

                            @if (!empty($item['impact_goal_comment']))
                                @typography([
                                    'element' => 'h2',
                                    'variant' => 'h4',
                                    'classList' => ['u-margin__top--2']
                                ])
                                    {{ __('Results', 'project-manager-integration') }}
                                @endtypography
                                @typography([
                                    'element' => 'p'
                                ])
                                    {{ $item['impact_goal_comment'] }}
                                @endtypography
                            @endif
                        @else
                            @typography([
                                'element' => 'h2',
                                'variant' => 'h4'
                            ])
                                {{ __('Impact goals', 'project-manager-integration') }}
                            @endtypography

                            @typography([
                                'element' => 'p'
                            ])
                                {{ $item['impact_goal'] }}
                            @endtypography
                        @endif
                    </div>
                @endif
            @endcard
        </div>
    @endforeach
</div>
