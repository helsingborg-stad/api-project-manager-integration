@segment([

])
<div class="u-padding__y--6 u-padding__y--8@lg u-padding__y--8@xl t-section-gray">
@group([
    'justifyContent' => 'space-between',
    'classList' => ['challenge__related', 'u-margin__bottom--3']
])
    @typography([
        'variant' => 'h2',
        'element' => 'h3',
        'classList' => ['challenge__related-heading'],
    ])
        {{$lang['moreChallenges']}} 
    @endtypography
    @link([
        'href' => $archive,
        'classList' => ['challenge__related-link'],
    ])
        @group([
            'alignItems' => 'center',
        ])
            {{$lang['showAll']}}
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
    @foreach($relatedPosts as $item)
    <div class="o-grid-3@md">
        @block([
            'heading' => $item->post_title,
            'meta' => $item->category,
            'ratio' => '12:16',
            'image' => ['src' => $item->thumbnail, 'alt' => $item->post_title],
            'link' => $item->url
                ])
        @endblock
    </div>
    @endforeach
</div> 
</div>
@endsegment