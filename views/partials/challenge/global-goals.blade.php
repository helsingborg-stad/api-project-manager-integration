@typography([
    'element' => 'h2',
    'variant' => 'h3',
    'classList' => ['content-width']
])
{{$globalGoalsTitle}}
@endtypography
    @if (!empty($globalGoalsDescription))
        {!!$globalGoalsDescription!!}
    @endif
<div class="global-goals">
    <div class="global-goals__container">
    @foreach($globalGoals as $item)
        <img class="global-goals__logo" src="{{$item['featured_image']}}">
    @endforeach
    </div>
</div>
