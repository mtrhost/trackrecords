@extends('templates.app')
@section('title', $player->name)
@section('layout')
@section('footer-scripts')
    
    <script src="{{ url('js/vue.js') }}"></script>
    <script src="{{ url('js/vue-resource.js') }}"></script>
    <script src="{{ url('js/progressbar.min.js') }}"></script>
    <script src="{{ url('js/datatables.min.js') }}"></script>
    <script>
        new Vue({
            el: '#player',
            data: {
                player: <?php echo $player->toJson() ?>,
                color: '',
                tab: 'games',
                totalGamesCount: 0,
                totalWinsCount: 0,
                totalLosesCount: 0,
                civilianNegativeActionsCount: 0,
                civilianGamesCount: 0,
                table: ''
            },
            methods: {
                isActive: function (menuItem) {
                    return this.tab === menuItem
                },
                setActive: function (menuItem) {
                    this.tab = menuItem
                },
                countCalculatedProperties: function() {
                    this.totalGamesCount = this.player.statistics.games_count_no_role + this.player.statistics.games_count_active 
                        + this.player.statistics.games_count_mafia + this.player.statistics.games_count_neutral;
                    this.totalWinsCount = this.player.statistics.wins_no_role + this.player.statistics.wins_active 
                        + this.player.statistics.wins_mafia + this.player.statistics.wins_neutral;
                    this.totalLosesCount = this.totalGamesCount - this.totalWinsCount;
                    this.civilianNegativeActionsCount = this.player.statistics.banished_civilian + this.player.statistics.lightnings_civilian;
                    this.civilianGamesCount = this.player.statistics.games_count_no_role + this.player.statistics.games_count_active;
                },
                getFactionLosesCount: function(faction) {
                    return this.player.statistics['games_count_' + faction] - this.player.statistics['wins_' + faction];
                }
            },
            mounted: function(){
                /*for (var player in this.players.data) {
                    setTimeout(this.getLastActivity(this.players.data[player].id), 5000);
                }*/
                this.countCalculatedProperties();
                var bar = new ProgressBar.Line('#winrate-bar', {
                    strokeWidth: 4,
                    easing: 'easeInOut',
                    duration: 1400,
                    color: this.player.winrate_color,
                    trailColor: '#eee',
                    trailWidth: 1,
                    svgStyle: {width: '100%', height: '100%'},
                    text: {
                        style: {
                            // Text color.
                            // Default: same as stroke color (options.color)
                            //color: '#999',
                            position: 'relative',
                            padding: 0,
                            margin: 0,
                            transform: null
                        },
                        autoStyleContainer: false
                    },
                    from: {color: '#b00b13'},
                    to: {color: '#D7B740'},
                    step: (state, bar) => {
                        bar.setText(bar.value() * 100 + ' %');
                    }
                });
                
                bar.animate(this.player.winrate / 100, 1.0);  // Number from 0.0 to 1.0

                Vue.nextTick(function () {
                    this.table = $("#GamesTable").DataTable({
                        "language": {
                            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Russian.json"
                        },
                        "bLengthChange": false,
                        "pageLength": 25,
                        columnDefs: [
                            { targets: [0, 1, 2, 3, 4], visible: true},
                            { targets: '_all', visible: false },
                            
                            { width: "40%", targets: [5] }
                        ],
                        responsive: true,
                        "order": [[ 0, 'desc' ]],
                        initComplete: function () {
                            this.api().columns([5]).every(function () {
                                var column = this;
                                var select = $('<select class="form-control" style="width:150px; display:inline-block;"><option value=""></option></select>')
                                    .appendTo( $('#GamesTable_filter') )
                                    .on( 'change', function () {
                                        var val = $.fn.dataTable.util.escapeRegex(
                                            $(this).val()
                                        );
                
                                        column
                                            .search( val ? '^'+val+'$' : '', true, false )
                                            .draw();
                                    } );
                
                                column.data().unique().sort().each( function ( d, j ) {
                                    select.append( '<option value="'+d+'">'+d+'</option>' )
                                } );
                            } );
                        }
                    });
                })
            },
            watch: {
                tab: function (newValue, oldValue) {
                    if (newValue == 'games') {
                        Vue.nextTick(function () {
                            $("#GamesTable").DataTable({
                                "language": {
                                    "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Russian.json"
                                },
                                "bLengthChange": false,
                                "pageLength": 25,
                                responsive: true,
                                columnDefs: [
                                    { targets: [0, 1, 2, 3, 4], visible: true},
                                    { targets: '_all', visible: false },
                                    
                                    { width: "40%", targets: [5] }
                                ],
                                "order": [[ 0, 'desc' ]],
                                initComplete: function () {
                                    this.api().columns([5]).every(function () {
                                        var column = this;
                                        var select = $('<select><option value=""></option></select>')
                                            .appendTo( $(column.footer()).empty() )
                                            .on( 'change', function () {
                                                var val = $.fn.dataTable.util.escapeRegex(
                                                    $(this).val()
                                                );
                        
                                                column
                                                    .search( val ? '^'+val+'$' : '', true, false )
                                                    .draw();
                                            } );
                        
                                        column.data().unique().sort().each( function ( d, j ) {
                                            select.append( '<option value="'+d+'">'+d+'</option>' )
                                        } );
                                    } );
                                }
                            });
                        })
                    }
                    if (newValue == 'partners') {
                        Vue.nextTick(function () {
                            $("#PartnersTable").DataTable({
                                "language": {
                                    "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Russian.json"
                                },
                                "bLengthChange": false,
                                "pageLength": 15,
                                responsive: true,
                                "order": [[ 0, 'asc' ]]
                            });
                        })
                    }
                }
            },
        });
    </script>
@endsection
<div id="player" class="container">
    <section class="row hero hero-profile">
        <div class="col-lg-4 col-sm-12">
            <div class="card player-card">
                <h3 class="card-header">{{ $player->name }}</h3>
                <img class="card-img-top mx-auto d-block" 
                    src="{{ $player->profile_image }}" alt="{{ $player->name }}">
                <div class="card-body">
                    <div id="winrate-bar" class="progress-bar-container line-styled"></div>
                </div>
                <div class="card-footer text-muted">
                    {{ $player->last_active }}
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-sm-12 table-responsive">
            <div class="container-fluid">
                <div class="row data-block">
                    <table class="table" role="grid">
                        <thead>
                            <th>Фракция</th>
                            <th>Игр сыграно</th>
                            <th>Побед / поражений</th>
                            <th>Доля побед</th>
                        </thead>
                        <tbody>
                            <tr class="profile__no-role-row">
                                <td>Мирный житель</td>
                                <td>{{ $player->statistics->games_count_no_role }}</td>
                                <td>{{ $player->statistics->wins_no_role . ' / '}}@{{ this.getFactionLosesCount('no_role') }}</td>
                                <td>{{ $player->winrate_no_role . ' %' }}</td>
                            </tr>
                            <tr class="profile__active-row" style="{{ 'color:' . $player->factions['active'][0]->color . ';' }}">
                                <td>Актив</td>
                                <td>{{ $player->statistics->games_count_active }}</td>
                                <td>{{ $player->statistics->wins_active . ' / '}}@{{ this.getFactionLosesCount('active') }}</td>
                                <td>{{ $player->winrate_active . ' %' }}</td>
                            </tr>
                            <tr class="profile__mafia-row" style="{{ 'color:' . $player->factions['mafia'][0]->color . ';' }}">
                                <td>Мафия</td>
                                <td>{{ $player->statistics->games_count_mafia }}</td>
                                <td>{{ $player->statistics->wins_mafia . ' / '}}@{{ this.getFactionLosesCount('mafia') }}</td>
                                <td>{{ $player->winrate_mafia . ' %' }}</td>
                            </tr>
                            <tr class="profile__neutral-row" style="{{ 'color:' . $player->factions['neutral'][0]->color . ';' }}">
                                <td>Маньяк</td>
                                <td>{{ $player->statistics->games_count_neutral }}</td>
                                <td>{{ $player->statistics->wins_neutral . ' / '}}@{{ this.getFactionLosesCount('neutral') }}</td>
                                <td>{{ $player->winrate_neutral . ' %' }}</td>
                            </tr>
                            <tr>
                                <td>Всего</td>
                                <td>@{{ totalGamesCount }}</td>
                                <td>@{{ totalWinsCount + '/' +  totalLosesCount}}</td>
                                <td>{{ $player->winrate . ' %' }}</td>
                            </tr>
                            <tr>
                                <td class="text-nowrap" colspan="2">Ролей получено: {{ $player->roleRate }}%</td>
                                <td class="text-nowrap" colspan="2">Игр проведено: {{ $player->games_mastered_count }}</td>
                            </tr>
                            <tr>
                                <td class="text-nowrap" colspan="2">Молний получено: {{ $player->lightningsCount }}</td>
                                <td class="text-nowrap" colspan="2">
                                    Выгнан днем/молнирован на мирном: @{{ civilianNegativeActionsCount + ' / ' + civilianGamesCount }}
                                    {{ ' (' . $player->cityNegativeActionsRate . '%)' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-nowrap" colspan="2">В среднем прожито дней на мафии: {{ $player->mafiaAverageDaysSurvived }}</td>
                                <td class="text-nowrap" colspan="2">Максимальная серия побед: {{ $player->statistics->maximal_winstreak }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link" :class="{ active:isActive('games') }" data-toggle="tab" @click="setActive('games')">Игры</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" :class="{ active:isActive('partners') }" data-toggle="tab" @click="setActive('partners')">Напарники</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" :class="{ active:isActive('achievements') }" data-toggle="tab" @click="setActive('achievements')">Ачивки</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <section class="row p-t-30">
        <div class="container">

            {{-- Секция игр --}}
            <template v-if="isActive('games')">
                <div class="row">
                    
                    <div class="col-lg-12">
                        <div class="card custom-card table-responsive">
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
                                                    <th>Роль</th>
                                                    <th>Результат</th>
                                                </thead>
                                                <tbody>
                                                    @foreach($player->games as $game)
                                                        <tr>
                                                            <td>{{ $game->number }}</td>
                                                            <td>
                                                                <a href="{{ route('game.details', $game->id) }}">
                                                                    {{ $game->name }}
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('player.details', $game->master->id) }}" target="_blank">
                                                                    {{ $game->master->name }}
                                                                </a>
                                                            </td>
                                                            <td>{!! $game->roleString !!}</td>
                                                            <td>{!! $game->statusString !!}</td>
                                                            <td>{{ $game->roles->first()->faction->group->title }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-7">
                                            <div class="dataTables_paginate paging_numbers" id="DataTables_Table_0_paginate">
                                                <ul class="pagination"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Секция напарников --}}
            <template v-if="isActive('partners')">
                <div class="row">
                    
                    <div class="col-lg-12">
                        <div class="card custom-card table-responsive">
                            <div class="card-block p-t-0">
                                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6"></div>
                                        <div class="col-sm-12 col-md-6"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="partners-table latest-matches table dataTable no-footer" id="PartnersTable" role="grid">
                                                <thead>
                                                    <th>№</th>
                                                    <th>Имя</th>
                                                    <th>Игр вместе</th>
                                                    <th>Побед вместе</th>
                                                    <th>Винрейт</th>
                                                </thead>
                                                <tbody>
                                                    @foreach($player->partners as $key => $partner)
                                                        <tr>
                                                            <td>{{ $key + 1 }}</td>
                                                            <td>
                                                                <a href="{{ route('player.details', $partner->id) }}">
                                                                    {{ $partner->name }}
                                                                </a>
                                                            </td>
                                                            <td>{{ $partner->games_count }}</td>
                                                            <td>{{ $partner->wins_count }}</td>
                                                            <td>{{ $partner->winrate }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-7">
                                            <div class="dataTables_paginate paging_numbers" id="DataTables_Table_0_paginate">
                                                <ul class="pagination"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Секция ачивок --}}
            <template v-if="isActive('achievements')">
                <div class="row data-block table-responsive">
                    @if($player->achievements->isEmpty())
                        <h2>Достижений нет</h2>
                    @else
                        <table class="table achievements__header-cells">
                            <tbody>
                                @foreach($player->achievements as $achievement)
                                    <tr>
                                        <td class="align-middle">
                                            <img class="achievements__main-image img-thumbnail-dark" src="{{ $achievement->image_original }}" alt="{{ $achievement->name }}">
                                        </td>
                                        <td class="align-middle">
                                            {{ $achievement->name }}
                                        </td>
                                        <td class="align-middle">
                                            {{ $achievement->condition }}
                                        </td>
                                        <td class="align-middle">
                                            {{ $achievement->description }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </template>
        </div>
    </section>

</div>
@endsection