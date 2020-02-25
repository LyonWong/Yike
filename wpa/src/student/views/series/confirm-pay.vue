<template>
  <section class="content-confirm">
    <div class="confirm-body">
      <div class="confirm-title">
        支付
      </div>
      <div class="confirm-content">
        <div class="con-price">
          <p>系列课价格</p>
          <p>&#65509;{{payInfo.order_amount}}</p>
        </div>
        <div class="con-pay">
          <div><span>优惠抵扣：</span>{{payInfo.deduct}}元</div>
          <div><span>余额支付：</span>{{payInfo.charge}}元</div>
          <div><span>微信支付：</span><span class="weixin">{{payInfo.surplus}}</span>元</div>
        </div>
      </div>
      <button class="confirm-button" @click="confirm">
        确认付款
      </button>
      <div class="confirm-close" @click="cancle">
        <span><i class="iconfont icon-guanbi"></i></span>
      </div>
    </div>
  </section>
</template>

<script>
  export default{
    name: 'sConfirm-pay',
    props: {
      payInfo: {
        type: null,
      },
    },
    methods: {
      confirm() {
        this.$emit('confirmPay', true);
      },
      cancle() {
        this.$emit('confirmPay', false);
      },
    },
  };
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";

  .button
    .content-confirm
      padding: 0;
      height: 100%;
  .content-confirm
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    -webkit-display: flex;
    align-items:center;
    justify-content: center;
    z-index: 10;
    background-color: rgba(0,0,0,.3);
    .confirm-body
      position: relative;
      padding: 0 40px 40px;
      width: 540px;
      background: #fff;
      border-radius: 12px;
      -webkit-border-radius: 12px;
      .confirm-title
        padding: 42px 0;
        text-align: center;
        border-bottom: 1px solid #DCDCDC;
      .con-price
        text-align: center;
        border-bottom: 1px solid #DCDCDC;
        >:first-child
          margin: 50px 0 0;
          px2px(font-size, 29px);
        >:last-child
          margin: 27px 0 50px;
          px2px(font-size, 65px);
      .con-pay
        padding: 38px 0;
        px2px(font-size, 29px);
        px2px(line-height, 40px);
        span
         color: #9CA7C1;
         &.weixin
           color: #fb6666;
      .confirm-button
        padding: 24px 0;
        width: 100%;
        border: 1px solid #12B7F5;
        color: #12B7F5;
        background: #fff;
        border-radius: 50px;
        -webkit-border-radius: 50px;
        px2px(font-size, 34px);
      .confirm-close
        position: absolute;
        left: 0;
        bottom: -150px;
        width: 100%;
        text-align: center;
        span
          display: inline-block;
          padding: 12px 18px;
          color: #fff;
          border: 3px solid #fff;
          border-radius: 100%;
          -webkit-border-radius: 100%;
          px2px(font-size, 50px);
          .iconfont
            px2px(font-size, 50px);

</style>
