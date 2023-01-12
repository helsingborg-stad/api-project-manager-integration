<style>
    .c-status-dot {
        border-radius: 100%;
        display: block;
        height: 16px;
        width: 16px;
    }
</style>

<div id="roadmap" class="section u-py-7 js-scroll-spy-section">
    <div class="o-container">
        <div class="o-grid">
            <div class="o-grid-12 u-mb-4">
                @typography([
                    'element' => 'h2',
                    'classList' => ['content-width']
                ])
                    {{ $platform['labels']['roadmap'] }}
                @endtypography
            </div>
            {{-- Roadmap --}}
            <div class="o-grid-12 u-mb-2">
                @if (!empty($platform['roadmap']['items']))
                    <div class="o-grid">
                        @foreach ($platform['roadmap']['items'] as $item)
                            <div class="o-grid-4">
                                @if (!empty($item['category']))
                                    @typography([
                                        'element' => 'meta',
                                        'classList' => ['content-width']
                                    ])
                                        {{ $item['category'] }}
                                    @endtypography
                                @endif

                                @typography([
                                    'element' => 'h3',
                                    'classList' => ['content-width']
                                ])
                                    {{ $item['title'] }}
                                @endtypography


                                @typography([
                                    'element' => 'p',
                                    'classList' => ['content-width']
                                ])
                                    {{ $item['content'] }}
                                @endtypography

                                <div class="o-grid u-mt-3 u-align-items-center">
                                    @if (!empty($item['status']) &&
                                        !empty($platform['roadmap']['statusColors']) &&
                                        isset($platform['roadmap']['statusColors'][$item['status']]))
                                        <div class="o-grid-1">
                                            <span class="c-status-dot u-display-inline-block"
                                                style="background-color:{{ $platform['roadmap']['statusColors'][$item['status']] }};"></span>
                                        </div>
                                    @endif
                                    <div class="o-grid-auto">
                                        @typography([
                                            'element' => 'p',
                                            'classList' => ['content-width']
                                        ])
                                            {{ $item['date'] }}
                                        @endtypography
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                @endif
            </div>
        </div>
    </div>
</div>
