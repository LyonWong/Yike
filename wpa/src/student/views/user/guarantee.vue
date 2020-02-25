<template>
  <section class="guarantee">
    <dl>
      <dt>支付保障：</dt>
      <dd>微信支付，方便快捷</dd>
    </dl>
    <dl>
      <dt>消息通知：</dt>
      <dd>关注易课公众号后可及时接收课程通知</dd>
    </dl>
    <dl>
      <dt>限时退款：</dt>
      <dd>听课一小时内，不满意无条件退款</dd>
    </dl>
    <dl>
      <dt>过期退款：</dt>
      <dd>课程结束7天后未听课自动退款</dd>
    </dl>
    <dl>
      <dt>申诉退款：</dt>
      <dd>超过退款时限，理由充分仍有机会申请退款。</dd>
    </dl>
  </section>
</template>

<script>
  export default{
    name: 'guarantee',
    components: {
    },
    mounted() {

    },
    methods: {
    },
  }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";

  .guarantee
    padding: 20px;
    px2px(font-size, 28px);
    dl
      margin: 0;
      padding: 5px 0;
      &:after
        content: ".";
        display: block;
        height: 0;
        clear: both;
        visibility: hidden;
      dt,dd
        float: left;
      dt
        width: 180px;
        color: #12b7f5;
      dd
        width: 480px;
</style>
