<template>
  <div class="pay-error-code" v-if="show">
    <div>
      <p>支付失败</p>
      <img src="https://assets.yike.fm//static/student/_static/student/img/qrcode.png" />
      <div>请长按二维码关注易灵微课公众号，从课程列表进入报名</div>
      <button @click="colseCode">知道了</button>
    </div>
  </div>
</template>

<script>
    import { mapGetters } from 'vuex';

    export default{
      name: 'payError-code',
      props: {
        show: Boolean,
      },
      methods: {
        colseCode() {
          this.$emit('updatePayErrorShow', false);
        }
      }
    }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import '~@lib/css/index.styl';

  .button
    .pay-error-code
      width: 100%;
      height: 100%;
      padding: 0;

  .pay-error-code
    position: fixed;
    left: 0;
    top: 0;
    background-color: rgba(0,0,0,0.6);
    text-align: center;
    px2px(font-size, 32px);
    >div
      margin: 140px auto 0;
      width: 440px;
      padding: 30px 64px;
      background: #fff;
      border-radius: 10px;
      p
        color: #12B7F5;
        px2px(font-size, 40px);
      >div
        margin-bottom: 40px;
    img
      width: 400px;
      height: 400px;
    button
      display: block;
      padding: 20px 0;
      width: 100%;
      border: 1px solid #12B7F5;
      border-radius: 50px;
      color: #12B7F5;
      background: #fff;
      px2px(font-size, 34px);

</style>
