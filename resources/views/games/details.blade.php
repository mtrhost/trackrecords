@extends('templates.app')
@section('title',$game->name)
@section('layout')
<section class="container-fluid" id="game">
    <div class="row settings">
        <div class="col-lg-4 col-sm-12 hidden-sm-down">
            <div class="data-block">
                <div class="widget widget-search widget-filter">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>№</td>
                                <td>{{ $game->number }}</td>
                            </tr>
                            <tr>
                                <td>Название</td>
                                <td>{{ $game->name }}</td>
                            </tr>
                            <tr>
                                <td>Сеттинг</td>
                                <td><a href="{{ route('setting.details', $game->setting->id) }}">{{ $game->setting->name }}</a></td>
                            </tr>
                            <tr>
                                <td>Ведущий</td>
                                <td><a href="{{ route('player.details', $game->master->id) }}">{{ $game->master->name }}</a></td>
                            </tr>
                            <tr>
                                <td>Дата начала</td>
                                <td>{{ $game->date }}</td>
                            </tr>
                            <tr>
                                <td>Длительность</td>
                                <td>{{ $game->length . ' дней' }}</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center"><a href="{{ $game->link }}" class="a-link">Ссылка на игру</a></td>
                            </tr>
                            <tr>
                                <td>Кол-во игроков</td>
                                <td>{{ $game->players_count }}</td>
                            </tr>
                            <tr>
                                <td>Фракция победитель</td>
                                <td>
                                    @foreach($game->winners as $index => $winner)
                                        <span class="{{ 'faction-' . $winner->faction->alias }}">
                                            {{ $winner->faction->name }}
                                            @if($index < $game->winners->count() - 1)
                                                <span>, </span>
                                            @endif
                                        </span>
                                    @endforeach
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-8 filter-results card custom-card table-responsive">
            <div class="post">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">№</th>
                            <th scope="col">Игрок</th>
                            <th scope="col">Роль</th>
                            <th scope="col">Статус</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($game->rolesSorted))
                            @foreach($game->rolesSorted as $key => $role)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td>
                                        <a href="{{ route('player.details', $role['player']['id']) }}">
                                            {{ $role['player']['name'] }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="{{ 'faction-' . $role['faction']['alias'] }}">
                                            {{ $role['role']['name'] }}
                                        </span>
                                    </td>
                                    <td>{{ $role['status']['name'] }}
                                        @if ($role['day'] !== null)
                                            <span>{{ ' ' . $role['day'] }}</span>
                                        @endif
                                        @if ($role['time_status'] !== null)
                                            <span>{{ ' ' . $role['time_status']['name'] }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection