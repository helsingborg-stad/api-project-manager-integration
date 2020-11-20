<?php global $post;

    // Category
    $category = !empty(get_the_terms($post->ID, 'challenge_category')) 
    ? get_the_terms($post->ID, 'challenge_category')[0]->name 
    : false; 
?>

<div class="grid">
    <div class="grid-xs-12">
        <div class="post post-single">
            <article class="u-mb-5" id="article">

                <header class="post-header">
                    @if ($category)
                        <small>{{$category}}</small>
                    @endif

                    <h1>{{ the_title() }}</h1>   
                </header>

                @if (get_field('post_single_show_featured_image') === true)
                    <img src="{{ municipio_get_thumbnail_source(null, array(700,700)) }}" alt="{{ the_title() }}">
                @endif

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
