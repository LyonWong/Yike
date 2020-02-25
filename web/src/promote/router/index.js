import Vue from 'vue'
import Router from 'vue-router'

const Home = r => require.ensure([], () => r(require('../view/Home')), 'promote/home')
const Invite = r => require.ensure([], () => r(require('../view/Invite')), 'promote/invite')

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/promote/',
      name: 'Home',
      component: Home
    },
    {
      path: '/promote/invite',
      name: 'Invite',
      component: Invite,
      meta: {
        title: '邀请卡'
      }
    }
  ]
})
