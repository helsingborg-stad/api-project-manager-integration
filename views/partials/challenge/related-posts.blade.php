@segment([
    'stretch' => true,
    'paddingTop' => false,
    'paddingBottom' => false,
    'background' => 'lighter'
])
    <div class="u-padding__bottom--8">
        @group([
            'justifyContent' => 'space-between',
            'classList' => ['challenge__related', 'u-margin__bottom--3']
        ])
            @typography([
                'variant' => 'h2',
                'element' => 'h3',
                'classList' => ['challenge__related-heading']
            ])
                {{ $challenge['relatedPosts']['title'] }}
            @endtypography
            @link([
                'href' => $challenge['archive'],
                'classList' => ['challenge__related-link']
            ])
                @group([
                    'alignItems' => 'center'
                ])
                    {{ $challenge['labels']['showAll'] }}
                    @icon([
                        'icon' => 'arrow_forward',
                        'classList' => ['challenge__related-icon'],
                        'size' => 'lg'
                    ])
                    @endicon
                @endgroup
            @endlink
        @endgroup

        <div class="o-grid">
            @foreach ($challenge['relatedPosts']['posts'] as $post)
                <div class="o-grid-3@md">
                    @block([
                        'heading' => $post->postTitle,
                        'meta' => $post->challenge->category,
                        'ratio' => '12:16',
                        'image' => ['src' => $post->thumbnail['src'], 'alt' => $post->postTitle],
                        'link' => $post->permalink
                    ])
                    @endblock
                </div>
            @endforeach
        </div>
    </div>
@endsegment
