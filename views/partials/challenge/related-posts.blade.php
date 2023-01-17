@segment([
    'stretch' => true,
    'paddingTop' => false,
    'paddingBottom' => false,
    'background' => 'lightest',
    'layout' => 'full-width'
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

        @includeWhen($challenge['relatedPosts']['posts'], 'partials.post.challenge-cards', [
            'posts' => $challenge['relatedPosts']['posts'],
        ])
    </div>
@endsegment
