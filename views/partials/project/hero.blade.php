@hero([
    'heroView' => 'initiative',
    'customHeroData' => [
        'image' => municipio_get_thumbnail_source($post->id, [456, 342], '4:3'),
        'background' => 'transparent',
    ]
])
    @slot('content')
        @typography([
            'element' => 'span',
            'classList' => ['page-header_meta']
        ])
            {!! get_the_terms($post->id, 'challenge_category')[0]->name !!}
        @endtypography
        @typography([
            'element' => 'h1',
            'variant' => 'h1',
            'classList' => ['page-header__title', 'u-margin__top--0 u-margin__bottom--3']
        ])
            {{ get_the_title() }}
        @endtypography
        @tooltip([
            'label' => $statusBar['label'],
            'icon' => 'info'
        ])
            {{ $statusBar['explainer'] }}
        @endtooltip
        @progressBar([
            'value' => $statusBar['value'],
            'isCancelled' => $statusBar['isCancelled']
        ])
        @endprogressbar
    @endslot
@endhero
