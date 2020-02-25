import Vue from 'vue'
import Router from 'vue-router'

const View = r => require.ensure([], () => r(require('../view/View')), 'study/view')
const Article = r => require.ensure([], () => r(require('../view/Article')), 'study/article')
const Board = r => require.ensure([], () => r(require('../view/Board')), 'study/board')
const BoardDetail = r => require.ensure([], () => r(require('../view/BoardDetail')), 'study/board-detail')
const Test = r => require.ensure([], () => r(require('../view/Test')), 'study/test')

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/study/view/:sn?',
      name: 'view',
      component: View,
      meta: {
        title: '易灵微课-课堂'
      }
    },
    {
      path: '/study/article/:sn?',
      name: 'article',
      component: Article,
      meta: {
        title: '易灵微课-文章'
      }
    },
    {
      path: '/study/preview/:sn',
      name: 'preview',
      component: View,
      meta: {
        title: '易灵微课-预览'
      }
    },
    {
      path: '/study/board/:type',
      name: 'Board',
      component: Board,
      meta: {
        title: '易灵微课-留言板'
      }
    },
    {
      path: '/study/board-detail',
      name: 'BoardDetail',
      component: BoardDetail,
      meta: {
        title: '易灵微课-留言详情'
      }
    },
    {
      path: '/study/test',
      name: 'test',
      component: Test
    }
  ]
})
