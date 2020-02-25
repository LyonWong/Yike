<template>
  <div class="siderbar">
    <div class="bar-info clearfix">
      <div class="bar-img pull-left">
        <img :src="userInfo.avatar">
      </div>
      <div class="bar-text pull-left">
        <h4 v-text="userInfo.name"></h4>
        <div class="total">
          <span>已开课程<br />{{userInfo | specKey('teacher.lesson.count')}}</span>&nbsp;&nbsp;&nbsp;
          <span>听课人次<i class="iconfont icon-wenhao" title="单人单课为一次"></i><br />{{userInfo | specKey('teacher.access.unique')}}</span>
        </div>
      </div>
    </div>
    <ul class="bar-list">
      <!--<li>-->
        <!--<router-link to="/all">-->
          <!--<i class="iconfont icon-all"></i>-->
          <!--&nbsp;&nbsp;-->
          <!--我的总览-->
        <!--</router-link>-->
      <!--</li>-->
      <li>
        <router-link to="/course">
          <i class="iconfont icon-menu"></i>
          &nbsp;&nbsp;
          课程列表
        </router-link>
      </li>
      <li>
        <a :href="`${liveHost}/create/posts`" target="_blank">
          <i class="iconfont icon-list"></i>
          &nbsp;&nbsp;
          文章列表
        </a>
      </li>
      <li>
        <router-link to="/handle">
          <i class="iconfont icon-chuli"></i>
          &nbsp;&nbsp;
          退款处理
        </router-link>
      </li>
      <li>
        <router-link to="/data">
          <i class="iconfont icon-shuju"></i>
          &nbsp;&nbsp;
          数据中心
        </router-link>
      </li>
      <li>
        <router-link to="/earning">
          <i class="iconfont icon-earning"></i>
          &nbsp;&nbsp;
          我的收益
        </router-link>
      </li>
      <li>
        <a href="javascript:;" @click="goToGuide">
          <i class="iconfont icon-zhinan1"></i>
          &nbsp;&nbsp;
          讲师指南
        </a>
      </li>
    </ul>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';

  export default{
    name: 'sider-bar',
    computed: {
      ...mapGetters({
        userInfo: 'getUserInfo',
      })
    },
    data() {
      return {
        liveHost: (process.env.LIVE_HOST ? process.env.LIVE_HOST.replace(/\/$/,'') : 'https://sandbox.yike.fm'),
        teacherHost:(process.env.TEACHER_HOST ? process.env.TEACHER_HOST.replace(/\/$/,'') : 'https://teacher.sandbox.yike.fm')
      }
    },
    methods: {
      goToGuide() {
        // 开始进入课堂
        let newWindow = window.open();
        setTimeout(()=>{
          newWindow.location = `${this.teacherHost}/doc`;
        });
      }
    }
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  .siderbar {
    float: left;
    width: 270px;
    height: 544px;
    background: #fff;
    border-radius: 4px;
    -webkit-border-radius: 4px;
    -moz-box-shadow:0px 2px 10px #dddddd;
    -webkit-box-shadow:0px 2px 10px #dddddd;
    box-shadow:0px 2px 10px #dddddd;

    a {
      text-decoration: none;
    }
    .bar-img {
      padding: 28px 24px;
      img {
        width: 53px;
        height: 53px;
        border-radius: 50%;
        -webkit-border-radius: 50%;
      }
    }
    ul {
      margin: 0;
      padding: 0;
      li {
        list-style-type: none;
        a {
          position: relative;
          display: block;
          padding: 20px 0 20px 40px;
          color: #aaaaaa;
          &:before {
            position: absolute;
            left: -2px;
            top: 20px;
            bottom: 20px;
            width: 0;
            content: "";
            background: #12B7F5;
            z-index: 1;
          }
          &.active {
            color: #12B7F5;
            &:before {
              width: 2px;
            }
          }
        }
      }
    }
    .bar-text {
      h4 {
        margin-bottom: 10px;
        font-weight: normal;
      }
      .icon-wenhao {
        color: #12b7f5;
      }
    }
    .total {
      display: -webkit-box;
      display: box;
      color: #7f8389;
      >* {
        display: -webkit-box;
        display: box;
        font-size: 14px;
        line-height: 26px;
        -webkit-box-flex: 1;
        box-flex: 1;
        text-align: center;
        box-align: center;
        -webkit-box-align: center;
        box-pack: center;
        -webkit-box-pack: center;
      }
    }
  }
</style>
