@hero([
    'image' => !empty($challenge['hero']['image']['src']) ? $challenge['hero']['image']['src'] : null,
    'title' => $challenge['hero']['title'],
    'meta' => $challenge['hero']['meta']
])
@endhero
