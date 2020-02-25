import Vue from 'vue'
import Router from 'vue-router'

const Home = r => require.ensure([], () => r(require('../view/Home')), 'my/home')
const Purchased = r => require.ensure([], () => r(require('../view/Purchased')), 'my/purchased')

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/my/home',
      alias: '/my/',
      name: 'Home',
      component: Home,
      meta: {
        title: '易灵微课-我的'
      }
    },
    {
      path: '/my/purchased',
      alias: '/my/lesson',
      name: 'Purchased',
      component: Purchased,
      meta: {
        title: '易灵微课-我的已购'
      }
    }
  ]
})
