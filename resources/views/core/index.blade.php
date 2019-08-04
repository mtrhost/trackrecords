@extends('templates.main')

@section('title', 'StatisticsReclaim')
@section('description', 'Тип описание')
@section('keywords', 'тип, мафия, pd')
@section('content')
<div id="main-content" class="main-content">
    <div id="primary" class="content-area">
        <div id="content" class="site-content no-banner" role="main">
            <!--section--->
            <div class="full gallery-section visible">
                <div class="gallery-left gbox">
                    <h2>Redesign! Rebuild! Reborn!</h2>
                    <p>Вот и стата подъехала.</p>
                    <p>
                        На этих страницах вы можете наблюдать результаты всех игр раздела, начиная с игры номер 66,5 (игры шипа до 67 игры), пока что так. Failed игры внесены но не учитываются, игры где ведущий
                        завершил игру ничьей или победой ведущего (да были и такие) вносятся в общую стату количества сыгранных игр, однако никак не влияют на винрейт и не отображаются в количестве игр в персональном профиле
                        (no pts ).
                    </p>
                    <p>Для любого попадания в стату нужно в сумме больше 20 игр
                        для мж или актива нужно более 15 игр на этой роли
                        для мирного более 20 игр на мирном, здесь же условие по вылетам на мирном
                        для мафии более 10, здесь же условие по выживаемости на мафии
                        для мана более 4
                        для попадания в общую стату (по винрейту) нужно более 30 (здесь же условие для частоты получения ролей и винстриков)
                    </p>
                    <p>Такие дела</p>
                </div>
            </div>
            <!--section--->
        </div>
        <!-- #content -->
    </div>
    <!-- #primary -->
</div>
<!-- #main-content -->
@endsection