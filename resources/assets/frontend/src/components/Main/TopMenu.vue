<template>
    <header class="pag hdr ">
        <div>
            <nav class="nav primary "> 
                <ul class="lst menu">
                    <li class="logo-itm">
                        <a v-on:click="setActive('')" class="logo" @click="switchTo('Main')">Mafia track records</a>
                    </li>
                    <li class="smenu">
                        <a v-on:click="setActive('players')" :class="{ on:isActive('players') }" class="pnl-button" @click="switchTo('PlayersList')">
                            <span>Игроки</span>
                        </a>
                    </li>
                    <li class="smenu">
                        <a v-on:click="setActive('games')" :class="{ on:isActive('games') }" class="pnl-button" @click="switchTo('GamesList')">
                            <span>Игры</span>
                        </a>
                    </li>
                    <li class="smenu">
                        <a v-on:click="setActive('settings')" :class="{ on:isActive('settings') }" class="pnl-button" @click="switchTo('SettingsList')">
                            <span>Сеттинги</span>
                        </a>
                    </li>
                    <li class="smenu">
                        <a v-on:click="setActive('achievements')" :class="{ on:isActive('achievements') }" class="pnl-button" @click="switchTo('AchievementsList')">
                            <span>Ачивки</span>
                        </a>
                    </li>
                    <li class="smenu">
                        <a v-on:click="setActive('statistics')" :class="{ on:isActive('statistics') }" class="pnl-button" @click="switchTo('Statistics')">
                            <span>Статистика</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="main__header-fixer"></div>
    </header>
</template>

<script>
    import {HTTP, Routes} from '@/components/Routes/Routes'

    export default {
        data() {
            return { activeItem: '' }
        },
        methods: {
            isActive: function (menuItem) {
                return this.activeItem === menuItem
            },
            setActive: function (menuItem) {
                this.activeItem = menuItem
            },
            switchTo (route) {
                this.$router.push({name: route})
            },
        }
    }
</script>

<style>
    .pag.hdr {
        overflow: hidden;
        width: 100%;
        height: auto;
    }
    .pag.hdr > div {
        position: fixed;
        z-index: 40;
        width: 100%;
    }
    .pag.hdr .nav {
        width: 100%;
    }
    .nav.primary {
        background-color: #374044;
        box-shadow: 0 0 7px rgba(0,0,0,0.7);
        cursor: pointer;
    }
    .nav.primary > .lst.menu {
        font-size: 1.15em;
        height: 70px;
        display: block;
        width: 100%;
        position: relative;
        overflow: hidden;
        margin: 0;
    }
    .nav.primary > .lst.menu > .logo-itm {
        width: auto;
        margin: 0;
        display: block;
    }
    .nav.primary > .lst.menu > li {
        display: block;
        float: left;
        border-right: 1px solid rgba(255,255,255,0.07);
    }
    .nav.primary > .lst.menu > .logo-itm > a {
        display: block;
        margin: 0 auto;
        height: 70px;
        line-height: 70px;
        text-align: center;
    }
    .nav.primary > .lst > li > a:hover, .nav.secondary .lst > li > a:hover {
        background-color: rgba(255,255,255,0.04);
        text-decoration: none;
    }
    .nav.primary > .lst > li > a, .nav.secondary .lst > li > a {
        color: #fff;
    }
    .nav.primary > .lst.menu .logo {
        width: 250px;
        background-position: 50% -3px;
        text-indent: -9999px;
        padding: 0 15px;
        background: url("/static/images/mtr.png") no-repeat 50% 0/95% transparent;
    }
    .nav.primary > .lst.menu > li > a {
        height: 70px;
        line-height: 70px;
        padding: 0 15px;
        display: block;
        text-align: center;
        color: #fff;
    }
    .nav.primary > .lst.menu > li > .on, .nav.primary > .lst > li > .on:hover {
        background-color: #f98c13;
        color: #fff;
    }
    .pag.hdr > .main__header-fixer {
        margin-top: 70px;
        display: block;
        position: relative;
        background-color: #2d363b;
        font-size: 1em;
        z-index: 30;
    }
</style>