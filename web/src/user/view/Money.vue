<template>
  <div class="v-user-money">
    <div class="overview flex-col">
      <router-link id="detail" to="./money-detail">查看明细</router-link>
      <div class="balance flex-col">
        <div class="tag">余额</div>
        <div class="num">{{overview.balance/100}}</div>
      </div>
      <div class="more flex-row">
        <div class="more-left flex-row">
          <div class="cash">
            <div class="tag">可提现</div>
            <div class="num">{{overview.cash/100}}</div>
          </div>
          <div class="voucher">
            <div class="tag">不可提现</div>
            <div class="num">{{overview.voucher/100}}</div>
          </div>
        </div>
        <div class="more-right">
          <router-link id="drawcash" class="flex-row" to="./money-drawcash">立即提现</router-link>
        </div>
      </div>
    </div>
    <div class="orders">
      <div class="head flex-row">
        <div class="order-title">订单列表</div>
        <div class="order-summary flex-row">
          <div class="done">已确认 ￥{{overview.done/100 || 0}}</div>
          <div class="paid">待确认 ￥{{(overview.paid/100||0) + (overview.firm/100||0)}}</div>
        </div>
      </div>
      <div class="body">
        <div class="list" v-infinite-scroll="orderScroll" infinite-scroll-disabled="order.isEnd" infinite-scroll-distance="500">
          <div class="order" :class="[item.status]" v-for="(item,idx) in order.list" :key="idx">
            <div class="order-info flex-row">
              <div class="cover">
                <img :src="item.cover"/>
              </div>
              <div class="desc flex-col">
                <div class="title">{{item.title}}</div>
                <div class="time">{{item.tms_create}}</div>
              </div>
            </div>
            <div class="order-amount">￥{{item.amount/100}}</div>
            <div class="order-remark flex-row">
              <div class="sn">订单号：{{item.sn}}</div>
              <div class="status">{{dictOrderStatus[item.status]}}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import infiniteScroll from 'vue-infinite-scroll'
  export default {
    name: 'user-money',
    components: {},
    directives: {infiniteScroll},
    data() {
      return {
        overview: {
          balance: null,
          cash: null,
          voucher: null,
          paid: null,
          firm: null,
          done: null,
          refund: null
        },
        order: {
          list: [],
          cursor: null,
          isEnd: false
        },
        dictOrderStatus: {
          paid: '待确认',
          firm: '待结算',
          done: '已结算',
          refund: '已退款'
        }
      }
    },
    created() {
      this.api.get('/api/user-money-overview').then( (res) => {
        this.overview = res.data
      }, this.api.onErrorSign)
    },
    methods: {
      orderScroll() {
        let limit = 10;
        this.api.get('/api/user-money-orders', {
          cursor: this.order.cursor,
          limit: limit
        }).then( (res) => {
          this.order.list.push(...res.data)
          this.order.cursor = this.order.list[this.order.list.length-1].order_id
          this.order.isEnd = res.data.length < limit
        })
      }
    }
  }
</script>

<style scoped>
  .overview {
    position: relative;
    justify-content: space-between;
    align-items: flex-start;
    height: 3.25rem;
    padding: .44rem .48rem;
    background: #7F83E2;
    box-sizing: border-box;
  }
  .tag {
    color: #fff;
    opacity: 0.5;
  }
  .num {
    color: #fff;
  }
  .balance {
    height: 1.12rem;
    justify-content: space-between;
    align-items: flex-start;
  }
  .balance .tag {
    font-size: .26rem;
  }
  .balance .num {
    font-size: .72rem;
  }
  .more {
    width: 100%;
    height: .72rem;
    justify-content: space-between;
  }
  .more-left {
    width: 4.5rem;
  }
  .more, .more-left {
  }
  .more .tag {
    font-size: .24rem;
  }
  .more .num {
    font-size: .24rem;
  }
  .cash, .voucher {
    flex-grow: 1;
    height: .72rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }
  #detail {
    position: absolute;
    top: .4rem;
    right: .53rem;
    font-size: .26rem;
    color: #fff;
  }
  #drawcash {
    width: 1.8rem;
    height: .72rem;
    border: 1px solid #fff;
    border-radius: .36rem;
    font-size: .3rem;
    color: #fff;
  }

  .orders {
    background: #EDEEFE;
    padding: .3rem;
  }
  .orders .head {
    justify-content: space-between;
    padding: .15rem;
  }
  .order-title {
    font-size: .36rem;
    color: #6C70D9;
  }
  .order-summary {
    width: 4.5rem;
    font-size: .24rem;
    color: #222;
  }
  .order-summary > div {
    flex-grow: 1;
    display: flex;
    justify-content: flex-end;
  }
  .order {
    margin: .2rem 0;
    padding: .2rem;
    border-radius: .12rem;
    background: #fff;
  }
  .order-info {
    justify-content: flex-start;
  }
  .order-info .cover {
    width: .7rem;
    height: .7rem;
    margin-right: .2rem;
  }
  .order-info .cover img {
    width: 100%;
    height: 100%;
  }
  .order-info .desc {
    height: .72rem;
    justify-content: space-between;
    align-items: flex-start;
  }
  .order-info .title {
    font-size: .3rem;
    color: #222;
  }
  .order-info .time {
    font-size: .2rem;
    color: #999;
  }
  .order-amount {
    text-align: right;
    font-size: .3rem;
    color: #696EEA;
  }
  .order-remark {
    margin-top: .1rem;
    justify-content: space-between;
    color: #222;
  }
  .order.refund  div {
    color: #bfbfbf;
  }
  .order.refund img {
    opacity: 0.3;
  }

</style>
