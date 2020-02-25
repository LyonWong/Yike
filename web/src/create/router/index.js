import Vue from 'vue'
import Router from 'vue-router'

const Posts = r => require.ensure([], () => r(require('../view/Posts')), 'create/posts')
const Prepare = r => require.ensure([], () => r(require('../view/Prepare')), 'create/prepare')
const Article = r => require.ensure([], () => r(require('../view/Article')), 'create/article')
const Column = r => require.ensure([], () => r(require('../view/Column')), 'create/view')
const Submit = r => require.ensure([], () => r(require('../view/Submit')), 'create/submit')
const Preview = r => require.ensure([], () => r(require('../view/Preview')), 'create/preview')
const GuideMarkdown = r => require.ensure([], () => r(require('../view/guide/Markdown')), 'create/guide')

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/create/posts',
      name: 'Posts',
      component: Posts,
      meta: {
        title: '易灵微课-我的文集'
      }
    },
    {
      path: '/create/prepare/:sn',
      name: 'Prepare',
      component: Prepare,
      meta: {
        title: '易灵微课'
      }
    },
    {
      path: '/create/article/:sn',
      name: 'Article',
      component: Article,
      meta: {
        title: '易灵微课'
      }
    },
    {
      path: '/create/column/:sn',
      name: 'Column',
      component: Column,
      meta: {
        title: '易灵微课-专栏'
      }
    },
    {
      path: '/create/submit/:sn',
      name: 'Submit',
      component: Submit,
      meta: {
        title: '易灵微课'
      }
    },
    {
      path: '/create/preview/:sn',
      name: 'Preview',
      component: Preview,
      meta: {
        title: '易灵微课'
      }
    },
    {
      path: '/create/guide/markdown',
      name: 'GuideMarkdown',
      component: GuideMarkdown,
      meta: {
        title: '易灵微课'
      }
    }
  ]
})
