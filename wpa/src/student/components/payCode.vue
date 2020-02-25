<template>
  <div class="pay-code" v-if="show">
    <img :src="codeUrl" />
    <i class="iconfont icon-guanbi" @click="colseCode"></i>
  </div>
</template>

<script>
    import { mapGetters } from 'vuex';

    export default{
      name: 'pay-code',
      props: {
        show: Boolean,
        codeUrl: {
          type: null,
        },
      },
      methods: {
        colseCode() {
          this.$emit('updatePayCodeShow', false);
        }
      }
    }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import '~@lib/css/index.styl';

  .button
    .pay-code
      width: 100%;
      height: 100%;
      padding: 0;

  .pay-code
    position: fixed;
    left: 0;
    top: 0;
    background-color: rgba(0,0,0,0.6);
    text-align: center;
    px2px(font-size, 32px);
    img
      margin-top: 300px;
      width: 400px;
      height: 400px;
    .iconfont
      position: absolute;
      left: 50%;
      top: 720px;
      margin-left: -32px;
      color: #fff;
      px2px(font-size, 64px);

</style>
