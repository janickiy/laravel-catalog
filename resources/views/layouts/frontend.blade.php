<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Белый каталог сайтов | @yield('title')</title>
    <meta name="description" content="@yield('description')">
    <meta name="keywords" content="@yield('keywords')">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="yandex-verification" content="4f86550c90840ae3">

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" href="{{ asset('img/my-links-manager-icon.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;750&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    {!! Html::style('css/bootstrap.min.css') !!}
    {!! Html::style('css/frontend.css') !!}

    @yield('css')

    <script>
        var SITE_URL = "{{ url('/') }}";
    </script>

    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({
            google_ad_client: "ca-pub-2243538192217050",
            enable_page_level_ads: true
        });
    </script>

    <script>
        (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
            m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
        ym(65306959, "init", {
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true
        });
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/65306959" style="position:absolute; left:-9999px;" alt=""></div></noscript>
</head>

<body>
<div class="site-shell">
    <header class="site-header">
        <div class="container site-header__inner">
            <a class="site-logo" href="{{ URL::route('index') }}" aria-label="My Links Manager">
                <img src="{{ asset('img/my-links-manager-logo.svg') }}" alt="My Links Manager">
            </a>

            <nav class="site-nav" aria-label="Основная навигация">
                <a class="site-nav__link {{ Request::is('/') ? 'is-active' : '' }}" href="{{ URL::route('index') }}">Главная</a>
                <a class="site-nav__link {{ Request::is('rules*') ? 'is-active' : '' }}" href="{{ URL::route('rules') }}">Правила</a>
                <a class="site-nav__link {{ Request::is('contact*') ? 'is-active' : '' }}" href="{{ URL::route('contact') }}">Обратная связь</a>
                <a class="site-nav__cta" href="{{ URL::route('addurl') }}">
                    <i class="bi bi-plus-lg"></i>
                    <span>Добавить сайт</span>
                </a>
            </nav>
        </div>
    </header>

    <main class="site-main">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <footer class="site-footer">
        <div class="container site-footer__inner">
            <span>© {{ date('Y') }} My Links Manager</span>
            <a href="{{ URL::route('rules') }}">Правила каталога</a>
        </div>
    </footer>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
    if (!window.jQuery) {
        document.write('<script src="{!! asset('js/libs/jquery-3.2.1.min.js') !!}"><\/script>');
    }
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>
    if (!window.jQuery.ui) {
        document.write('<script src="{!! asset('js/libs/jquery-ui.min.js') !!}"><\/script>');
    }
</script>

@yield('js')
</body>
</html>
