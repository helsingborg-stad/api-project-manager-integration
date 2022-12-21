<div class="o-grid-12">
    @card
        <div class="c-card__body u-pt-1">
            @typography([
                'element' => 'h2',
                'variant' => 'h4',
            ])
                {{$lang['linksLabel']}}
            @endtypography
            <ul>
                @foreach ($project['links'] as $link)
                    <li>
                        <a rel="noopener" target="_blank" href="{{ $link['url'] }}">{{ $link['title'] }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endcard
</div>