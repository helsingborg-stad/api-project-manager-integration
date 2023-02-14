<div class="o-grid-12">
    @card(['context' => ['project.links']])
        <div class="c-card__body u-pt-1">
            @typography([
                'element' => 'h2',
                'variant' => 'h4'
            ])
                {{ $project['labels']['links'] }}
            @endtypography
            <ul class="u-unlist u-padding__left--0">
                @foreach ($project['links'] as $link)
                    <li class="u-padding__left--0">
                        <a rel="noopener" target="_blank" href="{{ $link['url'] }}">{{ $link['title'] }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endcard
</div>
