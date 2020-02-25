<template>
  <div class="loading" v-show="show">
    <div class="loader-inner ball-pulse">
      <div></div>
      <div></div>
      <div></div>
    </div>
  </div>
</template>
<script>
  export default {
    name: 'v-loading',
    props: {
      show: {
        type: Boolean
      }
    },
    created(){
      //console.log(this);
    },
  };
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  @import 'index.styl';

  .loading
    background-color: rgba(255,255,255,.4);
</style>
