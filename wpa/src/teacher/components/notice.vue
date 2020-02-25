<template>
  <div class="notice clearfix">
    <span>课程：</span>入门到精通
    <span class="share">分享课程</span>
  </div>
</template>

<script>
    export default{
      name: 'v-notice',
      methods: {
      }
    }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  .notice {
    margin-bottom: 20px;
    padding: 14px 20px;
    font-size: 16px;
    background: #fff;

    .share {
      float: right;
      color: #12B7F5;
    }
  }
</style>
