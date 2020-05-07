<?php global $post; ?>
<div class="grid">
    <div class="grid-xs-12">
        <div class="post post-single">
            <article class="u-mb-5" id="article">
                @if (post_password_required($post))
                    {!! get_the_password_form() !!}
                @else
                    @if (isset(get_extended($post->post_content)['main']) && !empty(get_extended($post->post_content)['main']) && isset(get_extended($post->post_content)['extended']) && !empty(get_extended($post->post_content)['extended']))

                        {!! apply_filters('the_lead', get_extended($post->post_content)['main']) !!}
                        {!! apply_filters('the_content', get_extended($post->post_content)['extended']) !!}

                    @else
                        {!! apply_filters('the_content', $post->post_content) !!}
                    @endif
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
