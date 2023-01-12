@if (!empty($links))
    <div class="grid-xs-12 grid-md-auto u-mb-4">
        <div class="box box--outline box-filled">
            <div class="box-content">
                <h3>{{ $labels['links'] }}</h3>
                <ul class="pricon-list">
                    @foreach ($links as $link)
                        <li class="pricon-list__item pricon pricon-external-link">
                            <a target="_blank" href="{{ $link['url'] }}">{{ $link['title'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
