@if (!empty($title) && !empty($url) && !empty($buttonText))
    @collection([])
        @collection__item([
            'action' => [
                'link' => $url,
                'style' => 'filled',
                'text' => $buttonText,
                'target' => !empty($blank) ? '_blank' : '_top'
            ]
        ])
            @group([
                'direction' => 'row',
                'classList' => ['u-flex--gridgap']
            ])
                @if (!empty($imageUrl))
                    @image([
                        'src' => $imageUrl
                    ])
                    @endimage
                @endif

                @group([
                    'direction' => 'vertical'
                ])
                    @if (!empty($meta))
                        @typography([
                            'element' => 'span'
                        ])
                            {{ $meta }}
                        @endtypography
                    @endif
                    @typography([
                        'element' => 'h4',
                        'variant' => 'h3'
                    ])
                        {{ $title }}
                    @endtypography
                @endgroup
            @endgroup
        @endcollection__item
    @endcollection
@endif
