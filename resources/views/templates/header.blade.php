<div class="container p-r-0">
    <div class="navbar-backdrop">
        <div class="navbar">
        <div class="navbar-left">
            <a class="navbar-toggle">
                <i class="navbar-toggler-icon"></i>
            </a>
            <script>
                var element = document.getElementsByClassName('navbar-toggle')[0];
                element.addEventListener("click", function(e) {
                    var menu = document.querySelector('body') // Using a class instead, see note below.
                    menu.classList.toggle('navbar-open');
                }, false);
            </script>
            <a href="{{ route('index') }}">
                <img src="/static/images/mtr.png" class="img-logo" alt="Mafia Track Records" width="121" height="53">
            </a>
            <nav class="nav">
                <ul>
                    <li>
                        <a href="{{ route('player.index') }}">
                            Игроки
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('game.index') }}">
                            Игры
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('setting.index') }}">
                            Сеттинги
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('achievements.index') }}">
                            Ачивки
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('player.statistics') }}">
                            Статистика
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <div class="nav navbar-right">
            
        </div>

        </div>
    </div>
</div>