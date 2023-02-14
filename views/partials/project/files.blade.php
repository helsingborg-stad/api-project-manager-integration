<div class="o-grid-12">
    @card(['context' => ['project.files']])
        <div class="c-card__body u-pt-1">
            @typography([
                'element' => 'h2',
                'variant' => 'h4'
            ])
                {{ $project['labels']['files'] }}
            @endtypography
            <ul class="u-unlist u-padding__left--0">
                @foreach ($project['files'] as $file)
                    <li class="u-padding__left--0">
                        <a href="{{ $file['file']['url'] }}">{{ $file['title'] }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endcard
</div>
