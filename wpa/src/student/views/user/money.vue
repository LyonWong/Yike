<template>
  <section class="user-money">
    <div class="money-con">
      <scroller :on-infinite="infinite" noDataText="没有更多记录" ref="my_scroller">
        <div class="money-forward">
          <div>
            <!--<i class="iconfont icon-yue1"></i>-->
            <span>账户余额</span>
          </div>
          <div class="price" v-if="moneyBalance">
            &#65509;{{moneyBalance.balance}}
          <!--<i class="iconfont icon-open-right"></i>-->
          </div>
          <div v-if="moneyOverview">
            待确认收入 ￥{{moneyOverview.expect - moneyOverview.gross | decimal(2)}}
          </div>
          <div>
            <button @click="toMoneyCenter">明细</button>
            <button class="gap" @click="takeOutMoney">提现</button>
          </div>
        </div>
        <p class="order">订单列表</p>
        <ul>
          <li v-for="list in moneyBill">
            <div class="clearfix">
              <span class="pull-right">&#65509;{{list.order_amount}}</span>
              <div class="money-title">
                {{list.desc}}
                <span class="refund" v-if="list.status == 'refund'">已退款</span>
              </div>
            </div>
            <div class="clearfix" v-if="list.balance_var">
              <span class="pull-right">&#65509;{{list.balance_var}}</span>
              <span>
                余额抵扣
              </span>
            </div>
            <div class="clearfix">
              <span class="pull-right actual-pay">
                <span>实付款</span>
                <span>&#65509;{{list.paid_amount}}</span>
              </span>
              <span>
                {{list.tms}}
              </span>
            </div>
          </li>
        </ul>
      </scroller>
    </div>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';

  export default{
    name: 'money',
    components: {
    },
    computed: {
      ...mapGetters({
        moneyBalance: 'getMoneyBalance',
        /*moneyBill: 'getMoneyBill',*/
      }),
    },
    data() {
      return {
        cursor: '',
        moneyBill: [],
        moneyOverview: {},
      }
    },
    created() {
      // 账户余额
      this.$store.dispatch('fetchMoneyBalance').then(()=>{
        console.log('fetchMoneyBalance success');
      },(err)=>{
        swal({
          title: '错误提醒',
          text: (err.message ? err.message : '网络链接失败'),
          confirmButtonText: "知道了"
        });
      });

      this.$store.dispatch('fetchMoneyOverview').then((res)=>{
        this.moneyOverview = res;
      });
      // 获得订单列表
      /*this.infinite();*/
    },
    methods: {
      toMoneyCenter() {
        this.$router.push({ name: 'money-detail'});
      },
      takeOutMoney() {
        this.$router.push({ name: 'money-out'});
      },
      infinite(done) {
        // 开始
        let length = this.moneyBill.length;
        // 是否有游标和长度
        if (!length){
          return this.fetchBill(done);
        }
        try{
          this.fetchBill(done);
        }catch(e){};
      },
      fetchBill(done) {
        let query = this.cursor?{cursor:this.cursor,limit:10}:{limit:10};
        // 获得评价列表
        this.$store.dispatch('fetchMoneyBill', query).then((data) => {
          //
          this.moneyBill = [...this.moneyBill, ...data.list];
          this.cursor = data.cursor;
          //
          if (done) {
            if (data.list.length < query.limit) {
              done(true);
            } else {
              setTimeout(()=>{
                done();
              }, 1000);
            };
          };
          console.log('获取列表成功!');
        }, (error) => {
          done(true);
          console.log('fail');
          swal({
            title: '错误提醒',
            text: error,
            confirmButtonText: "知道了"
          });
        });
      },
    },
  };
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";

  .user-money
    background: #f7f9fc;
    px2px(font-size, 36px);
    .money-con
      ._v-container
        .loading-layer
          height: 200px !important;
        >._v-content>.loading-layer .spinner-holder .spinner
          width: 64px;
          height: 64px;
      .no-data-text
        px2px(font-size, 32px);
    .icon-yue1
      color: #FCB446;
      px2px(font-size, 42px);
    .order
      margin: 20px 0;
      text-align: center;
      px2px(font-size, 32px);
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
          &.gap
            margin-left: 160px;
      .price
        position: relative;
        /*px2px(top, 36px);
        px2px(right, 36px);*/
        px2px(font-size, 64px);
        .icon-open-right
          vertical-align: inherit;
          px2px(font-size, 28px);
    ul
      margin: 0;
      padding: 0;
      padding-bottom: 30px;
      list-style-type: none;
      li
        margin-bottom: 20px;
        padding: 0 28px;
        background: #fff;
        >:nth-of-type(1)
          padding: 30px 0;
        >:nth-of-type(2)
          color: #999;
          padding: 28px 0;
          px2px(font-size, 26px);
        >:last-child
          color: #999;
          padding: 22px 0;
          border-top: 1px solid #E6EAF2;
          px2px(font-size, 26px);
          .actual-pay
            px2px(font-size, 32px);
            >:first-child
              color: #3C4A55;
            >:last-child
              color: #FB6666;
        .refund
          padding: 3px 8px;
          color: #FB6666;
          border: 1px solid #FB6666;
          border-radius: 5px;
          -webkit-border-radius: 5px;
          px2px(font-size, 26px);
        .money-title
          width: 560px;

  @media only screen and (device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) {
    .user-money {
      .money-con {
        ._v-container {
          .loading-layer {
            height: 260px !important;
          }
        }
      }
    }
  }
</style>
