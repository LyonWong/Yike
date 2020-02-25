<template>
  <section class="user-money-out">
    <div class="money-con">
      <div class="money-forward">
        <div>
          <span>请输入优惠码</span>
        </div>
        <div class="input">
          <input type="text" placeholder="请输入优惠码" v-model="coupon" />
        </div>
        <div>
          <button @click="startDrawCash">兑换领取</button>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';
  import { trimStr } from '@lib/js/mUtils';
  // 学生域
  const studentHost = process.env.NODE_ENV == 'production' ? process.env.STUDENT_HOST.replace(/\/$/,'') : '/student.html';

  export default{
    name: 'money-coupon',
    components: {
    },
    computed: {
      ...mapGetters({
      }),
    },
    data() {
      return {
        coupon: '',
      }
    },
    created() {
    },
    methods: {
      startDrawCash() {
        let curCoupon = trimStr(this.coupon);
        if(!curCoupon)return swal({
          title: '错误提醒',
          text: '请输入优惠码',
          confirmButtonText: '知道了'
        });
        // 开始跳转
        window.location.href = `${studentHost}/promote-receive?sn=${curCoupon}`;
      },
    },
  };
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";

  .user-money-out
    background: #f7f9fc;
    px2px(font-size, 36px);
    .money-forward
      padding: 50px 0;
      background: #0595de;
      >*
        color: #fff;
        text-align: center;
        padding-bottom: 40px;
        px2px(line-height, 32px);
        px2px(font-size, 30px);
        >*
          vertical-align: middle;
      >:first-child
        span
          color: #9bd5f2;
      >:last-child
        padding-bottom: 0;
        button
          padding: 6px 30px;
          color: #fff;
          border: 1px solid #9bd5f2;
          background: transparent;
          border-radius: 40px;
          -webkit-border-radius: 40px;
          px2px(font-size, 30px);
      .price
        position: relative;
        px2px(font-size, 64px);
      .input
        input
          width: 300px;
          height: 60px;
          border: 1px solid #fff;
          text-align: center;
          border-radius: 10px;
          -webkit-border-radius: 10px;
          px2px(font-size, 32px);
</style>
