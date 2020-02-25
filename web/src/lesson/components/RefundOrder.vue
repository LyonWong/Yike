<template>
  <popup class="c-order-refund flex-col" :isOpen="refund" v-on:close="$emit('cancel')">
      <div slot="head" class="order-head flex-row font-medium">无条件退款</div>
      <div class="order-body">
        <div class="title">待退款内容</div>
        <div class="list items" v-if="_refund.list">
          <ul>
            <li v-for="(item, index) in _refund.list" :key="index" class="flex-row">
              <div>{{item.title}}</div>
              <div>￥{{item.order_amount}}</div>
            </li>
          </ul>
        </div>
        <div class="item">
          <div class="item-head font-bold">微信退还</div>
          <div class="item-info font-bold">￥{{_refund.total.weixin||_refund.total.wxa||0}}</div>
        </div>
        <div class="item">
          <div class="item-head font-bold">余额退还</div>
          <div class="item-info font-bold">￥{{_refund.total.balance||0}}</div>
        </div>
      </div>
      <div slot="foot" class="order-foot flex-row">
        <div class="foot-cancel flex-row click font-medium" @click="$emit('cancel')">取消</div>
        <div class="foot-message flex-row">总计：￥{{_refund.total | sum}}</div>
        <div class="foot-confirm flex-row click font-medium" @click="confirm">确认退款</div>
      </div>
  </popup>
</template>

<script>
  import Popup from "../../components/Popup";

  export default {
    name: 'refund-order',
    components: {Popup},
    props: ['profile', 'refund'],
    data() {
      return {
        checkWXPay: null
      }
    },
    computed: {
      _refund() {
        return this.refund || {list: [], total: {}}
      },
      isOpen() {
        return this.refund
      }
    },
    methods: {
      confirm() {
        this.api.post(`/api/order-refund-${this.profile.form}`, {
          sn: this.profile.sn
        }).then(() => {
          this.$emit('complete')
        })
      }
    },
    filters: {
      sum(vals) {
        let sum = 0
        for (let key in vals) {
          sum += vals[key]
        }
        return sum
      }
    }
  }
</script>

<style scoped>
  .c-order-refund {
    position: fixed;
    justify-content: flex-end;
    z-index: 100;
    bottom: 0;
    left: 0;
    height: 100%;
    width: 100%;
  }

  .frm-order {
    width: 7.5rem;
    background: #fff;
    border-radius: .2rem .2rem 0 0;
    color: #0D0D0D;
    z-index: 100;
  }

  .order-head {
    height: 1.4rem;
    font-size: .32rem;
  }

  .order-body {
    padding: 0 .3rem;
  }

  .order-foot {
    height: 1rem;
    width: 100%;
  }

  .foot-cancel {
    padding: .3rem;
    font-size: .28rem;
    color: #999;
    cursor: pointer;
  }

  .foot-message {
    flex-grow: 1;
    justify-content: flex-end;
    padding: 0 .48rem;
    font-size: .3rem;
    color: #2F57DA;
  }

  .foot-confirm {
    font-size: .32rem;
    color: #fff;
    background: #2F57DA;
    padding: 0 .48rem;
    height: 100%;
    cursor: pointer;
  }

  .title {
    font-size: .3rem;
    font-weight: bold;
  }

  .list {
    max-height: 2.5rem;
    padding: .2rem 0;
    display: -webkit-box;
    overflow-y: scroll;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    box-sizing: border-box;
    border: .1rem solid #fff;
  }

  .list ul {
    list-style: none;
    padding: 0;
    margin: 0;
    width: 100%;
  }

  .list li {
    justify-content: space-between;
    font-size: .27rem;
    color: #666;
    padding: .2rem;
  }

  .item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 1rem;
    font-size: .27rem;
    border-top: 1px solid #E6E6E6;
  }

  .item-head {
  }

  .item-info {
    color: #2F57DA;
  }
</style>
