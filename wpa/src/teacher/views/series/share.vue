<template>
  <section class="share">
    <div class="share-title font-weight">分享</div>
    <div class="link-box">
      <p>
        <i class="iconfont icon-yuanhuan"></i>
        邀请链接
      </p>
      <div class="input">
        <input type="text" ref="copy" v-bind:value="share_url"/>
        <button @click="copyEvent">复制</button>
      </div>
    </div>
    <div class="share-box clearfix">
      <div class="l-wxcode pull-left">
        <p>
          <i class="iconfont icon-yuanhuan"></i>
          课程二维码
        </p>
        <div class="code">
          <img v-lazy="qrcode" alt="">
          <span class="code-loading">正在生成中...</span>
        </div>
      </div>
      <!--<div class="r-wxcode pull-left">
        <p>
          <i class="iconfont icon-yuanhuan"></i>
          课程邀请卡
        </p>
        <div class="code">
          <img v-lazy="card" alt="" />
          <span class="code-loading">正在生成中...</span>
        </div>
      </div>-->
    </div>
    <div class="footer">
      <div class="footer-display">讲师可获得以上渠道99%的报名费用</div>
    </div>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';
  import swal from 'sweetalert';

  export default{
    name: 'series-share',
    computed: {
    },
    data(){
      return {
        card: '',
        qrcode: '',
        share_url:'',
      }
    },
    created() {
      let params = this.$route.params;
      // 获取已处理列表
      this.$store.dispatch('fetchSeriesShare', params).then((data) => {
        this.card = data.card;
        this.qrcode = data.qrcode;
        this.share_url = data.share_url;
        console.log('success');
      }, (err) => {
        swal({
          title: '错误提醒',
          text: err.message,
          confirmButtonText: "知道了"
        });
      });
    },
    methods: {
      copyEvent() {
        let input = this.$refs['copy'];
        input.select(); // 选取input元素的内容
        var succeeded;
        try {
          // 将选区内容复制到剪贴板
          succeeded = document.execCommand('copy');
        } catch (e) {
          succeeded = false;
        }
        if (succeeded) {
          swal({
            title: '',
            type: 'success',
            text: '复制成功',
            timer: 1000,
            confirmButtonText: '',
            confirmButtonColor: '#fff',
          });
        } else {
          swal({
            title: '',
            type: 'error',
            text: '复制失败',
            timer: 600,
            confirmButtonText: '',
            confirmButtonColor: '#fff',
          });
        }
      }
    }
  }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  .share
    background: #fff;
    .share-title
      margin: 0 20px;
      padding: 15px 0;
      border-bottom: 1px solid #E6EAF2;
    .footer
      border-top: 1px dashed #E6EAF2;
      font-size 16px;
      color :#3c4a55;
      height: 76px;
      text-align: center;
      position: relative;
      .footer-display
        margin-top:30px;
        padding-left: 100px;
        text-align: left;
    .link-box
      padding: 30px 100px 48px;
      background: #fafafb;
      .input
        position: relative;
        padding: 6px 100px 6px 16px;
        border: 1px solid #E6EAF2;
        background: #fff;
        border-radius: 20px;
        -webkit-border-radius: 20px;
        input
          width: 100%;
          font-size: 16px;
          border: 0 none;
          outline: none;
          &[disabled]{
            background: #fff;
          }
        button
          position: absolute;
          right: 0;
          top: 0;
          padding: 8px 32px;
          color: #fff;
          background: #12B7F5;
          border: 0 none;
          border-radius: 18px;
          -webkit-border-radius: 18px;
          z-index: 1;
          cursor: pointer;
    .share-box
      padding: 20px 0;
    .l-wxcode, .r-wxcode
      position: relative;
      width: 50%;
      .code-loading
        position: absolute;
        left: 122px;
        z-index: 1;
      img
        position: relative;
        z-index: 2;
    .l-wxcode
      >*
        padding-left: 100px;
      img
        border: 1px solid #fafafb;
      img[lazy=loading] {
        width: 20px;
      }
      img[lazy=loaded] {
        width: 200px;
      }
    .r-wxcode
      img[lazy=loading] {
        width: 20px;
      }
      img[lazy=loaded] {
        width: 300px;
      }
      .code-loading
        left: 22px;
    .icon-yuanhuan
      color: #12B7F5;
</style>
