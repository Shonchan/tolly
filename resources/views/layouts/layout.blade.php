<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
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

    <link rel="stylesheet" href="{{ url('css/style.css?v=1.3.3') }}">
</head>
<body>
<!-- Yandex.Metrika counter --> <script type="text/javascript" > (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)}; m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)}) (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym"); ym(48634619, "init", { id:48634619, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true, trackHash:true, ecommerce:"dataLayer" }); </script> <noscript><div><img src="https://mc.yandex.ru/watch/48634619" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->
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
    <script defer src="{{ url('js/tooltipster.js') }}"></script>
    <script defer src="{{ url('js/jquery.fancybox.min.js') }}"></script>
    <script defer src="{{ url('js/common.js?v=1.3.54') }}"></script>
    <script defer src="{{ url('js/awesomeRating.min.js?v=1') }}"></script>
    <script defer src="{{ url('js/swiper.min.js?v=1') }}"></script>

    @if (Route::currentRouteName() == 'createOrder')
        {{--<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA==" crossorigin=""></script>--}}
        {{--<script type="text/javascript" src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>--}}

        <script defer src="{{ url('js/cart.js?v=1.2.07') }}"></script>
    @endif

    <!-- Rating@Mail.ru counter --><script type="text/javascript">var tmr = window._tmr || (window._tmr = []); tmr.push({id: "3063200", type: "pageView", start: (new Date()).getTime()}); (function (d, w, id) { if (d.getElementById(id)) return; var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true; ts.id = id; ts.src = "https://top-fwz1.mail.ru/js/code.js"; var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);}; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "topmailru-code");</script><noscript><div><img src="https://top-fwz1.mail.ru/counter?id=3063200;js=na" style="border:0;position:absolute;left:-9999px;" alt="Top.Mail.Ru" /></div></noscript><!-- //Rating@Mail.ru counter -->
<!-- Chatra {literal} --><script>(function(d, w, c) { w.ChatraID = 'YtynLCjjtABqtZG8z'; window.ChatraSetup = { colors: { buttonText: '#fff', buttonBg: '#cc0000' } }; var s = d.createElement('script'); w[c] = w[c] || function() { (w[c].q = w[c].q || []).push(arguments); }; s.async = true; s.src = 'https://call.chatra.io/chatra.js'; if (d.head) d.head.appendChild(s); })(document, window, 'Chatra');</script><!-- /Chatra {/literal} -->
</body>
</html>
