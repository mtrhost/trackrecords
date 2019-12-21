@extends('templates.app')
@section('title','Игры')
@section('layout')
<section class="container games-page">
    <div class="card custom-card">
        <div class="card-header">
            <h1 class="card-title">
                Список игр
            </h1>
        </div>
        <div class="card-block p-y-0" id="players">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card custom-card">

                        <div class="card-block p-t-0">
                            <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6"></div>
                                    <div class="col-sm-12 col-md-6"></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="players-table latest-matches table dataTable no-footer" id="GamesTable" role="grid">
                                            <thead>
                                                <th>№</th>
                                                <th>Название</th>
                                                <th>Ведущий</th>
                                                <th>Победитель</th>
                                            </thead>
                                            <tbody>
                                                @foreach($games as $game)
                                                    <tr>
                                                        <td>{{ $game->number }}</td>
                                                        <td><a href="{{ route('game.details', $game->id) }}">{{ $game->name }}</a></td>
                                                        <td>
                                                            <a href="{{ route('player.details', $game->master->id) }}" target="_blank">
                                                                {{ $game->master->name }}
                                                            </a>
                                                        </td>
                                                        <td>{!! $game->winnersString !!}</td>
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
            </div>
            <div class="row">
                <div class="col-sm-12">
                <div class="filter-pagination m-b-30">
                    {{ $games->links() }}
                </div>
            </div>
            </div>
        </div>
    </div>

</section>
@endsection