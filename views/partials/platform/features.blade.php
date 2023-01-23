@segment([
    'id' => 'features',
    'stretch' => true,
    'paddingTop' => false,
    'paddingBottom' => true,
    'background' => 'lightest',
    'layout' => 'full-width',
    'classList' => ['js-scroll-spy-section']
])
    <div class="o-grid">
        @foreach ($platform['features'] as $feature)
            <div class="o-grid-4@md u-mb-4">
                @card([
                    'context' => ['platform.feature']
                ])
                    <div class="c-card__body">
                        @typography([
                            'classList' => ['c-card__heading'],
                            'element' => 'h3'
                        ])
                            {{ $feature['title'] }}
                        @endtypography
                        @if (!empty($feature['category']))
                            @typography([
                                'classList' => ['c-card__heading'],
                                'variant' => 'h4',
                                'element' => 'span'
                            ])
                                {{ $feature['category'] }}
                            @endtypography
                        @endif

                        @typography([])
                            {{ $feature['content'] }}
                        @endtypography
                    </div>
                @endcard
            </div>
        @endforeach
    </div>
@endsegment
