@extends('templates.master')

@section('content')

@if (get_field('archive_' . sanitize_title($postType) . '_filter_position', 'option') == 'top')
    @include('partials.archive-filters-project')
@endif

<div class="container main-container">
    <!-- @include('partials.breadcrumbs') -->

    <div class="grid">
        @if (get_field('archive_' . sanitize_title($postType) . '_show_sidebar_navigation', 'option'))
            @include('partials.sidebar-left')
        @endif

        <?php
            $cols = 'grid-md-12';
            if (is_active_sidebar('right-sidebar') && get_field('archive_' . sanitize_title($postType) . '_show_sidebar_navigation', 'option')) {
                $cols = 'grid-md-8 grid-lg-6';
            } elseif (is_active_sidebar('right-sidebar') || get_field('archive_' . sanitize_title($postType) . '_show_sidebar_navigation', 'option')) {
                $cols = 'grid-md-8 grid-lg-9';
            }
        ?>

        <div class="{{ $cols }}">

            @if (get_field('archive_' . sanitize_title($postType) . '_title', 'option') || is_category() || is_date())
            <div class="grid">
                <div class="grid-xs-12">
                    @if (get_field('archive_' . sanitize_title($postType) . '_title', 'option'))
                        @if (is_category())
                            <h1>{{ get_field('archive_' . sanitize_title($postType) . '_title', 'option') }}: {{ single_cat_title() }}</h1>
                        {!! category_description() !!}
                        @elseif (is_date())
                            <h1>{{ get_field('archive_' . sanitize_title($postType) . '_title', 'option') }}: {{ the_archive_title() }}</h1>
                        @else
                            <h1>{{ get_field('archive_' . sanitize_title($postType) . '_title', 'option') }}</h1>
                        @endif
                    @else
                        @if (is_category())
                            <h1>{{ single_cat_title() }}</h1>
                        {!! category_description() !!}
                        @elseif (is_date())
                            <h1>{{ the_archive_title() }}</h1>
                        @endif
                    @endif

                    @if (!empty(apply_filters('accessibility_items', array())))
                        <div class="u-mb-3">
                            @include('partials.accessibility-menu')
                        </div>
                    @endif
                </div>
            </div>
            @elseif (!empty(apply_filters('accessibility_items', array())))
                <div class="grid">
                    <div class="grid-xs-12 u-mb-3">
                        @include('partials.accessibility-menu')
                    </div>
                </div>
            @endif

            @if (is_active_sidebar('content-area-top'))
                <div class="grid grid--columns sidebar-content-area sidebar-content-area-top">
                    <?php dynamic_sidebar('content-area-top'); ?>
                </div>
            @endif

            @if (in_array($template, array('list')))
                @include('partials.blog.type.post-' . $template)
            @else
            @if (get_field('archive_' . sanitize_title($postType) . '_filter_position', 'option') == 'content')
                <div class="grid filter-content">
                    @include('partials.archive-filters-project')
                </div>
            @endif
                <div class="grid grid--columns" @if (in_array($template, array('cards'))) data-equal-container @endif>
                    @if (have_posts())
                        <?php $postNum = 0; ?>
                        @while(have_posts())
                            {!! the_post() !!}

                            @include('partials.blog.type.post-card-challenge', array('post' => get_post()))

                            <?php $postNum++; ?>
                        @endwhile
                    @else
                        <div class="grid-xs-12">
                            <div class="u-text-center u-py-6">
                                <h1> {{$noResultLabels[0]}} 😭</br>{{$noResultLabels[1]}}</h2>
                            </div>
                        </div>
                    @endif
                </div>

            @endif

            @if (is_active_sidebar('content-area'))
                <div class="grid grid--columns sidebar-content-area sidebar-content-area-bottom">
                    <?php dynamic_sidebar('content-area'); ?>
                </div>
            @endif

            <div class="grid">
                <div class="grid-sm-12 text-center u-mb-7">
                    {!!
                        paginate_links(array(
                            'type' => 'list'
                        ))
                    !!}
                </div>
            </div>
        </div>

        @include('partials.sidebar-right')
    </div>
</div>

@stop
