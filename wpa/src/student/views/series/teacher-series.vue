<template>
  <section class="series teacher-series" v-if="!ajaxing">
    <div class="list" v-if="series.length">
      <series-list :lists="series"></series-list>
    </div>
    <div class="list list-none" v-else>
      讲师暂未开设系列课
    </div>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';
  import seriesList from '@student/components/seriesList.vue';

  export default{
    name: 'teacher-series',
    components: {
      seriesList,
    },
    data() {
      return {
        series: null,
        ajaxing: true,
      };
    },
    created() {
      // 获取路由参数
      let params = this.$route.params;
      // 开始获取讲师详情
      this.$store.dispatch('fetchTeacherSeries', {tusn:params.series_sn}).then((data) => {
        //
        this.series = data;
        this.ajaxing = false;
      }, (error) => {
        this.ajaxing = false;
        console.log('fail');
      });
    },
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";

  .content
    .teacher-series
      margin: 0;
      padding-bottom: 100px;
  .list-none
    background: #fff;
    margin: 0 auto;
    padding: 1rem;
    text-align: center;
</style>
