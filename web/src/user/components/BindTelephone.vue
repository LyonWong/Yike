<template>
  <div class="c-bind-telephone">
    <div class="frm-logo">
      <img :src="app.linkToAssets('/img/logo/Original_518518@2x.png')"/>
    </div>
    <div class="frm-bind">
      <div class="box-bind">
        <div class="number flex-row">
          <input id="region" type="tel" v-model="regionCode" placeholder="地区"/>
          <input id="number" type="number" v-model="phoneNumber" placeholder="请输入手机号"/>
        </div>
        <div class="token flex-row">
          <input id="token" v-model="token" placeholder="请输入验证码" @keyup.enter="verify"/>
          <input type="button" id="fetch" class="btn" :disabled="!phoneNumber || !!coolingDown" @click="request" :value="fetchHint"/>
        </div>
        <div class="submit">
          <input type="button" id="submit" class="btn" :disabled="!phoneNumber || !token" @click="verify" value="确定"/>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'bind-telephone',
    data() {
      return {
        regionCode: 86,
        phoneNumber: '',
        token: '',
        coolingDown: null
      }
    },
    created() {
      this.api.get('/api/user-profile').then(() => {}, this.api.onErrorSign)
    },
    computed: {
      fetchHint() {
        if (this.coolingDown === null) {
          return '获取验证码'
        } else {
          return '重新获取' + (this.coolingDown ? `(${this.coolingDown}s)` : '')
        }
      }
    },
    methods: {
      request() {
        this.api.post('/api/bind-telephone-request', {
          telephone: `${this.regionCode}-${this.phoneNumber}`
        }).then((res) => {
          console.log(res)
          this.expire(60)
        }, (res) => {
          if (res.error === '0.1') {
            this.api.onErrorSign()
          }
          if (res.error === '1') { // CD
            this.expire(res.data)
          }
          if (res.error === '2') { // has been used
            this.phoneNumber = ''
          }
          this.bus.$emit('dialog', {
            info: {body: res.message}
          })
        })
      },
      verify() {
        this.api.post('/api/bind-telephone-verify', {
          telephone: `${this.regionCode}-${this.phoneNumber}`,
          token: this.token
        }).then( (res) => {
          if (res.data === false) {
            alert('验证失败')
          } else {
            this.bus.$emit('dialog', {
              info: {body: '绑定成功'},
              call: {
                prime: () => {
                  this.$router.back()
                }
              }
            })
          }
        })
      },
      expire(time) {
        this.coolingDown = time
        let handle = setInterval(() => {
          if (--this.coolingDown <= 0) {
            clearInterval(handle)
          }
        }, 1000)
      }
    }
  }
</script>

<style scoped>
  .c-bind-telephone {
    background: #fff;
  }
  .frm-logo {
    text-align: center;
  }
  .frm-logo img {
    width: 1.5rem;
    height: 1.5rem;
    margin-top: 1.5rem;
  }
  .frm-bind {
    padding: .24rem .6rem;
  }
  .number, .token {
    height: 1.2rem;
    border-bottom: 2px solid #EAEAEA;
  }
  #region {
    text-align: center;
    width: 0.6rem;
    border-right: 1px solid #BFBFBF;
  }
  #number {
    flex-grow: 1;
    border-left: 1px solid #BFBFBF;
  }
  #token {
    width: 3rem;
    flex-grow: 1;
  }
  #fetch {
    background: #fff;
    color: #5B50EA;
  }
  #fetch:disabled {
    color: #BFBFBF;
  }
  #submit {
    margin-top: .6rem;
    width: 100%;
    height: .84rem;
    color: #fff;
    border-radius: .42rem;
    background: #5B50EA;
  }
  #submit:disabled {
    opacity: 0.4;
  }
  input {
    padding: 0 .3rem;
    border-style: none;
    outline: none;
    font-size: .3rem;
  }
  input::-webkit-outer-spin-button,
  input::-webkit-inner-spin-button {
    -webkit-appearance: none !important;
    margin: 0;
  }
  input::-webkit-input-placeholder {
    color: #BFBFBF;
  }

</style>
