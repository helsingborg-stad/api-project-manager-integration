<div class="grid-xs-12 grid-md-auto">
    <div class="box box-filled box--lightblue">
        <div class="box-content">
            @typography([
                'element' => 'h3'
            ])
                {{ $platform['labels']['contacts'] }}
            @endtypography
            <ul class="unordered-list">
                @foreach ($platform['contacts'] as $contact)
                    <li>
                        @typography([
                            'element' => 'h4',
                            'classList' => ['content-width']
                        ])
                            {{ $contact['name'] }}
                            @if (!empty($contact['role']))
                                <br><small>{{ $contact['role'] }}</small>
                            @endif
                        @endtypography
                        <a href="mailto: {{ $contact['mail'] }}">{{ $contact['mail'] }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
