<?php global $post; ?>
<div class="grid">
    <div class="grid-xs-12">
        <div class="post post-single">
            <article class="u-mb-5 js-scroll-spy-section" id="article">
                @if (post_password_required($post))
                    {!! get_the_password_form() !!}
                @else
                    @if (!empty($project['contentPieces']))
                        @foreach ($project['contentPieces'] as $item)
                            <h2>{{ $item['title'] }}</h2>
                            {!! $item['content'] !!}
                        @endforeach
                    @endif
                @endif
                
                @if(
                    !empty($project['video']) 
                    && function_exists('build_youtube_url') 
                    && $youtubeUrl = build_youtube_url($project['video'][0]['url'])
                )
                    <div class="u-yt-wrapper">
                        <iframe 
                            frameborder="0" 
                            scrolling="no" 
                            marginheight="0" 
                            marginwidth="0" 

                            type="text/html"
                            src="{{$youtubeUrl}}">
                        </iframe>
                    </div>
                @endif
            </article>

            @if (is_single() && is_active_sidebar('content-area'))
                <div class="grid grid--columns sidebar-content-area sidebar-content-area-bottom">
                    <?php dynamic_sidebar('content-area'); ?>
                </div>
            @endif
        </div>
    </div>
</div>
