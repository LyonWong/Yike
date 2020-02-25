import Vue from 'vue'
import Router from 'vue-router'

const Index = r => require.ensure([], () => r(require('../view/Index')), 'home/index')
const My = r => require.ensure([], () => r(require('../view/My')), 'home/my')
const Article = r => require.ensure([], () => r(require('../view/Article')), 'home/article')
const Course = r => require.ensure([], () => r(require('../view/Course')), 'home/course')
const Column = r => require.ensure([], () => r(require('../view/Column')), 'home/column')
const Lesson = r => require.ensure([], () => r(require('../view/Lesson')), 'home/lesson')
const Series = r => require.ensure([], () => r(require('../view/Series')), 'home/series')
const Purchased = r => require.ensure([], () => r(require('../view/Purchased')), 'home/purchased')

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/home/:home/',
      name: 'home',
      component: Index,
      meta: {
        title: '易灵微课'
      }
    },
    {
      path: '/home/:home/my',
      name: 'List',
      component: My,
      meta: {
        title: '易灵微课'
      }
    },
    {
      path: '/home/:home/my/purchased',
      name: 'Purchased',
      component: Purchased,
      meta: {
        title: '我的已购'
      }
    },
    {
      path: '/home/:home/article/:sn?',
      name: 'home-article',
      component: Article,
      meta: {
        title: '易灵微课-文章'
      }
    },
    {
      path: '/home/:home/course/:sn?',
      name: 'home-course',
      component: Course,
      meta: {
        title: '易灵微课-课程'
      }
    },
    {
      path: '/home/:home/column/:sn?',
      name: 'home-column',
      component: Column,
      meta: {
        title: '易灵微课-专栏'
      }
    },
    {
      path: '/home/:home/series/:sn?',
      name: 'home-series',
      component: Series,
      meta: {
        title: '易灵微课-系列'
      }
    },
    {
      path: '/home/:home/lesson/:sn?',
      name: 'home-lesson',
      component: Lesson,
      meta: {
        title: '易灵微课-课程'
      }
    }
  ]
})
