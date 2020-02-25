<template>
  <nav class="toolbar">
    欢迎学员体验测试版0.2
  </nav>
</template>

<script>
    export default{
      name: 'nav-bar',
      methods: {
      }
    }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import '~@lib/css/index.styl';

  .toolbar {
    height: 36px;
    background: #fff;
    text-align: center;
    px2px(font-size, 36px);
  }
</style>
