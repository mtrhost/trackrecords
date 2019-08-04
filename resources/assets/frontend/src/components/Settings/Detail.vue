<template lang="html">
    <section class="bdy content reset cflex" v-if="typeof setting !== 'undefined'">
		<div class="c100 blk __content-mount">
            <b-row mb="4" class="page__header-row">
                <h1 class="mr-auto">{{ setting.name }}</h1>
                <b-col md="8" class="my-1 d-flex flex-row page__header-cells mb-4">
                    <b-col class="align-self-center text-center" v-if="setting.author !== undefined">
                        Автор: {{ setting.author.name }}
                    </b-col>
                    <b-col class="align-self-center text-center">
                        Игроков: {{ setting.players_count }}
                    </b-col>
                </b-col>
                <table class="table page__header-cells" v-if="setting.rolesSorted !== undefined">
                    <tbody>
                        <tr v-for="roleGroup in setting.rolesSorted">
                            <td>
                                {{ roleGroup[0].faction.name }}
                            </td>
                            <td>
                                <span v-for="(role, index) in roleGroup" :class="'faction-' + role.faction.alias" v-if="role.role !== undefined">
                                    {{ role.role.name }}<span v-if="index < roleGroup.length - 1">, </span>
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </b-row>
            <h3>Игры по сеттингу</h3>
			<GamesTable v-if="setting.games !== undefined" :games="setting.games" :showFilter="false"></GamesTable>
		</div>
	</section>
</template>

<script>
import { HTTP, Routes } from "@/components/Routes/Routes";
import GamesTable from "@/components/Games/Modules/Table";

export default {
    data () {
        return {
            setting: []
        }
    },
    components: {
        GamesTable
    },
    methods: {
        getSetting: function(id) {
            HTTP.post(Routes.getSetting, {id: id})
                .then(response => {
                    this.setting = response.data;
                })
                .catch(error => {
                    console.error(error)
                })
        },
        goToPlayer: function(id){
            this.$router.push({name: 'PlayerDetail', params: { id: id }})
        }
	},
	created () {
        this.getSetting(this.$route.params.id);
	},
};
</script>

<style lang="scss" scoped>

</style>
