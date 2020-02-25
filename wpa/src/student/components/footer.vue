<template>
  <footer class="footer">
    <a :href="`${host}lesson/home`" :class="{active: $route.path === '/course/list'}">
      <i class="iconfont" :class="{'icon-home-empty': $route.path !== '/course/list', 'icon-home-selected': $route.path === '/course/list'}"></i>
      <br />
      <span>首页&nbsp;</span>
    </a>
    <router-link to="/user">
      <i class="iconfont " :class="{'icon-my-empty': $route.path !== '/user', 'icon-my-selected': $route.path === '/user'}"></i>
      <br />
      <span>我的&nbsp;</span>
    </router-link>
  </footer>
</template>

<script>
    export default{
      name: 'v-footer',
      data() {
        return {
          host: process.env.LIVE_HOST
        }
      },
      methods: {
      }
    }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import '~@lib/css/index.styl';

  .active .iconfont {
    color: #2a4ec4;
  }

  .footer {
    position: fixed;
    display: -webkit-box;
    display: box;
    left: 0;
    right: 0;
    bottom: 0;
    height: 100px;
    background: #fff;
    /*-moz-box-shadow:0px -1px 20px #C7C7C7;*/
    /*-webkit-box-shadow:0px -1px 20px #C7C7C7;*/
    /*box-shadow:0px -1px 20px #C7C7C7;*/
    box-shadow: 0 0 10px #ddd;
    z-index: 6;
    /*px2px(font-size, 36px);*/
    font-size: 20px;

    > * {
      width: 50%;
      display: -webkit-box;
      display: box;
      -webkit-box-flex: 1;
      box-flex: 1;
      box-align: center;
      -webkit-box-align: center;
      -webkit-box-pack: center;
      text-align: center;
      text-decoration: none;
      /*color: #7f8389;*/
      color: #666;
      /*px2px(font-size, 40px);*/
      font-size: .26rem;

      &.active{
        /*color: #00a551;*/
      }
      i{
        /*px2px(font-size, 40px);*/
        font-size: .53rem;
      }
    }
  }

  @media only screen and (device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) {
    .footer {
      padding-bottom: 60px;
    }
  }
</style>
