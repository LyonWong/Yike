<template>
  <header class="header">

  </header>
</template>

<script>
    export default{
      name: 'v-header',
      methods: {
      }
    }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import '~@lib/css/index.styl';

  .header {
    padding: 20px;
    height: 36px;
    background: #fff;
    text-align: center;
    px2px(font-size, 36px);
  }
</style>
