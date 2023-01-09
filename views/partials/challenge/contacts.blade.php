@foreach ($contacts as $contact)
    @typography([])
        {{ $challenge['labels']['contactsLabel'] }}:
        @link([
            'href' => 'mailto:' . $contact['email']
        ])
            {{ $contact['email'] }}
        @endlink
    @endtypography
@endforeach
