<template>
  <section class="content-list">
    <course-list :lists="courseList"></course-list>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex'
  import courseList from '@student/components/courseList.vue';

  export default{
    name: 'list',
    components: {
      courseList
    },
    computed: {
      ...mapGetters({
        courseList: 'getCourseListInfo',
        courseScrollTop: 'getCourseScrollTop',
      })
    },
    created() {
      if (location.search.indexOf('?v=2') >= 0) {
        window.location.href = window.location.href = `${process.env.LIVE_HOST}lesson/home`
        return
      }
      // 已经请求过就返回
      if(this.courseList.length)return;
      this.$store.dispatch('fetchCourseList').then(() => {
        console.log('success');
      }, () => {
        console.log('fail');
      });
    },
    mounted() {
        document.body.scrollTop = this.courseScrollTop;
    },
    beforeRouteLeave(to, from, next) {
      // 记录滚动条位置
      this.$store.commit('UPDATE_COURSE_SCROLL', document.body.scrollTop);
      next(true);
    }
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";
</style>
