<?php global $post; ?>
<div class="grid">
    <div class="grid-xs-12">
        <div class="post post-single">

            <article class="u-mb-5 js-scroll-spy-section" id="article">
                @if (post_password_required($post))
                    {!! get_the_password_form() !!}
                @else
                    @if ($projectWhy['content'])
                        <h2>{{ $projectWhy['header'] }}</h2>
                        {!! $projectWhy['content'] !!}
                    @endif

                    @if ($projectWhat['content'])
                        <h2>{{ $projectWhat['header'] }}</h2>
                        {!! $projectWhat['content'] !!}
                    @endif

                    @if ($projectHow['content'])
                        <h2>{{ $projectHow['header'] }}</h2>
                        {!! $projectHow['content'] !!}
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
