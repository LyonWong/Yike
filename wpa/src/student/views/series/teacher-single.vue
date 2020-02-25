<template>
  <section class="single" v-if="!ajaxing">
    <div class="list" v-if="single.length">
      <single-list :lists="single"></single-list>
    </div>
    <div class="list list-none" v-else>
      讲师暂未开设单课
    </div>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';
  import singleList from '@student/components/singleList.vue';

  export default{
    name: 'teacher-single',
    components: {
      singleList,
    },
    data() {
      return {
        single: null,
        ajaxing: true,
      };
    },
    created() {
      // 获取路由参数
      let params = this.$route.params;
      // 开始获取讲师详情
      this.$store.dispatch('fetchTeacherSingle', {tusn:params.series_sn}).then((data) => {
        //
        this.single = data;
        this.ajaxing = false;
      }, (error) => {
        this.ajaxing = false;
        console.log('fail');
      });
    },
  }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";

  .single
    padding-bottom: 100px;
  .list-none
    background: #fff;
    margin: 0 auto;
    padding: 1rem;
    text-align: center;
</style>
