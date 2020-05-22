@if (!empty($enabledHeaderFilters) || !empty($enabledTaxonomyFilters))

    @if (get_field('archive_' . sanitize_title($postType) . '_filter_position', 'option') == 'content')
        <section class="u-mb-4 sidebar-content-area archive-filters grid-xs-12">
    @else
        <section class="creamy creamy-border-bottom gutter-lg gutter-vertical sidebar-content-area archive-filters u-py-0@xs u-py-0@sm">
    @endif
   
    <div class="filterbar hidden-md hidden-lg creamy u-py-1">
        <div class="container">
            <div class="grid">
                <div class="grid-xs-auto u-flex u-align-items-center"><span class="h4">Filtrera</span></div>
                <div class="grid-xs-auto u-text-right">
                    <button class="filterbar__toggle js-collapsible-toggle" data-collapsible-target=".js-collapse-filter">
                        <i class="pricon pricon-angle-down"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    

    <form method="get" action="{{ $archiveUrl }}" data-collapsed="true" class="container js-submit collapse-filter js-collapse-filter" id="archive-filter">

        @if (isset($enabledTaxonomyFilters->highlighted) && !empty($enabledTaxonomyFilters->highlighted))
        @foreach ($enabledTaxonomyFilters->highlighted as $taxKey => $taxonomy)
        @if(count($taxonomy->values) > 1)
        <div class="gutter gutter-top">
        <div class="grid">
            <div class="grid-xs-12">
                <ul>
                    <li class="highlighted-title"><h3>{{ $taxonomy->label }}</h3></li>
                    <ul class="nav nav-pills nav-horizontal nav-pills--badge">
                    @foreach ($taxonomy->values as $term)
                        <li>
                            <input id="segment-id-{{ $taxKey }}-{{ $term->slug }}" type="checkbox" name="filter[{{ $taxKey }}][]" value="{{ $term->slug }}" {{ checked(true, isset($_GET['filter'][$taxKey]) && is_array($_GET['filter'][$taxKey]) && in_array($term->slug, $_GET['filter'][$taxKey])) }}>
                            <a>
                                <label for="segment-id-{{ $taxKey }}-{{ $term->slug }}" class="checkbox inline-block">{{ $term->name }}</label>
                            </a>
                        </li>
                    @endforeach
                    </ul>
                </ul>
            </div>
        </div>
        </div>
        @endif
        @endforeach
        @endif

        <div class="grid">
            @if (in_array('text_search', $enabledHeaderFilters))
            <div class="grid-sm-12 grid-md-12 u-mb-0@xs u-mb-0@sm u-mb-2">
                <label for="filter-keyword" class="text-sm sr-only"><strong><?php _e('Search', 'municipio'); ?>:</strong></label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                    <input type="text" name="s" id="filter-keyword" class="form-control" value="{{ $searchQuery }}" placeholder="<?php _e('Search', 'municipio'); ?>">
                </div>
            </div>
            @endif

            @if (in_array('date_range', $enabledHeaderFilters))
            <div class="grid-sm-12 grid-md-auto">
                <label for="filter-date-from" class="text-sm sr-only"><strong><?php _e('Date published', 'municipio'); ?>:</strong></label>
                <div class="input-group">
                    <span class="input-group-addon"><?php _e('From', 'municipio'); ?>:</span>
                    <input type="text" name="from" placeholder="<?php _e('From date', 'municipio'); ?>…" id="filter-date-from" class="form-control datepicker-range datepicker-range-from" value="{{ isset($_GET['from']) && !empty($_GET['from']) ? sanitize_text_field($_GET['from']) : '' }}" readonly>
                    <span class="input-group-addon"><?php _e('To', 'municipio'); ?>:</span>
                    <input type="text" name="to" placeholder="<?php _e('To date', 'municipio'); ?>" class="form-control datepicker-range datepicker-range-to" value="{{ isset($_GET['to']) && !empty($_GET['to']) ? sanitize_text_field($_GET['to']) : '' }}" readonly>
                </div>
            </div>
            @endif

            @if (isset($enabledTaxonomyFilters->primary) && !empty($enabledTaxonomyFilters->primary))
                @foreach ($enabledTaxonomyFilters->primary as $taxKey => $tax)
                <div class="grid-sm-12 {{ $tax->type == 'multi' ? 'grid-md-fit-content' : 'grid-md-auto' }}">
                    <label for="filter-{{ $taxKey }}" class="text-sm sr-only">{{ $tax->label }}</label>
                    @if ($tax->type === 'single')
                        @include('partials.archive-filters.select')
                    @else
                        @include('partials.archive-filters.button-dropdown')
                    @endif
                </div>
                @endforeach
            @endif

            @if($queryString)
                <div class="grid-sm-12 grid-md-fit-content">
                    <a class="btn btn-block btn-contrasted pricon pricon-close pricon-space-right" href="{{ $archiveUrl }}"><?php _e('Clear filters', 'municipio'); ?></a>
                </div>
            @else
                <div class="grid-sm-12 grid-md-fit-content">
                    <a class="btn btn-block btn-contrasted disabled pricon pricon-close pricon-space-right" href="{{ $archiveUrl }}"><?php _e('Clear filters', 'municipio'); ?></a>
                </div>
            @endif
        </div>

        @if (isset($enabledTaxonomyFilters->row) && !empty($enabledTaxonomyFilters->row))
        @foreach ($enabledTaxonomyFilters->row as $taxKey => $taxonomy)
        @if(count($taxonomy->values) > 1)
        <div class="gutter gutter-top">
        <div class="grid">
            <div class="grid-xs-12">
                <ul class="segmented-control">
                    <li class="title">{{ $taxonomy->label }}:</li>
                    @foreach ($taxonomy->values as $term)
                        <li>
                            <input id="segment-id-{{ $taxKey }}-{{ $term->slug }}" type="{{ $taxonomy->type === 'single' ? 'radio' : 'checkbox' }}" name="filter[{{ $taxKey }}][]" value="{{ $term->slug }}" {{ checked(true, isset($_GET['filter'][$taxKey]) && is_array($_GET['filter'][$taxKey]) && in_array($term->slug, $_GET['filter'][$taxKey])) }}>
                            <label for="segment-id-{{ $taxKey }}-{{ $term->slug }}" class="checkbox inline-block">{{ $term->name }}</label>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        </div>
        @endif
        @endforeach
        @endif

        @if (isset($enabledTaxonomyFilters->folded) && !empty($enabledTaxonomyFilters->folded))
        <div class="gutter gutter-top" id="options" style="display: none;">
            <div class="grid" data-equal-container>
            @foreach ($enabledTaxonomyFilters->folded as $taxKey => $taxonomy)
                <div class="grid-md-4">
                    <div class="box box-panel box-panel-secondary" data-equal-item>
                        <h4 class="box-title">{{ $taxonomy->label }}</h4>
                        <div class="box-content">
                            <?php $taxonomy->slug = $taxKey; $dropdown = \Municipio\Content\PostFilters::getMultiTaxDropdown($taxonomy, 0, 'list-hierarchical'); ?>
                            {!! $dropdown !!}
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
        </div>
        @endif

        @if (isset($enabledTaxonomyFilters->folded) && !empty($enabledTaxonomyFilters->folded))
        <div class="grid no-margin gutter gutter-top gutter-sm">
            <div class="grid-xs-12">
                <button type="button" data-toggle="#options" class="btn btn-plain pricon pricon-plus-o pricon-space-right" data-toggle-text="Visa färre sökalternativ…" data-toggle-class="btn btn-plain pricon pricon-minus-o pricon-space-right">Visa fler sökalternativ…</a>
            </div>
        </div>
        @endif

    </form>
</section>
@endif
