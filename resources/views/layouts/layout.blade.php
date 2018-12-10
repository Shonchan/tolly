<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="initial-scale=1.0, width=device-width">
    <meta name="format-detection" content="telephone=no" />

    <title>@yield('title', '')</title>
    <meta name="description" content="@yield('description', '')" />
    <meta name="keywords" content="@yield('keywords', '')" />

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="TOLLY" />
    <meta property="og:title" content="@yield('ogtitle', '')" />
    <meta property="og:description" content="@yield('ogdescription', '')" />
    <meta property="og:url" content="@yield('canonical', '')" />
    <meta property="og:image" content="@yield('ogimage', url('/tolly.png'))" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="canonical" href="@yield('canonical', '')" />
    <link href="{{ url('storage/favicon.ico') }}" rel="shortcut icon" type="image/x-icon" />
    <link href="{{ url('storage/favicon.ico') }}" rel="icon" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,300i,400,400i,500,700,900&amp;subset=cyrillic" rel="stylesheet">
    {{--<link rel="preload" href="{{ mix('css/app.css') }}" as="style" />--}}
    {{--<link href="{{ mix('css/app.css') }}" rel="stylesheet" />--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/solid.css" integrity="sha384-Rw5qeepMFvJVEZdSo1nDQD5B6wX0m7c5Z/pLNvjkB14W6Yki1hKbSEQaX9ffUbWe" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/fontawesome.css" integrity="sha384-GVa9GOgVQgOk+TNYXu7S/InPTfSDTtBalSgkgqQ7sCik56N9ztlkoTr2f/T44oKV" crossorigin="anonymous">

    @if (Route::currentRouteName() == 'createOrder')
       {{-- <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"
              integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
              crossorigin=""/>
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css">
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css">--}}
        <script src="https://api-maps.yandex.ru/2.1/?apikey=3f1cc493-3146-45a9-b612-bf138ad76595&lang=ru_RU" type="text/javascript">
        </script>
    @endif

    @if(Route::currentRouteName() == 'product')
    <link rel="stylesheet" href="{{ url('css/fotorama.css?v=1.2.1') }}">
    @endif

    <link rel="stylesheet" href="{{ url('css/style.css?v=1.2.5') }}">
</head>
<body>
<!-- Yandex.Metrika counter --><script>(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter48634619 = new Ya.Metrika2({ id:48634619, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true, trackHash:true, ut:"noindex" }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/tag.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks2"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/48634619?ut=noindex" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->
<script async src="https://www.google-analytics.com/analytics.js"></script><script>window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;ga('create', 'UA-127625387-1', 'auto');ga('send', 'pageview');setTimeout("ga('send', 'event', 'read', '15_seconds')", 15000)</script>
<div class="wrap container">
    <div class="wrap-top">
        @include('layouts.header')
        @include('layouts.main')
    </div>
    <div class="wrap-down row">
        @include('layouts.footer')
    </div>
</div>

{{--    <script src="{{ mix('js/app.js') }}"></script>--}}
    <script defer src="{{ url('js/jquery.js') }}"></script>
    <script defer src="{{ url('js/jquery-ui.js') }}"></script>
    <script defer src="{{ url('js/touch.js') }}"></script>
    <script defer src="{{ url('js/scroll.js') }}"></script>
    <script defer src="{{ url('js/js.cookie.js') }}"></script>
    <script defer src="{{ url('js/inputmask.js') }}"></script>
    <script defer src="{{ url('js/inputmask.extensions.js') }}"></script>
    <script defer src="{{ url('js/jquery.inputmask.js') }}"></script>

    <script defer src="{{ url('js/swiper.js') }}"></script>
    <script defer src="{{ url('js/rank.js') }}"></script>
    <script defer src="{{ url('js/selectize.js') }}"></script>
    <script defer src="{{ url('js/fotorama.js') }}"></script>

    <script defer src="{{ url('js/jquery.fancybox.min.js') }}"></script>
    <script defer src="{{ url('js/common.js?v=1.2.64') }}"></script>
    <script defer src="{{ url('js/awesomeRating.min.js?v=1') }}"></script>
    <script defer src="{{ url('js/swiper.min.js?v=1') }}"></script>

    @if (Route::currentRouteName() == 'createOrder')
        {{--<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA==" crossorigin=""></script>--}}
        {{--<script type="text/javascript" src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>--}}

        <script defer src="{{ url('js/cart.js?v=1.1.64') }}"></script>
    @endif
</body>
</html>
