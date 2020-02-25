<template>
  <div class="app">
    <div class="loading" v-show="loading"></div>
    <div class="app-wrapper">
      <router-view></router-view>
    </div>
    <v-footer v-show="footer"></v-footer>
  </div>
</template>

<script>
import NavBar from './components/navbar'
import VHeader from './components/header'
import VFooter from './components/footer'
import { setCookie } from '@lib/js/mUtils';
import { mapGetters } from 'vuex';
import swal from 'sweetalert';

export default {
  components: {
    NavBar,
    VHeader,
    VFooter
  },
  created() {
    /*// 请求配置接口
    if(wx){
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
        this.$store.commit('UPDATE_WXSHARE_SHARE', 1);
      },()=>{
        // 不能分享
        console.log('不能分享');
        this.$store.commit('UPDATE_WXSHARE_SHARE', 2);
      });
    }*/
    // 获取用户信息
    this.$store.dispatch('fetchUserInfo').then((data) => {
      console.log('success');
    }, (err) => {
      //
      swal({
        title: '错误提醒',
        text: err.message,
        confirmButtonText: "知道了"
      });
    });
  },
  computed: mapGetters({
    loading: 'getLoading',
    footer: 'getFooter',
  })
}
</script>

<style lang="stylus" rel="stylesheet/stylus">
  html
    height: 100%;
    background: #f0eff5;
  body
    margin: 0;
    height: 100%;
    background: #f0eff5;
    /*font-family: "华文细黑", "Microsoft YaHei", "微软雅黑";*/
    font-family: -apple-system, BlinkMacSystemFont, 'PingFang SC', 'Helvetica Neue', STHeiti, 'Microsoft Yahei', Tahoma, Simsun, sans-serif;
    &.body-pc
      position: relative;
      margin: 0 auto;
      width: 640px;
      height: 100%;
      .detail
        .button
          margin: 0 auto;
          width: 640px;
      .user-center
        padding-bottom: 100px;
      .footer
        margin: 0 auto;
        width: 640px;
    .app
      height: 100%;
  .clearfix:after
    content: ".";
    display: block;
    height: 0;
    clear: both;
    visibility: hidden;
  .pull-left {
    float: left;
  }
  .pull-right {
    float: right;
  }
  .text-center {
    text-align: center;
  }
  .break-word {
    word-wrap: break-word;
    overflow: hidden;
  }
  .ellipsis{
    white-space:nowrap;
    text-overflow:ellipsis;
    overflow:hidden;
  }
  .clearfix {
    overflow:hidden;
    _zoom:1;
  }

  @media only screen and (device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) {
    .app-wrapper {
      padding-bottom: 60px;
    }
  }
</style>
