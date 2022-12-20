@if (!empty($scrollSpyMenuItems) && count($scrollSpyMenuItems) > 1)
    <div class="u-position--sticky u-level-9 u-margin__bottom--2 u-margin__top--3 u-display--none@lg u-display--none@xl" id="scroll-spy">
        @group([
            'wrap' => 'wrap',
            'classList' => ['scroll-spy__container']
        ])
        @foreach($scrollSpyMenuItems as $item)
            @link([
                'href' => $item['anchor'],
                'classList' => ['scroll-spy__item', 'u-color__text--dark']
            ])
                {{$item['label']}}
            @endlink
        @endforeach
        @endgroup
    </div>
@endif