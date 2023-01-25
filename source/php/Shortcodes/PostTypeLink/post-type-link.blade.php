@if (!empty($title) && !empty($url) && !empty($buttonText))
    @card([
        'classList' => ['c-card--compact-post', 'post-type-shortcode'],
        'context' => ['shortcode.post-type-link']
    ])
        <div class="c-card__body">
            @group([
                'direction' => 'row',
                'classList' => ['u-flex--gridgap']
            ])
                @if (!empty($imageUrl))
                    @image([
                        'src' => $imageUrl,
                        'classList' => ['u-display--none@xs']
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

                <div class="u-margin__left--auto u-display--flex u-align-items--center">
                    @button([
                        'href' => $url,
                        'style' => 'filled',
                        'color' => 'primary',
                        'text' => $buttonText,
                        'target' => !empty($blank) ? '_blank' : '_top',
                    ])
                    @endbutton
                </div>
            @endgroup
        </div>
    @endcard
@endif
