<template>
  <div class="button">
    <div class="submit">
      <div class="menu" @click="showMenu">
        <i class="iconfont icon-caidan"></i>
      </div>
      <div class="enroll" v-if="!paying && canPay">
        <button class="pay" @click="callWeiXinPay">立即购买</button>
      </div>
      <div class="enroll" v-if="!paying && !canPay">
        <button disabled class="pay">立即购买</button>
      </div>
      <div class="enroll" v-if="paying">
        <button class="enter">报名中...</button>
      </div>
    </div>
    <sConfirm-pay v-if="payShow" :payInfo="payInfo" @confirmPay="confirmPay"></sConfirm-pay>
    <focus-code :show="focusCodeShow" @updateFocusCodeShow="updateFocusCodeShow"></focus-code>
    <payError-code :show="payErrorShow" @updatePayErrorShow="updatePayErrorShow"></payError-code>
  </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    import sConfirmPay from '@student/views/series/confirm-pay';
    import payCode from '@student/components/payCode';
    import focusCode from '@student/components/focusCode';
    import payErrorCode from '@student/components/payErrorCode';

    export default{
      name: 'e-button',
      components: {
        payCode,
        focusCode,
        sConfirmPay,
        payErrorCode,
      },
      props: {
        orderDetail: {
          type: null
        },
        canPay: {
          type: null
        }
      },
      computed: mapGetters({

      }),
      data() {
        return {
          showMore: false,
          paying: false,
          enrollData: null,
          payUrl: '',
          payInfo: null,
          payShow: false,
          payCodeShow: false,
          focusCodeShow: false,
          payErrorShow: false,
        }
      },
      methods: {
        callWeiXinPay() {
          // 开启付款状态
          this.paying = true;
          // 课程报名
          this.lessonEnroll();
        },
        lessonEnroll() {
          // 课程报名
          // 数据
          this.enrollData = { ...this.orderDetail };
          this.payInfo = { ...this.orderDetail };
          // 判断付款类型
          // 发起支付请求
          if(this.orderDetail.margin < 0 && (this.orderDetail.surplus == this.orderDetail.price)){
            this.callpay(this.orderDetail.pay_data);
          }else{
            // 直接支付
            this.confirmPay(true);
            //
            /*this.payShow = true;*/
          }
        },
        confirmPay(isPay) {
          if(isPay){
            //
            if(this.enrollData.margin < 0){
              // 开始微信支付
              this.callpay(this.enrollData.pay_data);
            }else{
              // 普通支付
              this.generalpay(this.enrollData.order);
            }
          }else{
            this.paying = false;
          }
          //
          this.payShow = false;
        },
        generalpay(order){
          //
          this.$store.dispatch('fetchSeriesPurchase', {order:order}).then(()=>{
            // 关闭付款状态
            this.paying = false;
            // 判断是否已经关注
            if(this.enrollData.subscribed != 1){
              return this.focusCodeShow = true;
            }
            // 付款成功
            swal({
              title: '',
              text: '付款成功',
              confirmButtonText: "知道了"
            }, ()=>{
              // 跳转到系列课详情页
              // this.$router.push({ name: 'seriesBrief', ...this.$route.params });
              window.location.href = `/series-auto?series_sn=${this.$route.params.series_sn}`;
            });
          }, (err)=>{
            //
            swal({
              title: '错误提醒',
              text: (err.message ? err.message : '网络链接失败'),
              confirmButtonText: "知道了"
            });
            // 关闭付款状态
            this.paying = false;
          });
        },
        callpay(params) {
          if (typeof WeixinJSBridge == "undefined") {
            // 另一种方式支付
            if(wx){
              return this.chooseWXPay(params);
            }
            if (document.addEventListener) {
              document.addEventListener('WeixinJSBridgeReady', this.jsApiCall, false);
            } else if (document.attachEvent) {
              document.attachEvent('WeixinJSBridgeReady', this.jsApiCall);
              document.attachEvent('onWeixinJSBridgeReady', this.jsApiCall);
            }
          } else {
            this.jsApiCall(params);
          }
        },
        //调用微信JS api 支付
        jsApiCall(params) {
          var self = this;
          // 开始支付
          WeixinJSBridge.invoke(
            'getBrandWCPayRequest',
            params,
            function (res) {
              WeixinJSBridge.log(res.err_msg);
              /*alert(JSON.stringify(res))*/
              if (res.err_msg == 'get_brand_wcpay_request:ok') {
                // 判断是否已经关注
                if(self.enrollData.subscribed != 1){
                  self.focusCodeShow = true;
                }else{
                  // 跳转到系列课详情页
                  // self.$router.push({ name: 'seriesBrief', ...self.$route.params });
                  window.location.href = `/series-auto?series_sn=${self.$route.params.series_sn}`;
                }
                // 关闭付款状态
                self.paying = false;
              } else if (res.err_msg == 'get_brand_wcpay_request:fail') {
                // 开始重新支付
                self.payAgain(self);
              } else {
                // 关闭付款状态
                self.paying = false;
              }
            }
          );
        },
        chooseWXPay(params) {
          var self = this;
          var payConfig = {
            ...params,
            success: (res)=>{
              // alert(JSON.stringify(res))
              if (res.err_msg == 'get_brand_wcpay_request:ok') {
                // 判断是否已经关注
                if(self.enrollData.subscribed != 1){
                  self.focusCodeShow = true;
                }else{
                  // 跳转到系列课详情页
                  // self.$router.push({ name: 'seriesBrief', ...self.$route.params });
                  window.location.href = `/series-auto?series_sn=${this.$route.params.series_sn}`;
                }
                // 关闭付款状态
                self.paying = false;
              } else if (res.err_msg == 'get_brand_wcpay_request:fail') {
                // 开始重新支付
                self.payAgain(self);
              } else {
                // 关闭付款状态
                self.paying = false;
              }
            }
          };
          // 开始支付
          wx.chooseWXPay(payConfig);
        },
        payAgain(ctx){
          ctx.paying = false;
          ctx.payErrorShow = true;
        },
        updatePayCodeShow(show){
          this.payCodeShow = show;
        },
        updateFocusCodeShow(show) {
          // 跳转到系列课详情页
          // this.$router.push({ name: 'seriesBrief', ...this.$route.params });
          window.location.href = `/series-auto?series_sn=${this.$route.params.series_sn}`;
        },
        updatePayErrorShow(show) {
          // 确定
          this.payErrorShow = show;
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

  .body-pc
    .button
      margin: 0 auto;
      width: 640px;

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
    .enroll
      button
        width: 100%;
        height: 100%;
        color: #fff;
        background: #4DA9EB;
        border: 0 none;
        border-radius: 12px;
        -webkit-border-radius: 12px;
        px2px(font-size, 36px);
        &[disabled]
          background: #aaa;
  .submit
    display: -webkit-box;
    display: box;
    .enroll
      display: -webkit-box;
      display: box;
      width: 100%;
      height: 100%;
      color: #fff;
      border: 0 none;
      -webkit-box-flex: 3.8;
      box-flex: 3.8;
      border-radius: 12px;
      -webkit-border-radius: 12px;
      px2px(font-size, 36px);
      &[disabled]
        background: #aaa;
      button
        display: block;
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
