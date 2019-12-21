@extends('templates.app')
@section('title', $player->name)
@section('layout')
@section('footer-scripts')
    
    <script src="{{ url('js/vue.js') }}"></script>
    <script src="{{ url('js/vue-resource.js') }}"></script>
    <script src="{{ url('js/progressbar.min.js') }}"></script>
    <script src="{{ url('js/vdatatables.js') }}"></script>
    <script>
        new Vue({
            el: '#player',
            data: {
                player: <?php echo $player->toJson() ?>,
                color: '',
                tab: 'total',
                totalGamesCount: 0,
                totalWinsCount: 0,
                totalLosesCount: 0,
                civilianNegativeActionsCount: 0,
                civilianGamesCount: 0
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
                            position: 'absolute',
                            top: '30px',
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
            },
            watch: {
                tab: function (newValue, oldValue) {
                    if (newValue == 'games') {
                        Vue.nextTick(function () {
                            var table = new DataTable("#GamesTable", {
                                perPageSelect: false,
                            });
                        })
                    }
                }
            },
        });
    </script>
@endsection
<div id="player">
    <section class="hero hero-profile">
        <div class="overlay"></div>
        <div class="container">
            <div class="text-center col hidden-md-up m-y-20">
                <a href="#">
                    <img src="{{ $player->profile_image }}" alt="{{ $player->name }}">
                </a>
                <div class="profile-info m-t-10">
                    <h5 class="d-inline">{{ $player->name }}</h5>
                </div>

                <div class="m-t-10 steam-profile">
                    <progress class="winrate-bar" value="{{ $player->winrate }}" max="100"></progress> 
                </div>

                <div class="font-italic last-seen">
                    <span>{{ $player->last_active }}</span>
                </div>
            </div>
            <div class="hero-block">
                <div class="hero-left hidden-md-down">
                <div>
                    <h1 class="d-inline">{{ $player->name }}</h1>
                </div>
                <div class="m-t-10 steam-profile">
                    <div id="winrate-bar" class="progress-bar-container line-styled"></div>
                </div>
                <div class="font-italic last-seen">
                    <span>{{ $player->last_active }}</span>
                </div>
                </div>
            </div>
        </div>
    </section>
    <section class="toolbar toolbar-profile p-t-0 hidden-md-down">
        <div class="container">
            <div class="profile-avatar ">
                <a href="#">
                    <img src="{{ $player->profile_image }}" alt="{{ $player->name }}">
                </a>
                <div class="sticky">
                <div class="profile-info">
                    <h5>{{ $player->name }}</h5>
                    <span>{{ $player->last_active }}</span>
                </div>
                </div>
            </div>
            <ul class="toolbar-nav hidden-md-down">
                <li :class="{ active:isActive('total') }"><a @click="setActive('total')">Общая</a></li>
                <li :class="{ active:isActive('games') }"><a @click="setActive('games')">Игры</a></li>
                <li :class="{ active:isActive('achievements') }"><a @click="setActive('achievements')">Ачивки</a></li>
            </ul>
        </div>
    </section>
    <section class="p-t-30">
        <div class="container">

            {{-- Секция основной статистики --}}
            <template v-if="isActive('total')">
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
                                            <table class="players-table latest-matches table dataTable no-footer" id="DataTables_Table_0" role="grid">
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

            {{-- Секция игр --}}
            <template v-if="isActive('games')">
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
                                                    <th>Роль</th>
                                                    <th>Результат</th>
                                                </thead>
                                                <tbody>
                                                    @foreach($player->games as $game)
                                                        <tr>
                                                            <td>{{ $game->number }}</td>
                                                            <td>{{ $game->name }}</td>
                                                            <td>
                                                                <a href="{{ route('player.details', $game->master->id) }}" target="_blank">
                                                                    {{ $game->master->name }}
                                                                </a>
                                                            </td>
                                                            <td>{!! $game->roleString !!}</td>
                                                            <td>{!! $game->statusString !!}</td>
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
                <div class="card card-danger custom-card">
                    <div class="card-block p-y-0">
                        <table class="table achievements__header-cells">
                            <tbody>
                                @foreach($player->achievements as $achievement)
                                    <tr>
                                        <td class="align-middle">
                                            <img class="achievements__main-image" src="{{ $achievement->image_original }}" alt="{{ $achievement->name }}">
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
                    </div>
                </div>
            </template>
        </div>
    </section>

</div>
@endsection