@extends('layouts.frontend')

@section('title', $title)

@section('description', $description)

@section('keywords', $keywords)

@section('content')
    @if(isset($arr) && $arr)
        <section class="content-card">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">Каталог</span>
                    <h1>{{ $title }}</h1>
                    <p>Выберите раздел и найдите подходящие сайты в структурированном каталоге.</p>
                </div>
            </div>

            <div class="category-grid">
                @for ($i = 0; $i < $number; $i++)
                    @for ($j = 0; $j < \App\Helpers\SettingsHelpers::getSetting('COLUMNS_NUMBER'); $j++)
                        @if(isset($arr[$i][$j][1], $arr[$i][$j][0], $arr[$i][$j][3]))
                            <article class="category-card">
                                <a class="category-card__icon" href="{{ URL::route('catalog', ['id' => $arr[$i][$j][1] > 0 ? $arr[$i][$j][1] : '']) }}">
                                    <img src="{{ isset($arr[$i][$j][2]) && $arr[$i][$j][2] ? url('uploads/catalog/' . $arr[$i][$j][2]) : url('/img/catalog-placeholder.svg') }}" alt="{{ $arr[$i][$j][0] }}">
                                </a>

                                <div>
                                    <a class="category-card__title" href="{{ URL::route('catalog', ['id' => $arr[$i][$j][1] > 0 ? $arr[$i][$j][1] : '']) }}">
                                        {{ $arr[$i][$j][0] }}
                                    </a>

                                    @if($arr[$i][$j][1] > 0)
                                        <div class="category-card__children">
                                            {!! $arr[$i][$j][4] ?? '' !!}
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
                        <span class="eyebrow">Сайты</span>
                        <h2>@if(isset($catalog_name) && $catalog_name) {{ $catalog_name }} @else Недавно добавленные сайты @endif</h2>
                    </div>
                </div>

                @if($links && count($links))
                    <div class="link-list">
                        @foreach($links as $link)
                            @include('frontend.partials.link-card', ['link' => $link])
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">Нет ссылок</p>
                @endif
            </section>

            @if(isset($paginator) && $paginator)
                {!! $paginator !!}
            @endif
        </div>

        <aside class="content-sidebar">
            @for ($i = 0; $i < 3; $i++)
                <div class="ad-card">
                    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <ins class="adsbygoogle"
                         style="display:inline-block;width:200px;height:200px"
                         data-ad-client="ca-pub-2243538192217050"
                         data-ad-slot="0787053397"></ins>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                </div>
            @endfor
        </aside>
    </div>
@endsection
