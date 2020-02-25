<template>
  <section class="content">
    首页
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';

  export default{
    name: 'home',
    computed: {
      ...mapGetters({
        courseList: 'getCourseListInfo',
      })
    },
    mounted() {

    },
    methods: {
    },
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";
</style>
