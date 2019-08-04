<template lang="html">
    <section class="bdy content reset cflex">
		<div class="c100 blk __content-mount">
			<b-container fluid>
				<!-- User Interface controls -->
				<b-row mb="4" class="page__header-row">
                    <div class="col-3">
                        <table class="table page__header-cells" v-if="game.length > 0">
                            <tbody>
                                <tr>
                                    <td scope="col">№</td>
                                    <td scope="col">{{ game.number }}</td>
                                </tr>
                                <tr>
                                    <td scope="col">Название</td>
                                    <td scope="col">{{ game.name }}</td>
                                </tr>
                                <tr>
                                    <td scope="col">Сеттинг</td>
                                    <td scope="col">
                                        <router-link class="a-link" :to="{ name: 'SettingDetail', params: { id: game.setting.id }}" target="_blank">
                                            {{ game.setting.name }}
                                        </router-link>
                                    </td>
                                </tr>
                                <tr>
                                    <td scope="col">Ведущий</td>
                                    <td scope="col">
                                        <router-link class="a-link" :to="{ name: 'PlayerDetail', params: { id: game.master.id }}" target="_blank">
                                            {{ game.master.name }}
                                        </router-link>
                                    </td>
                                </tr>
                                <tr>
                                    <td scope="col">Дата начала</td>
                                    <td scope="col">{{ game.date }}</td>
                                </tr>
                                <tr>
                                    <td scope="col">Длительность</td>
                                    <td scope="col">{{ game.length + ' дней' }}</td>
                                </tr>
                                <tr>
                                    <td scope="col" colspan="2"><a :href="game.link" class="a-link">Ссылка на игру</a></td>
                                </tr>
                                <tr>
                                    <td scope="col">Кол-во игроков</td>
                                    <td scope="col">{{ game.players_count }}</td>
                                </tr>
                                <tr>
                                    <td scope="col">Фракция победитель</td>
                                    <td scope="col">
                                        <span v-for="(winner, index) in game.winners" :class="'faction-' + winner.faction.alias">
                                            {{ winner.faction.name }}<span v-if="index == winner.length - 1">, </span>
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-9">
                        <table class="table page__header-cells" v-if="game.length > 0">
                            <thead>
                                <tr>
                                    <th>№</th>
                                    <th>Игрок</th>
                                    <th>Роль</th>
                                    <th>Статус</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(role, index) in game.rolesSorted">
                                    <td>{{ index + 1 }}</td>
                                    <td>
                                        <router-link class="a-link" :to="{ name: 'PlayerDetail', params: { id: role.player.id }}" target="_blank">
                                            {{ role.player.name }}
                                        </router-link>
                                    </td>
                                    <td><span :class="'faction-' + role.faction.alias">{{ role.role.name }}</span></td>
                                    <td>{{ role.status.name }}<span v-if="role.day !== null">{{ ' ' + role.day }}</span><span v-if="role.time_status !== null">{{ ' ' + role.time_status.name }}</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
				</b-row>
			</b-container>
		</div>
	</section>
</template>

<script>
import { HTTP, Routes } from "@/components/Routes/Routes";

export default {
    data () {
        return {
            game: []
        }
    },
    methods: {
        getGame: function(id) {
            HTTP.post(Routes.getGame, {id: id})
                .then(response => {
                    this.game = response.data;
                })
                .catch(error => {
                    console.error(error)
                })
        },
        goToPlayer: function(id){
            this.$router.push({name: 'PlayerDetail', params: { id: id }})
        },
        goToSetting: function(id){
            this.$router.push({name: 'SettingDetail', params: { id: id }})
        }
	},
	created () {
        this.getGame(this.$route.params.id);
	},
};
</script>

<style lang="scss" scoped>

</style>
