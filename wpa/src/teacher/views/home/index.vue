<template>
  <section class="content-detail">
    {{ homeInfo }}
    <router-link to="/course/list">点击进入课程列表</router-link>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex'

  export default{
    name: 'home',
    computed: {
      ...mapGetters({
        homeInfo: 'getHomeInfo',
      })
    },
    created() {

    },
    methods: {
    },
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  .content-detail {
    text-align: center;
  }
</style>
