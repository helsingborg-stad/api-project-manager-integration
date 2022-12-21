@foreach ($project['residentInvolvement'] as $residentInvolement)
    <div class="o-grid-12">
        @card
            <div class="c-card__body">
                @typography([
                    'element' => 'h2',
                    'variant' => 'h4',
                ])
                    <small class="secondary-color tiny">{{ __('Resident involvement', 'project-manager-integration') }}</small>
                @endtypography
                <p class="u-padding__y--0 u-padding__x--0">
                    <b>
                        {{ $residentInvolement['description'] }}
                    </b>
                </p>
            </div>
        @endcard
    </div>
@endforeach