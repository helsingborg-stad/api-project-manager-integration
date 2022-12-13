@extends('templates.single')

@section('article.content.before') 
    @if (!empty($preamble)) 
        <p class="lead">{{$preamble}}</p>
    @endif
@stop

@section('article.content.after')
  @if(!empty($globalGoals))
    <h3 class="content-width">{{$globalGoalsTitle}}</h3>
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
    @endif
@stop