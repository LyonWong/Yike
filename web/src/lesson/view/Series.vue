<template>
  <div class="c-detail">
    <div class="profile">
      <detail-cover :profile="profile"></detail-cover>
      <div class="frm-profile">
        <div class="title font-bold">{{profile.title}}</div>
        <div class="datum flex-row">
          <div class="flex-col about">
            <div class="flex-row">
              <!--<i class="icon-yike icon-clock"></i>-->
              <span class="flex-row  text-desc font-medium"
                    v-if="profile.progress">已开 {{profile.progress[1]}}/{{profile.progress[0]}}节</span>
            </div>
            <div class="price-frm flex-row">
              <div class="on-sale flex-row" v-if="profile.price && 0">
                <span class="font-medium">特价特惠</span>
                <span class="on-sale-tag"></span>
              </div>
              <div class="price">
                <span class="n-price font-bold">￥{{profile.price}}</span>
                <span class="o-price" v-if="profile._price">￥{{profile._price}}</span>
              </div>
            </div>
          </div>
          <div class="flex-row">
          <div class="widget flex-col btn" @click="zsxq" v-if="conf.zsxq">
            <img class="icon-img" :src="app.linkToAssets('/img/logo/zsxq.png')"/>
            <span class="font-medium text-desc">知识星球</span>
          </div>
          <div class="widget flex-col btn" v-if="conf.activity" @click="activity">
            <i class="icon-yike icon-share"></i>
            <span class="font-medium text-desc" v-if="conf.activity">{{conf.activity.text}}</span>
          </div>
          </div>
        </div>
      </div>
      <detail-policy :policy="conf.policy"></detail-policy>
    </div>
    <div class="tabs" id="tabs">
      <tabs :items="tabs" :active="activeTab" v-on:switch="switchTab"></tabs>
    </div>
    <div id="lesson">
      <div id="teacher" class="teacher">
        <block title="讲师">
          <detail-teacher v-if="profile.teacher" :tusn="profile.teacher.sn"></detail-teacher>
          <a slot="more" v-if="profile.teacher" :href="`/user/teacher?usn=${profile.teacher.sn}`" class="icon-yike icon-arrow-r"></a>
        </block>
      </div>
      <div id="introduce" class="content border">
        <block title="简介">
          <div class="markdown" v-html="markdown(introduce)" @click="clickMarkdown"></div>
        </block>
      </div>
    </div>
    <div id="catalog">
      <div id="category" class="relative border" v-if="relative.length">
        <block title="目录">
          <lesson-cell :lesson="lessonStatus(item)" v-for="(item,index) in relative" :key="index"></lesson-cell>
        </block>
      </div>
    </div>
    <div class="invite">
      <Detail-invite v-if="profile.sn" :target="profile.sn"></Detail-invite>
    </div>
    <div class="contact">
      <DetailContact></DetailContact>
    </div>
    <div id="promote-bubble" v-if="individual && individual.promote && canEnroll">
      <promote-bubble :info="individual.promote"></promote-bubble>
    </div>
    <div class="control flex-row" v-if="individual">
      <div class="ctrl-home ctrl-icon" @click="home">
        <i class="icon-yike icon-home"></i>
        <span class="font-ragular">首页</span>
      </div>
      <div class="ctrl-portal ctrl-icon" v-if="conf.portal" @click="portal">
        <img :src="conf.portal.icon"/>
        <span class="font-ragular">{{conf.portal.text}}</span>
      </div>
      <div class="ctrl-zsxq ctrl-icon" v-if="conf.zsxq" @click="zsxq">
        <img :src="app.linkToAssets('/img/logo/zsxq.png')"/>
        <span class="font-ragular">星球</span>
      </div>
      <div class="ctrl-locked ctrl-text font-medium" v-if="check === 'invalid'">无效课程</div>
      <div class="ctrl-locked ctrl-text font-medium" v-if="check === 'refund'">已全部退款</div>
      <div class="ctrl-refund ctrl-text font-medium" v-if="canRefund" @click="refund">退款</div>
      <div class="ctrl-free ctrl-text font-medium " v-if="freeTry && check === false"
           @click="$router.push(`/lesson/detail?sn=${freeTry}&action=taste`)">
        免费试听
      </div>
      <div class="ctrl-locked ctrl-text font-medium" v-if="canEnroll && profile.status==='halted'">课程已下架</div>
      <div class="ctrl-locked ctrl-text font-medium" v-else-if="canEnroll && app.os() === 'iOS' && app.env() === 'wxa'">小程序iOS端暂不能支付</div>
      <div class="ctrl-enroll ctrl-text font-medium" @click="enroll(true)" v-else-if="canEnroll === true || canEnroll === individual.lesson">报名系列课</div>
      <div class="ctrl-enroll_ ctrl-text font-medium" @click="enroll" v-else-if="canEnroll && canEnroll < individual.lesson">报名剩余课程</div>
      <div class="ctrl-access ctrl-text font-medium" @click="study" v-if="check === 'access'">进入课堂</div>
      <div class="ctrl-locked ctrl-text font-medium" v-if="check === 'pending'">待开课</div>
    </div>
    <detail-order :order="order" v-on:cancel="cancelEnroll" v-on:complete="completeEnroll">
    </detail-order>
    <series-refund :refund="refundInfo" v-on:cancel="cancelRefund" v-on:complete="completeRefund"></series-refund>
    <modal-action v-on:close="displayAfterEnroll = false" :display="displayAfterEnroll" width="7rem" v-if="individual">
      <div slot="head">报名成功</div>
      <ul>
        <li>永久回放</li>
        <li>进入课堂1小时内无条件退款</li>
        <li v-if="!individual.subscribed">
          <span>关注公众号可接收开课提醒</span>
        </li>
      </ul>
      <div class="flex-row" v-if="!individual.subscribed">
        <img :src="app.linkToAssets('/img/qrcode/yike-fm.png')" style="width: 3rem; height: 3rem" @click="app.previewImageOne(app.linkToAssets('/img/qrcode/yike-fm.png'))"/>
      </div>
      <div slot="foot" class="btn btn-vice" @click="displayAfterEnroll = false" v-if="check==='access'">稍后再看</div>
      <div slot="foot" class="btn btn-primary" @click="study" v-if="check === 'access'">开始学习</div>
      <div slot="foot" class="btn btn-primary" @click="displayAfterEnroll = false" v-else>知道了</div>
    </modal-action>
    <modal-action v-on:close="displayBeforeActivity = false" :display="displayBeforeActivity" width="7rem" v-if="individual && conf.activity">
      <div slot="head">关注公众号不会走丢哦</div>
      <ul>
        <li>关注后可以获取【{{conf.activity.text}}】进度</li>
      </ul>
      <div class="flex-col">
        <img :src="app.linkToAssets('/img/qrcode/yike-fm.png')" style="width: 3rem; height: 3rem" @click="app.previewImageOne(app.linkToAssets('/img/qrcode/yike-fm.png'))"/>
        <div style="margin-top: .2rem">长按识别二维码关注</div>
      </div>
      <!--<div class="flex-row">-->
      <!--</div>-->
      <a slot="foot" class="btn btn-vice" :href="conf.activity.href">暂不关注</a>
    </modal-action>
    <modal-action v-on:close="follow=null" :display="follow" width="7rem" v-if="individual">
      <div slot="head">关注公众号才能报名哦</div>
      <div class="flex-col">
        <img :src="app.linkToAssets('/img/qrcode/yike-fm.png')" style="width: 3rem; height: 3rem"
             @click="app.previewImageOne(app.linkToAssets('/img/qrcode/yike-fm.png'))"/>
        <div style="margin-top: .2rem">微信识别二维码关注</div>
      </div>
      <a slot="foot" class="btn btn-vice" @click="follow=null">暂不关注</a>
      <a slot="foot" class="btn btn-primary" href="https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=MzUyMDAzNDYxNw==#wechat_redirect">前往关注</a>
    </modal-action>
    <modal-dialog v-if="dialog" :dialog="dialog" width="6rem" v-on:close="dialog=null"></modal-dialog>
  </div>
</template>

<script>
  import qs from 'qs'
  import Tabs from '@/components/Tabs'
  import Block from '@/components/Block'
  import Loading from '@/components/Loading'
  import DetailCover from '../components/DetailCover'
  import DetailTeacher from '../components/DetailTeacher'
  import DetailPolicy from '../components/DetailPolicy'
  import DetailInvite from "../components/DetailInvite";
  import DetailContact from '../components/DetailContact'
  import DetailOrder from '../components/DetailOrder'
  import SeriesRefund from "../components/SerieslRefund"
  import StatusLabel from '../components/unit/StatusLabel'
  import RatingCell from '../components/unit/RatingCell'
  import LessonCell from '../components/unit/LessonCell'
  // import ModalAction from "../../components/modal/Action"
  import PromoteBubble from "../components/unit/PromoteBubble"

  const  ModalAction = () => import('@/components/modal/Action')
  const  ModalDialog = () => import('@/components/modal/Dialog')
  const markdown = require('markdown-it')({html: true, breaks: true})

  export default {
    name: 'lesson-detail',
    components: {
      PromoteBubble,
      Loading,
      Tabs,
      Block,
      DetailCover,
      DetailTeacher,
      DetailPolicy,
      DetailInvite,
      DetailContact,
      DetailOrder,
      SeriesRefund,
      StatusLabel,
      RatingCell,
      LessonCell,
      ModalAction,
      ModalDialog
    },
    data() {
      return {
        sn: null,
        initializing: 5,
        profile: {},
        teacher: {},
        introduce: '',
        relative: [],
        individual: null,
        freeTry: null,
        rating: {},
        activeTab: 'lesson',
        CourseStatus: '',
        tabs: [
          {'key': 'lesson', name: '课程'},
          {'key': 'catalog', name: '目录'}
        ],
        conf: {},
        order: null,
        refundInfo: null,
        follow: null,
        displayAfterEnroll: false,
        displayBeforeActivity: false,
        dialog: null
      }
    },
    created() {
      this.sn = this.$route.params.sn || this.$route.query.sn
      this.api.get('/api/jweixin-config', {url: location.href}).then((res) => {
        res.data.jsApiList = ['onMenuShareTimeline', 'onMenuShareAppMessage']
        this.wx.config(res.data)
      })
      this.api.get('/api/individual-series', {
        sn: this.sn,
        mark: this.$route.query.mark
      }).then((res) => {
        this.initializing--
        this.individual = res.data
        // 检查前置动作
        let action = this.$route.query.action || window.localStorage.getItem('action')
        if (action) {
          try {
            window.localStorage.removeItem('action')
             setTimeout(this[action], 200)
          } catch (e) {}
        }
      }, window.localStorage.getItem('usn') ? this.api.onErrorSign : null).catch(() => {
        this.individual = {}
      })
      this.api.get('/api/series-profile', {
        sn: this.sn
      }).then((res) => {
        this.initializing--
          this.profile = res.data
          this.app.setTitle(this.profile.title)
          this.app.onShare({
            title: `易灵微课-${this.profile.title}`,
            desc: '永久回放，1小时不满意退款',
            imgUrl: this.profile.cover
          })
        }
      )
      this.api.get('/api/series-introduce', {
        sn: this.sn
      }).then((res) => {
        this.initializing--
        this.introduce = res.data.content
      })
      this.api.get('/api/series-relative', {
        sn: this.sn
      }).then((res) => {
        this.initializing--
        this.relative = res.data
        for (let lesson of res.data) {
          if (lesson.price === 0) {
            this.freeTry = lesson.sn
            return
          }
        }
      })
      this.api.get('/api/series-conf', {
        sn: this.sn
      }).then((res) => {
        this.initializing--
        this.conf = res.data
      })
    },
    mounted() {
    },
    updated() {
    },
    watch: {
      initializing: function(v) {
        if (v === 0) {
          setTimeout(this.initialized, 100)
        }
      }
    },
    computed: {
      check() {
        if (!this.profile.sn) {
          return 'invalid'
        }
        if (!this.individual.lesson) {
          return false
        }
        let access = false
        for (let lesson of this.individual.access) {
          if (lesson.step !== 'opened') {
            return 'access' // 有任一课程可观看
          }
          access = true
        }
        if (this.individual.access.length + this.individual.enroll.length === 0) {
          return 'refund' // 已全部退款
        }
        return access ? 'pending' : false; // 全部课程尚未开启
      },
      canEnroll() {
        if (this.individual.enroll) { // 已登录，可获取到报名记录
          return this.individual.enroll.length
        } else { // 未登录时显示可报名
          return true
        }
      },
      canRefund() {
        if (this.individual.refund) {
          return this.individual.refund.length
        } else {
          return 0
        }
      }
    },
    methods: {
      initialized() {
        if (this.$route.query.tab) {
          this.switchTab(this.$route.query.tab)
        }
        window.addEventListener('scroll', this.menu)
      },
      markdown(text) {
        return markdown.render(text || '');
      },
      switchTab(key) {
        let tabHeight = document.getElementById('tabs').offsetHeight
        let offsetTop = document.getElementById(key).offsetTop
        document.body.scrollTop = document.documentElement.scrollTop = offsetTop - tabHeight
        this.activeTab = key
      },
      enroll(re) { // 报名下单
        if (!this.canEnroll) {
          return false
        }
        if (this.individual.subscribed === false && this.conf.follow === 'force') {
          this.follow = 'force'
          return false
        }
        if (re) {
          window.localStorage.setItem('action', 'enroll')
        }
        this.api.post('/api/order-book-series', {
          sn: this.sn,
          origin: this.$route.query.origin
        }).then((res) => {
          if (res.data) { // 付费课程报名
            this.order = res.data
          } else { // 免费课程报名
            this.completeEnroll()
          }
        }, this.api.onErrorSign)
      },
      completeEnroll() { // 订单支付完成
        this.order = null
        // this.app.enableBodyScroll()
        // 重新获取课程状
        this.api.get('/api/individual-series', {
          sn: this.sn
        }).then((res) => {
          this.individual = res.data
          this.displayAfterEnroll = true
        })
      },
      cancelEnroll() { // 取消报名
        this.order = null
        window.localStorage.removeItem('action')
      },
      home() {
        this.$router.push('/lesson/home')
      },
      study() { // 进入课堂
        for (let lesson of this.individual.access) {
          if (lesson.step !== 'opened') {
            switch (lesson.form) {
              case 'im':
              case 'im_hide':
                let query = qs.stringify({
                  isOwner: 'no',
                  lesson_sn: lesson.sn,
                  teach: `${lesson.sn}-T`,
                  discuss: `${lesson.sn}-D`
                })
                window.location.href = `/live?${query}`
                return
              case 'view':
                window.location.href = `/study/view/${lesson.sn}`
                return
            }
          }
        }
      },
      zsxq() {
        if (!this.individual.sn) {
          window.localStorage.setItem('action', 'zsxq')
          this.app.signIn()
          return
        }
        if (this.individual.access.length < this.individual.lesson) {
          if (confirm('购买完整系列课才能加入星球，是否继续以游客身份访问星球')) {
            window.location.href = `/zsxq-entry?sn=${this.conf.zsxq.lesson}`
          }
        } else {
          if (this.individual.refund.length) {
            if (confirm('加入星球需放弃退款权利，是否继续')) {
              this.api.post('/api/order-confirm-series', {sn: this.sn}).then(() => {
                this.api.get('/api/individual-series', {
                  sn: this.sn
                }).then((r) => {
                  this.individual = r.data
                  this.zsxq()
                })
              }).catch((e) => {
                alert(e.message)
              })
            }
          } else {
            window.location.href = `/zsxq-entry?sn=${this.conf.zsxq.lesson}`
          }
        }
      },
      portal() {
        this.api.get(this.conf.portal.test).then(() => {
          if (this.conf.portal.path) { // 跳转外部地址
            window.open(this.conf.portal.path)
          }
          if (this.conf.portal.call) { // 执行内部操作
            let call = () => {
              window.localStorage.setItem(`PortalCalled-${this.sn}`, '1')
              this.api.post(this.conf.portal.call).then((res) => {
                this.dialog = {
                  info: {
                    head: '提醒',
                    body: res.data
                  }
                }
              })
            }
            let called = window.localStorage.getItem(`PortalCalled-${this.sn}`)
            if (this.conf.portal.ask && !called) { // 执行前先询问确认
              this.dialog = {
                info: {
                  head: '请确认',
                  body: this.conf.portal.ask
                },
                btn: {
                  prime: '确认',
                  vice: '取消'
                },
                call: {
                  prime: call
                }
              }
            } else { // 不询问直接执行
              call()
            }
          }
        }, (res) => {
          window.localStorage.setItem('action', 'portal')
          switch (res.error) {
            case '0.1':
              this.app.signIn()
              break;
            case '2':
              if (res.data.info) {
                this.dialog = {
                  info: {
                    head: res.data.info
                  },
                  btn: {
                    prime: '确认',
                    vice: '取消'
                  },
                  call: { // 返回结果有goto，则跳转执行
                    prime: res.data.goto  ? () => {
                      window.location.href = res.data.goto
                    } : null
                  }
                }
              } else {
                alert(res.data.info)
              }
              break;
          }
        })
      },
      activity() { // 邀请卡
        // window.location.href = `/promote/invite?sn=${this.sn}`
        if (this.individual.subscribed) {
          window.location.href = this.conf.activity.href
        } else {
          this.axios.get(this.conf.activity.href)
          this.displayBeforeActivity = true
        }
      },
      confirm(next) {
        if (confirm('是否放弃退款权利？')) {
          this.api.post('/api/order-confirm-series', {sn: this.sn}).then(() => {
            this.api.get('/api/individual-series', {
              sn: this.sn
            }).then((r) => {
              this.individual = r.data
              if (next) { // 执行后续操作
                this[next]()
              }
            })
          }).catch((e) => {
            alert(e.message)
          })
        }
      },
      refund() { // 退款
        this.api.get('/api/order-refund-series', {
          sn: this.sn
        }).then((res) => {
          this.refundInfo = res.data
        })
      },
      cancelRefund() {
        this.refundInfo = null
      },
      completeRefund() {
        this.refundInfo = null
        this.api.get('/api/individual-series', {
          sn: this.sn
        }).then((res) => {
          this.individual = res.data
        })
        alert('退款完成')
      },
      lessonStatus(lesson) {
        if (this.individual && this.individual.events) {
          if (lesson.status === 'finish') {
            lesson.status = this.individual.events[lesson.sn] || lesson.status
          }
        }
        return lesson
      },
      menu() {
        let d = document.documentElement
        if (navigator.userAgent.indexOf('Mobile') !== -1) {
          d = document.body
        }
        let tabsHeight = document.getElementById('tabs').offsetHeight
        if (d.scrollTop >= document.getElementById('catalog').offsetTop - tabsHeight || d.scrollHeight - d.scrollTop === d.clientHeight) {
          this.activeTab = 'catalog'
        } else {
          this.activeTab = 'lesson'
        }
      },
      clickMarkdown(e) {
        if (e.target.nodeName === 'IMG') {
          this.app.previewImageOne(e.target.src)
        }
      }
    }
  }
</script>

<style>
  @import "../../assets/css/markdown.css";
</style>

<style scoped>
  .border {
    border-bottom: 1px #ddd solid;
  }

  .c-detail {
    padding-bottom: 1rem;
    background: #f0eff5;
  }

  .profile {
    margin-bottom: .3rem;
    background: #fff;
  }

  .frm-profile {
    padding: .4rem .3rem;
  }

  .profile .title {
    height: .38rem;
    line-height: .38rem;
    font-size: .36rem;
    font-weight: bold;
    color: #0D0D0D;
  }

  .profile .datum {
    justify-content: space-between;
    padding-top: .2rem;
  }

  .profile .info {
    height: 1rem;
    justify-content: space-between;
  }

  .profile .widget {
    color: #666;
    margin-left: .2rem;
  }

  .widget .icon-share {
    color: #2F57DA;
    font-size: .5rem;
  }

  .widget .icon-img {
    width: .7rem;
    height: .7rem;
    margin: -.05rem 0 -.1rem 0;
  }

  .series {
    font-size: .24rem;
    color: #0D0D0D;
    line-height: .36rem;
    padding-top: .26rem;
  }

  .content {
    margin-bottom: .3rem;
    padding-bottom: .3rem;
    background: #fff;
  }

  .icon-clock {
    height: .3rem;
    line-height: .3rem;
    padding-right: .13rem;
  }

  .icon-clock:before {
    color: #2F57DA;
    font-size: .4rem;
  }

  .icon-clock + span {
    color: #808080;
    height: .3rem;
  }

  .price {
    display: flex;
    justify-content: flex-start;
    width: 100%;
    color: #F23F15;
    font-size: .42rem;
  }

  .control {
    z-index: 10;
    position: fixed;
    bottom: 0;
    height: 1rem;
    width: 7.5rem;
    box-shadow: 0 0 0.1rem rgba(0, 0, 0, .1);
    font-size: .32rem;
  }

  .control > div {
    height: 100%;
  }

  .ctrl-icon {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    font-size: .2rem;
    color: #666;
    background: #fff;
    cursor: pointer;
    width: 1rem;
  }

  .ctrl-icon > i {
    margin-top: .18rem;
    padding: 0 .3rem;
    font-size: .4rem;
    height: .4rem;
    color: #2F57DA;
  }
  .ctrl-icon > img {
    margin-top: .18rem;
    padding: 0 .3rem;
    width: .4rem;
    height: .4rem;
  }
  .ctrl-icon > span {
    font-size: .2rem;
    height: .42rem;
    line-height: .42rem;
  }
  .ctrl-icon.ctrl-zsxq > img {
    width: .4rem;
    height: .4rem;
    /*padding-bottom: .03rem;*/
    margin-bottom: -.03rem;
  }

  .ctrl-text {
    padding: 0 .32rem;
    display: flex;
    flex-grow: 1;
    align-items: center;
    justify-content: center;
  }

  .ctrl-enroll, .ctrl-access {
    color: #fff;
    background: #2F57DA;
    cursor: pointer;
  }

  .ctrl-refund, .ctrl-free {
    flex-grow: 0;
    background: #fff;
    color: #2F57DA;
    cursor: pointer;
    border-left: 1px solid #ccc;
  }

  .ctrl-enroll_ {
    color: #fff;
    padding: 0 1em;
    background: #63a4fb;
    cursor: pointer;
  }

  .ctrl-locked {
    color: #fff;
    background: #ccc;
  }

  .tabs {
    position: sticky;
    top: 0;
    cursor: pointer;
    z-index: 9;
  }

  .rating {
    margin-top: .3rem;
  }

  .contact {
    margin-top: .3rem;
  }

  .datum > .flex-col.about {
    justify-content: space-between;
    align-items: flex-start;
  }

  .price-frm {
    justify-content: flex-start;
    align-items: center;
    width: 100%;
    height: 0.36rem;
    padding-top: .2rem;
  }

  .on-sale {
    background: #F23F15;
    color: white;
    text-align: center;
  }

  .on-sale > span:first-child {
    display: block;
    width: .8rem;
    text-align: center;
    font-size: .2rem;
    padding-left: .2rem;
  }

  .on-sale-tag {
    border: #F23F15 0.2rem solid;
    border-right: 0.1rem solid #fff;
  }

  .price {
    align-items: flex-end;
    height: 0.36rem;
    line-height: 0.36rem;
  }

  .n-price {
    /*width: 0.8rem;*/
    /*padding-left: 0.31rem;*/
    font-size: 0.42rem;
    color: #F23F15;
  }

  .o-price {
    padding-left: 0.1rem;
    font-size: 0.27rem;
    text-decoration: line-through;
    color: #808080;
    line-height: 1;
  }

  .o-price + div {
    width: 0.96rem;
    height: 0.23rem;
    padding: 0.13rem 0 0 2.45rem;
    font-size: 0.2rem;
    text-align: right;
    color: #666666;
  }

  #promote-bubble {
    position: fixed;
    right: 0;
    bottom: 1.5rem;
    z-index: 50;
  }
</style>
