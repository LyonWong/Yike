<template>
  <section class="content">
    <router-view></router-view>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex'

  export default{
    name: 'series',
    computed: {
    },
    created() {

    },
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";
</style>
