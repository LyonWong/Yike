<template>
  <section class="user-info">
    简介
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';

  export default{
    name: 'detail-info',
    components: {
    },
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
