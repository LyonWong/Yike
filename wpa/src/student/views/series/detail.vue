<template>
  <section class="content detail series">
    <div v-if="series">
      <div class="detail-img" v-if="series.introduce.cover">
        <img :src="`${series.introduce.cover}`" />
      </div>
      <div class="tab">
        <router-link :to="{name:'seriesBrief'}" replace>介绍</router-link>
        <router-link :to="{name:'seriesBriefList'}" replace>目录</router-link>
      </div>
      <router-view v-if="series"></router-view>
      <div class="lesson-more" v-if="showMore">
        <div class="more-container">
          <!--<div @click="backToHome">课程列表</div>-->
          <div v-for="menu in series.conf.menu" @click="jumpToAppoint(menu.href)">{{menu.text}}</div>
          <!--<div @click="takePromote">参与推广课程</div>-->
          <!--<div @click="becomeTeacher">注册成为讲师</div>-->
        </div>
      </div>
      <s-button :text="text" :callBack="goToOrder" :disabled="disabled" v-if="!start" @showMenu="showMenu"></s-button>
      <s-button :text="starting" v-if="start"></s-button>
    </div>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';
  import sButton from '@student/components/button.vue';

  export default{
    name: 'series-detail',
    components: {
      sButton,
    },
    props: {
      isEnroll: {
        type: null
      },
    },
    computed: {
      ...mapGetters({
        userInfo: 'getUserInfo',
        assetsHost:'getAssetsHost',
      })
    },
    data() {
      return {
        start: false,
        series: null,
        text: '加载中...',
        starting: '订单生成中...',
        disabled: true,
        showMore: false,
        showLoading: false,
        liveHost: (process.env.NODE_ENV=='production'?process.env.LIVE_HOST.replace(/\/$/,'/live'):'/live.html'),
        studentShareHost: (process.env.NODE_ENV=='production'?process.env.STUDENT_HOST:'/student.html?'),
        signUpHost:(process.env.TEACHER_HOST ? process.env.TEACHER_HOST.replace(/\/$/,'') : 'https://teacher.sandbox.yike.fm/'),
      };
    },
    created() {
      if (location.search.indexOf('?v=2') >=0 ) {
        window.location.href = `${process.env.LIVE_HOST}lesson/series?sn=${this.$route.params.series_sn}&origin=${this.$route.query.origin || ''}`
        return
      }
      // 获取路由参数
      let params = this.$route.params;
      // 开始获取详情
      this.$store.dispatch('fetchSeriesDetail', params).then((data) => {
        this.series = data;
        if(data.purchase_check) {
          this.text = '报名系列课';
          this.disabled = false;
        }else {
          this.text = '已购买';
          this.disabled = true;
        }
        // 开始调用分享接口
        this.wxShare();
        console.log('success');
      }, () => {
        console.log('fail');
      });
    },
    mounted() {
      /*this.briefFold = (this.$refs['b-text'].offsetHeight>191?true:false);*/
    },
    methods: {
      showMenu(show) {
        this.showMore = show;
      },
      backToHome() {
        this.$router.push({ name: 'list' });
      },
      takePromote() {
        window.location.href = `/promote?target_sn=${this.series.sn}`;
      },
      becomeTeacher() {
        // 跳转
        window.location.href = `${this.signUpHost}/sign-up`;
      },
      jumpToAppoint(href) {
        window.location.href = href;
      },
      goToOrder() {
        // 开始订单详情
        this.start = true;
        this.$store.dispatch('fetchSeriesCheckOrder', this.$route.params).then((data) => {
          // 跳转到订单页
          if (window.__wxjs_environment === 'miniprogram') {
            this.$http.post('/series-order.api', {
              series_sn: this.$route.params.series_sn,
              origin: this.$route.query.origin
            }, {emulateJSON: true}).then((response) => {
              let res = response.body
              this.start = false;
              if (res.error === '0') {
                wx.miniProgram.navigateTo({
                  url: `/page/pay/index?order=${res.data.order}&tsn=${this.$route.params.series_sn}`
                });
              } else {
                swal({
                  title: '错误提醒',
                  text: (res.message ? res.message : '请求错误'),
                  confirmButtonText: '知道了'
                })
              }
            });
          } else if(this.$route.query.origin) {
            this.$router.push({ name: 'seriesOrder', params: { ...this.$route.params }, query: {origin: this.$route.query.origin} });
          }else {
            this.$router.push({ name: 'seriesOrder', ...this.$route.params });
          }

        }, (err) => {
          this.start = false;
          //
          swal({
            title: '错误提醒',
            text: (err.message ? err.message : '网络链接失败'),
            confirmButtonText: '知道了'
          });
        });
      },
      wxShare() {
        // 请求配置接口
        if(isWeiXin){
          this.$store.dispatch('fetchWXConfig', {url:encodeURIComponent(window.location.href)}).then((result)=>{
            // 微信操作
            wx.config({
              debug: false,
              appId: result.appId,
              timestamp: result.timestamp,
              nonceStr: result.nonceStr,
              signature: result.signature,
              jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage']
            });
            // 可以分享了
            console.log('可以分享');
            this.wxConfig(this.series);
            // 可以支付
            this.$store.commit('FETCH_SERIES_PAY', true);
          },()=>{
            // 不能分享
            console.log('不能分享');
          });
        }
      },
      wxConfig(data) {
        // 请求配置接口
        // 微信操作
        try{
          wx.ready(() => {
            let shareAppMessageLink = (data.teacher.sn == this.userInfo.sn) ? `${this.studentShareHost}share-series?series_sn=${data.sn}&origin=teacher-${data.teacher.sn}` : `${this.studentShareHost}share-series?series_sn=${data.sn}&origin=wxShare-message`;
            let shareTimelineLink = (data.teacher.sn == this.userInfo.sn) ? `${this.studentShareHost}share-series?series_sn=${data.sn}&origin=teacher-${data.teacher.sn}` : `${this.studentShareHost}share-series?series_sn=${data.sn}&origin=wxShare-timeline`;
            let imgUrl = data.introduce.cover ? data.introduce.cover : `${this.assetsHost}/static/student/_static/student/img/default-lesson-share.png`;
            // 微信发送给朋友
            wx.onMenuShareAppMessage({
              title: `${data.teacher.name} | ${data.name}`, // 分享标题
              desc: data.introduce.content, // 分享描述
              link: shareAppMessageLink, // 分享链接
              imgUrl: imgUrl, // 分享图标
              type: '', // 分享类型,music、video或link，不填默认为link
              dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
              success: () => {
                // 用户确认分享后执行的回调函数
                console.log('success');
              },
              cancel: () => {
                // 用户取消分享后执行的回调函数
                console.log('cancel');
              }
            });
            // 分享到朋友圈
            wx.onMenuShareTimeline({
              title: `${data.teacher.name} | ${data.name}`, // 分享标题
              link: shareTimelineLink, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
              imgUrl: imgUrl, // 分享图标
              success: () => {
                // 用户确认分享后执行的回调函数
                console.log('success');
              },
              cancel: () => {
                // 用户取消分享后执行的回调函数
                console.log('cancel');
              }
            });
            // 展示页面
            this.showLoading = true;
            setTimeout(()=>{
              this.$store.commit('FETCH_SERIES_SHARE', true);
            }, 10);
          });
          wx.error((res)=>{
            console.log(res.err_msg);
          });
        }catch(e){
          // 展示页面
          this.showLoading = true;
          setTimeout(()=>{
            this.$store.commit('FETCH_SERIES_SHARE', true);
          }, 10);
        };
      },
    },
  };
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";

  .content.series
    margin: 0;
    .mt20
      margin-top: 20px;
    .mb20
      margin-bottom: 20px;
    .brief-info
      .info-price
        .guarantee
          px2px(top, 10px);
    .lists
      margin-top: 15px;
      padding-bottom: 15px;
    .lesson-more
      position: fixed;
      top: 0;
      bottom: 0;
      margin: 0;
      width: 100%;
      background-color: rgba(0,0,0,.3);
      .more-container
        position: absolute;
        padding: 0;
        width: 100%;
        px2px(bottom, 98px);
      div>*
        padding: 30px 0;
        color: #333;
        background: #fff;
        text-align: center;
        border-top: 1px solid #d9d9d9;
  .body-pc
    .content.series
      .lesson-more
        width: 640px;
        .more-container
          bottom: 100px;

  @media only screen and (device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) {
    .content.series {
      div.lesson-more {
        .more-container {
          padding-bottom: 60px;
        }
      }
    }
  }
</style>
