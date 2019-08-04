<template lang="html">
	<section class="bdy content reset cflex">
		<div class="c100 blk __content-mount">
			<GamesTable :games="games"></GamesTable>
		</div>
	</section>
</template>

<script>
import { HTTP, Routes } from "@/components/Routes/Routes";
import GamesTable from "@/components/Games/Modules/Table";

export default {
    data () {
      return {
		games: [],
      }
	},
	components: {
        GamesTable
    },
    methods: {
        
	},
	beforeMount () {
		HTTP.post(Routes.getGames)
			.then(response => {
				this.games = response.data;
			})
			.catch(error => {
				console.error(error)
			})
	},
};
</script>

<style lang="scss" scoped>
	
</style>
