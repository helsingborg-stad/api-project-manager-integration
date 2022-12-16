@if ($posts)
    <div class="o-grid">
        @foreach ($posts as $key => $post)
            @php
                $post = \ProjectManagerIntegration\Helper\AddProjectData::addPostTags($post, $post->id);
            @endphp

            <div class="o-grid-3@md u-margin__bottom--8">
                @card([
                    'image' => $post->thumbnail['src'],
                    'link' => $post->permalink,
                    'heading' => $post->postTitle,
                    'content' => $post->taxonomies,
                    'meta' => $post->category,
                    'metaFirst' => true,
                    'context' => ['archive', 'archive.list', 'archive.list.card'],
                    'containerAware' => true,
                    'hasPlaceholder' => $anyPostHasImage && !isset($post->thumbnail),
                    'attributeList' => ['style' => 'z-index:' . (999-$key) . ';',],
                    'classList' => ['u-height--100'],

                ])
                  @slot('afterContent')
                    <div class="u-align-self--end u-width--100">
                        @tooltip([
                            'label' => $post->statusBar['label'],
                            'placement' => 'bottom',
                            'classList' => ['u-justify-content--end']
                        ])
                        {{$post->statusBar['explainer']}}
                        @endtooltip
                        @progressbar([
                            'value' => $post->statusBar['value'],
                            'isCancelled' => $post->statusBar['isCancelled'],
                        ])
                        @endprogressbar
                    </div>
                @endslot
                @endcard
            </div>
        @endforeach

@endif
