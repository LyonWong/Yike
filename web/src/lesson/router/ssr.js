import Vue from 'vue'
import Router from 'vue-router'
import Demo from '../view/Demo'
import Test from '../view/Test'
// import Home from '../view/Home'
import List from '../../blog/view/List'

Vue.use(Router)

export function createRouter() {
  return new Router({
    mode: 'history',
    routes: [
      {
        path: '/',
        name: 'Demo',
        component: Demo
      },
      {
        path: '/list',
        name: 'List',
        component: List
      },
      // {
      //   path: '/lesson/home',
      //   alias: ['/lesson/', '/lesson/home.html'],
      //   name: 'Home',
      //   component: Home,
      //   meta: {
      //     title: '易灵微课-首页'
      //   }
      // },
    //   {
    //     path: '/lesson/search',
    //     alias: ['/lesson/search.html', '/lesson/list', '/lesson/list.html'],
    //     name: 'Search',
    //     component: Search,
    //     meta: {
    //       title: '易灵微课-列表'
    //     }
    //   },
    //   {
    //     path: '/lesson/detail',
    //     name: 'Detail',
    //     component: Detail
    //   },
    //   {
    //     path: '/lesson/detail/:sn',
    //     name: 'Detail-Static',
    //     component: Detail
    //   },
    //   {
    //     path: '/lesson/series',
    //     name: 'Series',
    //     component: Series
    //   },
    //   {
    //     path: '/lesson/series/:sn',
    //     name: 'Series-Static',
    //     component: Series
    //   }
      {
        path: '/test',
        name: 'Test',
        component: Test
      }
    ]
    // scrollBehavior(to, from, savedPosition) {
    //   if (to.hash) {
    //     return {
    //       selector: to.hash
    //     }
    //   }
    // }
  })
}
