@foreach($contacts as $contact)
@typography([
])
    {{$lang['contactsLabel']}}: 
    @link([
        'href' => 'mailto:' . $contact['email'],
    ])
    {{$contact['email']}}
    @endlink
@endtypography
@endforeach