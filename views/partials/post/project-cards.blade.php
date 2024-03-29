@if ($posts)
    <div class="o-grid">
        @foreach ($posts as $key => $post)
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
                    'hasPlaceholder' => !isset($post->thumbnail),
                    'attributeList' => ['style' => 'z-index:' . (999 - $key) . ';'],
                    'classList' => ['u-height--100', 'c-card--flat', 'project-card']
                ])
                    @slot('afterContent')
                        @if(!empty($post->project->statusBar))
                        <div class="u-margin__top--auto u-width--100">
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
                        @endif
                    @endslot
                @endcard
            </div>
        @endforeach
    </div>
@endif
