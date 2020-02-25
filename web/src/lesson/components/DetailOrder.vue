<template>
  <popup class="c-lesson-detail-order flex-col" :isOpen="order" v-on:close="$emit('cancel')">
      <div slot="head" class="order-head flex-row font-medium">订单详情</div>
      <div class="order-body">
        <div class="title">{{_order.title}}</div>
        <div class="list items" v-if="_order.list">
          <ul>
            <li v-for="(lesson, index) in _order.list" :key="index" class="flex-row">
              <div>{{lesson.title}}</div>
              <div>￥{{lesson.price}}</div>
            </li>
          </ul>
        </div>
        <div class="item">
          <div class="item-head font-bold">小计</div>
          <div>
            <del v-if="_order.order_total > _order.order_price">￥{{_order.order_total}}</del>
            <span>￥{{_order.order_price}}</span>
          </div>
        </div>
        <div class="item" v-if="_order.deduct">
          <div class="item-head font-bold">优惠抵扣</div>
          <div class="item-info font-bold">-￥{{_order.deduct}}</div>
        </div>
        <div class="item">
          <div class="item-head font-bold">订单金额</div>
          <div class="item-info font-bold">￥{{_order.order_amount}}</div>
        </div>
        <div class="item" v-if="_order.charge">
          <div class="item-head font-bold">余额支付</div>
          <div class="item-info font-bold">-￥{{_order.charge}}</div>
        </div>
      </div>
      <div slot="foot" class="order-foot flex-row">
        <div class="foot-cancel flex-row click font-medium" @click="$emit('cancel')">取消</div>
        <div class="foot-message flex-row">应付: ￥{{_order.surplus}}</div>
        <div class="foot-confirm flex-row click font-medium" @click="confirm">确认支付</div>
      </div>
      <modal-action display="scanUrl" width="7rem" v-if="scanUrl" style="z-index:999">
        <div slot="head">请用微信扫码支付</div>
        <div class="flex-row">
          <img :src="`/make-qrcode?text=${scanUrl}`"/>
        </div>
        <div slot="foot" class="btn btn-vice" @click="cancelScan">取消支付</div>
      </modal-action>
  </popup>
</template>

<script>
  import Popup from "../../components/Popup";
  import ModalAction from "../../components/modal/Action";

  export default {
    name: 'lesson-detail-order',
    components: {ModalAction, Popup},
    props: ['order'],
    data() {
      return {
        checkWXPay: null,
        scanUrl: null
      }
    },
    created() {
      this.wx.checkJsApi({
        jsApiList: ['chooseWXPay'],
        success: (res) => {
          this.checkWXPay = res.checkResult.chooseWXPay
        }
      })
    },
    computed: {
      _order() {
        return this.order || {}
      },
      isOpen() {
        return this.order && this.app.env() !== 'wxa'
      }
    },
    watch: {
      order: function(v) {
        if (v && this.app.env() === 'wxa') {
          let version = this.app.cookie.get('version')
          if (0 && version && version > '1.2.5.2') {
            alert('小程序暂不支持支付，请前往公众号yike-fm购买课程')
          } else {
            let from = encodeURIComponent(`${location.href}`)
            this.wx.miniProgram.navigateTo({
              url: `/page/pay/index?order=${this.order.sn}&from=${from}`
            })
          }
          this.$emit('cancel')
        }
      }
    },
    methods: {
      confirm() {
        if (this.order.surplus === 0) {
          this.purchaseByBalance()
        } else if (this.app.env() === 'wxm') {
          this.purchaseByWeixinMp()
        } else {
          this.purchaseByWeixinScan()
        }
      },
      purchaseByBalance() {
        this.api.post('/api/order-purchase', {
          sn: this.order.sn
        }).then(() => {
          this.$emit('complete')
        })
      },
      purchaseByWeixinMp() {
        this.api.post('/api/order-prepay-wxm', {
          sn: this.order.sn
        }).then((res) => {
          this.wx.chooseWXPay({
            timestamp: res.data.timeStamp,
            nonceStr: res.data.nonceStr,
            package: res.data.package,
            signType: res.data.signType,
            paySign: res.data.paySign,
            success: () => {
              this.$emit('complete')
            }
          })
        })
      },
      purchaseByWeixinScan() {
        this.api.post('/api/order-prepay-wxs', {
          sn: this.order.sn
        }).then((res) => {
          this.scanUrl = window.btoa(res.data.scanUrl)
          let cnt = 100
          let hid = setInterval(() => {
            this.api.get('/api/order-inquiry', {
              sn: this.order.sn
            }, {loading: false}).then((r) => {
              if (r.data.status === 'paid') {
                clearInterval(hid)
                this.$emit('complete');
              }
            })
            if (!this.scanUrl || cnt-- < 0) {
              clearInterval(hid)
              this.$emit('cancel')
            }
          }, 3000)
        }, (res) => {
          switch (res.error) {
            case '0.1':
              this.api.onErrorSign()
              break;
            case '1': // 缺少公众号openid无法支付，需扫码跳转到微信内
              this.scanUrl = window.btoa(window.location.href)
              break;
          }
        })
      },
      cancelScan() {
        this.scanUrl = null
        this.$emit('cancel')
      }
    }
  }
</script>

<style scoped>
  .c-lesson-detail-order {
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
    color: #F23F15;
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
    color: #F23F15;
  }
  .item-vice {
    color: #2F57DA;
  }
  .item del {
    color: #999;
  }
</style>
