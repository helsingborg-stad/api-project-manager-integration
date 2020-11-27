<?php do_action('municipio/view/before_hero'); ?>

@if (!is_singular('project') && is_active_sidebar('slider-area') === true )
    <div class="hero has-stripe sidebar-slider-area">
        <div class="grid">
            <?php dynamic_sidebar('slider-area'); ?>
        </div>
        
        @include('partials.stripe')
        
        @if (rtrim($_SERVER['REQUEST_URI'], "/") == "" && is_array(get_field('search_display', 'option')) && in_array('hero', get_field('search_display', 'option')))
            {{ get_search_form() }}
        @endif
    </div>
@endif

@if (is_singular('project') && get_the_post_thumbnail_url())
    <div class="hero hero--project">
        <div class="hero__background-wrapper">
            <div class="hero__background" style="background-image:url('{{ municipio_get_thumbnail_source(null, array(1920,1080)) }}');"></div>
        </div>
        {{-- <img class="hero__image" src="{{ municipio_get_thumbnail_source(null, array(1920,1080)) }}" alt="{{ the_title() }}"> --}}
        <div class="project-header">
            <div class="container">
                <div class="grid">
                    <div class="grid-md-6 project-header__column project-header__column--image">
                        <img class="project-header__image" src="{{ municipio_get_thumbnail_source(null, array(500,500), '1:1') }}" alt="{{ the_title() }}">
                    </div>
                    <div class="grid-md-6 project-header__column project-header__column--content">
                        <div class="project-header__body">
                            <span class="project-header__meta">{{get_the_terms(get_queried_object_id(), 'project_organisation')[0]->name}}</span>
                            <h1 class="project-header__title">{{ the_title() }}</h1>

                            @if (!empty($statusBar) && $statusBar['value'] && $statusBar['label'])
                            <div class="statusbar u-mt-3">
                                <div class="statusbar__header u-mb-1 explain">
                                    <b class="statusbar__title">{{$statusBar['label']}}</b>

                                    @if (!empty($statusBar['explainer'])) 
                                        <span class="statusbar__explainer">
                                            <span data-tooltip="{{$statusBar['explainer']}}" data-tooltip-bottom>
                                                <i class="pricon pricon-info-o"></i>
                                            </span>
                                        </span>
                                    @endif
                                </div>
                                <div class="statusbar__content">
                                    <div class="c-progressbar">
                                        <div class="c-progressbar__value" style="width: {{$statusBar['value']}}%;"></div>                
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<?php do_action('municipio/view/after_hero'); ?>