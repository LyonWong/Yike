import Vue from 'vue'
import Router from 'vue-router'

const List = r => require.ensure([], () => r(require('../view/List')), 'blog/List')
const View = r => require.ensure([], () => r(require('../view/View')), 'blog/view')

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/blog/',
      name: 'List',
      component: List
    },
    {
      path: '/blog/view/:sn',
      name: 'View',
      component: View
    }
  ]
})
