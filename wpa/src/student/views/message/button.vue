<template>
  <div class="button">
    <div class="submit">
      <div class="menu" @click="showMenu">
        <i class="iconfont icon-caidan"></i>
      </div>
      <button @click="clickEvent" v-if="!disabled">&nbsp;&nbsp;{{text || "提交"}}</button>
      <button disabled v-if="disabled">&nbsp;&nbsp;{{text || "提交"}}</button>
    </div>
  </div>
</template>

<script>
    export default{
      name: 'm-button',
      props: {
        text: {
          type: String
        },
        callBack: {
          type: null
        },
        disabled: {
          type: Boolean
        },
      },
      data() {
        return {
          showMore: false,
        }
      },
      methods: {
        clickEvent() {
          this.callBack();
        },
        showMenu() {
          this.showMore = !this.showMore;
          this.$emit('showMenu', this.showMore);
        },
      }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  @import '~@lib/css/index.styl';

  .button
    position: fixed;
    left: 0;
    right: 0;
    bottom: 0;
    height: 100px;
    background: #fff;
    z-index: 7;

    >*
      padding: 10px 30px;
      height: 80px;
    .submit
      display: -webkit-box;
      display: box;
      button
        display: -webkit-box;
        display: box;
        padding: 0;
        width: 100%;
        height: 100%;
        color: #999;
        text-align: left;
        background: #F7F7F7;
        border: 1px solid #ccc;
        -webkit-box-flex: 3.8;
        box-flex: 3.8;
        border-radius: 12px;
        -webkit-border-radius: 12px;
        px2px(font-size, 36px);
        &[disabled]
          background: #aaa;
      >*
        display: -webkit-box;
        display: box;
        -webkit-box-flex: 1;
        box-flex: 1;
      .menu
        padding-left: 10px;
        width: 100px;
        box-align: center;
        -webkit-box-align: center;
        .iconfont
          px2px(font-size, 40px);

  @media only screen and (device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) {
    .button {
      padding-bottom: 60px;
    }
  }
</style>
