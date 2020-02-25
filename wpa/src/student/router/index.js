/**
 * 1.定义路由，每个路由应该映射一个组件
 * path : 浏览器的显示的路径
 * component : 路由的组件路径
 */

import App from '../App'

if(process.env.NODE_ENV == 'production'){
  /*home*/
  var home = r => require.ensure([], () => r(require('../views/home/index')), 'student/student-home');
  /*course*/
  var course = r => require.ensure([], () => r(require('../views/course/index')), 'student/student-course');
  var list = r => require.ensure([], () => r(require('../views/course/list')), 'student/student-courseList');
  var detail = r => require.ensure([], () => r(require('../views/course/detail')), 'student/student-courseDetail');
  var brief = r => require.ensure([], () => r(require('../views/course/brief')), 'student/student-courseBrief');
  var notice = r => require.ensure([], () => r(require('../views/course/notice')), 'student/student-courseNotice');
  var evaluate = r => require.ensure([], () => r(require('../views/course/evaluate')), 'student/student-courseEvaluate');
  var beTeacher = r => require.ensure([], () => r(require('../views/course/be-teacher')), 'student/student-courseBeTeacher');
  var evaluateLesson = r => require.ensure([], () => r(require('../views/course/evaluate-lesson')), 'student/student-courseEvaluateLesson');
  /*user*/
  var user = r => require.ensure([], () => r(require('../views/user/index')), 'student/student-user');
  var userCenter = r => require.ensure([], () => r(require('../views/user/center')), 'student/student-userCenter');
  var guarantee = r => require.ensure([], () => r(require('../views/user/guarantee')), 'student/student-guarantee');
  var enrolled = r => require.ensure([], () => r(require('../views/user/enroll')), 'student/student-userEnroll');
  var advise = r => require.ensure([], () => r(require('../views/user/advise')), 'student/student-userAdvise');
  var about = r => require.ensure([], () => r(require('../views/user/about')), 'student/student-userAbout');
  var money = r => require.ensure([], () => r(require('../views/user/money')), 'student/student-userMoney');
  var moneyOut = r => require.ensure([], () => r(require('../views/user/money-out')), 'student/student-userMoneyOut');
  var moneyDetail = r => require.ensure([], () => r(require('../views/user/money-detail')), 'student/student-userMoneyDetail');
  var moneyCoupon = r => require.ensure([], () => r(require('../views/user/money-coupon')), 'student/student-userMoneyCoupon');
  var teacherFollow = r => require.ensure([], () => r(require('../views/user/follow')), 'student/student-teacherFollow');
  /*test*/
  var userTest = r => require.ensure([], () => r(require('../views/test/index')), 'student/student-userTest');
  /*refund*/
  var refund = r => require.ensure([], () => r(require('../views/refund/index')), 'student/student-courseNotice');
  var reason = r => require.ensure([], () => r(require('../views/refund/reason')), 'student/student-courseReason');
  /*series*/
  var series = r => require.ensure([], () => r(require('../views/series/index')), 'student/student-series');
  var seriesOrder = r => require.ensure([], () => r(require('../views/series/order')), 'student/student-seriesOrder');
  var seriesList = r => require.ensure([], () => r(require('../views/series/list')), 'student/student-seriesList');
  var seriesDetail = r => require.ensure([], () => r(require('../views/series/detail')), 'student/student-seriesDetail');
  var seriesBrief = r => require.ensure([], () => r(require('../views/series/brief')), 'student/student-seriesBrief');
  var seriesBriefList = r => require.ensure([], () => r(require('../views/series/brief-list')), 'student/student-seriesBriefList');
  var seriesTeacher = r => require.ensure([], () => r(require('../views/series/teacher')), 'student/student-seriesTeacher');
  var seriesTeacherSingle = r => require.ensure([], () => r(require('../views/series/teacher-single')), 'student/student-seriesTeacherSingle');
  var seriesTeacherSeries = r => require.ensure([], () => r(require('../views/series/teacher-series')), 'student/student-seriesTeacherSeries');
  /*message*/
  var message = r => require.ensure([], () => r(require('../views/message/index')), 'student/student-message');
  var messageDiscuss = r => require.ensure([], () => r(require('../views/message/discuss')), 'student/student-messageDiscuss');
  var messageTask = r => require.ensure([], () => r(require('../views/message/task')), 'student/student-messageTask');
  var messageDetail = r => require.ensure([], () => r(require('../views/message/detail')), 'student/student-messageDetail');
  /*group*/
  var group = r => require.ensure([], () => r(require('../views/group/index')), 'student/student-group');
  var groupCreate = r => require.ensure([], () => r(require('../views/group/create')), 'student/student-groupCreate');
  var groupEnroll = r => require.ensure([], () => r(require('../views/group/enroll')), 'student/student-groupEnroll');
  var groupEnrollEnd = r => require.ensure([], () => r(require('../views/group/enroll-end')), 'student/student-groupEnrollEnd');
  /*rank*/
  var rank = r => require.ensure([], () => r(require('../views/rank/index')), 'student/student-rank');
  /*qrcode*/
  var qrcode = r => require.ensure([], () => r(require('../views/qrcode/index')), 'student/student-qrcode');
}else{
  /*home*/
  var home = require('../views/home/index');
  /*course*/
  var course = require('../views/course/index');
  var list = require('../views/course/list');
  var detail = require('../views/course/detail');
  var brief = require('../views/course/brief');
  var notice = require('../views/course/notice');
  var evaluate = require('../views/course/evaluate');
  var beTeacher = require('../views/course/be-teacher');
  var evaluateLesson = require('../views/course/evaluate-lesson');
  /*user*/
  var user = require('../views/user/index');
  var userCenter = require('../views/user/center');
  var guarantee = require('../views/user/guarantee');
  var enrolled = require('../views/user/enroll');
  var advise = require('../views/user/advise');
  var about = require('../views/user/about');
  var money = require('../views/user/money');
  var moneyOut = require('../views/user/money-out');
  var moneyDetail = require('../views/user/money-detail');
  var moneyCoupon = require('../views/user/money-coupon');
  var teacherFollow = require('../views/user/follow');
  /*test*/
  var userTest = require('../views/test/index');
  /*refund*/
  var refund = require('../views/refund/index');
  var reason = require('../views/refund/reason');
  /*series*/
  var series = require('../views/series/index');
  var seriesOrder = require('../views/series/order');
  var seriesList = require('../views/series/list');
  var seriesDetail = require('../views/series/detail');
  var seriesBrief = require('../views/series/brief');
  var seriesBriefList = require('../views/series/brief-list');
  var seriesTeacher = require('../views/series/teacher');
  var seriesTeacherSingle = require('../views/series/teacher-single');
  var seriesTeacherSeries = require('../views/series/teacher-series');
  /*message*/
  var message = require('../views/message/index');
  var messageDiscuss = require('../views/message/discuss');
  var messageTask = require('../views/message/task');
  var messageDetail = require('../views/message/detail');
  /*group*/
  var group = require('../views/group/index');
  var groupCreate = require('../views/group/create');
  var groupEnroll = require('../views/group/enroll');
  var groupEnrollEnd = require('../views/group/enroll-end');
  /*rank*/
  var rank = require('../views/rank/index');
  /*qrcode*/
  var qrcode = require('../views/qrcode/index');
}

export default [
  {
    path: '/',
    name: 'index',
    component: home,
    meta: {
      pageTitle: '易灵微课'
    },
    children: [
      {
        path: '',
        redirect: '/course/list'
      }
    ]
  },
  {
    path: '/course',
    name: 'course',
    component: course,
    children: [
      {
        path: '',
        redirect: '/course/list'
      },
      // 课程列表页
      {
        path: '/course/list',
        name: 'list',
        component: list,
        meta: {
          pageTitle: '易灵微课-首页'
        }
      },
      // 课程详情页
      {
        path: '/course/detail',
        name: 'detail',
        component: detail,
        meta: {
          pageTitle: '易灵微课-课程详情'
        },
        children: [
          {
            path: '',
            redirect: '/course/detail/brief'
          },
          {
            path: '/course/detail/brief',
            name: 'brief',
            component: brief,
            meta: {
              pageTitle: '易灵微课-课程简介'
            }
          },
          {
            path: '/course/detail/notice',
            name: 'notice',
            component: notice,
            meta: {
              pageTitle: '易灵微课-课程须知'
            }
          },
          /*{
            path: '/course/detail/evaluate',
            name: 'evaluate',
            component: evaluate,
            meta: {
              pageTitle: '易灵微课-课程评价'
            }
          }*/
        ]
      },
      // 评价某课程
      {
        path: '/course/evaluate/:lesson_sn',
        name: 'evaluate-lesson',
        component: evaluateLesson,
        meta: {
          pageTitle: '易灵微课-评价课程'
        }
      },
      // 课程评价页
      {
        path: '/course/evaluate',
        name: 'evaluate',
        component: evaluate,
        meta: {
          pageTitle: '易灵微课-评价课程'
        }
      },
      // 退款
      {
        path: '/course/refund',
        name: 'refund',
        component: refund,
        meta: {
          pageTitle: '易灵微课-申请退款'
        }
      },
      // 退款理由
      {
        path: '/course/reason',
        name: 'reason',
        component: reason,
        meta: {
          pageTitle: '易灵微课-处理结果'
        }
      },
      // 成为讲师
      {
        path: '/course/beTeacher',
        name: 'beTeacher',
        component: beTeacher,
        meta: {
          pageTitle: '易灵微课-注册成为讲师'
        }
      },
      // 系列课
      {
        path: 'series',
        name: 'series',
        component: series,
        meta: {
          pageTitle: '易灵微课'
        },
        children: [
          {
            path: '',
            redirect: 'list'
          },
          // 系列课列表页
          {
            path: 'list',
            name: 'seriesList',
            component: seriesList,
            meta: {
              pageTitle: '易灵微课-系列课列表'
            }
          },
          // 系列课详情页
          {
            path: 'detail/:series_sn',
            name: 'seriesDetail',
            component: seriesDetail,
            meta: {
              pageTitle: '易灵微课-系列课详情'
            },
            children: [
              {
                path: '',
                redirect: 'brief'
              },
              // 介绍
              {
                path: 'brief',
                name: 'seriesBrief',
                component: seriesBrief,
                meta: {
                  pageTitle: '易灵微课-系列课详情'
                }
              },
              // 列表
              {
                path: 'list',
                name: 'seriesBriefList',
                component: seriesBriefList,
                meta: {
                  pageTitle: '易灵微课-系列课详情'
                }
              },
            ],
          },
          // 订单页
          {
            path: 'order/:series_sn',
            name: 'seriesOrder',
            component: seriesOrder,
            meta: {
              pageTitle: '易灵微课-系列课详情'
            }
          },
          // 讲师主页
          {
            path: 'teacher/:series_sn',
            name: 'seriesTeacher',
            component: seriesTeacher,
            meta: {
              pageTitle: '易灵微课-讲师主页'
            },
            children: [
              {
                path: '',
                redirect: 'single'
              },
              // 单课
              {
                path: 'single',
                name: 'seriesTeacherSingle',
                component: seriesTeacherSingle,
                meta: {
                  pageTitle: '易灵微课-讲师主页'
                }
              },
              // 系列课
              {
                path: 'series',
                name: 'seriesTeacherSeries',
                component: seriesTeacherSeries,
                meta: {
                  pageTitle: '易灵微课-讲师主页'
                }
              },
            ],
          },
        ]
      },
      // 留言板
      {
        path: 'message/:lesson_sn',
        name: 'message',
        component: message,
        meta: {
          pageTitle: '易灵微课'
        },
        children: [
          {
            path: '',
            redirect: 'discuss'
          },
          // 讨论
          {
            path: 'discuss',
            name: 'messageDiscuss',
            component: messageDiscuss,
            meta: {
              pageTitle: '易灵微课-交流'
            }
          },
          // 作业
          {
            path: 'task',
            name: 'messageTask',
            component: messageTask,
            meta: {
              pageTitle: '易灵微课-交流'
            }
          },
          // 留言详情
          {
            path: 'detail/:cursor',
            name: 'messageDetail',
            component: messageDetail,
            meta: {
              pageTitle: '易灵微课-交流'
            }
          }
        ]
      },
      // 团体
      {
        path: 'group/:sn',
        name: 'group',
        component: group,
        meta: {
          pageTitle: '易灵微课'
        },
        children: [
          {
            path: '',
            redirect: 'create'
          },
          // 创建
          {
            path: 'create',
            name: 'groupCreate',
            component: groupCreate,
            meta: {
              pageTitle: '易灵微课-团体订单'
            }
          },
          // 报名
          {
            path: 'enroll',
            name: 'groupEnroll',
            component: groupEnroll,
            meta: {
              pageTitle: '易灵微课-团体报名'
            }
          },
          // 已报名
          {
            path: 'end',
            name: 'groupEnrollEnd',
            component: groupEnrollEnd,
            meta: {
              pageTitle: '易灵微课-团体报名'
            }
          },
        ]
      },
      // 排行榜
      {
        path: 'rank/:target',
        name: 'rank',
        component: rank,
        meta: {
          pageTitle: '易灵微课-排行榜'
        },
      },
    ]
  },
  {
    path: '/user',
    name: 'user',
    component: user,
    children: [
      // 课程列表页
      {
        path: '',
        name: 'userCenter',
        component: userCenter,
        meta: {
          pageTitle: '易灵微课-个人'
        }
      },
      // 已报名的课程
      {
        path: '/user/enrolled',
        name: 'enrolled',
        component: enrolled,
        meta: {
          pageTitle: '易灵微课-已报名的课程'
        }
      },
      // 课程保障页
      {
        path: '/user/guarantee',
        name: 'guarantee',
        component: guarantee,
        meta: {
          pageTitle: '易灵微课-课程保障'
        }
      },
      // 关于我们
      {
        path: '/user/about',
        name: 'about',
        component: about,
        meta: {
            pageTitle: '易灵微课-关于我们'
        }
      },
      // 建议与反馈
      {
        path: '/user/advise',
        name: 'advise',
        component: advise,
        meta: {
          pageTitle: '易灵微课-建议与反馈'
        }
      },
      // 账户中心
      {
        path: '/user/money',
        name: 'money',
        component: money,
        meta: {
          pageTitle: '易灵微课-账户中心'
        }
      },
      // 账户明细
      {
        path: '/user/money/detail',
        name: 'money-detail',
        component: moneyDetail,
        meta: {
          pageTitle: '易灵微课-余额明细'
        }
      },
      // 提取金额
      {
        path: '/user/money/out',
        name: 'money-out',
        component: moneyOut,
        meta: {
          pageTitle: '易灵微课-提现'
        }
      },
      // 兑换优惠券
      {
        path: '/user/money/coupon',
        name: 'money-coupon',
        component: moneyCoupon,
        meta: {
          pageTitle: '易灵微课-兑换优惠券'
        }
      },
      // 讲师关注列表
      {
        path: '/user/follow',
        name: 'teacherFollow',
        component: teacherFollow,
        meta: {
          pageTitle: '易灵微课-我关注的讲师'
        }
      },
      // 测试页面
      {
        path: '/user/test',
        name: 'user-test',
        component: userTest,
        meta: {
          pageTitle: '易灵微课-测试页面'
        }
      },
      // 测试页面
      {
        path: '/user/qrcode',
        name: 'user-qrcode',
        component: qrcode,
        meta: {
          pageTitle: '易灵微课-二维码'
        }
      },
    ]
  },
];
