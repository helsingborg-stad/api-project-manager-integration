@typography([
    'element' => 'h2',
    'variant' => 'h3',
    'classList' => ['content-width']
])
    {{ $challenge['globalGoalsTitle'] }}
@endtypography
@if (!empty($challenge['globalGoalsDescription']))
    {!! $challenge['globalGoalsDescription'] !!}
@endif
<div class="global-goals">
    <div class="global-goals__container">
        @foreach ($challenge['globalGoals'] as $termId)
            <img alt="{{ get_term($termId)->name }}" class="global-goals__logo"
                src="{{ get_term_meta($termId, 'featured_image', true) }}">
        @endforeach
    </div>
</div>
