@extends('layouts.frontend')

@section('title', $title)

@section('description', '')

@section('keywords', $link->keywords)


@section('css')

@endsection

@section('content')

    <div class="row">

        <div class="col-sm-12" style="margin-top:10px;">
            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- top2 -->
            <ins class="adsbygoogle"
                 style="display:block"
                 data-ad-client="ca-pub-2243538192217050"
                 data-ad-slot="8369734756"
                 data-ad-format="auto"
                 data-full-width-responsive="true"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div>


        <div class="col-sm-12 col-md-8 col-lg-8 bg-white rounded box-shadow" style="margin-top:20px; margin-bottom:10px;">

            <h1>{{ $link->name }}</h1>

            <p>{!! $link->full_description !!}</p>

            <p>Раздел каталога: {{ $link->catalog->name ?? 'Разное' }}</p>

            @if($link->contact)<p>Контакты: {{ $link->contact }}</p>@endif

            @if($link->phone)<p>Тел.: {{ $link->phone }}</p>@endif

            @if($link->email)<p>Email: {{ $link->email }}</p>@endif

            @if($link->city)<p>Город: {{ $link->city }}</p>@endif

            <p>Всего посещений сайта: {{ $link->views }}</p>

            <noindex><p>Адрес сайта: <a rel="nofollow" href="{{ URL::route('redirect', ['id' => $link->id]) }}">{{ $link->url }}</a></p></noindex>

        </div>


        <div style="margin:10px" class="col-sm-12 col-md-3 col-lg-3">
            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- left-block -->
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


    </div>

@endsection

@section('js')



@endsection
