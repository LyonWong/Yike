<template>
  <section class="content ios-qrcode">
    <img @click="clickImg($event)" src="https://assets.sandbox.yike.fm//static/student/_static/student/img/qrcode.png" />
    <br>
    <p>长按识别二维码</p>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';
  //
  let docEl = document.documentElement;
  let metaEl = document.querySelector('meta[name="viewport"]');

  export default{
    name: 'qrcode',
    data() {
      return {

      };
    },
    beforeRouteLeave(to, from, next) {
      //
      let dpr = window.devicePixelRatio || 1;
      let scale = 1 / dpr;
      // 设置viewport，进行缩放，达到高清效果
      metaEl.setAttribute('content', 'width=' + dpr * docEl.clientWidth + ',initial-scale=' + scale + ',maximum-scale=' + scale + ', minimum-scale=' + scale + ',user-scalable=no');
      // 设置data-dpr属性，留作的css hack之用
      docEl.setAttribute('data-dpr', dpr);
      // 恢复footer
      this.$store.commit('UPDATE_FOOTER', true);
      // 往下走
      next();
    },
    created() {
      // 设置viewport，取消进行缩放
      metaEl.setAttribute('content', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0');
      // 设置data-dpr属性
      docEl.setAttribute('data-dpr', 1);
      // 隐藏footer
      this.$store.commit('UPDATE_FOOTER', false);
    },
    mounted() {
      // 修改样式
      let parentNode = document.body.querySelector('.ios-qrcode').parentNode;
      parentNode.style.height = '100%';
      parentNode.parentNode.style.height = '100%';
    },
    methods: {
      clickImg(event) {
        // 阻止事件冒泡
        event.stopPropagation();
      },
      backToHistory() {
        // 跳转
        this.$router.go(-1);
      },
    },
  }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  .ios-qrcode
    height: 100%;
    text-align: center;
    background: rgba(0,0,0,0.7);
    img
      padding: 30px;
    p
      margin: 0;
      padding: 0;
      color: #fff;
      font-size: 18px;
  .app-wrapper
    height: 100%;
</style>
