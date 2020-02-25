<template>
  <section class="content test">
    your devicePixelRatio is {{devicePixelRatio}}
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';

  export default{
    name: 'test',
    data(){
      return {
        devicePixelRatio: 0,
      }
    },
    created() {
      this.devicePixelRatio = window.devicePixelRatio
    },
  }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";

  .test
    padding: 30px;
</style>
