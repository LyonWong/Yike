import Vue from 'vue'
import Router from 'vue-router'

const Bind = r => require.ensure([], () => r(require('../view/Bind')), 'user/bind')
const Datum = r => require.ensure([], () => r(require('../view/Datum')), 'user/datum')
const Teacher = r => require.ensure([], () => r(require('../view/Teacher')), 'user/teacher')
const TeacherNotice = r => require.ensure([], () => r(require('../view/TeacherNotice')), 'user/teacher')
const AboutUs = r => require.ensure([], () => r(require('../view/AboutUs')), 'user/aboutus')
const Money = r => require.ensure([], () => r(require('../view/Money')), 'user/money')
const MoneyDetail = r => require.ensure([], () => r(require('../view/MoneyDetail')), 'user/money')
const MoneyDrawcash = r => require.ensure([], () => r(require('../view/MoneyDrawcash')), 'user/money')

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/user/datum',
      name: 'Datum',
      component: Datum,
      meta: {
        title: '易灵微课-个人资料'
      }
    },
    {
      path: '/user/apply',
      name: 'Apply',
      component: Datum,
      meta: {
        title: '易灵微课-讲师申请'
      }
    },
    {
      path: '/user/teacher',
      name: 'Teacher',
      component: Teacher,
      meta: {
        title: '易灵微课-讲师主页'
      }
    },
    {
      path: '/user/teacher-notice',
      name: 'TeacherNotice',
      component: TeacherNotice,
      meta: {
        title: '易灵微课-讲师须知'
      }
    },
    {
      path: '/user/about-us',
      name: 'AboutUs',
      component: AboutUs,
      meta: {
        title: '易灵微课-关于我们'
      }
    },
    {
      path: '/user/bind/:contact',
      name: 'Bind',
      component: Bind,
      meta: {
        title: '易灵微课-身份绑定'
      }
    },
    {
      path: '/user/money',
      name: 'Money',
      component: Money,
      meta: {
        title: '易灵微课-账户中心'
      }
    },
    {
      path: '/user/money-detail',
      name: 'MoneyDetail',
      component: MoneyDetail,
      meta: {
        title: '易灵微课-资金明细'
      }
    },
    {
      path: '/user/money-drawcash',
      name: 'MoneyDrawcash',
      component: MoneyDrawcash,
      meta: {
        title: '易灵微课-资金提现'
      }
    }
  ]
})
