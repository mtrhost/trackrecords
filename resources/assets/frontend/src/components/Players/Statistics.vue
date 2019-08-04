<template lang="html">
	<section class="bdy content reset cflex">
		<div class="c100 blk __content-mount">
            <div class="blk tab-group">
                <div class="blk tab dark secondary">
					<ul class="lst left " data-role="tabs">
						<li :class="activeTab === 'gamesCount' ? 'on' : ''"><span class="tab-text" @click="setActiveTab('gamesCount');">Количество игр</span></li>
						<li :class="activeTab === 'lightningsCount' ? 'on' : ''"><span @click="setActiveTab('lightningsCount');" class="tab-text">Количество молний</span></li>
						<li :class="activeTab === 'winrate' ? 'on' : ''"><span @click="setActiveTab('winrate');" class="tab-text">Общий винрейт</span></li>
						<li :class="activeTab === 'winrateNoRole' ? 'on' : ''"><span @click="setActiveTab('winrateNoRole');" class="tab-text">Винрейт (мж)</span></li>
						<li :class="activeTab === 'winrateActive' ? 'on' : ''"><span @click="setActiveTab('winrateActive');" class="tab-text">Винрейт (актив)</span></li>
						<li :class="activeTab === 'winrateCivilian' ? 'on' : ''"><span @click="setActiveTab('winrateCivilian');" class="tab-text">Винрейт (мирный)</span></li>
						<li :class="activeTab === 'winrateMafia' ? 'on' : ''"><span @click="setActiveTab('winrateMafia');" class="tab-text">Винрейт (мафия)</span></li>
						<li :class="activeTab === 'winrateNeutral' ? 'on' : ''"><span @click="setActiveTab('winrateNeutral');" class="tab-text">Винрейт (нейтрал)</span></li>
						<li :class="activeTab === 'roleRate' ? 'on' : ''"><span @click="setActiveTab('roleRate');" class="tab-text">Роль</span></li>
						<li :class="activeTab === 'civilianNegative' ? 'on' : ''"><span @click="setActiveTab('civilianNegative');" class="tab-text">Подвел на мирном</span></li>
						<li :class="activeTab === 'mafiaSurvived' ? 'on' : ''"><span @click="setActiveTab('mafiaSurvived');" class="tab-text">Прожито на мафии</span></li>
						<li :class="activeTab === 'winstreak' ? 'on' : ''"><span @click="setActiveTab('winstreak');" class="tab-text">Винстрик</span></li>
					</ul>
				</div>
			</div>
            <div class="container-fluid" v-if="playersFilteredAndSorted.length > 0">
                <b-row mb="4">
                    <StatisticsTable 
                        v-if="playersFilteredAndSorted.length > 0" 
                        :players="playersFilteredAndSorted" 
                        :keyParam="keyParam"
                        :key="activeTab"
                        :currentPage="1"
                        :perPage="20">
                    </StatisticsTable>
                </b-row>
            </div>
		</div>
	</section>
</template>

<script>
import { HTTP, Routes } from "@/components/Routes/Routes";
import StatisticsTable from "@/components/Players/Modules/StatisticsTable";

export default {
    data () {
      return {
        players: [],
        activeTab: 'gamesCount',
        keyParam: {title: 'Количество игр', keys: ['gamesCount']}
      }
    },
    computed: {
        playersFilteredAndSorted () {
            switch(this.activeTab) {
                case 'gamesCount':
                    return this.playersByGames();
                break;
                case 'lightningsCount':
                    return this.playersByLightnings();
                break;
                case 'winrate':
                    return this.playersByWinrate();
                break;
                case 'winrateNoRole':
                    return this.playersByWinrateNoRole();
                break;
                case 'winrateActive':
                    return this.playersByWinrateActive();
                break;
                case 'winrateCivilian':
                    return this.playersByWinrateCivilian();
                break;
                case 'winrateMafia':
                    return this.playersByWinrateMafia();
                break;
                case 'winrateNeutral':
                    return this.playersByWinrateNeutral();
                break;
                case 'roleRate':
                    return this.playersByRoleRate();
                break;
                case 'civilianNegative':
                    return this.playersByCivilianNegativeActions();
                break;
                case 'mafiaSurvived':
                    return this.playersByMafiaAverageSurvivedDays();
                break;
                case 'winstreak':
                    return this.playersByMaximalWinstreak();
                break;
            }
        }
	},
    components: {
        StatisticsTable
    },
    methods: {
        setActiveTab: function(key) {
            this.activeTab = key;
            switch(key) {
                case 'gamesCount':
                    this.keyParam = {title: 'Количество игр', keys: ['gamesCount']};
                break;
                case 'lightningsCount':
                    this.keyParam = {title: 'Количество молний', keys: ['lightningsCount']};
                break;
                case 'winrate':
                    this.keyParam = {title: 'Винрейт (%)', keys: ['winrate']};
                break;
                case 'winrateNoRole':
                    this.keyParam = {title: 'Винрейт без роли (%)', keys: ['winrate_no_role']};
                break;
                case 'winrateActive':
                    this.keyParam = {title: 'Винрейт актив (%)', keys: ['winrate_active']};
                break;
                case 'winrateCivilian':
                    this.keyParam = {title: 'Винрейт мирный (%)', keys: ['winrateCivilian']};
                break;
                case 'winrateMafia':
                    this.keyParam = {title: 'Винрейт мафия (%)', keys: ['winrate_mafia']};
                break;
                case 'winrateNeutral':
                    this.keyParam = {title: 'Винрейт нейтрал (%)', keys: ['winrate_neutral']};
                break;
                case 'roleRate':
                    this.keyParam = {title: 'Активная роль (%)', keys: ['roleRate']};
                break;
                case 'civilianNegative':
                    this.keyParam = {title: 'Выгнан днем/ молнирован на мирной роли (%)', keys: ['cityNegativeActionsRate']};
                break;
                case 'mafiaSurvived':
                    this.keyParam = {title: 'Прожито на мафии в среднем (дней)', keys: ['mafiaAverageDaysSurvived']};
                break;
                case 'winstreak':
                    this.keyParam = {title: 'Винстрик', keys: ['statistics', 'maximal_winstreak']};
                break;
            }
        },
        filterAndSort: function(filterKeys, sortKeys, range, sortOrder) {
            if(sortOrder === undefined) sortOrder = 'desc';
            return this.players
				.filter(function (player, index) {
                    var result = player;
                    for(var i = 0; i < filterKeys.length; i++) {
                        result = result[filterKeys[i]];
                    }
                    return result > range;
                })
                .sort(function (a, b) {
                    var resultA = a;
                    var resultB = b;
                    for(var i = 0; i < sortKeys.length; i++) {
                        resultA = resultA[sortKeys[i]];
                        resultB = resultB[sortKeys[i]];
                    }
                    if(sortOrder === 'desc') {
                        return resultA > resultB ? -1 : 1;
                    } else {
                        return resultA < resultB ? -1 : 1;
                    }
                });
        },
        playersByGames: function() {
			return this.filterAndSort(['gamesCount'], ['gamesCount'], 30);
        },
        playersByLightnings: function() {
			return this.filterAndSort(['lightningsCount'], ['lightningsCount'], 5);
        },
        playersByWinrate: function() {
			return this.filterAndSort(['gamesCountWoMastered'], ['winrate'], 30);
        },
        playersByWinrateNoRole: function() {
            return this.filterAndSort(['statistics', 'games_count_no_role'], ['winrate_no_role'], 15);
        },
        playersByWinrateActive: function() {
            return this.filterAndSort(['statistics', 'games_count_active'], ['winrate_active'], 15);
        },
        playersByWinrateCivilian: function() {
            return this.filterAndSort(['statistics', 'civilian_games_count'], ['winrateCivilian'], 20);
        },
        playersByWinrateMafia: function() {
            return this.filterAndSort(['statistics', 'games_count_mafia'], ['winrate_mafia'], 10);
        },
        playersByWinrateNeutral: function() {
            return this.filterAndSort(['statistics', 'games_count_neutral'], ['winrate_neutral'], 4);
        },
        playersByRoleRate: function() {
            return this.filterAndSort(['gamesCountWoMastered'], ['roleRate'], 30);
        },
        playersByCivilianNegativeActions: function() {
            return this.filterAndSort(['statistics', 'civilian_games_count'], ['cityNegativeActionsRate'], 30, 'asc');
        },
        playersByMafiaAverageSurvivedDays: function() {
            return this.filterAndSort(['statistics', 'games_count_mafia'], ['mafiaAverageDaysSurvived'], 10);
        },
        playersByMaximalWinstreak: function() {
            return this.filterAndSort(['gamesCountWoMastered'], ['statistics', 'maximal_winstreak'], 30);
        }
	},
	beforeMount () {
		HTTP.post(Routes.getStatistics)
			.then(response => {
                this.players = response.data
			})
			.catch(error => {
				console.error(error)
			})
	},
};
</script>

<style lang="scss" scoped>
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
</style>
