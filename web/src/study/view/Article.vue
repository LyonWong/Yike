<template>
  <!--<div class="v-study-article" oncontextmenu='return false' oncopy="return false">-->
    <div class="v-study-article">
    <div class="profile" v-if="profile">
      <div class="cover" v-if="profile.cover">
        <img :src="`${profile.cover}!cc.750_400`"/>
      </div>
      <div class="title">{{profile.title}}</div>
      <div class="byline">
        <!--<div class="label">作者</div>-->
        <a class="name" :href="`/home/${profile.teacher.sn}/`">
          {{profile.teacher.name}}
        </a>
        <div class="date">
          {{profile.plan.dtm_start}}
        </div>
      </div>
      <div class="column" v-if="profile.series">
        <a class="name" :href="toColumn">
          {{profile.series.title}}
        </a>
      </div>
      <!--下滑时隐藏-->
      <div class="refund" v-if="conf.refundable && individual.refund && individual.refund.countdown>0 && refundOpacity" :style="{opacity: refundOpacity}">
        <div class="flex-row">
          <div class="countdown flex-row flex-item">
            <span class="time">
              {{individual.refund.countdown | countdown}}
            <!--<span class="colon">&nbsp;&nbsp;:&nbsp;&nbsp;:</span>-->
              <!--<span class="colon">0</span>-->
            </span>
          </div>
          <div class="btn btn-refund flex-row" @click="refund">退款</div>
        </div>
      </div>
    </div>
    <div class="content" v-if="profile">
      <div class="section" v-for="(row,idx) in records" :key="idx">
        <div class="locked flex-col" v-if="row.locked && individual && individual.status==='refund'">
          <span class="afterRefund">已退款</span>
        </div>
        <div class="locked flex-col" v-else-if="row.locked">
          <div class="hint">
            剩余
            <span v-if="row.locked.text">{{row.locked.text}}字</span>
            <span v-if="row.locked.image">{{row.locked.image}}张图片</span>
            <span v-if="row.locked.audio">{{row.locked.audio | durationTime }}音频</span>
            <span v-if="row.locked.video">{{row.locked.video | durationTime }}视频</span>
            <span v-if="(row.locked.text?1:0) + (row.locked.image?1:0) + (row.locked.audio?1:0) + (row.locked.video?1:0) <= 2">解锁后可见</span>
          </div>
          <div class="flex-col">
            <div class="btn purchase flex-col" v-if="conf.indie!==false">
              <!--<div class="price">￥{{profile.price}}</div>-->
              <div class="" @click="purchase">￥{{profile.price}}解锁文章</div>
              <span class="ifRefundable" v-if="conf.indie">— {{conf.refundable ? '1小时内可无条件退款' : '本文不支持退款'}} —</span>
            </div>
            <div class="btn purchase flex-col" v-if="profile.series">
              <!--<div class="price">￥{{profile.series.price}}</div>-->
              <a class="btn" :href="toColumn">￥{{profile.series.price}}订阅专栏</a>
              <span class="ifRefundable">— 解锁更多精彩内容 —</span>
            </div>
          </div>
        </div>
        <c-section :content="row.content" v-else-if="row.content.free"></c-section>
        <c-section :content="row.content" v-else oncopy="alert('付费内容禁止复制哦');return false" oncontextmenu="alert('付费内容禁止复制哦');return false"></c-section>
      </div>
    </div>
    <div class="nearby clearfix" v-if="nearby">
      <a class="float-left" v-if="nearby.prev" :href="`${$route.params.sn ? `./${nearby.prev.sn}` : './article?sn='}${nearby.prev.sn}`">上一篇</a>
      <a class="float-right" v-if="nearby.next" :href="`${$route.params.sn ? `./${nearby.prev.sn}` : './article?sn='}${nearby.next.sn}`">下一篇</a>
    </div>
    <!--以下内容待正文加载后再展示-->
    <!-- <div class="promote" v-if="profile && records.length"> -->
      <!-- <detail-invite :target="profile.sn"></detail-invite> -->
    <!-- </div> -->
    <div class="option flex-col" v-if="profile && records.length">
      <div class="invite flex-col">
        <div class="btn btn-invite" @click="invite">
          <i class="icon-yike icon-invite"></i>
          <span>分享</span>
        </div>
        <div class="desc-invite" v-if="profile.price && conf.commission">
          <span>邀请奖励￥{{profile.price * conf.commission | round(2) }}</span>
          <!--<a :href="app.linkToStudent(`/?v=2#course/rank/${sn}`)">查看排行榜</a>-->
        </div>
      </div>
      <admire class="admire flex-col" :sn="sn" :active="admire" v-on:cancel="admire=null">
        <div class="btn btn-admire" @click="admire=true">
          ￥赞赏
        </div>
      </admire>
    </div>
    <div class="discuss" v-if="conf.discuss!==false && records.length">
      <div class="board-head flex-row">
        <div class="board-tabs">
          <div class="board-tab font-bold">留言交流</div>
        </div>
        <div class="board-write" @click="discuss('argue')">写留言</div>
      </div>
      <div class="board-body">
        <board-list ref="argue" :sn="sn" type="argue"></board-list>
      </div>
    </div>
    <div class="purchase">
      <detail-order :order="order" v-on:cancel="cancelPurchase" v-on:complete="completePurchase"></detail-order>
      <refund-order :profile="profile" :refund="refundInfo" v-on:cancel="refundInfo=null" v-on:complete="completeRefund"></refund-order>
    </div>
    <div class="btn refundable" v-if="0 && conf.refundable && individual.refund && individual.refund.countdown>0" @click="refund">
      无条件退款 {{individual.refund.countdown | countdown}}
    </div>
    <div class="mask share mask-share flex-col" v-if="share" @click="share=null">
      <div class="via-weixin flex-item flex-col" v-if="app.env(2) === 'wx'">
        <i class="icon-yike icon-point"></i>
        <div class="serial">方式一</div>
        <div class="guide">点击右上角菜单，分享给好友</div>
      </div>
      <div class="via-link flex-item flex-col" v-else>
        <div class="serial">方式一</div>
        <div class="guide" @click.stop>分享邀请链接</div>
        <a class="guide">{{share}}</a>
        <div class="btn" v-clipboard="share" @success="bus.$emit('toast', {icon: 'ok', text:'复制成功', duration: 1000})">点击复制</div>
      </div>
      <hr>
      <div class="via-card flex-item flex-col">
        <div class="serial">方式二</div>
        <div class="guide">点击下方按钮，生成邀请卡</div>
        <a class="share btn" :href="`/promote/invite?sn=${sn}&origin=${origin}`">开始生成</a>
      </div>
    </div>
    <a id="backward" class="icon-yike icon-pencle flex-row" v-if="backward" :href="backward"></a>
  </div>
</template>

<script>
  // import qs from 'qs'
  import vue from 'vue'
  import qs from 'qs'
  import ClipBoards from 'vue-clipboards'
  import Filters from '@/assets/js/filters';
  import RefundOrder from "../../lesson/components/RefundOrder";
  import SecCtrl from "../../components/section/control"
  import Admire from "../components/Admire";
  const CSection = r => require.ensure([], () => r(require("../../components/section/index")), 'section')
  const BoardList = r => require.ensure([], () => r(require("../components/board/List")), 'board')
  const DetailOrder = r => require.ensure([], () => r(require("../../lesson/components/DetailOrder")))
  const DetailInvite = r => require.ensure([], () => r(require("../../lesson/components/DetailInvite")))
  vue.use(ClipBoards)
  export default {
    name: 'study-article',
    props: ['home'],
    components: {Admire, RefundOrder, CSection, BoardList, DetailOrder, DetailInvite},
    data() {
      return {
        sn: this.$route.params.sn || this.$route.query.sn,
        individual: {},
        profile: null,
        records: [],
        nearby: null,
        refundInfo: null,
        refundOpacity: 1,
        conf: {},
        order: null,
        share: null,
        admire: null,
        scrollTop: 0,
        backward: null // 是否显示返回按钮
      }
    },
    created() {
      // 微信分享
      this.api.get('/api/jweixin-config', {url: location.href}).then((res) => {
        res.data.jsApiList = ['onMenuShareTimeline', 'onMenuShareAppMessage'] // , 'updateAppMessageShareData', 'updateTimelineShareData']
        this.wx.config(res.data)
      })
      // 简介信息
      this.api.get('/api/lesson-profile', {
        sn: this.sn
      }).then((res) => {
        this.profile = res.data
        this.app.setTitle(this.profile.title)

        if (this.profile.status === 'closed') { // 下架内容跳转
          location.href = `/lesson/course/${this.sn}`
        }

        if (this.$route.query.psn) { // 有邀请码，自动领取
          this.api.post('/api/promote-draw', {
            psn: this.$route.query.psn
          })
        } else if (this.$route.query.inviter) { // 有邀请者，生成邀请码并自动领取
          this.api.post('/api/promote-invited', {
            sn: this.sn,
            origin: this.origin,
            from: this.$route.query.inviter
          })
        } else if (this.home && this.home !== this.profile.teacher.sn) { // 默认领取分销渠道(讲师渠道除外)邀请码，不覆盖领取
          this.api.post('/api/promote-home', {
            sn: this.$route.query.sn,
            from: this.home
          })
        }

        if (location.hash === '#submit') {
          if (this.profile && this.profile.series && this.profile.series.form==='column') {
            this.backward = `/create/column/${this.profile.series.sn}`
          } else {
            this.backward = `/create/posts#article`
          }
        }
      })
      this.init()
      // 上下文
      this.api.get('/api/lesson-nearby', {
        sn: this.sn
      }).then((res) => {
        this.nearby = res.data
      })
      // 配置信息
      this.api.get('/api/lesson-conf', {
        sn: this.sn
      }).then((res) => {
        this.conf = res.data
      })
      SecCtrl.init()
    },
    mounted() {
      window.addEventListener('scroll', () => {
        let scrollTop = document.documentElement.scrollTop || window.pageYOffset || document.body.scrollTop;
        let opacity = this.refundOpacity - (scrollTop - this.scrollTop)/200
        this.refundOpacity = Math.max(-2, Math.min(1, opacity))
        this.scrollTop = scrollTop
      })
    },
    methods: {
      init() {
        // 文章内容
        this.api.get('/api/study-article', {
          sn: this.sn,
          limit: -1
        }).then((res) => {
          this.records = res.data
        }, this.api.onErrorSign)
        // 个人信息
        this.api.get('/api/individual-lesson', {
          sn: this.sn
        }).then((res) => {
          this.individual = res.data
          // 行为继续
          let action = this.$route.query.action || window.localStorage.getItem('action')
          if (action) {
            try {
              window.localStorage.removeItem('action')
              setTimeout(this[action], 100)
            } catch (e) {
            }
          }
          // 退款倒计时
          // res.data.refund = {countdown: 1000}
          if (res.data.refund && res.data.refund.countdown) {
            setInterval(() => {
              this.individual.refund.countdown--
            }, 1000)
          }
          // 记录浏览一次
          this.api.post('/api/study-browse', {
            sn: this.sn,
            origin: this.origin
          })
          // 微信分享
          this.wx.ready(() => {
            let shareLink = location.origin + location.pathname + '?' + qs.stringify({
              sn: this.sn,
              inviter: this.individual.sn,
              origin: this.origin
            })
            this.app.onShare({
              link: shareLink,
              title: this.profile.title,
              imgUrl: this.profile.cover ? `${this.profile.cover}!previews` : this.profile.teacher.avatar,
              desc: '这篇文章不错'
            })
          })
        }, this.api.onErrorSign)
      },
      purchase(re) {
        if (re) { // 暂存报名动作
          window.localStorage.setItem('action', 'enroll')
        }
        if (this.conf.indie === false) {
          return false
        }
        this.api.post('/api/order-book-lesson', {
          sn: this.sn,
          origin: this.origin
        }).then((res) => {
          if (res.data) { // 付费课程报名
            this.order = res.data
          } else { // 免费课程报名
            this.completePurchase()
          }
        }, this.api.onErrorSign)
      },
      refund() { // 退款
        this.refundInfo = null
        this.api.get('/api/order-refund-article', {
          sn: this.sn
        }).then((res) => {
          this.refundInfo = res.data
        })
        /*
        let query = qs.stringify({
          isOwner: 'no',
          lesson_sn: this.sn,
          mode: this.individual.refund.mode,
          title: this.profile.title,
          teacher: this.profile.teacher.name
        })
        window.location.href = this.app.linkToStudent(`/?v=1#/course/refund?${query}`)
        */
      },
      cancelPurchase() {
        this.order = null
      },
      completePurchase() {
        this.order = null
        this.bus.$emit('toast', {icon: 'ok', text: '解锁成功', duration: 1000})
        // 报名后自动进入
        this.api.post('/api/study-access', {
          sn: this.sn
        }).then(() => {
          this.init()
        })
      },
      completeRefund() {
        this.refundInfo = null
        this.bus.$emit('toast', {icon: 'ok', text: '退款成功', duration: 1000})
        this.init()
      },
      invite() {
        this.api.get('/api/user-profile').then(() => {
          // todo 可转换未邀请码
          this.share = location.origin + location.pathname + '?' + qs.stringify({
            sn: this.sn,
            inviter: this.individual.sn,
            origin: this.origin
          })
          this.wx.ready(() => {
            this.app.onShare({
              link: this.share,
              title: this.profile.title,
              imgUrl: this.profile.cover ? `${this.profile.cover}!previews` : this.profile.teacher.avatar,
              desc: '这篇文章不错'
            })
          })
        }, this.api.onErrorSign)
      },
      discuss(type) {
        this.$refs[type].onPost()
      },
      onCopy(item) {
        if (item.content.free===false) {
          this.bus.$emit('dialog', {
            info: {body: '付费内容不能复制哦'}
          })
        }
        return item.content.free
      }
    },
    computed: {
      origin() {
        return this.$route.query.origin || (this.home ? `home-${this.home}` : null) || this.individual.origin
      },
      toColumn() {
        let href = this.home ? `/home/${this.home}/column?sn=${this.profile.series.sn}` : `/lesson/column?sn=${this.profile.series.sn}`
        // 前往专栏时，带上邀请人参数
        if (this.$route.query.inviter) {
          href += `&inviter=${this.$route.query.inviter}`
        } else if (this.individual && this.individual.promote) {
          href += `&inviter=${this.individual.promote.from.sn}`
        }
        return href
      }
    },
    filters: Filters
  }
</script>

<style scoped>
  .v-study-article {
    min-height: 100%;
    background: #fff;
  }

  .profile {
    background: #fff;
  }

  .cover > img {
    width: 7.5rem;
    height: 4rem;
  }

  .title {
    font-size: .5rem;
    padding: .3rem .6rem;
  }

  .byline {
    display: flex;
    align-items: center;
    padding: 0 .6rem;
    font-size: .28rem;
  }

  .byline .label {
    color: #888;
  }

  .byline .name {
    color: #2F57DA;
  }

  .byline .date {
    margin: 0 1em;
    color: #999;
  }

  .column {
    display: flex;
    padding: .3rem 0 0 .6rem ;
    font-size: .28rem;
  }

  .column .label {
    color: #888;
  }

  .column .name {
    color: #2F57DA;
  }

  .content, .nearby {
    padding: .3rem .6rem;
    background: #fff;
  }

  .nearby a {
    font-size: .3rem;
    color: #2F57DA;
  }

  .section {
    margin: 2em 0;
  }

  /* 旧样式
  .content .locked {
    position: relative;
    overflow: hidden;
    height: 3.6rem;
    background:linear-gradient(-135deg,rgba(203,230,254,1) 0%,rgba(144,200,255,1) 99%);
    border-radius: .1rem;
    justify-content: space-around;
  }
  .locked div {
    z-index: 2
  }
  .content .locked:before {
    position: absolute;
    left: -.9rem;
    bottom: -.5rem;
    content: ' ';
    width:2.8rem;
    height:2.8rem;
    background:linear-gradient(180deg,rgba(203,230,254,1) 0%,rgba(144,200,255,1) 99%);
    border-radius:50%;
  }

  .content .locked:after {
    position: absolute;
    right: -.5rem;
    top: -.9rem;
    content: ' ';
    width:2.8rem;
    height:2.8rem;
    background:linear-gradient(180deg,rgba(203,230,254,1) 0%,rgba(144,200,255,1) 99%);
    border-radius:50%;
  }
  .locked  .hint {
    display: flex;
    justify-content: flex-start;
    margin: .6rem 0 .3rem 0;
    font-size: .26rem;
    color: #fff;
  }
  .locked .price {
    color: #2F57DA;
    font-size: .3rem;
  }
  .locked .purchase {
    font-size: .4rem;
    height: .8rem;
    padding: 0 1em;
    color: #2F57DA;
    margin: .5em;
    border: 1px solid #2F57DA;
    background: #fff;
    border-radius: .4rem;
  }
  .locked > .subscribe {
    margin-top: 1em;
    font-size: .3rem;
    color: #2F57DA;
    border-bottom: 1px solid #2F57DA;
  }
  .locked > .ifRefundable {
    color: #2F57DA;
    margin: 1em;
    font-size: .22rem;
  }
  .locked > .afterRefund {
    color: #fff;
    font-size: .4rem;
  }
  */
  .locked .hint {
    color: #2F57DA;
    font-size: .28rem;
  }
  .locked .hint > span:after {
    content: '，'
  }
  .locked .hint > span:last-child:after {
    display: none;
  }
  .locked > .flex-col {
    margin: .3rem 0;
  }
  .locked .purchase {
    width: 6.3rem;
    font-size: .36rem;
    color: #2F57DA;
    border:1px solid #2F57DA;
    height: 1.2rem;
    border-radius: 4rem;
    /*padding: 0 .5em;*/
    background: #D8E1FD;
    margin: .5em;
  }
  .locked .purchase a {
    color: #2F57DA;
  }
  .locked .flex-col:first-child {
    /*margin-top: .5em;*/
    /*margin: .5em;*/
  }
  .locked .flex-col:last-child {
    /*margin-bottom: .5em;*/
    /*margin: .5em;*/
  }
  .locked .ifRefundable {
    margin-top: .5em;
    font-size: .2rem;
    color: #2F57DA;
  }
  .promote {
    margin-top: .3rem;
  }
  .discuss {
    background: #fff;
  }
  .board-head {
    justify-content: space-between;
    height: 1rem;
    line-height: 1rem;
    padding: 0 .3rem;
    border-top: .2rem solid #F2F2F2;
    border-bottom: 1px solid #ccc;
  }
  .board-tab {
    font-size: .3rem;
  }
  .board-write {
    color: #576B95;
    font-size: .3rem;
  }
  .board-body {
    padding: 0 .6rem;
  }
  /*
  .refundable {
    position: fixed;
    left: 0;
    background: rgba(47, 87, 218, 0.8);
    color: #fff;
    font-size: .3rem;
    padding: .2rem;
    border-radius: 0 .2rem .2rem 0;
  }
  */
  .refundable {
    position:fixed;
    width: 7.5rem;
    bottom: 0;
    background: #ddd;
    text-align: center;
    font-size: .3rem;
    padding: 1em 0;
    color: #2F57DA;
  }
  .option {
    background: #fff;
  }
  .option .btn {
    color: #2F57DA;
    font-size:.3rem;
    padding: .5em .8em;
    border: .02rem solid #2F57DA;
    border-radius: .36rem;
  }
  .btn-invite i{
    font-size: .3rem;
  }
  .invite {
    margin-bottom: .49rem;
  }
  .desc-invite span {
    font-size: .26rem;
    color: #999;
    margin: 0 .5em;
  }
  .desc-invite {
    margin-top: .3rem;
    font-size: .26rem;
  }
  .desc-invite a {
    color: #2F57DA;
    margin: 0 .5em;
  }
  .mask-share {
    color: #fff;
    background: rgba(0, 0, 0, 0.6)
  }
  .share > .flex-item > div {
    margin: .15rem 0;
  }
  .share hr {
    color: #fff;
    width: 3.3rem;
  }
  .share .serial {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 1rem;
    height: 1rem;
    font-size: .24rem;
    border: 1px solid #fff;
    border-radius: 50%;
  }
  .share .guide {
    margin: .15rem 0;
    padding: 0 .6rem;
    word-break: break-all;
  }
  .via-weixin {
  }
  .via-weixin .icon-point {
    position: absolute;
    top: .45rem;
    right: .25rem;
    font-size: .5rem;
  }
  .share .btn {
    margin-top: 1rem;
    padding: .2rem .36rem;
    color: #fff;
    background: #2F57DA;
    border-radius: .08rem;
  }
  .refund {
    position: fixed;
    top: 0;
    height: .8rem;
    font-size: .26rem;
    margin-top: 1rem;
    right: 0;
    display: flex;
    justify-content: flex-end;
    box-shadow: 0 0 10px #bbb;
    border-radius: .4rem 0 0 .4rem;
  }
  .btn-refund {
    background: #2F57DA;
    color: #fff;
    height: .8rem;
    padding: 0 1em;
  }
  .refund .countdown {
    /*box-shadow: 0 0 10px #999;*/
    background: #E9EEFF;
    height: .8rem;
    color: #2F57DA;
    padding: 0 1em;
    border-radius: .4rem 0 0 .4rem;
  }
  .time:before {
    content: " ";
    color: #f00;
    position: absolute;
    left: 1em;
    font-family: monospace;
    background: #0f0;
  }
  .refund .time {
    position: relative;
    /*margin-left: .5em;*/
    /*background: #2F57DA;*/
    /*background: linear-gradient(to right, #2F57DA 25%, transparent 25%, transparent 37.5%, #2F57DA 37.5%, #2F57DA 62.5%, transparent 62.5%, transparent 75%, #2F57DA 75%);*/
    /*font-family: monospace;*/
    color: #2F57DA;
  }
  .refund .colon {
    position: absolute;
    top: 0;
    left: 0;
    color: #f00;
    background: #0f0;
  }
  #backward {
    position: fixed;
    right: .3rem;
    top: .3rem;
    height: 1rem;
    width: 1rem;
    border-radius: 50%;
    font-size: .6rem;
    opacity: 0.8;
    /*background: #2F57DA;*/
    /*color: #fff;*/
    color: #333;
    background: #fff;
    box-shadow: 0 0 .1rem #999;
  }
</style>
