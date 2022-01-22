@extends('layouts.admin')

@section('content')
<section class="bdy content reset cflex">
    <div class="c100 blk __content-mount">
        <div class="container-fluid admin-container">
            <div class="row d-flex justify-content-center">
                <div class="col-8 mt-4" id="game">
                    <h3>Игра</h3>
                    <form action="{{ route('admin.game.save') }}" @submit.prevent="saveGame">
                        {{ csrf_field() }}
                        <div class="bordered-form page__header-row">
                            <div class="form-group">
                                <input class="form-control" placeholder="Название" name="game[name]" value="" v-model="game.name">
                            </div>
                            <div class="form-group form-inline">
                                <div class="w-50">
                                    <input class="form-control w-100" ref="setting" placeholder="Сеттинг" name="game[setting_name]" value="">
                                </div>
                                <div class="w-50">
                                    <select class="form-control w-100" placeholder="Фракция" v-model="game.winners" value="" multiple>
                                        <option v-for="faction in factions" :value="faction.id" :data-alias="faction.alias" :data-group="faction.group_id">@{{ faction.name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-inline">
                                <div class="w-50">
                                    <input class="form-control w-100" placeholder="Номер" name="game[number]" value="" v-model.number="game.number">
                                </div>
                                <div class="w-50">
                                    <input class="form-control w-100" placeholder="Длительность" name="game[length]" value="" v-model.number="game.length">
                                </div>
                            </div>
                            <div class="form-group form-inline">
                                <div class="w-50">
                                    <input class="form-control w-100" ref="master" placeholder="Ведущий" name="game[master]" value="">
                                </div>
                                <div class="w-50">
                                    <input class="form-control w-100" placeholder="Дата" name="game[date]" value="" v-model="game.date">
                                </div>
                            </div>
                            <div class="form-group form-inline">
                                <div class="w-50">
                                    <input class="form-control w-100" placeholder="Ссылка" name="game[link]" value="" v-model="game.link">
                                </div>
                                <div class="w-50">
                                    <select class="form-control w-100" placeholder="Статус" name="game[status]" v-model="game.status">
                                        <option v-for="status in gameStatuses" :value="status.id">@{{ status.value }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-inline">
                                <div class="w-25">
                                    <input class="form-control w-100" placeholder="Игроков" name="game[players_count]" value="" v-model.number="game.players_count">
                                </div>
                                <div class="w-25">
                                    <input class="form-control w-100" placeholder="Активов" name="game[active_count]" value="" v-model.number="game.active_count">
                                </div>
                                <div class="w-25">
                                    <input class="form-control w-100" placeholder="Мафии" name="game[mafia_count]" value="" v-model.number="game.mafia_count">
                                </div>
                                <div class="w-25">
                                    <input class="form-control w-100" placeholder="Нейтралов" name="game[neutral_count]" value="" v-model.number="game.neutral_count">
                                </div>
                            </div>
                            <div v-for="role in newGameRoles" class="form-group row">
                                <div class="col-2">
                                    <input data-faction="" ref="player" class="form-control" placeholder="Игрок" :name="'game[roles][' + role + '][player_name]'" value="" data-faction="">
                                </div>
                                <div class="col-2" v-if="game.roles !== undefined && game.roles[role-1] !== undefined && game.roles[role-1].availableRoles !== undefined">
                                    <select data-faction="" class="form-control" placeholder="Роль" :name="'game[roles][' + role-1 + '][role_id]'" value="" v-model.number="game.roles[role-1].role_id">
                                        <option v-for="roles in game.roles[role-1].availableRoles" :value="roles.role.id">@{{ roles.role.name }}</option>
                                    </select>
                                </div>
                                <div class="col-2">
                                    <select data-faction="" class="form-control" placeholder="Фракция" :name="'game[roles][' + role + '][faction_id]'" value="" @change="setBlockFaction($event, role-1)">
                                        <option v-for="faction in factions" :value="faction.id" :data-alias="faction.alias" :data-group="faction.group_id">@{{ faction.name }}</option>
                                    </select>
                                </div>
                                <div class="col-2">
                                    <select class="form-control overwhelm-input" placeholder="Статус" :name="'game[roles][' + role + '][status_id]'" value="" v-model.number="game.roles[role-1].status_id">
                                        <option v-for="status in statuses" :value="status.id">@{{ status.name }}</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <input class="form-control" placeholder="День" :name="'game[roles][' + role + '][day]'" value="" data-faction="" v-model.number="game.roles[role-1].day">
                                </div>
                                <div class="col-2">
                                    <select class="form-control overwhelm-input" placeholder="Время суток" :name="'game[roles][' + role + '][time_status_id]'" value="" v-model.number="game.roles[role-1].time_status_id">
                                        <option v-for="timeStatus in timeStatuses" :value="timeStatus.id">@{{ timeStatus.name }}</option>
                                    </select>
                                </div>
                                <a v-if="newGameRoles === role" class="new-input-icon" @click="addRoleBlock">
                                    <span class="icon-medium icons-add-new-medium"></span>
                                </a>
                            </div>
                            <button type="submit" class="btn btn-primary">Сохранить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('footer-scripts')
    <script>
        new Vue({
            el: '#game',
            data: {
                settings: {!! json_encode($settings) !!},
                players: {!! json_encode($players) !!},
                factions: {!! json_encode($factions) !!},
                statuses: {!! json_encode($statuses) !!},
                timeStatuses: {!! json_encode($timeStatuses) !!},
                gameStatuses: {!! json_encode($gameStatuses) !!},
                newGameRoles: 0,
                game: {
                    _token: '{{ csrf_token() }}',
                    'winners': []
                },
                setting: {}
            },
            watch: {
                game: {
                    handler: function (val){
                        if(val.setting_id !== undefined && this.newGameRoles === 0) {
                            this.newGameRoles = 1;
                            this.setting = this.settings.find(function(value){
                                return value.id === val.setting_id;
                            });
                            Vue.set(this.game, 'roles', {0: {'availableRoles': this.setting.setting_roles.filter(function(value){ return value.faction.alias === 'no-role'; })}});
                            Vue.nextTick(() => {
                                let player = this.$refs.player[0];
                                this.initializeAwesomplete(player, 'players', 'player_id');
                                this.setGameRoleParam(this.newGameRoles-1, 'faction_id', 1);
                                this.setGameRoleParam(this.newGameRoles-1, 'role_id', 1);
                            });
                        }
                    },
                    deep: true
                }
            },
            methods: {
                saveGame: function(e) {
                    var form = e.target;
                    // if (form.request_json.value !== "") {
                    //     var data = form.request_json.value;
                    // } else {
                    //     var data = this.game;
                    // }
                    var data = this.game;
                    this.$http
                        .post(form.action, data)
                        .then(response => {
                            if(response.data) {
                                this.successIziToast('Игра успешно сохранена');
                            } else {
                                this.whoopsIziToast('Ошибка при сохранении игры');
                            }
                        }).catch((err) => {
                            console.log(error);
                            this.whoopsIziToast('Ошибка при сохранении');
                        })
                },
                successIziToast: function(message) {
                    iziToast.show({
                        title: '',
                        color: 'green',
                        message: message,
                        position: 'topRight',
                        timeout: 3000,
                        onClosed: function () {
                            window.location.reload(true);
                        }
                    });
                },      
                whoopsIziToast: function(message){
                    var params = {
                        title: 'Whoops: Что-то пошло не так',
                        color: 'red',
                        message: message,
                        position: 'topRight',
                        timeout: 5000
                    };
                    iziToast.show(params);
                },
                setBlockFaction: function(e, role) {
                    let faction = e.target.querySelector('option:checked').dataset.alias;
                    let group = e.target.querySelector('option:checked').dataset.group;
                    let changedInputs = e.target.closest('.form-group').querySelectorAll('input:not([type="hidden"]), select');
                    for(var i in changedInputs) {
                        if(changedInputs[i].dataset !== undefined) {
                            changedInputs[i].dataset.faction = faction;
                        }
                    }

                    this.setGameRoleParam(role, 'faction_id', e.target.value);
                    this.applyAvailableRoles(faction, group, role);
                },
                addRoleBlock: function() {
                    Vue.set(this.game.roles, this.newGameRoles, {'availableRoles': this.setting.setting_roles.filter(function(value){ return value.faction.alias === 'no-role'; })});
                    console.log(JSON.stringify(this.game));
                    this.newGameRoles+=1;
                    Vue.nextTick(() => {
                        let player = this.$refs.player[this.newGameRoles-1];
                        this.initializeAwesomplete(player, 'players', 'player_id', this.newGameRoles-1);
                        this.setGameRoleParam(this.newGameRoles-1, 'faction_id', 1);
                        this.setGameRoleParam(this.newGameRoles-1, 'role_id', 1);
                    });
                },
                initializeAwesomplete: function(dom, dataArrayName, outputDataName) {
                    var self = this;
                    new Awesomplete(dom, {
                        minChars: 2,
                        list: this[dataArrayName].map(function(val){ return {label: val.name, value: val.id}; }),
                    });
                    dom.addEventListener('awesomplete-selectcomplete', function(event) {
                        event.preventDefault();
                        if(outputDataName === 'player_id') {
                            self.setGameRoleParam(self.newGameRoles-1, 'player_id', event.text.value);
                        } else {
                            Vue.set(self.game, outputDataName, parseInt(event.text.value));
                        }
                        event.target.value = event.text.label;
                    });
                },
                setGameRoleParam: function(index, innerKey, value) {
                    if(this.game.roles === undefined) {
                        Vue.set(this.game, 'roles', []);
                    }
                    if(this.game.roles[index] === undefined) {
                        Vue.set(this.game.roles, index, {[innerKey]: value});
                    } else {
                        Vue.set(this.game.roles[index], innerKey, value);
                    }
                },
                applyAvailableRoles: function(faction, group, index) {
                    Vue.set(this.game.roles[index], 'availableRoles', this.setting.setting_roles.filter(function(value){
                        if(parseInt(group) === 4) {
                            return parseInt(value.faction.group_id) === parseInt(group);
                        } else {
                            return value.faction.alias === faction; 
                        }
                    }));
                }
            },
            mounted () {
                let setting = this.$refs.setting;
                this.initializeAwesomplete(setting, 'settings', 'setting_id');

                let master = this.$refs.master;
                this.initializeAwesomplete(master, 'players', 'master_id');

                //this.setRoleAwesomplete(this.newRoles);
            },
        })
    </script>
@endsection