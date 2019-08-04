<template lang="html">
    <b-container fluid>
        <b-row mb="4" class="page__header-row" v-if="showFilter">
            <b-col md="4" class="my-1">
                <div class="form-row">
                    <input class="form-control" type="text" v-model="filter" placeholder="Введите поисковый запрос" />
                </div>
            </b-col>
            <b-col md="8" class="my-1 d-flex flex-row page__header-cells">
                <b-col class="align-self-center text-center">
                    Всего игр: {{ statistics.gamesCount }}
                </b-col>
                <b-col class="align-self-center text-center">
                    Побед города: {{ statistics.cityWins }}
                </b-col>
                <b-col class="align-self-center text-center">
                    Побед мафии: {{ statistics.mafiaWins }}
                </b-col>
                <b-col class="align-self-center text-center">
                    Побед маньяков: {{ statistics.neutralWins }}
                </b-col>
                <b-col cols="3" class="align-self-center text-center">
                    Прочее (ничьи / failed и т.д.): {{ statistics.failed }}
                </b-col>
            </b-col>
        </b-row>
        <b-row mb="4">
			 <b-table v-if="!playerDetail"
					show-empty
                    stacked="md"
                    :items="games"
                    :fields="fields"
                    :current-page="currentPage"
                    :per-page="20"
                    :filter="filter"
                    :sort-by.sync="sortBy"
                    :sort-desc.sync="sortDesc"
                    :sort-direction="sortDirection"
                    @filtered="onFiltered"
            >
                <span slot="info" slot-scope="data" @click="goToGameDetail(data.value.id)" class="a-link">{{ data.value.name }}</span>
                <span slot="master" slot-scope="data" @click="goToPlayerDetail(data.value.id)" class="a-link">{{ data.value.name }}</span>
                <div slot="winnersString" slot-scope="data" v-html="data.value"></div>
            </b-table>
            <b-table v-else
					show-empty
                    stacked="md"
                    :items="games"
                    :fields="fields"
                    :current-page="currentPage"
                    :per-page="20"
                    :filter="filter"
                    :sort-by.sync="sortBy"
                    :sort-desc.sync="sortDesc"
                    :sort-direction="sortDirection"
                    @filtered="onFiltered"
            >
                <span slot="info" slot-scope="data" @click="goToGameDetail(data.value.id)" class="a-link">{{ data.value.name }}</span>
                <span slot="master" slot-scope="data" @click="goToPlayerDetail(data.value.id)" class="a-link">{{ data.value.name }}</span>
				<div slot="role" slot-scope="data" v-html="data.item.roleString"></div>
				<div slot="status" slot-scope="data" v-html="data.item.statusString"></div>
            </b-table>
        </b-row>
        <b-row class="mtr__pagination">
            <b-col md="6" class="my-1">
                <b-pagination :total-rows="statistics.gamesCount" :per-page="20" v-model="currentPage" class="mtr__pagination-items" />
            </b-col>
        </b-row>

    </b-container>
</template>

<script>
import { HTTP, Routes } from "@/components/Routes/Routes";

export default {
    data () {
      return {
		fields: [
			{ key: 'number', label: '№', sortable: true },
			{ key: 'info', label: 'Название'},
			{ key: 'master', label: 'Ведущий', sortable: true },
		],
		statistics: {
			gamesCount: 0,
			cityWins: 0,
			mafiaWins: 0,
			neutralWins: 0,
		},
		currentPage: 1,
		sortBy: 'number',
		sortDesc: true,
		sortDirection: 'desc',
		filter: null,
      }
    },
    props: {
		showFilter: {
			type: Boolean,
			required: false,
			default: true
        },
		playerDetail: {
			type: Boolean,
			required: false,
			default: false
        },
        games: {
            type: Array,
            required: true
        }
	},
	computed: {
		sortOptions () {
			return this.fields
				.filter(f => f.sortable)
				.map(f => { return { text: f.label, value: f.key } })
		}
	},
    methods: {
        onFiltered (filteredItems) {
			this.countFilterStatistics(filteredItems);
			this.currentPage = 1
		},
		countFilterStatistics: function(games) {
			this.statistics.gamesCount = games.length;
			this.statistics.cityWins = this.countFactionsWinsCount(games, ['no-role', 'active']);
			this.statistics.mafiaWins = this.countFactionsWinsCount(games, ['mafia']);
			this.statistics.neutralWins = this.countFactionsWinsCount(games, ['neutral']);
			this.statistics.failed = games.filter(function(value){
				return value.status !== '';
			}).length
		},
		countFactionsWinsCount: function(games, factions) {
			return games.filter(function(value){
				if(value.status === '') {
					for(var key in value.winners) {
						if(factions.indexOf(value.winners[key].faction.group.alias) >= 0) {
							return true;
						}
					}
				}
				return false;
			}).length
		},
		goToPlayerDetail: function (id) {
            this.$router.push({name: 'PlayerDetail', params: { id: id }})
		},
		goToGameDetail: function (id) {
            this.$router.push({name: 'GameDetail', params: { id: id }})
        }
    },
    beforeMount () {
        console.log(JSON.stringify(this.games));
		this.countFilterStatistics(this.games);
		if(this.playerDetail) {
			this.fields.push({ key: 'role', label: 'Роль'});
			this.fields.push({ key: 'status', label: 'Результат'});
		} else {
			this.fields.push({ key: 'winnersString', label: 'Победители', sortable: true});
		}
	},
};
</script>

<style lang="scss" scoped>
	.games__filter-row {
		padding: 30px 25px 25px;
		background-color: #374044;
		border-bottom: 3px solid #f98c13;
		input, select {
			background-color: #2d363b;
			border: 1px solid #0094d9;
			color: #fff;
		}
		.filter__stats {
			color: #bcbcbc;
			background-color: #2d363b;
			border: 1px solid #0094d9;
			padding-left: 0;
			padding-right: 0;
			.col {
				padding: 8px 0px 8px 0px;
				border-right: 1px solid #0094d9;
			}
		}
	}
</style>
