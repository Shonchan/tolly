<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', '')</title><meta name="description" content="@yield('description', '')" />
    <meta property="og:site_name" content="Tolly" />
    <meta property="og:title" content="@yield('ogtitle', '')" />
    <meta property="og:description" content="@yield('ogdescription', '')" />
    <meta property="og:url" content="@yield('canonical', '')" />
    <meta property="og:image" content="@yield('ogimage', url('/topin.jpg'))" />
    <link rel="canonical" href="@yield('canonical', '')" />
    <link href="{{ url('/favicon.ico') }}" rel="shortcut icon" type="image/x-icon" />
    <link href="{{ url('/favicon.ico') }}" rel="icon" type="image/x-icon" />
    <link rel="preload" href="{{ mix('css/app.css') }}" as="style" />
    <link href="{{ mix('css/app.css') }}" rel="stylesheet" />

</head>
<body>
    <div id="app">
        @include('layouts/header');
        @include('layouts/main');
    </div>

<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
