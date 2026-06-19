@extends('layouts.frontend')

@section('title', $title)

@section('description', '')

@section('keywords', $link->keywords)

@section('content')
    <div class="content-layout">
        <div class="content-main">
            <article class="content-card">
                <div class="section-heading">
                    <div>
                        <span class="eyebrow">{{ __('interface.frontend.site_card') }}</span>
                        <h1>{{ $link->name }}</h1>
                    </div>
                </div>

                @if($link->full_description)
                    <div class="rich-text">{!! $link->full_description !!}</div>
                @endif

                <div class="details-list">
                    <div class="details-list__item">
                        <span class="details-list__label">{{ __('interface.frontend.catalog_section') }}</span>
                        <span>{{ $link->catalog->name ?? __('interface.common.misc') }}</span>
                    </div>

                    @if($link->contact)
                        <div class="details-list__item">
                            <span class="details-list__label">{{ __('interface.common.contacts') }}</span>
                            <span>{{ $link->contact }}</span>
                        </div>
                    @endif

                    @if($link->phone)
                        <div class="details-list__item">
                            <span class="details-list__label">{{ __('interface.common.phone') }}</span>
                            <span>{{ $link->phone }}</span>
                        </div>
                    @endif

                    @if($link->email)
                        <div class="details-list__item">
                            <span class="details-list__label">Email</span>
                            <span>{{ $link->email }}</span>
                        </div>
                    @endif

                    @if($link->city)
                        <div class="details-list__item">
                            <span class="details-list__label">{{ __('interface.common.city') }}</span>
                            <span>{{ $link->city }}</span>
                        </div>
                    @endif

                    <div class="details-list__item">
                        <span class="details-list__label">{{ __('interface.frontend.visits') }}</span>
                        <span>{{ $link->views }}</span>
                    </div>

                    <div class="details-list__item">
                        <span class="details-list__label">{{ __('interface.common.site_address') }}</span>
                        <noindex>
                            <a rel="nofollow" href="{{ URL::route('redirect', ['id' => $link->id]) }}">{{ $link->url }}</a>
                        </noindex>
                    </div>
                </div>
            </article>

            @if($similar_links && count($similar_links))
                <section class="content-card">
                    <div class="section-heading">
                        <div>
                            <span class="eyebrow">{{ __('interface.frontend.more_in_category') }}</span>
                            <h2>{{ __('interface.frontend.similar_sites') }}</h2>
                        </div>
                    </div>

                    <div class="link-list">
                        @foreach($similar_links as $link)
                            @include('frontend.partials.link-card', ['link' => $link])
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        <aside class="content-sidebar">
            <div class="ad-card">
                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-2243538192217050"
                     data-ad-slot="6522655839"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            </div>
        </aside>
    </div>
@endsection
