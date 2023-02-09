@group([
    'direction' => 'vertical',
])
    <div><strong>{{$project['labels']['published']}}:</strong> {{get_the_date()}}</div>
    <div><strong>{{$project['labels']['updated']}}:</strong> {{get_the_modified_date()}}</div>
@endgroup