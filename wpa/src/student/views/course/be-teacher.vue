<template>
  <section class="be-teacher">
    <div class="money-con">
      <div class="money-forward">
        <div>
          <span>易灵微课讲师注册</span>
        </div>
        <div class="input">
          <input type="text" placeholder="请输入邮箱地址" v-model="price" @blur="priceBlur" />
        </div>
        <div>
          <button @click="startDrawCash" v-if="!doing">发送验证邮件</button>
          <button v-if="doing">发送中...</button>
        </div>
      </div>
      <div class="letter">
        <ol>
          <li><span class="iconfont icon-dot"></span>微信提现限制单笔/单日不超2万，大额提现请联系我们人工处理</li>
          <li><span class="iconfont icon-dot"></span>注册成为讲师，参与课程推广可获得分成佣金</li>
          <li><span class="iconfont icon-dot"></span>学员听课并确认付款72小时后结算分成佣金，若有退款，将做相应扣除</li>
          <li><span class="iconfont icon-dot"></span>可提现余额也可直接用于购买课程，购买时将优先使用不可提现的部分</li>
        </ol>
      </div>
    </div>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';
  import { trimStr } from '@lib/js/mUtils';

  export default{
    name: 'be-teacher',
    components: {
    },
    computed: {
      ...mapGetters({
        moneyBalance: 'getMoneyBalance',
      }),
    },
    data() {
      return {
        canShow: false,
        doing: false,
        gross: 0,
        remain: 0,
        price: '',
      }
    },
    created() {
      // 账户余额
      this.$store.dispatch('fetchMoneyOverview').then((data)=>{
        //
        this.canShow = true;
        this.gross = data.gross;
        this.remain = data.remain;
        console.log('fetchMoneyOverview success');
      },(err)=>{
        swal({
          title: '错误提醒',
          text: (err.message ? err.message : '网络链接失败'),
          confirmButtonText: "知道了"
        });
      });
    },
    methods: {
      startDrawCash() {
        let curPrice = trimStr(this.price);
        if(!curPrice)return swal({
          title: '错误提醒',
          text: '请输入提现金额',
          confirmButtonText: '知道了'
        });
        // 开始提现
        this.doing = true;
        this.$store.dispatch('fetchMoneyDrawCash', {amount:curPrice}).then((data)=>{
          //
          this.gross = data.gross;
          this.remain = data.remain;
          this.price = '';
          this.doing = false;
          // 提示
          swal({
            title: '',
            text: '提现成功',
            confirmButtonText: '知道了'
          });
          console.log('fetchMoneyDrawCash success');
        },(err)=>{
          this.doing = false;
          //
          swal({
            title: '错误提醒',
            text: (err.message ? err.message : '网络链接失败'),
            confirmButtonText: '知道了'
          });
        });
      },
      priceBlur() {
        this.price = this.price.match(/\d*/)[0];
      },
    },
  };
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";

  .be-teacher
    background: #f7f9fc;
    px2px(font-size, 36px);
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
      .price
        position: relative;
        px2px(font-size, 64px);
      .input
        input
          width: 300px;
          height: 60px;
          border: 1px solid #fff;
          text-align: center;
          border-radius: 10px;
          -webkit-border-radius: 10px;
          px2px(font-size, 32px);
  ol
    padding: 0 30px;
    list-style-type: none;
    li
      position: relative;
      padding: 0 0 15px 20px;
      color: #9ca7c1;
      px2px(line-height, 46px);
      px2px(font-size, 32px);
      .iconfont
        position: absolute;
        px2px(left, -10px);
        px2px(font-size, 34px);
</style>
