<div class="o-grid-12" id="about">
    @card(['classList' => ['u-color__bg--lighter']])
        <div class="c-card__body">
            <ul class="unlist">
                @foreach ($project['meta'] as $meta)
                    <li @if (!$loop->first) class="u-margin__top--3" @endif>
                        @typography([
                            'element' => 'h2',
                            'variant' => 'h4'
                        ])
                            {{ $meta['title'] }}
                        @endtypography
                        @if (!empty($meta['url']))
                            <a href="{{ $meta['url'] }}">
                                @typography([
                                    'element' => 'p',
                                    'classList' => ['u-margin__top--0']
                                ])
                                    {!! $meta['content'] !!}
                                @endtypography

                            </a>
                        @else
                            @typography([
                                'element' => 'p',
                                'classList' => ['u-margin__top--0']
                            ])
                                {!! $meta['content'] !!}
                            @endtypography
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endcard
</div>
