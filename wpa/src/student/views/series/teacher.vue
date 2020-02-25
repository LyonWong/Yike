<template>
  <section class="content">
    <div class="teacher" v-if="teacher">
      <teacher-info :teacher="teacher"></teacher-info>
      <div class="tab">
        <router-link :to="{name:'seriesTeacherSingle'}" replace>单课</router-link>
        <router-link :to="{name:'seriesTeacherSeries'}" replace>系列课</router-link>
      </div>
    </div>
    <router-view></router-view>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';
  import teacherInfo from '@student/components/teacherinfo.vue';

  export default{
    name: 'series-teacher',
    components: {
      teacherInfo,
    },
    data() {
      return {
        teacher: null,
      };
    },
    created() {
      // 获取路由参数
      let params = this.$route.params;
      // 开始获取讲师详情
      this.$store.dispatch('fetchSeriesTeacher', {tusn:params.series_sn}).then((data) => {
        //
        this.teacher = data;
      }, (error) => {
        console.log('fail');
      });
    },
  }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";

  .teacher
    .tab
      display: -webkit-box;
      display: box;
      margin-top: 15px;
      height: 88px;
      background: #fff;
      border-bottom: 1px solid #e6eaf2;
      px2px(line-height, 88px);
      >*
        display: -webkit-box;
        display: box;
        -webkit-box-flex: 1;
        box-flex: 1;
        margin: 0 40px;
        justify-content: center;
        text-decoration: none;
        color: #3c4a55;
        border-bottom: 2px solid #fff;
        &.active
          color: #12b7f5;
          border-color: #12b7f5;
</style>
