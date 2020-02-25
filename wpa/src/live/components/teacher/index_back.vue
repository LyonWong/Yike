<template>
  <div class="teacher-bar">
    <div class="bar-title">
      <div class="bar-img pull-left">
        <img :src="teacherInfo.avatar">
      </div>
      <div class="bar-text pull-left">
        <h4 v-text="teacherInfo.name"></h4>
        <div class="total">
          <span>已开课程<br />{{statsInfo['teacher.lesson.count'] || 0}}</span>&nbsp;&nbsp;&nbsp;
          <span>听课人次<i class="iconfont icon-wenhao" title="单人单课为一次"></i><br />{{statsInfo['teacher.access.unique'] || 0}}</span>
        </div>
      </div>
    </div>
    <ul class="bar-list">
      <!--<li>-->
        <!--<a href="https://teacher.sandbox.yike.fm">-->
          <!--<i class="iconfont icon-all"></i>-->
          <!--&nbsp;&nbsp;-->
          <!--我的总览-->
        <!--</a>-->
      <!--</li>-->
      <li>
        <a :href="teacherHost" class="active">
          <i class="iconfont icon-menu"></i>
          &nbsp;&nbsp;
          我的课程
        </a>
      </li>
      <li>
        <a :href="`${teacherHost}#/handle`">
          <i class="iconfont icon-chuli"></i>
          &nbsp;&nbsp;
          退款处理
        </a>
      </li>
      <li>
        <a :href="`${teacherHost}#/data`">
          <i class="iconfont icon-shuju"></i>
          &nbsp;&nbsp;
          数据中心
        </a>
      </li>
      <li>
        <a :href="`${teacherHost}#/earning`">
          <i class="iconfont icon-shuju"></i>
          &nbsp;&nbsp;
          我的收益
        </a>
      </li>
    </ul>
  </div>
</template>
<script>
  import {mapState} from 'vuex';

  export default {
    name: 'v-teacher',
    computed: {
      ...mapState([
        'teacherInfo',
        'teacherHost',
        'statsInfo'
      ])
    },
    props: {

    },
  };
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import 'index.styl';
</style>
