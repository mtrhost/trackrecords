<template lang="html">
	<section class="bdy content reset cflex">
		<div class="c100 blk __content-mount">
			<b-row mb="4" class="page__header-row">
                <h1 class="mr-auto">Зал достижений</h1>
                <table class="table page__header-cells" v-if="achievements.length > 0">
                    <tbody>
                        <tr v-for="achievement in achievements">
                            <td class="align-middle">
                                <img class="achievements__main-image" :src="achievement.image_original" :alt="achievement.name">
                            </td>
                            <td class="align-middle">
                                {{ achievement.name }}
                            </td>
                            <td class="align-middle">
                                {{ achievement.condition }}
                            </td>
                            <td class="align-middle">
                                {{ achievement.description }}
                            </td>
                            <td class="align-middle">
                                <span v-if="player.id !== undefined" v-for="(player, index) in achievement.players">
                                    <router-link class="a-link" :to="{ name: 'PlayerDetail', params: { id: player.id }}" target="_blank">
                                        {{ player.name }}</router-link><span v-if="index < achievement.players.length - 1">, </span>
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </b-row>
		</div>
	</section>
</template>

<script>
import { HTTP, Routes } from "@/components/Routes/Routes";

export default {
    data () {
      return {
		achievements: []
      }
	},
    methods: {
        
	},
	beforeMount () {
		HTTP.post(Routes.getAchievements)
			.then(response => {
				this.achievements = response.data;
			})
			.catch(error => {
				console.error(error)
			})
	},
};
</script>

<style lang="scss" scoped>
    .achievements__main-image {
        width: 128px;
    }
</style>
