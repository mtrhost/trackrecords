import axios from 'axios'

// Настройки по умолчанию для всех запросов
export const HTTP = axios.create({
	baseUrl: 'http://rrr.loc/',
	headers: {
		'X-Requested-With': 'XMLHttpRequest'
		// 'Retry-After': 1000
	},
	timeout: 10000
	// crossDomain: true
	// withCredentials: true
})

// Список путей для общения с сервером через POST
export const Routes = {
  	getMain: '/', // url главной страницы
	getPlayers: '/players/list', // список игроков
	getPlayer: '/players/show', // детальная информация по игроку
	parsePlayerProfile: '/players/parse', // спаршенная инфа по игроку
	getGames: '/games/list', // список игр
	getGame: '/games/show', // игра
	getSettings: '/settings/list', // список сеттингов
	getSetting: '/settings/show', // список сеттингов
	getAchievements: '/achievements/list', // список ачивок и их обладателей
	getStatistics: '/players/statistics', // статистика игроков
	login: '/auth/login', //залогиниться
	getCsrfToken: '/auth/csrf', //залогиниться
}
