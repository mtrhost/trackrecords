import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue';
import App from './App'
import router from './router'
import notifications from 'vue-izitoast'
import 'izitoast/dist/css/iziToast.min.css'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import '../static/css/main.css'
//import '../static/css/normalize.css'
import store from '@/store'

//import {GlobalStringFunctions} from '@/components/Mixins/GlobalStringFunctions'
//import {GlobalDateFunctions} from '@/components/Mixins/GlobalDateFunctions'

Vue.config.productionTip = false
// Vue.use(Vuex)
Vue.use(notifications, { position: 'topRight' })
Vue.use(BootstrapVue);
// see docs for available options

//Vue.mixin(GlobalStringFunctions)
//Vue.mixin(GlobalDateFunctions)

router.beforeEach((to, from, next) => {
  document.title = to.meta.title
  next()
})

/* eslint-disable no-new */
new Vue({
  el: '#app',
  store,
  router,
  template: '<App/>',
  components: { App }
})
