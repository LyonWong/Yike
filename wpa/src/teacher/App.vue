<template>
  <div class="app">
    <v-header></v-header>
    <div class="container mt20 clearfix">
      <sider-bar></sider-bar>
      <div class="box">
        <v-notice v-if="isNotice"></v-notice>
        <router-view></router-view>
      </div>
      <div class="loading" v-show="loading">
        <div class="loader-inner ball-pulse">
          <div></div>
          <div></div>
          <div></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SiderBar from './components/siderbar';
import VHeader from './components/header';
import VNotice from './components/notice';
import { setCookie } from '@lib/js/mUtils';
import swal from 'sweetalert';
import { mapGetters } from 'vuex';

export default {
  components: {
    SiderBar,
    VHeader,
    VNotice
  },
  created() {
    // 获取用户信息
    this.$store.dispatch('fetchUserInfo').then((data1) => {
      this.$store.dispatch('fetchStatsOverview').then((data2) => {
        this.$store.commit('FETCH_USER_INFO', { ...data1, ...data2});
        console.log('success');
      }, (err) => {
        /*alert(err.message);*/
        swal({
          title: '错误提醒',
          text: err.message,
          confirmButtonText: "知道了"
        });
      });

    }, (err) => {
      /*alert(err.message);*/
      swal({
        title: '错误提醒',
        text: err.message,
        confirmButtonText: "知道了"
      });
    });
  },
  computed: mapGetters({
    loading: 'getLoading',
    isNotice: 'getIsNotice',
  })
}
</script>

<style lang="stylus" rel="stylesheet/stylus">
  body
    margin: 0;
    background: #EFEFF4;
    font-family: "华文细黑", "Microsoft YaHei", "微软雅黑";
  .container
    margin: 0 auto;
    width: 1300px;
  .clearfix:after
    content: ".";
    display: block;
    height: 0;
    clear: both;
    visibility: hidden;
  .box
    float:right;
    width: 1000px;
  .mt20
    margin-top: 20px;
  .pull-left {
    float: left;
  }
  .pull-right {
    float: right;
  }
  .break-word {
    word-wrap: break-word;
    overflow: hidden;
  }
  .loading
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index 30
    width: 100%
    height: 100%;
    background-color: #ffffff
    opacity: 0.7
    .ball-pulse
      position: absolute
      top: 50%
      left: 45%
      div
        background-color: #03a9f4;
        width: 15px;
        height: 15px;
        border-radius: 100%;
        margin: 2px;
        animation-fill-mode: both;
        display: inline-block;
      div:nth-child(1)
        animation: scale 0.75s -0.24s infinite cubic-bezier(0.2, 0.68, 0.18, 1.08);
      div:nth-child(2)
        animation: scale 0.75s -0.12s infinite cubic-bezier(0.2, 0.68, 0.18, 1.08);
      div:nth-child(3)
        animation: scale 0.75s 0s infinite cubic-bezier(0.2, 0.68, 0.18, 1.08)


    @-webkit-keyframes scale {
      0% {
        -webkit-transform: scale(1);
        transform: scale(1);
        opacity: 1; }
      45% {
        -webkit-transform: scale(0.1);
        transform: scale(0.1);
        opacity: 0.7; }
      80% {
        -webkit-transform: scale(1);
        transform: scale(1);
        opacity: 1; } }
    @keyframes scale {
      0% {
        -webkit-transform: scale(1);
        transform: scale(1);
        opacity: 1; }
      45% {
        -webkit-transform: scale(0.1);
        transform: scale(0.1);
        opacity: 0.7; }
      80% {
        -webkit-transform: scale(1);
        transform: scale(1);
        opacity: 1; } }
  button{
    outline:none;
  }
  .v-note-wrapper{
    height: 300px;
    z-index: 3 !important;
  }
  .font-weight {
    font-weight: bold;
  }
  .box > section {
    min-height: 546px;
  }
</style>
