import Vue from 'vue'
import Router from 'vue-router'
// import List from '../view/List'
import View from '../view/View'
import Test from '../view/Test'

Vue.use(Router)

export function createRouter() {
  return new Router({
    mode: 'history',
    routes: [
      {
        path: '/blog/',
        name: 'List',
        component: View
      },
      {
        path: '/blog/view/:sn',
        name: 'Home',
        component: View
      },
      {
        path: '/blog/test',
        name: 'Test',
        component: Test
      }
    ]
  })
}
