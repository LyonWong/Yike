<template>
  <div class="focus-code" v-if="show">
    <div>
      <p>报名成功</p>
      <img @touchstart="touchstart($event)" :src="`${assetsHost}/static/student/_static/student/img/qrcode.png`" />
      <div>关注易灵微课，接收开课通知</div>
      <button @click="colseCode">知道了</button>
    </div>
  </div>
</template>

<script>
    import { mapGetters } from 'vuex';

    export default{
      name: 'focus-code',
      computed: {
        ...mapGetters({
          assetsHost: 'getAssetsHost',
        })
      },
      props: {
        show: Boolean,
      },
      data() {
        return {
          isIos: (navigator.userAgent.match(/(iPhone|iPad|ios)/i) != null?true:false),
        }
      },
      methods: {
        colseCode() {
          this.$emit('updateFocusCodeShow', false);
        },
        touchstart(event) {
          event.stopPropagation();
          // 是不是ios设备
          if(this.isIos){
            wx.previewImage({
              current: `${this.assetsHost}/static/student/_static/student/img/qrcode.png`,
              urls: [`${this.assetsHost}/static/student/_static/student/img/qrcode.png`],
            });
          }
        },
      },
    }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import '~@lib/css/index.styl';

  .button
    .focus-code
      width: 100%;
      height: 100%;
      padding: 0;

  .focus-code
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
