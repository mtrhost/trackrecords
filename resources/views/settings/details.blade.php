@extends('templates.app')
@section('title',$setting->name)
@section('layout')
<div id="setting" class="container">
    <div class="card custom-card">
        <div class="card-header">
            <h1 class="card-title">
                Список ролей
            </h1>
        </div>
        <div class="card-block p-y-0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card custom-card">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Автор</td>
                                    <td><a href="{{ route('player.details', $setting->author->id) }}">{{ $setting->author->name }}</a></td>
                                </tr>
                                @foreach($setting->rolesSorted as $roleGroup)
                                    <tr>
                                        <td>
                                            {{ $roleGroup[0]['faction']['name'] }}
                                        </td>
                                        <td>
                                        @foreach($roleGroup as $index => $role)
                                            <span class="{{ 'faction-' . $role['faction']['alias'] }}">
                                                {{ $role['role']['name'] }}
                                                @if($index < count($roleGroup) - 1)
                                                    <span>, </span>
                                                @endif
                                            </span>
                                        @endforeach
                                        </td>
                                    </td>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-header">
            <h1 class="card-title">
                Игры по сеттингу
            </h1>
        </div>
        <div class="card-block table-responsive">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card custom-card">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">№</th>
                                    <th scope="col">Название</th>
                                    <th scope="col">Ведущий</th>
                                    <th scope="col">Победитель</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($setting->games as $game)
                                    <tr>
                                        <td>{{ $game->number }}</td>
                                        <td><a href="{{ route('game.details', $game->id) }}">{{ $game->name }}</a></td>
                                        <td>
                                            <a href="{{ route('player.details', $game->master->id) }}" target="_blank">
                                                {{ $game->master->name }}
                                            </a>
                                        </td>
                                        <td>{!! $game->getWinnersString() !!}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection