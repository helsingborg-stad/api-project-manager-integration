<div class="o-grid u-padding__top--8">
    @typography([
        'variant' => 'h2',
        'element' => 'h3'
    ])
        {{ $platform['relatedProjects']['title'] }}
    @endtypography
    @foreach ($platform['relatedProjects']['posts'] as $key => $post)
        <div class="o-grid-3@md u-margin__bottom--8">
            @card([
                'image' => $post->thumbnail['src'],
                'link' => $post->permalink,
                'heading' => $post->postTitle,
                'content' => $post->project->taxonomies,
                'meta' => $post->project->category,
                'metaFirst' => true,
                'context' => ['archive', 'archive.list', 'archive.list.card'],
                'containerAware' => true,
                'hasPlaceholder' => false,
                'attributeList' => ['style' => 'z-index:' . (999 - $key) . ';'],
                'classList' => ['u-height--100']
            ])
                @slot('afterContent')
                    <div class="u-align-self--end u-width--100">
                        @tooltip([
                            'label' => $post->project->statusBar['label'],
                            'placement' => 'bottom',
                            'classList' => ['u-justify-content--end']
                        ])
                            {{ $post->project->statusBar['explainer'] }}
                        @endtooltip
                        @progressBar([
                            'value' => $post->project->statusBar['value'],
                            'isCancelled' => $post->project->statusBar['isCancelled']
                        ])
                        @endprogressBar
                    </div>
                @endslot
            @endcard
        </div>
    @endforeach
</div>
