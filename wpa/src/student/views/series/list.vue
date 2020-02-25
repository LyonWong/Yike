<template>
  <section class="content-list">
    <single-list :lists="seriesList"></single-list>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex'
  import singleList from '@student/components/singleList.vue';

  export default{
    name: 'series-list',
    components: {
      singleList
    },
    computed: {
      ...mapGetters({
        seriesList: 'getSeriesList',
        /*courseScrollTop: 'getCourseScrollTop',*/
      })
    },
    created() {
      // 已经请求过就返回
      /*if(this.courseList.length)return;
      this.$store.dispatch('fetchCourseList').then(() => {
        console.log('success');
      }, () => {
        console.log('fail');
      });*/
    },
    mounted() {
        /*document.body.scrollTop = this.courseScrollTop;*/
    },
    beforeRouteLeave(to, from, next) {
      /*// 记录滚动条位置
      this.$store.commit('UPDATE_COURSE_SCROLL', document.body.scrollTop);
      next(true);*/
    }
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";
</style>
