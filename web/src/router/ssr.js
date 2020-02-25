import Vue from 'vue'
import Router from 'vue-router'
import List from '../blog/view/List'
import View from '../blog/view/View'
import Test from '../blog/view/Test'
import Demo from '../lesson/view/Demo'

Vue.use(Router)

export function createRouter() {
  return new Router({
    mode: 'history',
    routes: [
      {
        path: '/demo',
        name: 'Demo',
        component: Demo
      },
      {
        path: '/blog/',
        name: 'List',
        component: List
      },
      {
        path: '/blog/view/:sn',
        name: 'View',
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
