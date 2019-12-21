<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{--CSRF Token--}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--Meta--}}
    <title>@yield('title')</title>

    {{--Scripts--}}
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>

    {{--Styles--}}


    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/normalize.css') }}" rel="stylesheet">
    <link href="{{ asset('css/css.css') }}" rel="stylesheet">
    <link href="{{ asset('css/extended.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet">
    <link href="{{ asset('css/vdatatables.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    @yield('header-styles')
</head>
<body>
<div id="app">
    <header id="header">
        @include('templates.header')
    </header>
    <div class="wrapper">
        <div class="content">
            @yield('layout')
        </div>
        <div class="container"></div>
        <footer id="footer">
            @include('templates.footer')
        </footer>
    </div>
</div>
@yield('footer-scripts')
</body>
</html>