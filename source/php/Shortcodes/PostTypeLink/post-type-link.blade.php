@if (!empty($title) && !empty($url) && !empty($buttonText))
    <section class="featured-post">
        @if (!empty($imageUrl))
            <div class="featured-post__image">
                <img src="{{ $imageUrl }}">
            </div>
        @endif
        <div class="featured-post__content">
            @if (!empty($meta))
                <span class="featured-post__meta">
                    {{ $meta }}
                </span>
            @endif
            <h4 class="featured-post__title h3">
                {{ $title }}
            </h4>
        </div>
        <div class="featured-post__action">
            <a class="btn btn-light" href="{{ $url }}"
                @if (!empty($blank)) target="_blank" @endif>{{ $buttonText }}</a>
        </div>
    </section>
@endif
