@extends('layouts.frontend')

@section('title', $title)

@section('description', $description)

@section('keywords', $keywords)

@section('content')
    @if(isset($arr) && $arr)
        <section class="content-card catalog-section">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">{{ __('interface.frontend.catalog') }}</span>
                    <h1>{{ $title }}</h1>
                    <p>{{ __('interface.frontend.catalog_intro') }}</p>
                </div>
            </div>

            <div class="category-grid">
                @php
                    $catalogColumns = max(1, (int) \App\Helpers\SettingsHelpers::getSetting('COLUMNS_NUMBER'));
                    $categoryIcons = [
                        'test-business-and-finance.svg' => 'business',
                        'test-education.svg' => 'education',
                        'test-entertainment-and-arts.svg' => 'entertainment',
                        'test-food-and-dining.svg' => 'food',
                        'test-health-and-medicine.svg' => 'health',
                        'test-home-and-garden.svg' => 'home',
                        'test-news-and-media.svg' => 'news',
                        'test-professional-services.svg' => 'services',
                        'test-shopping.svg' => 'shopping',
                        'test-sports-and-fitness.svg' => 'sports',
                        'test-technology.svg' => 'technology',
                        'test-travel-and-transport.svg' => 'travel',
                    ];
                @endphp
                @for ($i = 0; $i < $number; $i++)
                    @for ($j = 0; $j < $catalogColumns; $j++)
                        @if(isset($arr[$i][$j][1], $arr[$i][$j][0], $arr[$i][$j][3]))
                            @php
                                $categoryImage = $arr[$i][$j][2] ?? null;
                                $categoryImageExists = $categoryImage && file_exists(public_path('uploads/catalog/' . $categoryImage));
                                $categoryIcon = $categoryIcons[$categoryImage ?? ''] ?? 'folder';
                                $customImage = $categoryImageExists && !isset($categoryIcons[$categoryImage]);
                                $childrenHtml = $arr[$i][$j][4] ?? '';
                                $hasChildren = $arr[$i][$j][1] > 0 && trim(strip_tags($childrenHtml)) !== '';
                            @endphp
                            <article class="category-card category-card--{{ $categoryIcon }} {{ $hasChildren ? 'category-card--with-children' : 'category-card--without-children' }}">
                                <a class="category-card__icon" aria-label="{{ $arr[$i][$j][0] }}" href="{{ URL::route('catalog', ['id' => $arr[$i][$j][1] > 0 ? $arr[$i][$j][1] : '']) }}">
                                    @if($customImage)
                                        <img src="{{ asset('uploads/catalog/' . $categoryImage) }}" alt="" loading="lazy" decoding="async">
                                    @else
                                        <x-directory-icon :name="$categoryIcon" />
                                    @endif
                                </a>

                                <div class="category-card__body">
                                    <a class="category-card__title" href="{{ URL::route('catalog', ['id' => $arr[$i][$j][1] > 0 ? $arr[$i][$j][1] : '']) }}">
                                        {{ $arr[$i][$j][0] }}
                                    </a>

                                    @if($hasChildren)
                                        <div class="category-card__children">
                                            {!! $childrenHtml !!}
                                        </div>
                                    @endif
                                </div>
                            </article>
                        @endif
                    @endfor
                @endfor
            </div>
        </section>
    @endif

    @if(isset($pathway) && $pathway)
        <div class="breadcrumb-panel">{!! $pathway !!}</div>
    @endif

    <div class="content-layout">
        <div class="content-main">
            @if(isset($paginator) && $paginator)
                {!! $paginator !!}
            @endif

            <section class="content-card">
                <div class="section-heading">
                    <div>
                        <span class="eyebrow">{{ __('interface.frontend.sites') }}</span>
                        @if(empty($arr) && isset($catalog_name))
                            <h1>{{ $catalog_name }}</h1>
                        @else
                            <h2>@if(isset($catalog_name) && $catalog_name) {{ $catalog_name }} @else {{ __('interface.frontend.recent_sites') }} @endif</h2>
                        @endif
                    </div>
                </div>

                @if($links && count($links))
                    <div class="link-list">
                        @foreach($links as $link)
                            @include('frontend.partials.link-card', ['link' => $link])
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">{{ __('interface.common.no_links') }}</p>
                @endif
            </section>

            @if(isset($paginator) && $paginator)
                {!! $paginator !!}
            @endif
        </div>

        <aside class="content-sidebar">
            @include('frontend.partials.top-sites-card', ['topLinks' => $topLinks ?? collect()])
        </aside>
    </div>
@endsection
