<template>
  <section class="content-message">
    <router-view></router-view>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';

  export default{
    name: 'message',
    computed: {
    },
    created() {

    },
  };
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";
</style>
