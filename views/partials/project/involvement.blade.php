<div id="residentInvolvement">
    @foreach ($project['residentInvolvement'] as $residentInvolement)
        <div class="o-grid-12">
            @card(['classList' => ['u-color__bg--lighter']])
                <div class="c-card__body">
                    @typography([
                        'element' => 'h2',
                        'variant' => 'h4'
                    ])
                        {{ __('Resident involvement', 'project-manager-integration') }}
                    @endtypography
                    @typography([
                        'element' => 'p'
                    ])
                        {{ $residentInvolement['description'] }}
                    @endtypography
                </div>
            @endcard
        </div>
    @endforeach
</div>
