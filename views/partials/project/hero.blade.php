<section class="project-hero u-position--relative">
    <div class="project-hero__image-container u-overflow--hidden">
        <div class="project-hero__image-background u-position--absolute u-height--100 u-width--100" style="background-image: url({{municipio_get_thumbnail_source($post->id, [456, 342], '4:3')}});"></div>
    </div>
    <div class="o-container">
        @group([
            'wrap' => 'wrap',
            'classList' => ['project-hero__container', 'o-grid', 'u-flex-direction--row--reverse'],

        ])
            <div class="o-grid-12@sm o-grid-6@md">
                @if (municipio_get_thumbnail_source($post->id, [500, 500], '1:1'))
                    @image([
                        'src' => municipio_get_thumbnail_source($post->id, [500, 500], '1:1'),
                        'classList' => ['u-margin__bottom--0']
                    ])
                    @endimage       
                @endif
            </div>
                @group([
                    'justifyContent' => 'center',
                    'direction' => 'vertical',
                    'classList' => ['project-hero__content', 'o-grid-12@sm', 'o-grid-6@md']
                ])
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
                @endgroup
            </div>
        @endgroup
    </div>
</section>