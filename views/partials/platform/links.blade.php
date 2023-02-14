<div class="o-grid-12">
    @card(['context' => ['platform.links']])
        <div class="c-card__body u-pt-1">
            @typography([
                'element' => 'h2',
                'variant' => 'h4'
            ])
                {{ $platform['labels']['links'] }}
            @endtypography
            <ul>
                @foreach ($platform['links'] as $link)
                    <li>
                        <a rel="noopener" target="_blank" href="{{ $link['url'] }}">{{ $link['title'] }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endcard
</div>
