@foreach($contacts as $contact)
@typography([
])
    {{$lang['contact']}}: 
    @link([
        'href' => 'mailto:' . $contact['email'],
    ])
    {{$contact['email']}}
    @endlink
@endtypography
@endforeach