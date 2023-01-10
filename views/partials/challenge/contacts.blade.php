@foreach ($challenge['contacts'] as $contact)
    @typography([])
        {{ $challenge['labels']['contact'] }}:
        @link([
            'href' => 'mailto:' . $contact['email']
        ])
            {{ $contact['email'] }}
        @endlink
    @endtypography
@endforeach
