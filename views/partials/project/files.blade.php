<div class="o-grid-12">
    @card(['context' => ['project.files']])
        <div class="c-card__body u-pt-1">
            @typography([
                'element' => 'h2',
                'variant' => 'h4'
            ])
                {{ $project['labels']['files'] }}
            @endtypography
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
