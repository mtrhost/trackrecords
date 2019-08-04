<template>
    <table class="table page__header-cells">
        <thead>
            <tr>
                <th scope="col">Фракция</th>
                <th scope="col">Игр сыграно</th>
                <th scope="col">Побед / поражений</th>
                <th scope="col">Доля побед</th>
            </tr>
        </thead>
        <tbody>
            <tr class="profile__no-role-row">
                <th scope="row">Мирный житель</th>
                <td>{{ player.statistics.games_count_no_role }}</td>
                <td>{{ player.statistics.wins_no_role + '/' + this.getFactionLosesCount('no_role') }}</td>
                <td>{{ player.winrate_no_role + ' %' }}</td>
            </tr>
            <tr class="profile__active-row" :style="'color:' + player.factions['active'][0].color + ';'">
                <th scope="row">Актив</th>
                <td>{{ player.statistics.games_count_active }}</td>
                <td>{{ player.statistics.wins_active + '/' + this.getFactionLosesCount('active') }}</td>
                <td>{{ player.winrate_active + ' %' }}</td>
            </tr>
            <tr class="profile__mafia-row" :style="'color:' + player.factions['mafia'][0].color + ';'">
                <th scope="row">Мафия</th>
                <td>{{ player.statistics.games_count_mafia }}</td>
                <td>{{ player.statistics.wins_mafia + '/' + this.getFactionLosesCount('mafia') }}</td>
                <td>{{ player.winrate_mafia + ' %' }}</td>
            </tr>
            <tr class="profile__neutral-row" :style="'color:' + player.factions['neutral'][0].color + ';'">
                <th scope="row">Маньяк</th>
                <td>{{ player.statistics.games_count_neutral }}</td>
                <td>{{ player.statistics.wins_neutral + '/' + this.getFactionLosesCount('neutral') }}</td>
                <td>{{ player.winrate_neutral + ' %' }}</td>
            </tr>
            <tr>
                <th scope="row">Всего</th>
                <td>{{ totalGamesCount }}</td>
                <td>{{ totalWinsCount + '/' +  totalLosesCount}}</td>
                <td>{{ player.winrate + ' %' }}</td>
            </tr>
            <tr>
                <td class="text-nowrap" colspan="2">Ролей получено: {{ player.roleRate }}%</td>
                <td class="text-nowrap" colspan="2">Игр проведено: {{ player.games_mastered_count }}</td>
            </tr>
            <tr>
                <td class="text-nowrap" colspan="2">Молний получено: {{ player.lightningsCount }}</td>
                <td class="text-nowrap" colspan="2">Выгнан днем/молнирован на мирном: {{ civilianNegativeActionsCount + '/' + civilianGamesCount + ' (' + this.player.cityNegativeActionsRate + '%)' }}</td>
            </tr>
            <tr>
                <td class="text-nowrap" colspan="2">В среднем прожито дней на мафии: {{ player.mafiaAverageDaysSurvived }}</td>
                <td class="text-nowrap" colspan="2">Максимальная серия побед: {{ player.statistics.maximal_winstreak }}</td>
            </tr>
        </tbody>
    </table>
</template>

<script>
    import {HTTP, Routes} from '@/components/Routes/Routes'

    export default {
        name: 'WinrateTable',
        data() {
            return {
                totalGamesCount: 0,
                totalWinsCount: 0,
                totalLosesCount: 0,
                civilianNegativeActionsCount: 0,
                civilianGamesCount: 0
            }
        },
        props: {
            player: {
                type: Array,
                required: true
            }
        },
        mounted: function() {
            this.countCalculatedProperties();
        },
        methods: {
            countCalculatedProperties: function() {
                this.totalGamesCount = this.player.statistics.games_count_no_role + this.player.statistics.games_count_active 
                    + this.player.statistics.games_count_mafia + this.player.statistics.games_count_neutral;
                this.totalWinsCount = this.player.statistics.wins_no_role + this.player.statistics.wins_active 
                    + this.player.statistics.wins_mafia + this.player.statistics.wins_neutral;
                this.totalLosesCount = this.totalGamesCount - this.totalWinsCount;
                this.civilianNegativeActionsCount = this.player.statistics.banished_civilian + this.player.statistics.lightnings_civilian;
                this.civilianGamesCount = this.player.statistics.games_count_no_role + this.player.statistics.games_count_active;
            },
            getFactionLosesCount: function(faction) {
                return this.player.statistics['games_count_' + faction] - this.player.statistics['wins_' + faction];
            }
        }
    }
</script>

<style lang="scss" scoped>
    .table {
        font-size: 18px;
    }
</style>