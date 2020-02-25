<template>
  <div class="app" :class="{'is-wx':isWeiXin,'is-pc':isPC}">
    <v-header v-if="!isWeiXin"></v-header>
    <v-loading :show="loadShow"></v-loading>
    <router-view></router-view>
  </div>
</template>

<script>
  import vHeader from '@live/components/header/index.vue';
  import vLoading from '@live/components/loading/index.vue';
  import { mapState } from 'vuex';

  window._canKick_ = false;

  export default {
    name: 'app',
    components: {
      vHeader,
      vLoading,
    },
    computed: {
      ...mapState([
        'loadShow',
      ])
    },
    created(){
    },
    data() {
      return {
        isPC,
        isWeiXin,
      };
    },
    methods: {
    }
  };

</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import '~@lib/css/index.styl';

  html, body
    margin: 0;
    height: 100%;
    background: #ecedf1;
    overflow-y: hidden;
    &.body-pc
      background: url(labelBgImg) no-repeat;
      background-position: center center;
      background-attachment: fixed;
      background-size: cover;
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
  button{
    outline:none;
  }
  .app
    font-family: "华文细黑", "Microsoft YaHei", "微软雅黑";
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    height: 100%;
    margin: 0 auto;
    color: #3C4A55;
    &.is-wx
      padding-top: 0px !important;
    &.is-pc
      max-width: 1200px;
      padding-top: 61px;
      height: auto;
    .show
      transform: translateX(250px);
    .page-cover
      position: fixed;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
      background: rgba(0, 0, 0, 0.4);
      z-index: 7;
    .first
      position: fixed;
      top: 0px;
      z-index: 20;
      width: 100%;
      height: 100%;
      background: #FF0000;

</style>
