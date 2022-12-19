@foreach ($project['residentInvolvement'] as $residentInvolement)
    <div class="o-grid-12">
        @card
            <div class="c-card__body">
                <h4>
                    <small
                        class="secondary-color tiny">{{ __('Resident involvement', 'project-manager-integration') }}</small>
                </h4>
                <p class="u-p-0">
                    <b>
                        {{ $residentInvolement['description'] }}
                    </b>
                </p>
            </div>
        @endcard
    </div>
@endforeach