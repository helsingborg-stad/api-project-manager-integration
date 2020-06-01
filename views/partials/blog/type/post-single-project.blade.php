<?php global $post; ?>
<div class="grid">
    <div class="grid-xs-12">
        <div class="post post-single">
            <article class="u-mb-5" id="article">
                @if (post_password_required($post))
                    {!! get_the_password_form() !!}
                @else
                    {!! apply_filters('the_content', $post->post_content) !!}
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
