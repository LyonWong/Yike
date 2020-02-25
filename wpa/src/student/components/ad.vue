<template>
  <div class="ad" v-show="show">
    <div class="loader-inner ball-pulse">
      <div class="content">
        <img :src="lesson_src">
        <div class="button" @click="enterDetail">
          <button>&nbsp;</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
    export default{
      name: 'v-ad',
      props: {
        show: {
          type: null,
        },
      },
      data() {
        return {
          lesson_src: '',
        };
      },
      created() {
        let storage = /\.sandbox\./ig.test(process.env.STUDENT_HOST)?'https://storage.sandbox.yike.fm':'https://storage.sandbox.yike.fm';
        //
        try{
          this.lesson_src = `${storage}/lesson/${this.$route.query.lesson_sn}/ad-${this.show}`
        }catch(e){};
      },
      methods: {
        enterDetail() {
          this.$emit('updateAdShow', false);
        },
      },
    };
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  @import '~@lib/css/index.styl';

  .ad
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 30;
    width: 100%
    height: 100%;
    background-color: #fff;
    opacity: 1;
    .ball-pulse
      position: absolute;
      top: 0;
      bottom: 0;
      left: 0;
      right: 0;
      >div
        display: -webkit-box;
        display: box;
        width: 100%;
        height: 100%;
        >*
          display: -webkit-box;
          display: box;
          width: 100%;
          -webkit-box-orient: vertical;
          -webkit-line-clamp: initial;
          &.button
            position: absolute;
            left: 0;
            bottom: 0;
            px2px(top, 1015px)
            >button
              display: block;
              padding: 26px 0;
              border: 0 none;
              color: #fff;
              background: #12B7F5;
              background: transparent;
              px2px(font-size, 36px);

</style>
