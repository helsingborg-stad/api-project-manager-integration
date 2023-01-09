<div class="o-grid u-padding__top--8">
    @typography([
        'variant' => 'h2',
        'element' => 'h3'
    ])
        {{ $challenge['labels']['relatedProjects'] }}
    @endtypography
    @foreach ($relatedProjects as $key => $item)
        <div class="o-grid-3@md u-margin__bottom--8">
            @card([
                'heading' => $item->post_title,
                'meta' => $item->category,
                'metaFirst' => true,
                'content' => $item->taxonomies,
                'link' => $item->permalink,
                'image' => $item->thumbnail,
                'classList' => ['u-height--100'],
                'attributeList' => ['style' => 'z-index:' . (999 - $key) . ';']
            ])
                @slot('afterContent')
                    <div class="u-align-self--end u-width--100">
                        @tooltip([
                            'label' => $item->statusBar['label'],
                            'placement' => 'bottom',
                            'classList' => ['u-justify-content--end']
                        ])
                            {{ $item->statusBar['explainer'] }}
                        @endtooltip
                        @progressbar([
                            'value' => $item->statusBar['value'],
                            'isCancelled' => $item->statusBar['isCancelled']
                        ])
                        @endprogressbar
                    </div>
                @endslot
            @endcard
        </div>
    @endforeach
</div>
