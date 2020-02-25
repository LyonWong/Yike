<template>
  <section class="user">
    <router-view></router-view>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';

  export default{
    name: 'user',
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
