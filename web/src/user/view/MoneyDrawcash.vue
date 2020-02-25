<template>
  <div class="v-user-money-drawcash">
    <div class="panel flex-col">
      <div class="desc">可提现余额：{{overview.cash/100}}</div>
      <div class="amount">
        <input type="number" v-model="amount" @keydown.enter="drawcash(amount)" @focus="amount=null"/>
      </div>
      <div class="button flex-row" @click="drawcash(amount)">提现</div>
    </div>
    <div class="guide">
      <ul>
        <li>微信提现限制单笔/单日不超2万，大额提现请联系我们人工处理</li>
        <li>学员听课并确认付款72小时后结算分成，若有退款，将做相应扣除</li>
        <li>可提现余额也可直接用于购买课程，购买时优先使用不可提现的部分</li>
      </ul>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'user-money-drawcash',
    data() {
      return {
        overview: {},
        amount: null
      }
    },
    created() {
      this.api.get('/api/user-money-overview').then((res) => {
        this.overview = res.data
        this.amount = this.overview.cash/100
      }, this.api.onErrorSign)
    },
    methods: {
      drawcash(amount) {
        this.api.post('/api/user-money-drawcash', {
          amount: amount
        }).then(() => {
          this.bus.$emit('dialog', {
            info: {
              body: '提现成功'
            },
            btn: {
              prime: '返回上层',
              vice: '继续提现'
            },
            call: {
              prime: () => {
                this.$router.back()
              }
            }
          })
        }, (err) => {
          this.bus.$emit('dialog', {
            info: {
              body: err.message
            }
          })
        })
      }
    }
  }
</script>

<style scoped>
  .panel {
    background: #7F83E2;
    color: #fff;
  }
  .desc {
    margin: .44rem 0 .36rem 0;
    font-size: .26rem;
  }
  .amount input {
    width: 4.2rem;
    height: .72rem;
    outline: none;
    border: none;
    text-align: center;
    font-size: .48rem;
    color: #fefefe;
    background: rgba(255,255,255,0.2)
  }

  .amount input::-webkit-inner-spin-button {
    -webkit-appearance: none;
  }

  .button {
    margin: .45rem;
    width: 1.5rem;
    height: .6rem;
    border: 1px solid #fff;
    border-radius: .3rem;
    font-size: .26rem;
  }
  .guide {
    padding: .3rem;
    font-size: .26rem;
    color: #333;
  }
  .guide ul {
    list-style: none;
  }
  .guide li {
    position: relative;
    margin: .5em 0;
    text-align: justify;
  }
  .guide li:before {
    content: '■';
    position: absolute;
    left: -.24rem;
    color: #7F83E2;
    font-size: .16rem;
    line-height: .3rem;
  }
</style>
