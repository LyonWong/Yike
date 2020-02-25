import Vue from 'vue'
import Router from 'vue-router'

const ContentSecurity = r => require.ensure([], () => r(require('../view/ContentSecurity')), 'tool/content-security')

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/tool/content-security',
      name: 'ContentSecurity',
      component: ContentSecurity,
      meta: {
        title: '易灵微课-内容安全检查'
      }
    }
  ]
})
