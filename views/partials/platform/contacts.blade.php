<div class="o-grid-12">
    @card(['context' => ['platform.contacts']])
        <div class="c-card__body">
            @typography([
                'element' => 'h2',
                'variant' => 'h3'
            ])
                {{ $platform['labels']['contacts'] }}
            @endtypography
            @foreach ($platform['contacts'] as $contact)
                @typography([
                    'element' => 'p'
                ])
                    <strong>{{ $platform['labels']['name'] }}:</strong> {{ $contact['name'] }}
                    @if ($contact['mail'])
                        <br> <strong>{{ $platform['labels']['mail'] }}: </strong><a
                            href="mailto:{{ $contact['mail'] }}">{{ $contact['mail'] }}</a>
                    @endif
                @endtypography
            @endforeach
        </div>
    @endcard
</div>
