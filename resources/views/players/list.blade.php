@extends('templates.app')
@section('title','Игроки')
@section('layout')
@section('footer-scripts')
    <script src="{{ url('js/vue.js') }}"></script>
    <script src="{{ url('js/vue-resource.js') }}"></script>
    <script src="{{ url('js/datatables.min.js') }}"></script>
    <script>
        new Vue({
            el: '#players',
            data: {
                players: <?php echo $players->toJson() ?>
            },
            methods: {
                getLastActivity: function (playerId) {
                    this.$http
                        .post("<?php echo route('player.getLastActivity')?>", { _token: "{{ csrf_token() }}", id: playerId})
                        .then(function (response) {
                            this.$refs['js-player-' + playerId + '-last-activity'].innerText = response.body;
                        }, console.log)
                        .catch(function (error) {
                            console.log(error);
                            setTimeout(this.getLastActivity(playerId), 1000);
                        });
                }
            },
            mounted: function() {
                $(".players-table").DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Russian.json"
                    },
                    "bLengthChange": false,
                    "pageLength": 50,
                    "ordering": false
                });
            },
            created: function(){
                /*for (var player in this.players.data) {
                    setTimeout(this.getLastActivity(this.players.data[player].id), 5000);
                }*/
            }
        });
    </script>
@endsection
<section class="container players-page">
    <div class="card custom-card">
        <div class="card-header">
            <h1 class="card-title">
                Список игроков
            </h1>
        </div>
        <div class="card-block data-block table-responsive" id="players">
            <div class="row">
                <div class="col-sm-12">
                    <table class="players-table table">
                        <thead>
                        <tr>
                            <th>Ник</th>
                            <th>Дата последней игры</th>
                            <th>Количество игр</th>
                            <th>Винрейт</th>
                            <th>Последний онлайн</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($players as $player)
                                @php $player->getWinrate(); @endphp
                                <tr>
                                    <td><a href="{{ route('player.details', $player->id) }}">{{ $player->name }}</a></td>
                                    <td>{{ $player->last_game }}</td>
                                    <td>{{ empty($player->statistics) ? 0 : $player->statistics->games_count }}</td>
                                    <td>{{ $player->winrate }} %</td>
                                    <td :ref="'js-player-' + {{ $player->id }} + '-last-activity'">--</td>
                                </tr>
                            @endforeach 
                        </tbody>
                    </table>
                </div>
            </div>
            <?php /*<div class="row">
                <div class="col-sm-12">
                    <div class="filter-pagination m-b-30">
                        {{ $players->links() }}
                    </div>
                </div>
            </div>*/?>
        </div>
    </div>

</section>
@endsection