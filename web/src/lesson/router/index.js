import Vue from 'vue'
import Router from 'vue-router'

const Home = r => require.ensure([], () => r(require('../view/Home')), 'lesson/home')
const Test = r => require.ensure([], () => r(require('../view/Test')), 'lesson/test')
const List = r => require.ensure([], () => r(require('../view/List')), 'lesson/list')
const Search = r => require.ensure([], () => r(require('../view/Search')), 'lesson/search')
const Detail = r => require.ensure([], () => r(require('../view/Detail')), 'lesson/detail')
const Series = r => require.ensure([], () => r(require('../view/Series')), 'lesson/series')
const Course = r => require.ensure([], () => r(require('../view/Course')), 'lesson/course')
const Column = r => require.ensure([], () => r(require('../view/Column')), 'lesson/column')

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/lesson/home',
      alias: '/lesson/',
      name: 'Home',
      component: Home,
      meta: {
        title: '易灵微课-首页'
      }
    },
    {
      path: '/lesson/list',
      name: 'List',
      component: List,
      meta: {
        title: '易灵微课-列表'
      }
    },
    {
      path: '/lesson/search',
      name: 'Search',
      component: Search,
      meta: {
        title: '易灵微课-搜索'
      }
    },
    {
      path: '/lesson/detail',
      name: 'Detail',
      component: Detail
    },
    {
      path: '/lesson/detail/:sn',
      name: 'Detail-Static',
      component: Detail
    },
    {
      path: '/lesson/series',
      name: 'Series',
      component: Series
    },
    {
      path: '/lesson/series/:sn',
      name: 'Series-Static',
      component: Series
    },
    {
      path: '/lesson/course',
      name: 'Course',
      component: Course
    },
    {
      path: '/lesson/course/:sn',
      name: 'Course-Static',
      component: Course
    },
    {
      path: '/lesson/column',
      name: 'Column',
      component: Column
    },
    {
      path: '/lesson/column/:sn',
      name: 'Column-Static',
      component: Column
    },
    {
      path: '/lesson/test',
      name: 'Test',
      component: Test
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
