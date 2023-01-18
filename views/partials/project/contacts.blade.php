<div class="o-grid-12">
    @card(['classList' => ['u-color__bg--lighter']])
        <div class="c-card__body">
            @typography([
                'element' => 'h2',
                'variant' => 'h3'
            ])
                {{ $project['labels']['contact'] }}
            @endtypography
            @foreach ($project['contacts'] as $contact)
                @typography([
                    'element' => 'p'
                ])
                    <strong>{{ $project['labels']['name'] }}:</strong> {{ $contact['name'] }}
                    @if ($contact['email'])
                        <br> <strong>{{ $project['labels']['email'] }}: </strong><a
                            href="mailto:{{ $contact['email'] }}">{{ $contact['email'] }}</a>
                    @endif
                @endtypography
            @endforeach
        </div>
    @endcard
</div>
