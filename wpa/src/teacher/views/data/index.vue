<template>
  <section class="data-center">
    <router-view></router-view>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex'

  export default{
    name: 'data',
    computed: {
      ...mapGetters({
        /*dataCourse: 'getDataCourse',
        dataOrigin: 'getDataOrigin',*/
      })
    },
    created() {
      this.$store.commit('CHANGE_IS_NOTICE', false);
    },
    methods: {
    },
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";
</style>
