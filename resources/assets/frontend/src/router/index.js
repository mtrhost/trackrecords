import Vue from 'vue'
import Router from 'vue-router'
import Main from '@/components/Pages/Main'
import PlayersList from '@/components/Players/List'
import PlayerDetail from '@/components/Players/Detail'
import GamesList from '@/components/Games/List'
import GameDetail from '@/components/Games/Detail'
import SettingsList from '@/components/Settings/List'
import SettingDetail from '@/components/Settings/Detail'
import AchievementsList from '@/components/Achievements/List'
import Statistics from '@/components/players/Statistics'
import Login from '@/components/auth/Login'

Vue.use(Router)

export default new Router({
  mode: 'history',
  base: '/',
  routes: [
    {
        path: '/',
        name: 'Main',
        component: Main,
        meta: {
            title: 'Главная страница',
            metaTags: [
            {
                name: 'description',
                content: 'Mafia track records'
            },
            {
                property: 'og:description',
                content: 'The home page of our example app.'
            }
            ]
        }
    },
    {
        path: '/players',
        name: 'PlayersList',
        component: PlayersList,
        meta: {
            title: 'Игроки',
            metaTags: [
                {
                    name: 'description',
                    content: 'Игроки'
                },
                {
                    property: 'og:description',
                    content: 'Игроки'
                }
            ]
        }
    },
    {
        path: '/players/:id',
        name: 'PlayerDetail',
        component: PlayerDetail,
        meta: {
            title: 'Персональная статистика',
            metaTags: [
                {
                    name: 'description',
                    content: 'Персональная статистика'
                },
                {
                    property: 'og:description',
                    content: 'Персональная статистика'
                }
            ]
        }
    },
    {
        path: '/games',
        name: 'GamesList',
        component: GamesList,
        meta: {
            title: 'Игры',
            metaTags: [
                {
                    name: 'description',
                    content: 'Игры'
                },
                {
                    property: 'og:description',
                    content: 'Игры'
                }
            ]
        }
    },
    {
        path: '/games/:id',
        name: 'GameDetail',
        component: GameDetail,
        meta: {
            title: 'Игра',
            metaTags: [
                {
                    name: 'description',
                    content: 'Игра'
                },
                {
                    property: 'og:description',
                    content: 'Игра'
                }
            ]
        }
    },
    {
        path: '/settings',
        name: 'SettingsList',
        component: SettingsList,
        meta: {
            title: 'Сеттинги',
            metaTags: [
                {
                    name: 'description',
                    content: 'Сеттинги'
                },
                {
                    property: 'og:description',
                    content: 'Сеттинги'
                }
            ]
        }
    },
    {
        path: '/settings/:id',
        name: 'SettingDetail',
        component: SettingDetail,
        meta: {
            title: 'Сеттинг',
            metaTags: [
                {
                    name: 'description',
                    content: 'Сеттинг'
                },
                {
                    property: 'og:description',
                    content: 'Сеттинг'
                }
            ]
        }
    },
    {
        path: '/achievements',
        name: 'AchievementsList',
        component: AchievementsList,
        meta: {
            title: 'Ачивки',
            metaTags: [
                {
                    name: 'description',
                    content: 'Ачивки'
                },
                {
                    property: 'og:description',
                    content: 'Ачивки'
                }
            ]
        }
    },
    {
        path: '/statistics',
        name: 'Statistics',
        component: Statistics,
        meta: {
            title: 'Статистика',
            metaTags: [
                {
                    name: 'description',
                    content: 'Статистика'
                },
                {
                    property: 'og:description',
                    content: 'Статистика'
                }
            ]
        }
    },
    /*{
        path: '/admin',
        name: 'Login',
        component: Login,
        meta: {
            title: 'Авторизация',
            metaTags: [
                {
                    name: 'description',
                    content: 'Авторизация'
                },
                {
                    property: 'og:description',
                    content: 'Авторизация'
                }
            ]
        }
    },*/
  ]
})
