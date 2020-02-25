<template>
  <div class="c-admire">
    <slot></slot>
    <div class="mask flex-col" v-if="active" @click="$emit('cancel')">
      <div class="container flex-col" @click.stop>
        <div class="head flex-row">
          赞赏作者
        </div>
        <div class="body">
          <div class="choose-amount" v-show="!payQRCode">
            <div class="preset">
              <div class="button amount" v-for="(amount, idx) in amounts" :key="idx" @click="admire(amount)">￥{{amount}}</div>
            </div>
            <div class="custom flex-row">
              <label class="flex-row">￥<input v-model="amount" type="number" placeholder="其他金额" @keydown.enter="admire(amount)"/></label>
              <div class="button flex-row" @click="admire(amount)">确定</div>
            </div>
          </div>
          <div class="scan-to-pay flex-col" v-if="payQRCode">
            <img :src="payQRCode"/>
            <div>微信扫码赞赏</div>
          </div>
        </div>
      </div>
      <i class="close icon-yike icon-cross"></i>
    </div>
    <div class="list">
      <img v-for="(user,idx) in users" :key="idx" :src="user.avatar">
    </div>
  </div>
</template>

<script>
  export default {
    name: 'admire',
    props: ['sn', 'active'],
    data() {
      return {
        users: [],
        amounts: [1, 5, 10, 20, 50, 100],
        amount: null,
        payQRCode: null
      }
    },
    created() {
      this.init()
    },
    methods: {
      init() {
        this.api.get('/api/order-admired', {sn: this.sn, limit: 21}).then((res) => {
          this.users = res.data
        })
      },
      admire(amount) {
        this.api.post('/api/order-admire', {
          sn: this.sn,
          amount: Math.round(amount*100)
        }).then((res) => {
          switch (this.app.env()) {
            case 'wxm':
              this.admireWXM(res.data)
              break;
            default:
              this.admireScan(res.data)
          }
        }, (err) => {
          this.bus.$emit('dialog', {
            info: {body: err.message}
          })
        })
      },
      admireWXM(sn) {
        this.api.post('/api/order-prepay-wxm', {sn: sn, useBalance: false}).then((res) => {
          this.wx.chooseWXPay({
            timestamp: res.data.timeStamp,
            nonceStr: res.data.nonceStr,
            package: res.data.package,
            signType: res.data.signType,
            paySign: res.data.paySign,
            success: () => {
              this.complete()
            }
          })
        })
      },
      admireScan(sn) {
        this.api.post('/api/order-prepay-wxs', {sn: sn, useBalance: false}).then((res) => {
          this.payQRCode = '/make-qrcode?text=' + window.btoa(res.data.scanUrl)
          let cnt = 100
          let hid = setInterval(() => {
            this.api.get('/api/order-inquiry', {sn: sn}).then((r) => {
              if (r.data.status === 'done') {
                clearInterval(hid)
                this.complete()
              }
            })
            if (!this.payQRCode|| cnt-- < 0) {
              clearInterval(hid)
              this.$emit('cancel')
            }
          }, 3000)
        })
      },
      complete() {
        this.init()
        this.$emit('cancel')
      }
    }
  }
</script>

<style scoped>
  .mask {
    position: fixed;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.3)
  }
  .list {
    padding: .3rem;
    width: 5rem;
    text-align: center;
  }
  .list img {
    width: .5rem;
    height: .5rem;
    margin: .1rem;
  }
  .container {
    width: 5.4rem;
    background: #fff;
    border-radius: .08rem;
  }
  .head {
    padding: 1em;
    color: #F95E5E;
    font-size: .36rem;
    font-weight: bold;
  }
  .body {
    padding: .21rem;
  }
  .close {
    margin: 1em;
    width: 1.6em;
    height: 1.6em;
    line-height: 1.6em;
    text-align: center;
    font-size: .3rem;
    color: #fff;
    border: .1em solid #fff;
    border-radius: 50%;
  }
  .button {
    cursor: pointer;
    display: inline-block;
    margin: .2rem .15rem;
    width: 1.36rem;
    height: .6rem;
    line-height: .6rem;
    box-sizing: border-box;
    border-radius: .3rem;
    text-align: center;
    font-size: .32rem;
  }
  .button.amount {
    border: 2px solid #FAC5BF;
    background: #FFEBEB;
    color: #FF8686;
  }
  .custom label {
    flex-grow: 1;
    justify-content: space-between;
    margin: 0 .15rem;
    padding: .07rem .3rem;
    box-sizing: border-box;
    border:2px solid #FAC5BF;
    border-radius: 2em;
    color: #FF8686;
    font-size: .32rem;
  }
  .custom input {
    flex-grow: 1;
    width: 1.36rem;
    font-size: .32rem;
    border: none;
    outline: none;
    text-align: center;
    color: #FF8686;
  }
  .custom input::-webkit-outer-spin-button,
  .custom input::-webkit-inner-spin-button {
    -webkit-appearance: none;
  }
  .custom input::-webkit-input-placeholder {
    text-align: center;
    color: rgba(255,134,134,0.3);
  }
  .custom .button {
    color: #fff;
    background: #F95E5E;
  }
  .scan-to-pay {
    width: 2.7rem;
    font-size: .28rem;
  }
  .scan-to-pay img {
    width: 100%;
    height: 100%;
  }
  .scan-to-pay div {
    margin: 1em 0;
  }
</style>
