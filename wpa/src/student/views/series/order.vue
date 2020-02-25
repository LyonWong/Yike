<template>
  <section>
    <div class="order">
      <div class="order-detail" v-if="orders">
        <div class="order-tit ellipsis clearfix" @click="goToSeriesDeatil">
          <!--<i class="iconfont icon-xiliekebiaoqian"></i>-->
          <span class="arrow"></span>
          {{ orders.series_name }}
        </div>
        <div class="order-list">
          <div class="item" v-for="lesson in orders.lessons">
            {{ lesson.title }}
            <span class="right">
              &#165;
              {{ lesson.price }}
            </span>
          </div>
        </div>
      </div>
      <div class="order-detail" v-if="orders">
        <div class="order-tit order-price clearfix">
          订单价格
          <span class="right">
            <span class="total">&#165;{{ orders.order_total }}</span>
            <span>&#165;{{ orders.order_price }}</span>
          </span>
        </div>
        <div class="order-list">
          <div class="item">
            优惠抵扣项
            <span class="right">
              &#165;
              {{ orders.deduct }}
            </span>
          </div>
          <div class="item">
            余额支付
            <span class="right">
              &#165;
              {{ orders.charge }}
            </span>
          </div>
          <div class="item">
            微信支付
            <span class="right weixin">
              &#165;
              {{ orders.surplus }}
            </span>
          </div>
        </div>
      </div>
    </div>
    <e-button v-if="orders" :canPay="canPay" :orderDetail="orders" @showMenu="showMenu"></e-button>
    <e-button v-if="!orders" :canPay="canPay"></e-button>
    <div class="lesson-more" v-if="showMore">
      <div class="more-container">
        <div @click="backToHome">回到首页</div>
        <div @click="backToHistory">返回上一级</div>
      </div>
    </div>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';
  import eButton from '@student/views/series/button.vue';

  export default{
    name: 'series-order',
    components: {
      eButton,
    },
    props: {
      canShare: {
        type: null
      },
    },
    data() {
      return {
        canPay: false,
        orders: null,
        showMore: false,
      };
    },
    created() {
      // 获取路由参数
      let params = this.$route.params;
      let query = this.$route.query;
      let opt = {
        ...params
      };
      if(query.origin) {
        opt['origin'] = query.origin;
      }
      // 开始获取详情
      this.$store.dispatch('fetchSeriesOrder', opt).then((data) => {
        this.orders = data;
        // 开始配置
        this.wxConfig();
        console.log('success');
      }, () => {
        console.log('fail');
      });
    },
    mounted(){

    },
    methods: {
      goToSeriesDeatil() {
        // 跳转到系列课详情页
        this.$router.push({ name: 'seriesBrief', ...this.$route.params });
      },
      showMenu(show) {
        this.showMore = show;
      },
      backToHome() {
        this.$router.push({ name: 'list' });
      },
      backToHistory() {
        // 跳转
        this.$router.go(-1);
      },
      wxConfig() {
        // 请求配置接口
        if(isWeiXin){
          this.$store.dispatch('fetchWXConfig', {url:encodeURIComponent(window.location.href)}).then((result)=>{
            // 微信操作
            wx.config({
              debug: false,
              appId: result.appId,
              timestamp: result.timestamp,
              nonceStr: result.nonceStr,
              signature: result.signature,
              jsApiList: ['chooseWXPay'],
            });
            // 可以支付
            setTimeout(()=>{
              this.canPay = true;
            }, 500);
            console.log('可以支付了');

          }, () => {
            // 不能支付
            console.log('不能支付');
          });
        }
      },
    },
  };
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";

  .order
    padding: 15px;
    padding-bottom: 100px;
    .order-detail
      margin-bottom: 15px;
      padding: 20px;
      background: #fff;
      .order-tit
        position: relative;
        padding: 5px 60px 20px 0;
        border-bottom: 1px solid #d9d9d9;
        &.order-price
          padding: 10px 0 20px;
          .total
            padding-right: 10px;
            color: #9ca7c1;
            text-decoration: line-through;
            px2px(font-size, 26px);
        .right
          position: absolute;
          px2px(right, 0);
        .arrow
          position: absolute;
          width: 40px;
          height: 40px;
          px2px(top, 18px);
          px2px(right, 0);
          &:after
            content: '';
            display: block;
            width: 14px;
            height: 14px;
            border-right: 4px solid #9ca7c1;
            border-top: 4px solid #9ca7c1;
            -webkit-transform: rotate(45deg); /*箭头方向可以自由切换角度*/
            transform: rotate(45deg);
        .iconfont
          position: absolute;
          left: 0;
          color: #12b7f5;
          px2px(font-size, 36px);
      .order-list
        padding-top: 5px;
        .item
          position: relative;
          padding: 15px 0;
          color: #666;
          .right
            position: absolute;
            px2px(right, 0);
            &.weixin
              color: #fb6666;
  div.lesson-more
    position: fixed;
    top: 0;
    bottom: 0;
    margin: 0;
    padding: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,.3);
    z-index: 6;
    .more-container
      position: absolute;
      padding: 0;
      width: 100%;
      px2px(bottom, 98px);
    div>*
      padding: 30px 0;
      color: #333;
      background: #fff;
      text-align: center;
      border-top: 1px solid #d9d9d9;
  .body-pc
    div.lesson-more
      width: 640px;
      .more-container
        bottom: 100px;

  @media only screen and (device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) {
    .order {
      div.lesson-more {
        .more-container {
          padding-bottom: 60px;
        }
      }
    }
  }
</style>
