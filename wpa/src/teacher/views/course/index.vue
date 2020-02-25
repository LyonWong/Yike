<template>
  <section class="content">
    <router-view></router-view>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex'

  export default{
    name: 'course',
    computed: {
    },
    created() {
      //this.$store.dispatch('changeHomeInfo', '欢迎欢迎!');
      this.$store.commit('CHANGE_IS_NOTICE', false);
    },
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">

</style>
