<div class="o-grid">
    @foreach($posts as $post)
    @php 
        $post = \ProjectManagerIntegration\Helper\AddProjectData::addPostData($post, $post->id);
    @endphp
    <div class="o-grid-3@md">
        @block([
            'heading' => $post->postTitle,
            'meta' => $post->category,
            'ratio' => '12:16',
            'image' => ['src' => $post->thumbnail['src'], 'alt' => $post->postTitle],
            'link' => $post->permalink
                ])
        @endblock
    </div>
    @endforeach
</div> 