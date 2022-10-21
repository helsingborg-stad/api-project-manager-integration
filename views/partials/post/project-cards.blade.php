@if ($posts)
    <div class="o-grid">
        @foreach ($posts as $post)
            @php
                // Category
                $category = !empty(get_the_terms($post->id, 'challenge_category')) ? get_the_terms($post->id, 'challenge_category')[0]->name : false;
                
                $postTags = [];
                
                if (!empty(get_the_terms($post->id, 'project_sector'))) {
                    $postTags = array_merge($postTags, get_the_terms($post->id, 'project_sector'));
                }
                
                if (!empty(get_the_terms($post->id, 'project_technology'))) {
                    $postTags = array_merge($postTags, get_the_terms($post->id, 'project_technology'));
                }
                
                if (empty(!$postTags)) {
                    $postTags = array_reduce(
                        $postTags,
                        function ($accumilator, $term) {
                            if (empty($accumilator)) {
                                $accumilator = '<span>' . $term->name . '</span>';
                            } else {
                                $accumilator .= ' / ' . '<span>' . $term->name . '</span>';
                            }
                
                            return $accumilator;
                        },
                        '',
                    );
                }
                
                $statusBar = \ProjectManagerIntegration\UI\ProjectStatus::create($post->id);
            @endphp

            <div class="{{ $gridColumnClass }}">
                @card([
                    'link' => $post->permalink,
                    'imageFirst' => true,
                    'image' => $post->thumbnail,
                    'heading' => $post->postTitle,
                    'classList' => ['t-archive-card', 'u-height--100', 'u-display--flex'],
                    'content' => $post->excerptShort,
                    'tags' => $post->termsunlinked,
                    'date' => $post->archiveDate,
                    'dateBadge' => $post->archiveDateFormat == 'date-badge',
                    'context' => ['archive', 'archive.list', 'archive.list.card'],
                    'containerAware' => true,
                    'hasPlaceholder' => $anyPostHasImage && !isset($post->thumbnail['src'])
                ])
                    <div class="c-card__image">
                        <div class="c-card__image-background"
                            style="background-image:url('{{ $post->thumbnail['src'] ?? '' }}');">
                        </div>
                    </div>

                    <div class="c-card__body">
                        <div
                            class="u-display--flex  u-flex-direction--column u-justify-content--space-between u-height--100">
                            <div>
                                @typography([
                                    'element' => 'span',
                                    'variant' => 'meta'
                                ])
                                    {!! $category !!}
                                @endtypography
                                @typography([
                                    'element' => 'h3',
                                    'variant' => 'h3',
                                    'classList' => ['c-card__heading', 'u-margin__top--05', 'u-margin__bottom--2']
                                ])
                                    {!! $post->postTitle !!}
                                @endtypography

                                @if (!empty($postTags))
                                    <div>
                                        {!! $postTags !!}
                                    </div>
                                @endif
                            </div>
                            <div>
                                @if (!empty($statusBar) && $statusBar['value'] > -1 && $statusBar['label'])
                                    <div class="statusbar u-margin__top--2">
                                        <div class="statusbar__header u-mb-1 explain">
                                            <b class="statusbar__title">{{ $statusBar['label'] }}</b>

                                            @if (!empty($statusBar['explainer']))
                                                <span class="statusbar__explainer">
                                                    <span data-tooltip="{{ $statusBar['explainer'] }}" data-tooltip-bottom>
                                                        <i class="pricon pricon-info-o"></i>
                                                    </span>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="statusbar__content">
                                            <div class="c-progressbar">
                                                <div class="c-progressbar__value {{ $statusBar['isCancelled'] ? 'is-disabled' : '' }}"
                                                    style="width: {{ $statusBar['value'] }}%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endcard
            </div>
        @endforeach
    </div>
@endif
