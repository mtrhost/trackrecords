<template lang="html">
<div class="container blk __content-mount player__profile">
    <div class="row profile__overview-block page__header-row">
        <div class="col-3">
            <div class="card">
                <img class="card-img-top" :src="player.profileImage" :alt="player.name">
                <div class="card-header text-center">
                    {{ player.name }}
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item text-center align-middle">
                        <span class="icon last-online-icon">{{ parsedData.last_active }}</span>
                    </li>
                    <li class="list-group-item text-center align-middle">
                        <span class="icon hourglass-icon">{{ player.last_game }}</span>
                    </li>
                    <li class="list-group-item profile__winrate-bar">
                        <div class="progress" :class="this.winrate_bar_class">
                            <b-progress :max="100" :precision="2" show-value class="mb-3">
                                <b-progress-bar v-if="player !== null" :value="player.winrate" :label="player.winrate + ' %'">
                                </b-progress-bar>
                            </b-progress>
                        </div>
                        <!--<div class="progress">
                            <div class="progress-bar" role="progressbar" :aria-valuenow="player.winrate" 
                                aria-valuemin="0" aria-valuemax="100">{{ player.winrate + ' %' }}</div>
                        </div>-->
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-9">
            <WinrateTable v-if="player !== null && player !== undefined" :player="player"></WinrateTable>
        </div>
        <div class="col-12 blk tab-group">
            <div class="blk tab dark secondary">
                <ul class="lst left">
                    <li class="nav-item" :class="isActiveTab('games') ? 'on' : ''">
                        <span class="tab-text" @click="setActiveTab('games')">Игры</span>
                    </li>
                    <li class="nav-item" :class="isActiveTab('achievements') ? 'on' : ''">
                        <span class="tab-text" @click="setActiveTab('achievements')">Ачивки</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="profile__main-block mt-4">
        <div v-if="isActiveTab('games')">
            <GamesTable v-if="player !== null && player.games !== undefined" :games="player.games" :showFilter="false" :playerDetail="true"></GamesTable>
        </div>
        <div v-if="isActiveTab('achievements')">
            <table class="table" v-if="player.achievements.length > 0">
                <tbody>
                    <tr v-for="achievement in player.achievements">
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
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</template>

<script>
import { HTTP, Routes } from "@/components/Routes/Routes";
import WinrateTable from "@/components/Players/Modules/WinrateTable";
import GamesTable from "@/components/Games/Modules/Table";

export default {
    data () {
        return {
            player: null,
            parsedData: [],
            winrate_bar_class: 'well',
            tab: 'games'
        }
    },
	components: {
        WinrateTable,
        GamesTable
    },
    methods: {
        getPlayer: function(id) {
            HTTP.post(Routes.getPlayer, {id: id})
                .then(response => {
                    this.player = response.data;
                    console.log(JSON.stringify(this.player.games));
                    this.countWinrateClass();
                })
                .catch(error => {
                    console.error(error)
                })
        },
        parseProfile: function(id) {
            HTTP.post(Routes.parsePlayerProfile, {id: id})
                .then(response => {
                    this.parsedData = response.data;
                })
                .catch(error => {
                    console.error(error)
                })
        },
        countWinrateClass: function() {
            if(!this.player.is_active) {
                this.winrate_bar_class = 'inactive';
            } else {
                if(this.player.winrate < 45) {
                    this.winrate_bar_class = 'bad';
                } else if (this.player.winrate > 50) {
                    this.winrate_bar_class = 'great';
                }
            }
        },
        isActiveTab: function(tab) {
            return this.tab === tab;
        },
        setActiveTab: function(tab) {
            this.tab = tab;
        }
	},
	created () {
        this.getPlayer(this.$route.params.id);
        this.parseProfile(this.$route.params.id);
	},
};
</script>

<style lang="scss" scoped>
    .player__profile {
        min-height: 768px;
        .page__header-row {
            padding: 30px 25px 0px;
        }
        .profile__overview-block {
            min-height: 200px;
            .profile__winrate-bar {
                padding: 0;
                height: 44px;
                .progress {
                    height: 100%;
                    width: 100%;
                }
                .bad .progress-bar {
                    background-color: #b00b13;
                }
                .well .progress-bar {
                    background-color: #4e90ec;
                }
                .great .progress-bar {
                    background-color: #D7B740;
                }
                .inactive .progress-bar {
                    background-color: #D3D3D3;
                }
                .progress-bar {
                    color:#242424;
                }
            }
        }
        .blk.tab-group {
            margin-top: 20px;
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
            margin-top: -1px;
        }
        .blk.tab-group > .blk.tab.secondary > .lst > .on, .blk.tab-group > .blk.tab.secondary > .lst > .on > .tab-text {
            color: #f98c13;
        }
        .blk.tab-group > .blk.tab.secondary > .lst > li {
            text-transform: none;
        }
        .achievements__main-image {
            width: 128px;
        }
        /*.profile__main-block .profile__nav-tabs {

        }*/
    }
</style>
