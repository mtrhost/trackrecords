<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/vue.js') }}"></script>
    <script src="{{ asset('js/vue-resource.js') }}"></script>
    <script src="{{ asset('js/iziToast.min.js') }}"></script>
    <script src="{{ asset('js/awesomplete.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/normalize.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/iziToast.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/awesomplete.css') }}">
</head>
<body>
    <div id="app" class="luxury">
        <div id="top" data-role="page scroll-action">
            <!-- PAGE -->
            <div class="pag ui-panel-wrapper">
                <header class="pag hdr ">
                    <div>
                        <nav class="nav primary "> 
                            <ul class="lst menu">
                                <li class="logo-itm">
                                    <a href="{{ url('/') }}" class="logo">Главная</a>
                                </li>
                                @if (Auth::check())
                                    <li class="smenu">
                                        <a href="{{ route('admin.roles') }}" class="pnl-button">
                                            <span>Роли</span>
                                        </a>
                                    </li>
                                    <li class="smenu">
                                        <a href="{{ route('admin.players') }}" class="pnl-button">
                                            <span>Игроки</span>
                                        </a>
                                    </li>
                                    <li class="smenu">
                                        <a href="{{ route('admin.setting') }}" class="pnl-button">
                                            <span>Сеттинг</span>
                                        </a>
                                    </li>
                                    <li class="smenu">
                                        <a href="{{ route('admin.gane') }}" class="pnl-button">
                                            <span>Игра</span>
                                        </a>
                                    </li>
                                    <li class="smenu">
                                        <a href="{{ url('/logout') }}" class="pnl-button">
                                            <span>Выйти</span>
                                        </a>
                                    </li>
                                @else
                                
                                @endif
                                
                            </ul>
                        </nav>
                    </div>
                    <div class="main__header-fixer"></div>
                </header>
                <section class="pag bdy">
                    <div class="bdy__main dashboard">
                        @yield('content')
                    </div>
                    <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="ui-id-1" tabindex="0" style="display: none;"></ul>
                    <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="ui-id-3" tabindex="0" style="display: none;"></ul>
                </section>

                <!-- PAGE FOOTER -->
                <footer class="ftr pag">
                    <div class="disclosures">
                        Mafia track records |
                    </div>
                </footer>
            </div>
        </div>
    </div>
    @yield('footer-scripts')
</body>
</html>