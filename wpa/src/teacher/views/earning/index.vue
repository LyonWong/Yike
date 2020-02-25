<template>
  <section class="earning">
    <div class="earning-title">我的收益</div>
    <div class="earning-body">
      <div class="money">
        <div>
          <span>累计分成(元)</span>
          <br />
          <div>
            <span v-if="earningRevenue" v-text="earningRevenue.gross">4000.00</span>
          </div>
        </div>
        <div>
          <span>可提现金额(元)</span>
          <br />
          <div>
            <span v-if="earningRevenue" v-text="earningRevenue.remain">500.00</span>
          </div>
        </div>
      </div>
      <div class="withdrawals">
        <div>
          <span>提现金额</span>
          <span>微信提现单笔/单日上限为2万</span>
        </div>
        <input v-model="money" @blur="withdrawalsBlur" type="text" />
        <div class="button">
          <button @click="Withdrawals">提现到微信零钱</button>
        </div>
      </div>
    </div>
    <router-view></router-view>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';

  export default{
    name: 'earning',
    components: {
    },
    computed: {
      ...mapGetters({
        earningList: 'getEarningList',
        earningRevenue: 'getEarningRevenue',
      })
    },
    data() {
      return {
        money: '',
      }
    },
    created() {
      this.$store.commit('CHANGE_IS_NOTICE', false);
      // 获取讲师收益
      this.$store.dispatch('fetchEarningRevenue').then((data) => {
        console.log('fetchEarningRevenue success');
      }, (err) => {
        swal({
          title: '错误提醒',
          text: err.message,
          confirmButtonText: "知道了"
        });
      });
    },
    methods: {
      withdrawalsBlur() {
        this.money = this.money.match(/\d*(\.\d{0,2})?/)[0];
      },
      Withdrawals(){
        // 小于1
        if(this.money < 1){
          return swal({
            title: '错误提醒',
            text: '提现金额不能小于¥1',
            confirmButtonText: "知道了"
          });
        };
        // 大于2万
        if(this.money > 20000){
          return swal({
            title: '错误提醒',
            text: '提现单笔/单日上线为2万',
            confirmButtonText: "知道了"
          });
        };
        // 开始提现
        this.$store.dispatch('fetchEarningAction', {amount:this.money}).then((data) => {
          swal({
            title: '',
            text: '提现成功',
            confirmButtonText: "知道了"
          }, ()=>{
            window.location.reload();
          });
        }, (err) => {
          swal({
            title: '错误提醒',
            text: err.message,
            confirmButtonText: "知道了"
          });
        });
      }
    },
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";
</style>
