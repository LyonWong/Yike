<template>
  <div class="c-admire" :class="[env() ? 'wx' : 'pc']">
    <div class="mask" @click="$emit('cancel')"></div>
    <div class="box">
    <i class="iconfont icon-guanbi" @click="$emit('cancel')"></i>
    <div class="head">赞赏讲师</div>
    <div class="scan" v-if="scan">
      <img :src="`/make-qrcode?text=${scan}`"/>
      <div>微信扫码赞赏</div>
    </div>
    <div class="body" v-else>
      <div class="select-amount">
        <div class="amount btn" v-for="(amount,idx) in amounts" :key="idx" @click="admire(amount)">
          ￥{{amount}}
        </div>
      </div>
      <div class="input-amount">
        <label>￥ <input v-model="inputAmount" type="number" align="center" placeholder="其他金额"/> </label>
        <div class="btn" @click="admire(inputAmount)">确定</div>
      </div>
    </div>
    </div>
  </div>
</template>

<script>
  import vue from 'vue';
  // const wx = require('weixin-js-sdk');
  const _prefix = process.env.NODE_ENV == 'production' ? process.env.LIVE_HOST.replace(/\/$/,'') : '/api';
  export default {
    name: 'admire',
    props: ['lesson'],
    data() {
      return {
        amounts: [1, 5, 10, 20, 50, 100],
        inputAmount: null,
        scan: null
      }
    },
    created() {
    },
    methods: {
      admire(amount) {
        vue.http.post(`${_prefix}/api/order-admire`, {
            sn: this.lesson.sn,
            amount: Math.round(amount*100)
        }).then((res) => {
          console.log('amount', res)
          if (res.data.error === '0') {
            switch (this.env()) {
              case 'wxm':
                this.admireWXM(res.data.data)
                break;
              default:
                this.admireScan(res.data.data)
            }
          } else {
            alert(res.data.message)
          }
        })
      },
      admireWXM(sn) {
        vue.http.post(`${_prefix}/api/order-prepay-wxm`, {sn: sn, useBalance: false}).then((response) => {
          let res = response.data
          wx.chooseWXPay({
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
      admireScan(sn) {
        vue.http.post(`${_prefix}/api/order-prepay-wxs`, {sn: sn, useBalance: false}).then((res) => {
          console.log(res)
          this.scan = window.btoa(res.data.data.scanUrl)
          let cnt = 100
          let hid = setInterval(() => {
            vue.http.get(`${_prefix}/api/order-inquiry?sn=${sn}`).then((r) => {
              if (r.data.data.status === 'done') {
                clearInterval(hid)
                this.$emit('complete');
              }
            })
            if (!this.scan|| cnt-- < 0) {
              clearInterval(hid)
              this.$emit('cancel')
            }
          }, 3000)
        })
      },
      cancel() {
        this.scan = null
        this.$emit('cancel');
      },
      env(len) {
        if (window.__wxjs_environment === 'miniprogram') {
          return 'wxa'.substr(0, len)
        }
        if (navigator.userAgent.match(/WindowsWechat/)) {
          return 'wxw'.substr(0, len)
        }
        if (navigator.userAgent.match(/Mobile.*MicroMessenger/)) {
          return 'wxm'.substr(0, len)
        }
        return undefined
      },
    }
  }
</script>

<style lang="stylus" rel="stylesheet/stylus" scoped>
  @import "index.styl";
  .c-admire {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 99;
    padding: 10px 0;
    px2px(font-size, 40px);
  }
  .mask {
    background: rgba(0,0,0,0.3);
    width: 100%;
    height: 100%;
  }
  .box {
    position: absolute;
    padding-top: 1em;
    left: 50%;
    top: 50%;
    background: #fff;
    border-radius: 8px;
    transform: translate(-50%, -50%);
  }
  .pc .box{
    width: 19.5em;
  }
  .wx .box{
    width: 16em;
    .amount {
      margin: 0.5em;
    }
    .input-amount {
      label, .btn {
        margin: 0 .5em;
      }
      label {
        padding: 0 1em;
      }

    }
  }
  .head {
    px2px(font-size, 48px);
    text-align: center;
    padding: 20px;
    color: #F95E5E;
    font-weight: bold;
  }
  .scan {
    text-align: center;
    px2px(font-size, 40px);
    width: 50%;
    margin: 0 auto 1em;
    padding: 1em 0;
    img {
      width: 100%
    }
    div {
      padding: 1em;
    }
  }
  .body {
    padding: 10px;
  }
  .foot {
    text-align: center;
    padding: 1em;
  }
  .icon-guanbi {
    position: absolute;
    bottom: -3em;
    left: 50%;
    transform: translate(-50%, 0);
    cursor: pointer;
    px2px(font-size, 32px);
    color: #fff;
    border:2px solid #fff;
    border-radius: 50%;
    padding: .3em;
    width: 1.5em;
    height: 1.5em;
    text-align: center;
    line-height: 1.5em;
  }
  .select-amount {
    display: flex;
    flex-wrap: wrap;
  }
  .amount {
    flex-grow: 1;
    px2px(font-size, 32px);
    display: inline-block;
    text-align: center;
    width: 4em;
    line-height: 1.5em;
    border: 2px solid #FAC5BF;
    border-radius: 2em;
    color: #FF8686;
    background: #FFEBEB;
    padding: .3em;
    margin: 0.5em 1em;
  }
  .input-amount {
    margin: 1em 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    px2px(font-size, 32px);
      label {
        display:flex;
        flex-grow: 2;
        align-items: center;
        px2px(font-size, 32px);
        border: 2px solid #FAC5BF;
        border-radius: 2em;
        padding: 0.3em 1em;
        margin: 0 1em;
        color: #FF8686;
      }
      input::-webkit-outer-spin-button,
      input::-webkit-inner-spin-button {
        -webkit-appearance: none;
      }
      input::-webkit-input-placeholder {
        text-align: center;
        color: rgba(255,134,134,0.3);
      }
      input {
        width: 3em;
        margin-right: 1em;
        flex-grow: 1;
        outline: none;
        padding: .2em;
        border: none;
        text-align: center;
        color: #FF8686;
        px2px(font-size, 32px);
        px2px(height, 40px);
        px2px(line-height, 40px);
      }
      .btn {
        flex-grow: 1;
        white-space: nowrap;
        px2px(font-size, 32px);
        color: #fff;
        background: #F95E5E;
        border: 2px solid #F95E5E;
        border-radius: 2em;
        margin: .5em 1em;
        padding: .3em;
        text-align: center;
        line-height: 1.5em;
      }
  }
  .btn {
    cursor: pointer;
  }

</style>
