<template lang="html">
	<section class="bdy content reset cflex">
		<div class="c100 blk __content-mount">
			<div class="blk tab-group">
				<div class="blk tab dark">
					<ul class="lst left" v-for="(players, tab) in playersGroups" :key="tab" data-role="tabs">
						<li :class="{ 'on': isActive(tab) }" v-on:click="setActive(tab)">
                            <span class="tab-text">{{ players[0].sort_letter }}</span>
                        </li>
					</ul>
				</div>
                <!--<div class="blk tab dark secondary">
					<ul class="lst left " data-role="tabs">
						<li class="on"><span class="tab-text">Все</span></li>
						<li class=""><span class="tab-text">Активные</span></li>
						<li class=""><span class="tab-text">Неактивные</span></li>
					</ul>
				</div>-->
                <div class="players__list-container">
                    <isotope ref="cpt" v-images-loaded:on.progress="layout" :list="[]" :options="{}">
                        <div class="players__image-container" v-for="player in playersGroups[activeTab]" :key="player.id"  @click="goToDetail(player.id)">
                            <img :src="player.profileImage" alt="Not found" class="players__image-item">
                            <div class="players__image-overlay" v-bind:class="{ 'low-games-count': player.low_games_count }">
                                <div class="players__nickname">{{ player.name }}</div>
                                <div class="players__info container">
                                    <div class="row">
                                        <div class="players__last-game col-sm">
                                            <div class="text-center align-middle">
                                                <span :class="player.low_games_count ? 'hourglass-icon-red' : 'hourglass-icon'" class="icon"/>
                                                {{ player.last_game }}
                                            </div>
                                        </div>
                                        <div class="players__winrate col-sm">
                                            <div class="text-center align-middle">
                                                <span :class="player.low_games_count ? 'award-icon-red' : 'award-icon'" class="icon"/>
                                                {{ player.winrate }}%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </isotope>
                </div>
			</div>
		</div>
		<ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="ui-id-2" tabindex="0" style="display: none;"></ul>
		<ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="ui-id-4" tabindex="0" style="display: none;"></ul>
	</section>
</template>

<script>
import { HTTP, Routes } from "@/components/Routes/Routes";
import imagesLoaded from 'vue-images-loaded';
import isotope from 'vueisotope';

export default {
    data () {
      return {
        playersGroups: [],
        activeTab: 0
      }
    },
	components: {
        //TopMenu // Верхнее меню
        isotope
	},
	directives: {
        imagesLoaded
    },
    methods: {
        layout () {
            this.$refs.cpt.layout('masonry');
        }     ,
        isActive: function (tab) {
            return this.activeTab === tab
        },
        setActive: function (tab) {
            this.activeTab = tab
        },
        goToDetail: function (id) {
            this.$router.push({name: 'PlayerDetail', params: { id: id }})
        }
	},
	beforeMount () {
		HTTP.post(Routes.getPlayers)
			.then(response => {
				console.log('Игроки')
                this.playersGroups = response.data
                console.log(this.playersGroups[this.activeTab])
			})
			.catch(error => {
				console.error(error)
			})
	},
};
</script>

<style lang="scss" scoped>
/*body*/
.pag.bdy > div, .pag.ftr > div {
	max-width: 95%;
	margin: 0 auto;
}
.pag.bdy .bdy.content.reset {
    padding: 0;
    background-color: transparent;
    border: 0;
}
.pag.bdy .bdy.content {
    overflow: hidden;
    border: 1px solid #ddd;
    margin-bottom: 20px;
}
.players__tabs-nav {
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    padding-left: 0;
    -webkit-transition: -webkit-transform .3s cubic-bezier(.645,.045,.355,1);
    transition: -webkit-transform .3s cubic-bezier(.645,.045,.355,1);
    -o-transition: transform .3s cubic-bezier(.645,.045,.355,1);
    transition: transform .3s cubic-bezier(.645,.045,.355,1);
    transition: transform .3s cubic-bezier(.645,.045,.355,1),-webkit-transform .3s cubic-bezier(.645,.045,.355,1);
    position: relative;
    margin: 0;
    list-style: none;
    display: inline-block;
}
.players__tabs-nav-wrap {
    overflow: hidden;
    margin-bottom: -1px;
	font-size: 14px;
	line-height: 1.5;
	white-space: nowrap;
}
.players__tabs-nav-scroll {
    overflow: hidden;
    white-space: nowrap;
}
.players__tabs-nav-animated {
	display: flex;
	flex-basis: 10%;
	flex-flow: wrap;
}
.players__tabs-bottom .players__tabs-ink-bar-animated, .players__tabs-top .players__tabs-ink-bar-animated {

    -webkit-transition: width .3s cubic-bezier(.645,.045,.355,1),-webkit-transform .3s cubic-bezier(.645,.045,.355,1);
    transition: width .3s cubic-bezier(.645,.045,.355,1),-webkit-transform .3s cubic-bezier(.645,.045,.355,1);
    -o-transition: transform .3s cubic-bezier(.645,.045,.355,1),width .3s cubic-bezier(.645,.045,.355,1);
    transition: transform .3s cubic-bezier(.645,.045,.355,1),width .3s cubic-bezier(.645,.045,.355,1);
    transition: transform .3s cubic-bezier(.645,.045,.355,1),width .3s cubic-bezier(.645,.045,.355,1),-webkit-transform .3s cubic-bezier(.645,.045,.355,1);
	display: block;
    transform: translate3d(0px, 0px, 0px);
    width: 76px;
}
.blk.tab-group > .blk.tab {
    margin: 0;
    border: 0 none;
}
.blk.tab {
    padding: 15px 0;
    margin-bottom: 10px;
    position: relative;
}
.blk.tab.dark {
    padding: 0;
	background-color: #2d363b;
}
.blk.tab > .lst.left {
    float: left;
	margin-bottom: 0;
}
.blk.tab.dark > .lst > li {
    padding: 0;
	float: left;
}
.blk.tab.dark > .lst > .on, .blk.tab.dark > .lst > .on > .tab-text, .blk.tab.dark > .lst > .on > button {
    color: #fff;
    background-color: #374044;
}
.blk.tab > .lst > .on {
    font-weight: bold;
    color: #000;
}
.blk.tab.dark > .lst > li > .tab-text, .blk.tab.dark > .lst > li > button, .blk.tab.dark > .lst > li > span {
    display: block;
    padding: 10px;
    text-align: center;
}
.blk.tab.dark > .lst > li > .tab-text {
    color: #bcbcbc;
    -o-transition: all .3s linear;
    -moz-transition: all .3s linear;
    -khtml-transition: all .3s linear;
    -webkit-transition: all .3s linear;
    -ms-transition: all .3s linear;
    transition: all .3s linear;
	cursor: pointer;
}
.blk.tab.dark > .lst > li > .tab-text:hover {
    background-color: rgba(100,100,100,0.1);
    color: #fff;
}
.blk.tab-group > .blk.tab.secondary {
    background-color: #374044;
    border-bottom: 3px solid #f98c13;
    margin-top: -1px;
}
.blk.tab-group > .blk.tab.secondary > .lst > .on, .blk.tab-group > .blk.tab.secondary > .lst > .on > .tab-text {
    color: #f98c13;
}
.blk.tab-group > .blk.tab.secondary > .lst > li {
    text-transform: none;
}
.players__list-container {
    min-height: 768px;
}
.players__image-container {
    width: 19.45%;
    /*height: 260px;*/
    overflow: hidden;
    cursor: pointer;
    margin-bottom:10px;
    .players__image-item {
        width: 100%;
    }
    .players__image-overlay {
        position: absolute;
        width: 100%;
        height: 100%;
        left: 0;
        top: 0;
        text-align: center;
        /*background: -webkit-gradient(linear, left top, left bottom, from(rgba(106, 188, 221, 0.95)), to(rgba(58, 151, 190, 0.95)));
        background: -webkit-linear-gradient(rgba(106, 188, 221, 0.95), rgba(58, 151, 190, 0.95));
        background: -o-linear-gradient(rgba(106, 188, 221, 0.95), rgba(58, 151, 190, 0.95));
        background: linear-gradient(rgba(106, 188, 221, 0.95), rgba(58, 151, 190, 0.95));*/

        background: -webkit-gradient(linear, left top, left bottom, from(rgba(209, 210, 211, 0.95)), to(rgba(209, 210, 211, 0.95)));
        background: -webkit-linear-gradient(rgba(209, 210, 211, 0.95), rgba(209, 210, 211, 0.95));
        background: -o-linear-gradient(rgba(209, 210, 211, 0.95), rgba(209, 210, 211, 0.95));
        background: linear-gradient(rgba(209, 210, 211, 0.95), rgba(209, 210, 211, 0.95));
        z-index: 50;
        cursor: pointer;
        opacity: 0;
        visibility: hidden;
        -webkit-transition: opacity 0.3s, visibility 0.3s;
        -o-transition: opacity 0.3s, visibility 0.3s;
        transition: opacity 0.3s, visibility 0.3s;
        .players__nickname {
            padding: 19px 30px 20px 30px;
            position: absolute;
            left: 0;
            right: 0;
            top: 50%;
            margin: -30px auto 0 auto;
            font-weight: 800;
            font-size: 18px;
        }
        .players__info {
            height: 40px;
            width: 100%;
            position: absolute;
            bottom: 0;
            left: 0;
            font-size: 16px;
            .row {
                height: 100%;
            }
            > div {
                padding: 0 0 0 22px;
            }
        }
    }
    .low-games-count {
        color: #ff353b;
    }
}
.players__image-container:hover .players__image-overlay {
    opacity: 1;
    visibility: visible;
}
</style>
