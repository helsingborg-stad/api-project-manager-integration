<div id="get-started" class="section u-py-5 js-scroll-spy-section">
    <div class="o-container">
        <div class="o-grid">
            <div class="o-grid-12">
                <article id="article" class="c-article clearfix full u-mb-4">
                    @typography([
                        'element' => 'h2',
                        'classList' => ['content-width']
                    ])
                        {{ $platform['getStartedHeading'] }}
                    @endtypography
                    {!! $platform['getStartedContent'] !!}
                </article>
            </div>
        </div>
    </div>
</div>
