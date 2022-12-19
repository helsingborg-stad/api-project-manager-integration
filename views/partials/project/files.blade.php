<div class="o-grid-12">
    @card
        <div class="c-card__body u-pt-1">
            <h4>{{$lang['filesLabel']}}</h4>
            <ul>
                @foreach ($project['files'] as $file)
                    <li>
                        <a href="{{ $file['file']['url'] }}">{{ $file['title'] }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endcard
</div>