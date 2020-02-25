<template>
  <div class="loading" v-show="show">
    <div class="loader-inner ball-pulse">
      <div></div>
      <div></div>
      <div></div>
    </div>
    <div class="progress">
      <span>上传</span>
      <span v-text="width"></span>
    </div>
    <div class="is-sending" v-if="width == '100%'">录音正在发送...</div>
  </div>
</template>
<script>
  export default {
    name: 'sending',
    props: {
      show: {
        type: Boolean
      },
      width: {
        //type: String
      }
    },
    updated() {
      console.log('updated');
    },
    data() {
      return {
      };
    },
    created(){
      //console.log(this);
    },
  };
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  @import 'index.styl';
  .loading
    top: 50%;
    height: 150px;
    .progress
      position: absolute;
      top: 14%;
      left: 46%;
      span
        font-size: 14px;
        color: #03a9f4;
    .ball-pulse
      top: 0;
  .is-sending
    position: absolute;
    top: 69%;
    left: 45%;
    color: #03a9f4;
    px2px(font-size, 32px);
</style>
