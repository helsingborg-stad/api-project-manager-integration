    <div class="grid-xs-12 grid-md-auto">
        <div class="box box--outline box-filled">
            <div class="box-content">
                @typography([
                    'element' => 'h3'
                ])
                    {{ $platform['labels']['contacts'] }}
                @endtypography
                <h3>{{ $labels['documents'] }}</h3>
                <ul class="pricon-list">
                    @foreach ($platform['files'] as $file)
                        <li class="pricon-list__item {{ implode(' ', $file['classNames']) }}">
                            <a target="_blank" rel="noopener" href="{{ $file['attachment'] }}">{{ $file['title'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
