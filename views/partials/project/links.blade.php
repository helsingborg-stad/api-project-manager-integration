<div class="o-grid-12">
    @card
        <div class="c-card__body u-pt-1">
            <h4>{{$lang['linksLabel']}}</h4>
            <ul>
                @foreach ($project['links'] as $link)
                    <li>
                        <a target="_blank" href="{{ $link['url'] }}">{{ $link['title'] }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endcard
</div>