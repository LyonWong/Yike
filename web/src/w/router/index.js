import Vue from 'vue'
import Router from 'vue-router'

const CreatePrepare = r => require.ensure([], () => r(require('../view/create/Prepare')), 'create/prepare')

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/w/create/prepare/:sn?',
      name: 'CreatePrepare',
      component: CreatePrepare
    }
  ]
})
