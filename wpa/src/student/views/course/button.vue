<template>
  <div class="button">
    <div class="submit">
      <div class="menu" @click="showMenu">
        <i class="iconfont icon-caidan"></i>
      </div>
      <div class="enroll" v-if="!paying && courseDetail && (isEnroll != 'refund' && isEnroll != 'browse' && isEnroll != 'reset' && courseDetail.step != 'closed')">
        <button class="enter" @click="startLesson" v-if="courseDetail.step == 'onlive' || courseDetail.step == 'repose' || courseDetail.step == 'finish'">进入课堂</button>
        <button disabled v-if="courseDetail.step == 'opened'">计划开课:{{`${courseDetail.plan.dtm_now}#${courseDetail.plan.dtm_start}` | moment}}</button>
      </div>
      <div class="enroll" v-if="!paying && (isEnroll == 'browse' || isEnroll == 'reset') && courseDetail && courseDetail.step != 'closed'">
        <button class="free" v-if="courseDetail.price == 0 && !canEnter" @click="callWeiXinPay">免费报名</button>
        <button disabled class="pay" v-if="courseDetail.price > 0 && !canEnter && enableButton">立即报名</button>
        <button class="pay" v-if="courseDetail.price > 0 && !canEnter && !enableButton" @click="callWeiXinPay">立即报名</button>
        <button class="enter" v-if="canEnter && (courseDetail.step == 'onlive' || courseDetail.step == 'repose')" @click="startLesson">进入课堂</button>
        <button disabled v-if="canEnter && courseDetail.step == 'opened'">未开课</button>
      </div>
      <div class="enroll refund" v-if="!paying && isEnroll == 'refund'">
        <button disabled>已退款</button>
      </div>
      <div class="enroll" v-if="paying">
        <button class="enter">报名中...</button>
      </div>
      <div class="enroll" v-if="courseDetail && courseDetail.step == 'closed'">
        <button disabled>已下架</button>
      </div>
    </div>
    <confirm-pay v-if="payShow" :payInfo="payInfo" @confirmPay="confirmPay"></confirm-pay>
    <!--<pay-code :show="payCodeShow" :codeUrl="payUrl" @updatePayCodeShow="updatePayCodeShow"></pay-code>-->
    <focus-code :show="focusCodeShow" @updateFocusCodeShow="updateFocusCodeShow"></focus-code>
    <payError-code :show="payErrorShow" @updatePayErrorShow="updatePayErrorShow"></payError-code>
  </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    import confirmPay from '@student/views/course/confirm-pay';
    import payCode from '@student/components/payCode';
    import focusCode from '@student/components/focusCode';
    import payErrorCode from '@student/components/payErrorCode';

    export default{
      name: 'v-button',
      components: {
        payCode,
        focusCode,
        confirmPay,
        payErrorCode,
      },
      props: {
        isEnroll: {
          type: null
        },
        courseDetail: {
          type: null
        },
        liveHost: {
          type: String
        },
        enableButton: {
          type: null
        }
      },
      computed: mapGetters({

      }),
      data() {
        return {
          paying: false,
          enrollData: null,
          canEnter: false,
          query: {},
          payUrl: '',
          payInfo: null,
          payShow: false,
          payCodeShow: false,
          focusCodeShow: false,
          payErrorShow: false,
        }
      },
      created(){
        //获取路由参数
        this.query = this.$route.query;
      },
      methods: {
        showMenu() {
          this.showMore = !this.showMore;
          this.$emit('showMenu', this.showMore);
        },
        callWeiXinPay() {
          // 开启付款状态
          this.paying = true;
          // 课程报名
          this.lessonEnroll(this.query);
        },
        lessonEnroll(query) {
          // 课程报名
          // 已请求过
          /*if(this.enrollData && this.enrollData.order && this.enrollData.price>0){
            // 发起支付请求
            return this.payShow = true;
            // return this.callpay(this.enrollData.pay_data);
          }*/
          // 报名开始
          this.$store.dispatch('fetchLessonEnroll', query).then((data) => {
            // 数据
            this.enrollData = { ...data };
            this.payInfo = { ...data };
            // 是否需要付费
            if(data.price > 0){
              // 发起支付请求
              if (data.surplus > 0 && window.__wxjs_environment === 'miniprogram') {
                wx.miniProgram.navigateTo({
                  url: `/page/pay/index?order=${data.order}&tsn=${query.lesson_sn}`
                });
                this.paying = false
              } else if(data.margin < 0 && (data.surplus == data.price)){
                this.callpay(data.pay_data);
              }else{
                this.payShow = true;
              }
            }else{
              this.canEnter = true;
              this.paying = false;
              // 进入课堂
              if(data.subscribed != 1){
                return this.focusCodeShow = true;
              }
              //
              swal({
                title: '',
                text: '报名成功!',
                confirmButtonText: "知道了"
              }, ()=>{
                // 直接进入
                if(this.courseDetail && this.courseDetail.step == 'opened'){
                  window.location.reload();
                }else{
                  // 进入课堂
                  this.startLesson();
                }
              });
            }
          }, (err) => {
            this.paying = false;
            //
            swal({
              title: '错误提醒',
              text: err.message,
              confirmButtonText: "知道了"
            });
          });
        },
        lessonAccess() {
          // 是否有权限进入课堂
          let query = this.query;
          this.$store.dispatch('fetchLessonAccess', query).then((data) => {
            let params = `?isOwner=no&lesson_sn=${query.lesson_sn}&teacherEnter=yes`;
            for(let d in data){
              params = `${params}&${d}=${data[d]}`;
            };
            window.location.href = `${this.liveHost}${params}`;
          }, (err) => {
            //
            swal({
              title: '错误提醒',
              text: (err.message ? err.message : '网络链接失败'),
              confirmButtonText: "知道了"
            });
          });
        },
        startLesson() {
          // 开始课程
          this.lessonAccess();
        },
        confirmPay(isPay) {
          if(isPay){
            //
            if(this.enrollData.margin < 0){
              // 开始微信支付
              if (this.enrollData.pay_data === false && window.__wxjs_environment === 'miniprogram') {
                wx.miniProgram.navigateTo ({
                  url: `/page/pay/index?order=${data.order}&tsn=${query.lesson_sn}`
                })
                this.paying = false;
              } else {
                this.callpay(this.enrollData.pay_data);
              }
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
          this.$store.dispatch('fetchLessonPurchase', {order:order}).then(()=>{
            // 关闭付款状态
            this.paying = false;
            // 开通直播通道
            this.canEnter = true;
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
              // 进入课堂
              if(this.courseDetail && this.courseDetail.step == 'opened'){
                setTimeout(()=>{
                  window.location.reload();
                }, 500);
              }else{
                // 进入课堂
                this.startLesson();
              }
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
            // 开始
            // 另一种方式支付
            if(wx){
              return this.chooseWXPay(params);
            }
            //
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
              /*alert(JSON.stringify(res))
              swal({
                title: '',
                text: JSON.stringify(res),
                confirmButtonText: "知道了"
              });*/
              if (res.err_msg == 'get_brand_wcpay_request:ok') {
                // 判断是否已经关注
                if(self.enrollData.subscribed != 1){
                  self.focusCodeShow = true;
                }else{
                  // 进入课堂
                  if(self.courseDetail && self.courseDetail.step == 'opened'){
                    window.location.reload();
                  }else{
                    // 进入课堂
                    self.startLesson();
                  }
                }
                // 关闭付款状态
                self.paying = false;
                // 开通直播通道
                self.canEnter = true;
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
              alert(JSON.stringify(res))
              if (res.err_msg == 'get_brand_wcpay_request:ok') {
                // 判断是否已经关注
                if(self.enrollData.subscribed != 1){
                  self.focusCodeShow = true;
                }else{
                  // 进入课堂
                  if(self.courseDetail && self.courseDetail.step == 'opened'){
                    window.location.reload();
                  }else{
                    // 进入课堂
                    self.startLesson();
                  }
                }
                // 关闭付款状态
                self.paying = false;
                // 开通直播通道
                self.canEnter = true;
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
          /*return swal({
            title: '错误提醒',
            text: '若遇到支付问题，请长按页面底部二维码，关注易灵微课公众号，从课程列表进入报名',
            confirmButtonText: "知道了"
          });*/
          // 重新付费开始
          /*ctx.$store.dispatch('fetchLessonEnroll', {type:'native', ...ctx.query}).then((data)=>{
            ctx.paying = false;
            ctx.payCodeShow = true;
            ctx.payUrl = data.pay_url;
          }, (err) => {
            ctx.paying = false;
            //
            swal({
              title: '错误提醒',
              text: err.message,
              confirmButtonText: "知道了"
            });
          });*/
        },
        updatePayCodeShow(show){
          this.payCodeShow = show;
        },
        updateFocusCodeShow(show) {
          // 确定
          if(!show){
            if(this.courseDetail && this.courseDetail.step == 'opened'){
              window.location.reload();
            }else{
              // 进入课堂
              this.startLesson();
            }
          }
        },
        updatePayErrorShow(show) {
          // 确定
          this.payErrorShow = show;
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
    .enroll
      button
        display: block;
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
