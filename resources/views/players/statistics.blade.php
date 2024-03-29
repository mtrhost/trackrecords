@extends('templates.app')
@section('title','Статистика')
@section('layout')
@section('footer-scripts')
    <script src="{{ url('js/vue.js') }}"></script>
    <script src="{{ url('js/vue-resource.js') }}"></script>
    <script src="{{ url('js/datatables.min.js') }}"></script>
    <script>
        new Vue({
            el: '#statistics',
            data: {
                activeTab: 'gamesCount',
                players: <?= json_encode($statistics); ?>,
                datatable: null
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
                        case 'activeSurvived':
                            return this.playersByActiveAverageSurvivedDays();
                        break;
                        case 'mafiaSurvived':
                            return this.playersByMafiaAverageSurvivedDays();
                        break;
                        case 'neutralSurvived':
                            return this.playersByNeutralAverageSurvivedDays();
                        break;
                        case 'winstreak':
                            return this.playersByMaximalWinstreak();
                        break;
                        case 'caesar':
                            return this.playersByAchievements();
                        break;
                    }
                }
            },
            methods: {
                setActiveTab: function (menuItem) {
                    this.activeTab = menuItem
                },
                filterAndSort: function(filterKeys, sortKeys, range, sortOrder, excludeInactive) {
                    if(sortOrder === undefined) sortOrder = 'desc';
                    return this.players
                        .filter(function (player, index) {
                            if (excludeInactive === true && player.isActive === false) {
                                return false;
                            }
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
                    return this.filterAndSort(['gamesCountWoMastered'], ['winrate'], 30, 'desc', true);
                },
                playersByWinrateNoRole: function() {
                    return this.filterAndSort(['statistics', 'games_count_no_role'], ['winrate_no_role'], 15, 'desc', true);
                },
                playersByWinrateActive: function() {
                    return this.filterAndSort(['statistics', 'games_count_active'], ['winrate_active'], 15, 'desc', true);
                },
                playersByWinrateCivilian: function() {
                    return this.filterAndSort(['statistics', 'civilian_games_count'], ['winrateCivilian'], 20, 'desc', true);
                },
                playersByWinrateMafia: function() {
                    return this.filterAndSort(['statistics', 'games_count_mafia'], ['winrate_mafia'], 10, 'desc', true);
                },
                playersByWinrateNeutral: function() {
                    return this.filterAndSort(['statistics', 'games_count_neutral'], ['winrate_neutral'], 4, 'desc', true);
                },
                playersByRoleRate: function() {
                    return this.filterAndSort(['gamesCountWoMastered'], ['roleRate'], 30, 'desc', true);
                },
                playersByCivilianNegativeActions: function() {
                    return this.filterAndSort(['statistics', 'civilian_games_count'], ['cityNegativeActionsRate'], 30, 'asc', true);
                },
                playersByActiveAverageSurvivedDays: function() {
                    return this.filterAndSort(['statistics', 'games_count_active'], ['activeAverageDaysSurvived'], 10, 'desc', true);
                },
                playersByMafiaAverageSurvivedDays: function() {
                    return this.filterAndSort(['statistics', 'games_count_mafia'], ['mafiaAverageDaysSurvived'], 10, 'desc', true);
                },
                playersByNeutralAverageSurvivedDays: function() {
                    return this.filterAndSort(['statistics', 'games_count_neutral'], ['neutralAverageDaysSurvived'], 5, 'desc', true);
                },
                playersByMaximalWinstreak: function() {
                    return this.filterAndSort(['gamesCountWoMastered'], ['statistics', 'maximal_winstreak'], 30, 'desc', true);
                },
                playersByAchievements: function() {
                    return this.filterAndSort(['achievements_count'], ['achievements_count'], 3);
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
                this.datatable = $("#table-" + this.activeTab).DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Russian.json"
                    },
                    "bLengthChange": false,
                    "pageLength": 25
                });
            },
            watch: {
                playersFilteredAndSorted: function (newValue) {
                    var self = this;
                    this.datatable.destroy();
                    self.$nextTick(function () {
                        this.datatable = $("#table-" + self.activeTab).DataTable({
                            "language": {
                                "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Russian.json"
                            },
                            "bLengthChange": false,
                            "pageLength": 25
                        });
                    })
                }
            }
        });
    </script>
@endsection
<section id="statistics" class="container-fluid">
    <div class="row">
        <div class="col-lg-2 col-sm-12 hidden-sm-down">
            <div class="nav nav-pills flex-column">
                <ul class="nav flex-column sidebar-menu">
                    <li class="nav-item" data-tab="gamesCount"><a class="nav-link" :class="activeTab === 'gamesCount' ? 'active' : ''" @click="setActiveTab('gamesCount');">Количество игр</a></li>
                    <li class="nav-item" data-tab="lightningsCount"><a @click="setActiveTab('lightningsCount');" class="nav-link" :class="activeTab === 'lightningsCount' ? 'active' : ''">Количество молний</a></li>
                    <li class="nav-item" data-tab="winrate"><a @click="setActiveTab('winrate');" class="nav-link" :class="activeTab === 'winrate' ? 'active' : ''">Общий винрейт</a></li>
                    <li class="nav-item" data-tab="winrateNoRole"><a @click="setActiveTab('winrateNoRole');" class="nav-link" :class="activeTab === 'winrateNoRole' ? 'active' : ''">Винрейт (мж)</a></li>
                    <li class="nav-item" data-tab="winrateActive"><a @click="setActiveTab('winrateActive');" class="nav-link" :class="activeTab === 'winrateActive' ? 'active' : ''">Винрейт (актив)</a></li>
                    <li class="nav-item" data-tab="winrateCivilian"><a @click="setActiveTab('winrateCivilian');" class="nav-link" :class="activeTab === 'winrateCivilian' ? 'active' : ''">Винрейт (мирный)</a></li>
                    <li class="nav-item" data-tab="winrateMafia"><a @click="setActiveTab('winrateMafia');" class="nav-link" :class="activeTab === 'winrateMafia' ? 'active' : ''">Винрейт (мафия)</a></li>
                    <li class="nav-item" data-tab="winrateNeutral"><a @click="setActiveTab('winrateNeutral');" class="nav-link" :class="activeTab === 'winrateNeutral' ? 'active' : ''">Винрейт (нейтрал)</a></li>
                    <li class="nav-item" data-tab="roleRate"><a @click="setActiveTab('roleRate');" class="nav-link" :class="activeTab === 'roleRate' ? 'active' : ''">Роль</a></li>
                    <li class="nav-item" data-tab="civilianNegative"><a @click="setActiveTab('civilianNegative');" class="nav-link" :class="activeTab === 'civilianNegative' ? 'active' : ''">Подвел на мирном</a></li>
                    <li class="nav-item" data-tab="activeSurvived"><a @click="setActiveTab('activeSurvived');" class="nav-link" :class="activeTab === 'activeSurvived' ? 'active' : ''">Прожито на активе</a></li>
                    <li class="nav-item" data-tab="mafiaSurvived"><a @click="setActiveTab('mafiaSurvived');" class="nav-link" :class="activeTab === 'mafiaSurvived' ? 'active' : ''">Прожито на мафии</a></li>
                    <li class="nav-item" data-tab="neutralSurvived"><a @click="setActiveTab('neutralSurvived');" class="nav-link" :class="activeTab === 'neutralSurvived' ? 'active' : ''">Прожито на нейтрале</a></li>
                    <li class="nav-item" data-tab="winstreak"><a @click="setActiveTab('winstreak');" class="nav-link" :class="activeTab === 'winstreak' ? 'active' : ''">Винстрик</a></li>
                    <li class="nav-item" data-tab="caesar"><a @click="setActiveTab('caesar');" class="nav-link" :class="activeTab === 'caesar' ? 'active' : ''">Ачивок</a></li>
                </ul>
            </div>
        </div>

        <div class="col-lg-10 filter-results card custom-card table-responsive">
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

            <div class="post" v-show="activeTab === 'activeSurvived'">
                <table class="table" id="table-activeSurvived">
                    <thead>
                        <tr>
                            <th scope="col">№</th>
                            <th scope="col">Игрок</th>
                            <th scope="col">Прожито на активе в среднем (дней)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(player, index) in playersFilteredAndSorted">
                            <td>@{{ index + 1 }}</td>
                            <td><a :href="player.routeLink">@{{ player.name }}</a></td>
                            <td>@{{ player.activeAverageDaysSurvived }}</td>
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

            <div class="post" v-show="activeTab === 'neutralSurvived'">
                <table class="table" id="table-neutralSurvived">
                    <thead>
                        <tr>
                            <th scope="col">№</th>
                            <th scope="col">Игрок</th>
                            <th scope="col">Прожито на нейтрале в среднем (дней)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(player, index) in playersFilteredAndSorted">
                            <td>@{{ index + 1 }}</td>
                            <td><a :href="player.routeLink">@{{ player.name }}</a></td>
                            <td>@{{ player.neutralAverageDaysSurvived }}</td>
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

            <div class="post" v-show="activeTab === 'caesar'">
                <table class="table" id="table-caesar">
                    <thead>
                        <tr>
                            <th scope="col">№</th>
                            <th scope="col">Игрок</th>
                            <th scope="col">Ачивок</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(player, index) in playersFilteredAndSorted">
                            <td>@{{ index + 1 }}</td>
                            <td><a :href="player.routeLink">@{{ player.name }}</a></td>
                            <td>@{{ player.achievements_count }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</section>
@endsection