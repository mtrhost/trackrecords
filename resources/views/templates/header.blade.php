<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="{{ route('index') }}">
        <img src="/static/images/mtr.png" class="img-logo" alt="Mafia Track Records" width="121" height="53">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarMain">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item{{ (\Route::is('player.index') || \Route::is('player.details')) ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('player.index') }}">
                    Игроки
                </a>
            </li>
            <li class="nav-item{{ \Route::is('game.index') || \Route::is('game.details') ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('game.index') }}">Игры</a>
            </li>
            <li class="nav-item{{ \Route::is('setting.index') || \Route::is('setting.details') ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('setting.index') }}">Сеттинги</a>
            </li>
            <li class="nav-item{{ \Route::is('achievements.index') ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('achievements.index') }}">Ачивки</a>
            </li>
            <li class="nav-item{{ \Route::is('player.statistics') ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('player.statistics') }}">Статистика</a>
            </li>
        </ul>
    </div>
</nav>