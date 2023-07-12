<div id="statusExplanation" class="o-grid">
    <div class="o-grid-12">
        @card([
            'context' => ['project.statusExplanation'],
            'attributeList' => [
                'style' => 'background-color: #F9DAE2;',
            ],
        ])
        <div class="c-card__body">
            @group([
                'justifyContent' => 'space-between',
            ])
                @typography([
                    'element' => 'h2',
                    'variant' => 'h4',
                    'attributeList' => [
                        'style' => 'color: #DA291C;'
                    ]
                ])
                    {{ $project['statusBar']['label'] }}
                @endtypography
                @icon([
                    'icon' => 'favorite',
                    'customColor' => '#DA291C',
                ])
                @endicon
            @endgroup
            @typography([
                'element' => 'p'
            ])
                {{$project['projectStatusDescription']}}
            @endtypography
                
                </div>
        @endcard
    </div>
</div>