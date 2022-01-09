@extends('templates.app')
@section('title','Игры')
@section('layout')
@section('footer-scripts')
    <script src="{{ url('js/vue.js') }}"></script>
    <script src="{{ url('js/vue-resource.js') }}"></script>
    <script src="{{ url('js/datatables.min.js') }}"></script>
    <script>
        new Vue({
            el: '#games',
            data: {
                settings: <?php echo $games->toJson() ?>
            },
            methods: {
                
            },
            mounted: function() {
                $(".players-table").DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Russian.json"
                    },
                    "bLengthChange": false,
                    "pageLength": 50,
                    "order": [[ 0, "desc" ]]
                    //"ordering": false
                });
            },
            created: function(){
                /*for (var player in this.players) {
                    setTimeout(this.getLastActivity(this.players[player].id), 5000);
                }*/
            }
        });
    </script>
@endsection
<section class="container games-page" id="games">
    <div class="card custom-card">
        <div class="card-header">
            <h1 class="card-title">
                Список игр
            </h1>
        </div>
        <div class="card-header container">
			<div class="row">
				<div class="col-sm">
					Количество игр: {{ $games->count() }}
				</div>
				<div class="col-sm">
					Побед города: {{ $games->filter(static function ($game) { return $game->isCityWin; })->count() }}
				</div>
				<div class="col-sm">
					Побед мафии: {{ $games->filter(static function ($game) { return $game->isMafiaWin; })->count() }}
				</div>
				<div class="col-sm">
					Побед нейтралов: {{ $games->filter(static function ($game) { return $game->isNeutralWin; })->count() }}
				</div>
				<div class="col-sm">
					Фейледы (и прочее): {{ $games->filter(static function ($game) { return $game->isFailed; })->count() }}
				</div>
			</div>
        </div>
        <div class="card-block table-responsive" id="players">
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
        </div>
    </div>

</section>
@endsection