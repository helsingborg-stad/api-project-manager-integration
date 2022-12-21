<div class="o-grid-12">
    @card
        <div class="c-card__body">
            @typography([
                'element' => 'h2',
                'variant' => 'h4'
            ])
            {{$lang['contactsLabel']}}
            @endtypography
            @foreach ($project['contacts'] as $contact)
                <p><strong>{{$lang['name']}}:</strong> {{ $contact['name'] }}
                    @if ($contact['email'])
                        <br> <strong>{{$lang['email']}}: </strong><a href="mailto:{{ $contact['email'] }}">{{ $contact['email'] }}</a>
                    @endif
                </p>
            @endforeach
        </div>
    @endcard
</div>