<template>
  <div class="qrcode">
    <div>
      <span>
        关注易灵微课
        <br />
        发现更多课程&nbsp;
      </span>
      <!--获取更多课程-->
      <div class="qrcode-img">
        <img @touchstart="touchstart($event)" :src="`${assetsHost}/static/student/_static/student/img/qrcode.png`" />
      </div>
    </div>
  </div>
</template>

<script>
    import { mapGetters } from 'vuex';

    export default{
      name: 'qr-code',
      computed: {
        ...mapGetters({
          assetsHost: 'getAssetsHost',
        })
      },
      data() {
        return {
          startTime: 0,
          touch: false,
          isIos: (navigator.userAgent.match(/(iPhone|iPad|ios)/i) != null?true:false),
        }
      },
      methods: {
        backToHome() {
          this.$router.push({ name: 'course' });
        },
        touchstart(event) {
          event.stopPropagation();
          // 是不是ios设备
          if(this.isIos){
            //this.startTime = new Date().getTime();
            wx.previewImage({
             current: `${this.assetsHost}/static/student/_static/student/img/qrcode.png`,
             urls: [`${this.assetsHost}/static/student/_static/student/img/qrcode.png`],
            });
          }
        },
        touchend(event) {
          event.stopPropagation();
          // 是不是ios设备
          //if(this.isIos && (new Date().getTime()-this.startTime>500)){
          console.log(wx)
            wx.previewImage({
              current: `${this.assetsHost}/static/student/_static/student/img/qrcode.png`,
              urls: [`${this.assetsHost}/static/student/_static/student/img/qrcode.png`],
            });
            //window.location.href = `${this.$parent.studentHost}qrcode.html`;
          //}
        },
      }
    }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import '~@lib/css/index.styl';

  .qrcode
    position: relative;
    display: -webkit-box;
    display: box;
    padding: 0 25px;
    background: #fff;
    px2px(font-size, 32px);
    >*
      position: relative;
      display: -webkit-box;
      display: box;
      padding: 36px 0;
      -webkit-box-flex: 1;
      box-flex: 1;
      text-align: center;
      box-align: center;
      -webkit-box-align: center;
      box-pack: center;
      -webkit-box-pack: center;
      px2px(line-height, 50px);

      &:first-child
        position: relative;
        px2px(font-size, 30px);
      &:nth-of-type(2)
        border-left: 1px solid #e6eaf2;
        color: #9ca7c1;
        px2px(font-size, 30px);
      .iconfont
        px2px(font-size, 80px);
    &.is-border
      >*
        border-top: 1px solid #d9d9d9;
    .qrcode-img
      position: relative;
      margin-left: 15px;
      width: 220px;
      height: 220px;
      img
        position: relative;
        width: 220px;
        height: 220px;

</style>
