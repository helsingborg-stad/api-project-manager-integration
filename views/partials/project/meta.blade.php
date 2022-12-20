<div class="o-grid-12" id="about">
    @card
        <div class="c-card__body">
            <ul class="box-project-meta__list">
                @foreach ($project['meta'] as $meta)
                    <li>
                        <h4>{{ $meta['title'] }}</h4>
                        @if (!empty($meta['url']))
                            <a href="{{ $meta['url'] }}">
                                <p>{!! $meta['content'] !!}</p>
                        </a>
                        @else
                            <p>{!! $meta['content'] !!}</p>
                        @endif
                </li>
                @endforeach
            </ul>
        </div>
    @endcard
</div>