<template>
  <div class="c-detail">
    <div class="profile">
      <detail-cover :profile="profile"></detail-cover>
      <div class="frm-profile">
        <div class="title font-bold">{{profile.title}}</div>
        <span class="series text-desc font-medium btn" v-if="profile.series" @click="go">
          所属系列 · {{profile.series.name}}
        </span>
        <div class="datum flex-row">
          <div class="info flex-col">
            <div class="flex-row" v-if="profile.plan">
              <i class="icon-yike icon-clock flex-col"></i>
              <span class="time text-desc font-medium flex-row">{{profile.plan.dtm_start}}</span>
              <status-label :status="profile.status"/>
            </div>
            <div class="price font-bold">￥{{profile.price}}</div>
          </div>
          <div class="flex-row">
            <div class="widget flex-col btn" @click="zsxq" v-if="conf.zsxq">
              <img class="icon-img" :src="app.linkToAssets('/img/logo/zsxq.png')">
              <span class="text-desc font-medium">知识星球</span>
            </div>
            <div class="widget flex-col btn" v-if="conf.activity" @click="activity">
              <i class="icon-yike icon-share"></i>
              <span class="text-desc font-medium" v-if="conf.activity">{{conf.activity.text}}</span>
            </div>
          </div>
        </div>
      </div>
      <detail-policy :policy="conf.policy"></detail-policy>
    </div>
    <div class="tabs">
      <tabs :items="tabs" :active="activeTab" v-on:switch="switchTab"></tabs>
    </div>
    <div class="teacher">
      <block title="讲师">
        <detail-teacher v-if="profile.teacher" :tusn="profile.teacher.sn"></detail-teacher>
        <a slot="more" v-if="profile.teacher" :href="`/user/teacher?usn=${profile.teacher.sn}`"
           class="icon-yike icon-arrow-r"></a>
      </block>
    </div>
    <div class="content border">
      <block title="简介">
        <div v-html="markdown(introduce)" class="markdown" @click="clickMarkdown"></div>
      </block>
    </div>
    <div class="relative border" v-if="relative.length">
      <block title="相关课程">
        <router-link slot="more" :to="`/lesson/series?sn=${profile.series.sn}#catalog`">
          <span>查看全部</span>
          <i class="icon-yike icon-arrow-r text-desc"></i>
        </router-link>
        <lesson-cell :lesson="item" v-for="(item,index) in relative" :key="index"></lesson-cell>
      </block>
    </div>
    <div class="rating border">
      <block :title="ratingTitle">
        <div slot="more" v-if="rating.stats">
          <a :href="app.linkToStudent(`/?v=1#/course/message/${sn}/task?lesson_sn=${sn}`)"
             class="btn">
            <span class="font-medium">已有{{rating.stats.turnout}}人评价</span>
            <i class="icon-yike icon-arrow-r text-desc"></i>
          </a>
        </div>
        <rating-cell v-for="(rate,index) in rating.list" :rate="rate" :key="index"></rating-cell>
      </block>
    </div>
    <div class="invite">
      <Detail-invite v-if="profile.sn" :target="profile.sn"></Detail-invite>
    </div>
    <div class="contact">
      <DetailContact></DetailContact>
    </div>
    <div id="promote-bubble" v-if="individual && individual.promote && check === 'enroll'">
      <promote-bubble :info="individual.promote"></promote-bubble>
    </div>
    <div class="control flex-row" v-if="individual">
      <div class="ctrl-home ctrl-icon" @click="home">
        <i class="icon-yike icon-home"></i>
        <span class="font-ragular">首页</span>
      </div>
      <div class="ctrl-zsxq ctrl-icon" v-if="conf.zsxq" @click="zsxq">
        <img :src="app.linkToAssets('/img/logo/zsxq.png')">
        <span class="font-ragular">星球</span>
      </div>
      <div class="ctrl-refund ctrl-text font-medium" @click="refund" v-if="individual.refund">退款</div>
      <div class="ctrl-locked ctrl-text font-medium" v-if="individual.status === 'refund'">已退款</div>
      <div class="ctrl-audition ctrl-text font-medium" @click="study" v-if="showAudition">
        限时试听 [{{individual.promote.extime | countdown}}]
      </div>
      <div class="ctrl-enroll ctrl-text font-medium" @click="enroll(true)"
           v-if="check === 'enroll' && !(app.os() === 'iOS' && app.env() === 'wxa')">立即报名
      </div>
      <div class="ctrl-locked ctrl-text font-medium"
           v-if="check === 'enroll' && app.os() === 'iOS' && app.env() === 'wxa'">小程序iOS端暂不能支付
      </div>
      <div class="ctrl-access ctrl-text font-medium" @click="study" v-else-if="check === 'access'">进入课堂</div>
      <div class="ctrl-locked ctrl-text font-medium" v-else-if="check === 'wait'">已报名，等待开课</div>
      <div class="ctrl-locked ctrl-text font-medium" v-else-if="check === 'closed'">课程已关闭</div>
      <div class="ctrl-locked ctrl-text font-medium" v-else-if="check === 'submit'">课程审核中</div>
      <div class="ctrl-locked ctrl-text font-medium" v-else-if="check === 'halted'">课程已下架</div>
    </div>
    <detail-order :order="order" v-on:cancel="cancelEnroll" v-on:complete="completeEnroll"></detail-order>
    <modal-action v-on:close="displayAfterEnroll = false" :display="displayAfterEnroll" width="7rem" v-if="individual">
      <div slot="head">报名成功</div>
      <ul>
        <li>永久回放</li>
        <li>听课开始1小时内无条件退款</li>
        <li v-if="!individual.subscribed">
          <span>关注公众号可接收开课提醒</span>
        </li>
      </ul>
      <div class="flex-row" v-if="!individual.subscribed">
        <img :src="app.linkToAssets('/img/qrcode/yike-fm.png')" style="width: 3rem; height: 3rem"
             @click="app.previewImageOne(app.linkToAssets('/img/qrcode/yike-fm.png'))"/>
      </div>
      <div slot="foot" class="btn btn-vice" @click="displayAfterEnroll = false" v-if="check==='access'">稍后再看</div>
      <div slot="foot" class="btn btn-primary" @click="study" v-if="check==='access'">开始学习</div>
      <div slot="foot" class="btn btn-primary" @click="displayAfterEnroll = false" v-else>知道了</div>
    </modal-action>
    <modal-action v-on:close="displayBeforeActivity = false" :display="displayBeforeActivity" width="7rem"
                  v-if="individual">
      <div slot="head">关注公众号不会走丢哦</div>
      <ul>
        <li>关注后可以获取【{{conf.activity.text}}】进度</li>
      </ul>
      <div class="flex-col">
        <img :src="app.linkToAssets('/img/qrcode/yike-fm.png')" style="width: 3rem; height: 3rem"
             @click="app.previewImageOne(app.linkToAssets('/img/qrcode/yike-fm.png'))"/>
        <div style="margin-top: .2rem">长按识别二维码关注</div>
      </div>
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
  </div>
</template>

<script>
  import qs from 'qs'
  import Tabs from '@/components/Tabs'
  import Block from '@/components/Block'
  import DetailCover from '../components/DetailCover'
  import DetailTeacher from '../components/DetailTeacher'
  import DetailPolicy from '../components/DetailPolicy'
  import DetailInvite from '../components/DetailInvite'
  import DetailContact from '../components/DetailContact'
  import DetailOrder from '../components/DetailOrder'
  import StatusLabel from '../components/unit/StatusLabel'
  import RatingCell from '../components/unit/RatingCell'
  import LessonCell from '../components/unit/LessonCell'
  import ModalAction from "../../components/modal/Action";
  import PromoteBubble from "../components/unit/PromoteBubble";
  import Filters from '@/assets/js/filters';

  const markdown = require('markdown-it')({html: true, breaks: true})

  export default {
    name: 'lesson-detail',
    components: {
      PromoteBubble,
      ModalAction,
      Tabs,
      Block,
      DetailCover,
      DetailTeacher,
      DetailPolicy,
      DetailInvite,
      DetailContact,
      DetailOrder,
      StatusLabel,
      RatingCell,
      LessonCell
    },
    data() {
      return {
        sn: null,
        profile: {},
        teacher: {},
        introduce: '',
        relative: [],
        individual: null,
        rating: {},
        conf: {},
        activeTab: 'lesson',
        CourseStatus: '',
        tabs: [
          {'key': 'lesson', name: '课程'},
          {'key': 'board', name: '交流'}
        ],
        order: null,
        follow: null,
        displayAfterEnroll: false,
        displayBeforeActivity: false
      }
    },
    created() {
      this.sn = this.$route.params.sn || this.$route.query.sn
      this.api.get('/api/jweixin-config', {url: location.href}).then((res) => {
        res.data.jsApiList = ['onMenuShareTimeline', 'onMenuShareAppMessage']
        this.wx.config(res.data)
      })
      this.api.get('/api/lesson-conf', {sn: this.sn}).then((res) => {
        this.conf = res.data
      })
      this.api.get('/api/lesson-profile', {
        sn: this.sn
      }).then((res) => {
          this.profile = res.data
          this.app.setTitle(this.profile.title)
          this.app.onShare({
            title: `易灵微课-${this.profile.title}`,
            desc: '永久回放，1小时不满意退款',
            imgUrl: this.profile.cover
          })
        }
      )
      this.api.get('/api/individual-lesson', {
        sn: this.sn,
        mark: this.$route.query.mark
      }).then((res) => {
        this.individual = res.data
        let action = this.$route.query.action || window.localStorage.getItem('action')
        if (action) {
          try {
            window.localStorage.removeItem('action')
            setTimeout(this[action], 100)
          } catch (e) {
          }
        }
        if (res.data.promote && res.data.promote.type==='audition') {
          setInterval(() => {
            this.individual.promote.extime--
          }, 1000)
        }
      }, window.localStorage.getItem('usn') ? this.api.onErrorSign : null).catch(() => {
        this.individual = {
          'status': 'fresh'
        }
      })
      this.api.get('/api/lesson-introduce', {
        sn: this.sn
      }).then((res) => {
        this.introduce = res.data
      })
      this.api.get('/api/lesson-relative', {
        sn: this.sn
      }).then((res) => {
        this.relative = res.data
      })
      this.api.get('/api/lesson-rating', {
        sn: this.sn
      }).then((res) => {
        this.rating = res.data
      })
    },
    computed: {
      check() {
        switch (this.individual.status) {
          case 'fresh':
          case 'reset':
            if (this.profile.series && this.profile.series.status==='halted') {
              return 'halted'
            } else if (['opened', 'onlive', 'repose', 'finish'].indexOf(this.profile.status) !== -1) {
              return 'enroll'
            } else {
              return this.profile.status
            }
          case 'enroll':
          case 'access':
            if (this.profile.status === 'opened') {
              return 'wait'
            } else if (['onlive', 'repose', 'finish', 'halted'].indexOf(this.profile.status) !== -1) {
              return 'access'
            } else {
              return this.profile.status
            }
          default:
            return this.individual.status
        }
      },
      ratingTitle() {
        let title = '评分'
        if (this.rating.stats) {
          title += `(${this.rating.stats.score === 0 ? 0 : this.rating.stats.score.toFixed(1)})`
        }
        return title
      },
      showAudition() {
        if (!(this.individual && this.individual.promote)) {
          return false
        }
        if (this.individual.promote.type === 'audition' &&
          this.individual.promote.extime > 0 &&
          this.check === 'enroll'
        ) {
          return true
        } else {
          return false
        }
      }
    },
    methods: {
      markdown(text) {
        return markdown.render(text || '');
      },
      switchTab(key) {
        this.activeTab = key
        switch (key) {
          case 'board':
            window.location.href = `/study/board/argue?sn=${this.sn}`
            break;
          default:
            break;
        }
      },
      enroll(re) { // 报名下单
        if (this.check !== 'enroll') {
          return false
        }
        if (this.individual.subscribed === false && this.conf.follow === 'force') {
          this.follow = 'force'
          return false
        }
        if (re) { // 暂存报名动作
          window.localStorage.setItem('action', 'enroll')
        }
        this.api.post('/api/order-book-lesson', {
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
      taste() { // 试听
        this.api.get('/api/individual-lesson', {
          sn: this.sn
        }).then((res) => {
          if (res.data.status === 'fresh') {
            this.enroll()
          }
        })
      },
      completeEnroll() { // 订单支付完成
        this.order = null
        // 重新获取课程状态
        this.api.get('/api/individual-lesson', {
          sn: this.sn
        }).then((res) => {
          this.individual = res.data
          if (this.profile.price === 0 && this.check === 'access') {
            this.study()
          } else {
            this.displayAfterEnroll = true
          }
        })
      },
      cancelEnroll() { // 取消报名
        this.order = null
      },
      home() { // 返回首页
        this.$router.push('/lesson/home')
        // window.location.href = this.app.linkToStudent('/')
      },
      zsxq() {
        if (!this.individual.sn) {
          window.localStorage.setItem('action', 'zsxq')
          this.app.signIn()
          return
        }
        if (['enroll', 'access'].indexOf(this.individual.status) === -1) {
          if (confirm('尚未报名，继续以游客身份访问星球')) {
            window.location.href = `/zsxq-entry?sn=${this.conf.zsxq.lesson}`
          }
        } else {
          if (this.individual.refund) {
            if (confirm('加入星球需放弃退款权利，是否继续')) {
              this.api.post('/api/order-confirm-lesson', {sn: this.sn}).then(() => {
                this.api.get('/api/individual-lesson', {
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
      study() { // 进入课堂
        if (this.profile.status === 'opened') {
          alert('尚未开课')
          return false
        }
        switch (this.profile.form) {
          case 'im':
          case 'im_hide':
            let query = qs.stringify({
              isOwner: 'no',
              teacherEnter: 'yes',
              lesson_sn: this.sn,
              teach: `${this.sn}-T`,
              discuss: `${this.sn}-D`
            })
            window.location.href = `/live?${query}`
            break;
          case 'view':
            window.location.href = `/study/view/${this.sn}`
            break;
          default:
            alert('未知的课堂形式'+this.profile.form)
        }
      },
      activity() {
        if (this.individual.subscribed) {
          window.location.href = this.conf.activity.href
        } else {
          this.axios.get(this.conf.activity.href)
          this.displayBeforeActivity = true
        }
      },
      refund() { // 退款
        let query = qs.stringify({
          isOwner: 'no',
          lesson_sn: this.sn,
          mode: this.individual.refund.mode,
          title: this.profile.title,
          teacher: this.profile.teacher.name
        })
        window.location.href = this.app.linkToStudent(`/?v=1#/course/refund?${query}`)
      },
      clickMarkdown(e) {
        if (e.target.nodeName === 'IMG') {
          this.app.previewImageOne(e.target.src)
        }
      },
      go(index) {
        location.href = `/lesson/series?sn=${this.profile.series.sn}`
      }
    },
    filters: {
      countdown: Filters.countdown
    }
  }
</script>
<style>
  @import '../../assets/css/markdown.css';
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
    height: .34rem;
    line-height: .34rem;
    font-size: .36rem;
    font-weight: bold;
    color: #0D0D0D;
  }

  .profile .datum {
    justify-content: space-between;
  }

  .profile .time {
    margin-right: .2rem;
    color: #808080;
  }

  .profile .info {
    justify-content: space-between;
  }

  .info .flex-row {
    height: .8rem;
    line-height: .8rem;
  }

  .profile .widget {
    justify-content: space-between;
    height: .92rem;
    padding-top: .2rem;
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

  span.series {
    height: .25rem;
    display: inline-block;
    text-decoration: none;
    color: #2F57DA;
    line-height: .25rem;
    margin-top: .3rem;
  }

  .content {
    margin-bottom: .3rem;
    padding-bottom: .3rem;
    background: #fff;
  }

  .icon-clock:before {
    padding-right: .13rem;
    color: #2F57DA;
    font-size: .4rem;
  }

  .price {
    display: flex;
    justify-content: flex-start;
    width: 100%;
    height: .32rem;
    line-height: .32rem;
    color: #F23F15;
    font-size: .42rem;
  }

  .control {
    z-index: 10;
    position: fixed;
    bottom: 0;
    height: 1rem;
    width: 7.5rem;
    font-size: .32rem;
    box-shadow: 0 0 0.1rem rgba(0, 0, 0, .1);
  }

  .control > div {
    height: 100%;
  }

  .ctrl-icon {
    width: 1rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    font-size: .2rem;
    color: #666;
    background: #fff;
    cursor: pointer;
  }

  .ctrl-icon > i {
    font-size: .4rem;
    color: #2F57DA;
    padding-top: .1rem;
  }

  .ctrl-icon > img {
    width: .5rem;
    height: .5rem;
    padding-top: .1rem;
    margin-bottom: -.04rem;
  }

  .ctrl-icon > span {
    font-size: .2rem;
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

  .ctrl-refund, .ctrl-audition {
    flex-grow: 0;
    color: #2F57DA;
    background: #fff;
    cursor: pointer;
    padding: 0 .68rem;
    border-left: 1px solid #ccc;
  }

  .ctrl-refund_ {
    flex-grow: 0;
    color: #fff;
    padding: 0 .68rem;
    background: #63a4fb;
    cursor: pointer;
  }

  .ctrl-locked {
    background: #ccc;
    color: #fff;
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

  #promote-bubble {
    position: fixed;
    right: 0;
    bottom: 1.5rem;
    z-index: 50;
  }
</style>
