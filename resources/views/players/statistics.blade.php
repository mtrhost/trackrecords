@extends('templates.app')
@section('title','Статистика')
@section('layout')
@section('footer-scripts')
    <script src="{{ url('js/vue.js') }}"></script>
    <script src="{{ url('js/vue-resource.js') }}"></script>
    <script src="{{ url('js/vdatatables.js') }}"></script>
    <script>
        new Vue({
            el: '#statistics',
            data: {
                activeTab: 'gamesCount',
                players: <?= json_encode($statistics); ?>
            },
            computed: {
                playersFilteredAndSorted () {
                    switch(this.activeTab) {
                        case 'gamesCount':
                            return this.playersByGames();
                        break;
                        case 'lightningsCount':
                            return this.playersByLightnings();
                        break;
                        case 'winrate':
                            return this.playersByWinrate();
                        break;
                        case 'winrateNoRole':
                            return this.playersByWinrateNoRole();
                        break;
                        case 'winrateActive':
                            return this.playersByWinrateActive();
                        break;
                        case 'winrateCivilian':
                            return this.playersByWinrateCivilian();
                        break;
                        case 'winrateMafia':
                            return this.playersByWinrateMafia();
                        break;
                        case 'winrateNeutral':
                            return this.playersByWinrateNeutral();
                        break;
                        case 'roleRate':
                            return this.playersByRoleRate();
                        break;
                        case 'civilianNegative':
                            return this.playersByCivilianNegativeActions();
                        break;
                        case 'mafiaSurvived':
                            return this.playersByMafiaAverageSurvivedDays();
                        break;
                        case 'winstreak':
                            return this.playersByMaximalWinstreak();
                        break;
                    }
                }
            },
            methods: {
                setActiveTab: function (menuItem) {
                    this.activeTab = menuItem
                },
                filterAndSort: function(filterKeys, sortKeys, range, sortOrder) {
                    if(sortOrder === undefined) sortOrder = 'desc';
                    return this.players
                        .filter(function (player, index) {
                            var result = player;
                            for(var i = 0; i < filterKeys.length; i++) {
                                result = result[filterKeys[i]];
                            }
                            return result > range;
                        })
                        .sort(function (a, b) {
                            var resultA = a;
                            var resultB = b;
                            for(var i = 0; i < sortKeys.length; i++) {
                                resultA = resultA[sortKeys[i]];
                                resultB = resultB[sortKeys[i]];
                            }
                            if(sortOrder === 'desc') {
                                return resultA > resultB ? -1 : 1;
                            } else {
                                return resultA < resultB ? -1 : 1;
                            }
                        });
                },
                playersByGames: function() {
                    return this.filterAndSort(['gamesCount'], ['gamesCount'], 30);
                },
                playersByLightnings: function() {
                    return this.filterAndSort(['lightningsCount'], ['lightningsCount'], 5);
                },
                playersByWinrate: function() {
                    return this.filterAndSort(['gamesCountWoMastered'], ['winrate'], 30);
                },
                playersByWinrateNoRole: function() {
                    return this.filterAndSort(['statistics', 'games_count_no_role'], ['winrate_no_role'], 15);
                },
                playersByWinrateActive: function() {
                    return this.filterAndSort(['statistics', 'games_count_active'], ['winrate_active'], 15);
                },
                playersByWinrateCivilian: function() {
                    return this.filterAndSort(['statistics', 'civilian_games_count'], ['winrateCivilian'], 20);
                },
                playersByWinrateMafia: function() {
                    return this.filterAndSort(['statistics', 'games_count_mafia'], ['winrate_mafia'], 10);
                },
                playersByWinrateNeutral: function() {
                    return this.filterAndSort(['statistics', 'games_count_neutral'], ['winrate_neutral'], 4);
                },
                playersByRoleRate: function() {
                    return this.filterAndSort(['gamesCountWoMastered'], ['roleRate'], 30);
                },
                playersByCivilianNegativeActions: function() {
                    return this.filterAndSort(['statistics', 'civilian_games_count'], ['cityNegativeActionsRate'], 30, 'asc');
                },
                playersByMafiaAverageSurvivedDays: function() {
                    return this.filterAndSort(['statistics', 'games_count_mafia'], ['mafiaAverageDaysSurvived'], 10);
                },
                playersByMaximalWinstreak: function() {
                    return this.filterAndSort(['gamesCountWoMastered'], ['statistics', 'maximal_winstreak'], 30);
                }
            },
            mounted: function(){
                /*for (var player in this.players.data) {
                    setTimeout(this.getLastActivity(this.players.data[player].id), 5000);
                }*/
                /*var tabs = document.querySelectorAll('.sidebar-menu li');
                for(var i = 0; i < tabs.length; i++) {
                    new DataTable("#table-" + tabs[i].getAttribute('data-tab'), {
                        perPageSelect: false,
                        perPage: 25
                    });
                }*/
                new DataTable("#table-" + this.activeTab, {
                    perPageSelect: false,
                    perPage: 25
                });
            },
            watch: {
                playersFilteredAndSorted: function (newValue) {
                    var self = this;
                    self.$nextTick(function () {
                        new DataTable("#table-" + self.activeTab, {
                            perPageSelect: false,
                            perPage: 25
                        });
                    })
                }
            }
        });
    </script>
@endsection
<section id="statistics">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 hidden-sm-down">
                <div class="sidebar">
                    <div class="widget widget-search widget-filter">
                        <ul class="nav flex-column sidebar-menu">
                            <li data-tab="gamesCount" :class="activeTab === 'gamesCount' ? 'active' : ''"><a class="tab-text" @click="setActiveTab('gamesCount');">Количество игр</a></li>
                            <li data-tab="lightningsCount" :class="activeTab === 'lightningsCount' ? 'active' : ''"><a @click="setActiveTab('lightningsCount');" class="tab-text">Количество молний</a></li>
                            <li data-tab="winrate" :class="activeTab === 'winrate' ? 'active' : ''"><a @click="setActiveTab('winrate');" class="tab-text">Общий винрейт</a></li>
                            <li data-tab="winrateNoRole" :class="activeTab === 'winrateNoRole' ? 'active' : ''"><a @click="setActiveTab('winrateNoRole');" class="tab-text">Винрейт (мж)</a></li>
                            <li data-tab="winrateActive" :class="activeTab === 'winrateActive' ? 'active' : ''"><a @click="setActiveTab('winrateActive');" class="tab-text">Винрейт (актив)</a></li>
                            <li data-tab="winrateCivilian" :class="activeTab === 'winrateCivilian' ? 'active' : ''"><a @click="setActiveTab('winrateCivilian');" class="tab-text">Винрейт (мирный)</a></li>
                            <li data-tab="winrateMafia" :class="activeTab === 'winrateMafia' ? 'active' : ''"><a @click="setActiveTab('winrateMafia');" class="tab-text">Винрейт (мафия)</a></li>
                            <li data-tab="winrateNeutral" :class="activeTab === 'winrateNeutral' ? 'active' : ''"><a @click="setActiveTab('winrateNeutral');" class="tab-text">Винрейт (нейтрал)</a></li>
                            <li data-tab="roleRate" :class="activeTab === 'roleRate' ? 'active' : ''"><a @click="setActiveTab('roleRate');" class="tab-text">Роль</a></li>
                            <li data-tab="civilianNegative" :class="activeTab === 'civilianNegative' ? 'active' : ''"><a @click="setActiveTab('civilianNegative');" class="tab-text">Подвел на мирном</a></li>
                            <li data-tab="mafiaSurvived" :class="activeTab === 'mafiaSurvived' ? 'active' : ''"><a @click="setActiveTab('mafiaSurvived');" class="tab-text">Прожито на мафии</a></li>
                            <li data-tab="winstreak" :class="activeTab === 'winstreak' ? 'active' : ''"><a @click="setActiveTab('winstreak');" class="tab-text">Винстрик</a></li>
                        </ul>
                    </div>
                </div>
            </div>
    
            <div class="col-lg-8 filter-results card custom-card">
                <div class="m-y-10">
                    <h5 class="available-players"></h5>
                </div>
    
                <div class="post" v-show="activeTab === 'gamesCount'">
                    <table class="table" id="table-gamesCount">
                        <thead>
                            <tr>
                                <th scope="col">№</th>
                                <th scope="col">Игрок</th>
                                <th scope="col">Кол-во игр</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(player, index) in playersFilteredAndSorted">
                                <td>@{{ index + 1 }}</td>
                                <td><a :href="player.routeLink">@{{ player.name }}</a></td>
                                <td>@{{ player.gamesCount }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="post" v-show="activeTab === 'lightningsCount'">
                    <table class="table" id="table-lightningsCount">
                        <thead>
                            <tr>
                                <th scope="col">№</th>
                                <th scope="col">Игрок</th>
                                <th scope="col">Кол-во молний</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(player, index) in playersFilteredAndSorted">
                                <td>@{{ index + 1 }}</td>
                                <td><a :href="player.routeLink">@{{ player.name }}</a></td>
                                <td>@{{ player.lightningsCount }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="post" v-show="activeTab === 'winrate'">
                    <table class="table" id="table-winrate">
                        <thead>
                            <tr>
                                <th scope="col">№</th>
                                <th scope="col">Игрок</th>
                                <th scope="col">Винрейт (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(player, index) in playersFilteredAndSorted">
                                <td>@{{ index + 1 }}</td>
                                <td><a :href="player.routeLink">@{{ player.name }}</a></td>
                                <td>@{{ player.winrate }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="post" v-show="activeTab === 'winrateNoRole'">
                    <table class="table" id="table-winrateNoRole">
                        <thead>
                            <tr>
                                <th scope="col">№</th>
                                <th scope="col">Игрок</th>
                                <th scope="col">Винрейт (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(player, index) in playersFilteredAndSorted">
                                <td>@{{ index + 1 }}</td>
                                <td><a :href="player.routeLink">@{{ player.name }}</a></td>
                                <td>@{{ player.winrate_no_role }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="post" v-show="activeTab === 'winrateActive'">
                    <table class="table" id="table-winrateActive">
                        <thead>
                            <tr>
                                <th scope="col">№</th>
                                <th scope="col">Игрок</th>
                                <th scope="col">Винрейт (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(player, index) in playersFilteredAndSorted">
                                <td>@{{ index + 1 }}</td>
                                <td><a :href="player.routeLink">@{{ player.name }}</a></td>
                                <td>@{{ player.winrate_active }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="post" v-show="activeTab === 'winrateCivilian'">
                    <table class="table" id="table-winrateCivilian">
                        <thead>
                            <tr>
                                <th scope="col">№</th>
                                <th scope="col">Игрок</th>
                                <th scope="col">Винрейт (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(player, index) in playersFilteredAndSorted">
                                <td>@{{ index + 1 }}</td>
                                <td><a :href="player.routeLink">@{{ player.name }}</a></td>
                                <td>@{{ player.winrateCivilian }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="post" v-show="activeTab === 'winrateMafia'">
                    <table class="table" id="table-winrateMafia">
                        <thead>
                            <tr>
                                <th scope="col">№</th>
                                <th scope="col">Игрок</th>
                                <th scope="col">Винрейт (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(player, index) in playersFilteredAndSorted">
                                <td>@{{ index + 1 }}</td>
                                <td><a :href="player.routeLink">@{{ player.name }}</a></td>
                                <td>@{{ player.winrate_mafia }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="post" v-show="activeTab === 'winrateNeutral'">
                    <table class="table" id="table-winrateNeutral">
                        <thead>
                            <tr>
                                <th scope="col">№</th>
                                <th scope="col">Игрок</th>
                                <th scope="col">Винрейт (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(player, index) in playersFilteredAndSorted">
                                <td>@{{ index + 1 }}</td>
                                <td><a :href="player.routeLink">@{{ player.name }}</a></td>
                                <td>@{{ player.winrate_neutral }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="post" v-show="activeTab === 'roleRate'">
                    <table class="table" id="table-roleRate">
                        <thead>
                            <tr>
                                <th scope="col">№</th>
                                <th scope="col">Игрок</th>
                                <th scope="col">Доля (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(player, index) in playersFilteredAndSorted">
                                <td>@{{ index + 1 }}</td>
                                <td><a :href="player.routeLink">@{{ player.name }}</a></td>
                                <td>@{{ player.roleRate }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="post" v-show="activeTab === 'civilianNegative'">
                    <table class="table" id="table-civilianNegative">
                        <thead>
                            <tr>
                                <th scope="col">№</th>
                                <th scope="col">Игрок</th>
                                <th scope="col">Выгнан днем/ молнирован на мирной роли (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(player, index) in playersFilteredAndSorted">
                                <td>@{{ index + 1 }}</td>
                                <td><a :href="player.routeLink">@{{ player.name }}</a></td>
                                <td>@{{ player.cityNegativeActionsRate }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="post" v-show="activeTab === 'mafiaSurvived'">
                    <table class="table" id="table-mafiaSurvived">
                        <thead>
                            <tr>
                                <th scope="col">№</th>
                                <th scope="col">Игрок</th>
                                <th scope="col">Прожито на мафии в среднем (дней)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(player, index) in playersFilteredAndSorted">
                                <td>@{{ index + 1 }}</td>
                                <td><a :href="player.routeLink">@{{ player.name }}</a></td>
                                <td>@{{ player.mafiaAverageDaysSurvived }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="post" v-show="activeTab === 'winstreak'">
                    <table class="table" id="table-winstreak">
                        <thead>
                            <tr>
                                <th scope="col">№</th>
                                <th scope="col">Игрок</th>
                                <th scope="col">Винстрик</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(player, index) in playersFilteredAndSorted">
                                <td>@{{ index + 1 }}</td>
                                <td><a :href="player.routeLink">@{{ player.name }}</a></td>
                                <td>@{{ player.statistics.maximal_winstreak }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
    
            </div>
        </div>
    </div>
</section>
@endsection