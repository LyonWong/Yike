<template>
  <section class="user-center">
    <div class="user-img">
      <div>
        <img :src="userInfo.avatar" />
      </div>
      <div class="name" v-text="userInfo.name"></div>
      <!--<div class="listen">已经听过2个教程</div>-->
    </div>
    <ul>
      <li>
        <router-link to="/user/enrolled">
          <i class="iconfont icon-buy"></i>
          <span>已报名课程</span>
          <span class="arrow"></span>
        </router-link>
      </li>
      <li>
        <router-link to="/user/follow">
          <i class="iconfont icon-follow"></i>
          <span>已关注讲师</span>
          <span class="arrow"></span>
        </router-link>
      </li>
      <li>
        <router-link to="/user/money">
          <i class="iconfont icon-qianbao1"></i>
          <span>账户资金</span>
          <span class="arrow"></span>
        </router-link>
      </li>
      <li>
        <router-link to="/user/money/coupon">
          <i class="iconfont icon-youhuiquan"></i>
          <span>兑换优惠券</span>
          <span class="arrow"></span>
        </router-link>
      </li>
    </ul>
    <ul>
      <!-- <li>
        <router-link to="/user/guarantee">
          <i class="iconfont icon-info"></i>
          <span>课程保障</span>
          <span class="arrow"></span>
        </router-link>
      </li> -->
      <li>
        <router-link to="/user/advise">
          <i class="iconfont icon-comment"></i>
          <span>建议与反馈</span>
          <span class="arrow"></span>
        </router-link>
      </li>
      <li>
        <a href="javascript:;" @click="becomeTeacher">
          <i class="iconfont icon-laoshi"></i>
          <span>注册成为讲师</span>
          <span class="arrow"></span>
        </a>
      </li>
      <li>
        <router-link to="/user/about">
          <i class="iconfont icon-cloud-we"></i>
          <span>关于我们</span>
          <span class="arrow"></span>
        </router-link>
      </li>
    </ul>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';

  export default{
    name: 'u-center',
    components: {
    },
    computed: {
      ...mapGetters({
        userInfo: 'getUserInfo',
      })
    },
    data() {
        return {
        teacherHost:(process.env.TEACHER_HOST ? process.env.TEACHER_HOST.replace(/\/$/,'') : 'https://teacher.sandbox.yike.fm/')
        }
    },
    mounted() {

    },
    methods: {
         becomeTeacher() {
            window.location.href = `${this.teacherHost}/sign-up`;
        }
    },
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";

  a {color: #3c4a55}
  a i {color: #aaa}
</style>
