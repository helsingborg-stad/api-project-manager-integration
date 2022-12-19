<section class="page-header page-header--{{ get_post_type() }}" style="background-image: url({{municipio_get_thumbnail_source($post->id, [456, 342], '4:3')}});">
    <div class="o-container page-header__container">
        @group([
            'wrap' => 'wrap',
            'classList' => ['o-grid', 'u-flex-direction--row--reverse']

        ])
            <div class="o-grid-12@xs o-grid-6@sm">
                @if (municipio_get_thumbnail_source($post->id, [456, 342], '4:3'))
                    @image([
                        'src' => municipio_get_thumbnail_source($post->id, [456, 342], '4:3'),
                    ])
                    @endimage       
                @endif
            </div>
            <div class="o-grid-12@xs o-grid-6@sm">
                <div class="u-ml-auto">
                    @if (get_field('page_header_meta', $post->id) || !empty(get_the_terms($post->id, 'challenge_category')))
                    @typography([

                    ])
                      {!! get_the_terms($post->id, 'challenge_category')[0]->name !!}
                    @endtypography
                    @endif
                    @typography([
                        'variant' => 'h1',
                        'element' => 'h1',
                        'classList' => ['u-margin__top--1']
                    ])
                        {{ get_the_title() }}
                    @endtypography
                    @if (!empty($statusBar) && $statusBar['value'] > -1 && $statusBar['label'])
                    <div class="u-align-self--end u-width--100 u-margin__top--3">  
                        @tooltip([
                            'label' => $statusBar['label'],
                            'placement' => 'bottom',
                            'classList' => ['u-justify-content--end']
                        ])
                            {{ $statusBar['explainer'] }}
                        @endtooltip
                        @progressbar([
                            'value' => $statusBar['value'],
                            'isCancelled' => $statusBar['isCancelled'],
                        ])
                        @endprogressbar
                    </div>
                    @endif
                </div>
            </div>
        @endgroup
    </div>
</section>