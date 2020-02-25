import Vue from 'vue'
import Router from 'vue-router'

const Env = r => require.ensure([], () => r(require('../view/Env')), 'test/env')
const Loading = r => require.ensure([], () => r(require('../view/Loading')), 'test/loading')

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/test/env',
      name: 'Env',
      component: Env
    },
    {
      path: '/test/loading',
      name: 'Loading',
      component: Loading
    }
  ],
  scrollBehavior (to, from, savedPosition) {
    if (to.hash) {
      return {
        selector: to.hash
      }
    }
  }
})
