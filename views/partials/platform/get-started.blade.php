@segment([
    'stretch' => true,
    'paddingTop' => false,
    'paddingBottom' => true,
    'layout' => 'full-width',
    'id' => 'get-started',
    'classList' => ['js-scroll-spy-section']
])


    <article class="c-article c-article--readable-width">
        @typography([
            'element' => 'h2',
            'classList' => ['content-width']
        ])
            {{ $platform['getStartedHeading'] }}
        @endtypography
        {!! $platform['getStartedContent'] !!}
    </article>



@endsegment
