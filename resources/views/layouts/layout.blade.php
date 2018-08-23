<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="format-detection" content="telephone=no">

    <title>@yield('title', '')</title><meta name="description" content="@yield('description', '')" />
    <meta property="og:site_name" content="Tolly" />
    <meta property="og:title" content="@yield('ogtitle', '')" />
    <meta property="og:description" content="@yield('ogdescription', '')" />
    <meta property="og:url" content="@yield('canonical', '')" />
    <meta property="og:image" content="@yield('ogimage', url('/topin.jpg'))" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="canonical" href="@yield('canonical', '')" />
    <link href="{{ url('storage/favicon.ico') }}" rel="shortcut icon" type="image/x-icon" />
    <link href="{{ url('storage/favicon.ico') }}" rel="icon" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,300i,400,400i,500,700,900&amp;subset=cyrillic" rel="stylesheet">
    {{--<link rel="preload" href="{{ mix('css/app.css') }}" as="style" />--}}
    {{--<link href="{{ mix('css/app.css') }}" rel="stylesheet" />--}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/solid.css" integrity="sha384-Rw5qeepMFvJVEZdSo1nDQD5B6wX0m7c5Z/pLNvjkB14W6Yki1hKbSEQaX9ffUbWe" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/fontawesome.css" integrity="sha384-GVa9GOgVQgOk+TNYXu7S/InPTfSDTtBalSgkgqQ7sCik56N9ztlkoTr2f/T44oKV" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ url('css/test.css?v=2') }}">

</head>
<body>
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
    <script type="text/javascript" src="{{ url('js/jquery.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/jquery-ui.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/touch.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/scroll.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/js.cookie.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>
    <script type="text/javascript" src="{{ url('js/common.js?v=41') }}"></script>
</body>
</html>
