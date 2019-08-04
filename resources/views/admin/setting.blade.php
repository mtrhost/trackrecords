@extends('layouts.admin')

@section('content')
<section class="bdy content reset cflex">
    <div class="c100 blk __content-mount">
        <div class="container-fluid admin-container">
            <div class="row d-flex justify-content-center">
                <div class="col-8 mt-4" id="setting">
                    <h3>Сеттинг</h3>
                    <form action="{{ route('admin.setting.save') }}" @submit.prevent="saveSetting">
                        {{ csrf_field() }}
                        <div class="bordered-form page__header-row">
                            <div class="form-group">
                                <input class="form-control" placeholder="Название" name="setting[name]" value="" v-model="setting.name">
                            </div>
                            <div class="form-group form-inline">
                                <div class="w-50">
                                    <input class="form-control w-100" placeholder="Кол-во игроков" name="setting[players_count]" value="" v-model.number="setting.players_count">
                                </div>
                                <div class="w-50">
                                    <input class="form-control w-100 typeahead-author" placeholder="Автор" name="setting[author_name]" value="">
                                </div>
                            </div>
                            <!--v-for="faction in factions" :class="'faction-' + faction.alias"-->
                            <div v-for="role in newRoles" class="form-group row">
                                <div class="col-5">
                                    <input ref="roleName" class="form-control" placeholder="Роль" :name="'setting[roles][' + role + '][role_name]'" value="" data-faction="">
                                    <input ref="roleId" type="hidden" :name="'setting[roles][' + role + '][role_id]'" value="">
                                </div>
                                <div class="col-5">
                                    <select data-faction="" class="form-control overwhelm-input" placeholder="Фракция" :name="'setting[roles][' + role + '][faction_id]'" value="" @change="setBlockFaction($event, role-1)">
                                        <option v-for="faction in factions" :value="faction.id" :data-alias="faction.alias">@{{ faction.name }}</option>
                                    </select>
                                </div>
                                <a v-if="newRoles === role" class="new-input-icon" @click="addRoleBlock">
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
            el: '#setting',
            data: {
                players: {!! json_encode($players) !!},
                roles: {!! json_encode($roles) !!},
                factions: {!! json_encode($factions) !!},
                newRoles: 1,
                setting: {
                    _token: '{{ csrf_token() }}'
                }
            },
            methods: {
                setBlockFaction: function(e, role) {
                    let faction = e.target.querySelector('option:checked').dataset.alias;
                    let changedInputs = e.target.closest('.form-group').querySelectorAll('input:not([type="hidden"]), select');
                    for(var i in changedInputs) {
                        if(changedInputs[i].dataset !== undefined) {
                            changedInputs[i].dataset.faction = faction;
                        }
                    }

                    this.setSettingRoleParam(role, 'faction_id', e.target.value);
                },
                addRoleBlock: function() {
                    this.newRoles+=1;
                    console.log(this.setting.name);
                    Vue.nextTick(() => {
                        this.setRoleAwesomplete(this.newRoles);
                    });
                },
                saveSetting: function(e) {
                    var form = e.target;
                    this.$http
                        .post(form.action, this.setting)
                        .then(response => {
                            if(response.data) {
                                this.successIziToast('Сеттинг успешно сохранена');
                            } else {
                                this.whoopsIziToast('Ошибка при сохранении сеттинга');
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
                setRoleAwesomplete: function(roleId) {
                    var self = this;
                    let role = this.$refs.roleName[roleId-1];
                    new Awesomplete(role, {
                        minChars: 2,
                        list: this.roles.map(function(val){ return {label: val.name, value: val.id}; }),
                    });
                    role.addEventListener('awesomplete-selectcomplete', function(event) {
                        event.preventDefault();
                        self.$refs.roleId[roleId-1].value = event.text.value;
                        self.setSettingRoleParam(roleId-1, 'role_id', event.text.value);
                        event.target.value = event.text.label;
                    });
                },
                setSettingRoleParam: function(index, innerKey, value) {
                    if(this.setting.roles === undefined) {
                        Vue.set(this.setting, 'roles', []);
                    }
                    if(this.setting.roles[index] === undefined) {
                        Vue.set(this.setting.roles, index, {[innerKey]: value, 'faction_id': 1});
                    } else {
                        Vue.set(this.setting.roles[index], innerKey, value);
                    }
                }
            },
            mounted () {
                var self = this;
                let author = document.querySelector('.typeahead-author');
                new Awesomplete(author, {
                    minChars: 2,
                    list: this.players.map(function(val){ return {label: val.name, value: val.id}; }),
                });
                author.addEventListener('awesomplete-selectcomplete', function(event) {
                    event.preventDefault();
                    Vue.set(self.setting, 'author_id', parseInt(event.text.value));
                    event.target.value = event.text.label;
                });

                this.setRoleAwesomplete(this.newRoles);
            },
        })
    </script>
@endsection