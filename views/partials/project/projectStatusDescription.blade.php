<div id="statusExplanation" class="o-grid">
    <div class="o-grid-12">
        @card([
            'context' => ['project.statusExplanation'],
            'attributeList' => [
                'style' => 'background-color: #F9DAE2;',
            ],
        ])
        <div class="c-card__body">
            @typography([
                'element' => 'h2',
                'variant' => 'h4',
                'attributeList' => [
                    'style' => 'color: #DA291C;'
                ]
            ])
                {{ $project['statusBar']['label'] }}
            @endtypography
            @typography([
                'element' => 'p'
            ])
                {{$project['projectStatusDescription']}}
            @endtypography
                
                </div>
        @endcard
    </div>
</div>