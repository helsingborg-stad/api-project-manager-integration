<div class="o-grid">
    @foreach ($posts as $post)
        <div class="o-grid-3@md">
            @block([
                'heading' => $post->postTitle,
                'ratio' => '12:16',
                'image' => ['src' => $post->thumbnail['src'], 'alt' => $post->postTitle],
                'link' => $post->permalink
            ])
            @endblock
        </div>
    @endforeach
</div>
