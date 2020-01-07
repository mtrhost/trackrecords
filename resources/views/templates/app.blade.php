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
    <script src="{{ asset('js/jQuery.js') }}"></script>
    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>

    {{--Styles--}}

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/normalize.css') }}" rel="stylesheet">
    <link href="{{ asset('css/css.css') }}" rel="stylesheet">
    <?php /*<link href="{{ asset('css/extended.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet">
    <link href="{{ asset('css/theme.min.css') }}" rel="stylesheet">*/?>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <?php /*<link href="{{ asset('css/style.css') }}" rel="stylesheet">*/?>
    @yield('header-styles')
</head>
<body>
<div id="app">
    <header id="header">
        @include('templates.header')
    </header>
    <div class="container-fluid">
        <div class="row">
            @yield('layout')
        </div>
    </div>
</div>
<footer id="footer" class="footer">
    @include('templates.footer')
</footer>
@yield('footer-scripts')
</body>
</html>