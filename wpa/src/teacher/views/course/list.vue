<template>
  <section class="content-list">
    <course-list :lists="courseList"></course-list>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex'
  import courseList from '@teacher/components/courseList.vue';

  export default{
    name: 'list',
    components: {
      courseList
    },
    computed: {
      ...mapGetters({
        courseList: 'getCourseListInfo',
      })
    },
    created() {
      this.$store.dispatch('fetchCourseList').then(() => {
        console.log('success');
      }, () => {
        console.log('fail');
      });
    },
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  .content-list
    padding: 20px;
    background: #fff;
    border-radius: 4px;
    -webkit-border-radius: 4px;
</style>
