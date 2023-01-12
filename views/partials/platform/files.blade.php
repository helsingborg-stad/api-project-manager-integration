@if (!empty($files))
    <div class="grid-xs-12 grid-md-auto">
        <div class="box box--outline box-filled">
            <div class="box-content">
                <h3>{{ $labels['documents'] }}</h3>
                <ul class="pricon-list">
                    @foreach ($files as $file)
                        <li class="pricon-list__item {{ implode(' ', $file['classNames']) }}">
                            <a target="_blank" href="{{ $file['attachment'] }}">{{ $file['title'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
