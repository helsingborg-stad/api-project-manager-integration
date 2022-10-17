<?php global $post;

    // Category
    $category = !empty(get_the_terms($post->ID, 'challenge_category')) 
    ? get_the_terms($post->ID, 'challenge_category')[0]->name 
    : false; 
?>


<div class="grid">
    <div class="grid-xs-12">
        <div class="post post-single post post-{{get_post_type()}} u-mx-auto">
            <article id="article" class="article">
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
                        @if (!empty($preamble)) 
                            <p class="lead">{{$preamble}}</p>
                        @endif

                        {!! apply_filters('the_content', $post->post_content) !!}
                    @endif
                @endif

                @if (!empty($contacts) && !empty($contacts[0]['name']))                   
                        <p>Kontakt: 
                            @if(!empty($contacts[0]['email']))
                                <a href="mailto: {{$contacts[0]['email']}}">{{$contacts[0]['email']}}</a>
                            @else
                            {{$contacts[0]['name']}} 
                            @endif
                        </p>
                    @else
                @endif

                @if(!empty($focalPoints))
                    <h3 class="content-width">{{$focalPointTitle}}</h3>
                    @if (!empty($focalPointDescription))
                        {!!$focalPointDescription!!}
                    @endif
                    <ul>
                        @foreach($focalPoints as $item)
                        <li>
                            <a target="_blank" href="{{$item['url']}}">{{$item['name']}}</a>
                        </li>
                        @endforeach
                    </ul>

                @endif

                @if(!empty($globalGoals))
                    <h3 class="content-width">{{$globalGoalsTitle}}</h3>
                    @if (!empty($globalGoalsDescription))
                        {!!$globalGoalsDescription!!}
                    @endif
                    <div class="global-goals">
                    <div class="global-goals__container">
                        @foreach($globalGoals as $item)
                            <img class="global-goals__logo" src="{{$item['featured_image']}}">
                        @endforeach
                        </div>
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
