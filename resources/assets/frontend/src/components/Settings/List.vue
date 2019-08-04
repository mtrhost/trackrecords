<template lang="html">
	<section class="bdy content reset cflex">
		<div class="c100 blk __content-mount">
			<b-container fluid>
				<b-row mb="4" class="settings__filter-row">
					<b-col md="4" class="my-1">
						<div class="form-row">
							<input class="form-control" type="text" v-model="filter" placeholder="Введите поисковый запрос" />
						</div>
					</b-col>
					<b-col md="8" class="my-1 d-flex flex-row filter__stats">
						<b-col class="align-self-center text-center">
							Всего сеттингов в базе: {{ settings.length }}
						</b-col>
					</b-col>
				</b-row>
				<b-row mb="4">
					<b-table show-empty
							stacked="md"
							:items="settings"
							:fields="fields"
							:current-page="currentPage"
							:per-page="20"
							:filter="filter"
							:sort-by.sync="sortBy"
							:sort-desc.sync="sortDesc"
							:sort-direction="sortDirection"
							@filtered="onFiltered"
					>
						<span slot="info" slot-scope="data" @click="goToSettingDetail(data.value.id)" class="a-link">{{ data.value.name }}</span>
						<span slot="author" slot-scope="data" @click="goToPlayerDetail(data.value.id)" class="a-link">{{ data.value.name }}</span>
					</b-table>
				</b-row>
				<b-row class="mtr__pagination">
					<b-col md="6" class="my-1">
						<b-pagination :total-rows="settings.length" :per-page="20" v-model="currentPage" class="mtr__pagination-items" />
					</b-col>
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
		settings: [],
		fields: [
			{ key: 'id', label: '№', sortable: true },
			{ key: 'info', label: 'Название', sortable: true},
			{ key: 'players_count', label: 'Количество игроков', sortable: true },
			{ key: 'author', label: 'Автор', sortable: true },
		],
		currentPage: 1,
		sortBy: 'id',
		sortDesc: true,
		sortDirection: 'desc',
		filter: null,
      }
	},
    methods: {
        goToPlayerDetail: function(id){
			this.$router.push({name: 'PlayerDetail', params: { id: id }})
		},
		goToSettingDetail: function(id){
			this.$router.push({name: 'SettingDetail', params: { id: id }})
		},
	},
	beforeMount () {
		HTTP.post(Routes.getSettings)
			.then(response => {
				this.settings = response.data;
			})
			.catch(error => {
				console.error(error)
			})
	},
};
</script>

<style lang="scss" scoped>
	.settings__filter-row {
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
