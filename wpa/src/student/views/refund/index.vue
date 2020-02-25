<template>
  <section class="refund" v-if="count == 2">
    <div class="refund-warn">
      <p v-if="mode =='freely'">无条件退款 </p>
      <p v-if="mode =='apply'">向讲师提交退款申请</p>
      <p v-if="mode =='appeal'">向平台提交退款申诉</p>
      <div class="refund-list">
        <ul>
          <li v-if="mode=='freely'">&middot;&nbsp;退款后不能评价本课程</li>
          <li v-if="mode =='apply'">&middot;&nbsp;提交退款申请后不能评价本课程</li>
          <li>&middot;&nbsp;退款成功后不能再次购买本课程</li>
          <li>&middot;&nbsp;退款成功后不能继续观看本课程</li>
        </ul>
      </div>
    </div>
    <div class="refund-info">
      <dl>
        <dt>课程</dt>
        <dd v-text="title"></dd>
      </dl>
      <dl>
        <dt>金额</dt>
        <dd class="price">{{price?`&#65509;${price}`:'免费'}}</dd>
      </dl>
      <dl>
        <dt>讲师</dt>
        <dd>{{teacher}}</dd>
      </dl>
    </div>
    <div class="refund-reason" v-if="mode != 'freely'">
      <p>退款理由</p>
      <div class="textarea">
        <textarea placeholder="请填写足够充分的退款理由" v-model="data.reason" @blur="blurEvent"></textarea>
        <p>{{words}}/1000</p>
      </div>
    </div>
    <div class="refund-info">
      <div>退款路线</div>
      <dl>
        <dt>账户余额</dt>
        <dd class="right">&#65509;{{balance}}</dd>
      </dl>
      <dl>
        <dt>微信支付</dt>
        <dd class="right">&#65509;{{weixin}}</dd>
      </dl>
    </div>
    <s-button v-if="!refunding" :callBack="submitRefund" :text="text"></s-button>
    <s-button v-if="refunding" :text="refundingText"></s-button>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';
  import { trimStr } from '@lib/js/mUtils';
  import sButton from '@student/components/button';
  import swal from 'sweetalert';

  export default{
    name: 'refund',
    components: {
      sButton
    },
    computed: {
      ...mapGetters({

      })
    },
    data() {
      return {
        title: '',
        teacher: '',
        price: 0,
        words: 0,
        text: '',
        mode: '',
        count: 0,
        weixin: '',
        balance: '',
        refunding: false,
        refundingText: '退款中...',
        data: {
          lesson_sn: null,
          reason: '',
        }
      }
    },
    created() {
      let query = this.$route.query;
      try{
        if(typeof(query) === 'string'){
          query = JSON.parse(query);
        }
        this.data.lesson_sn = query.lesson_sn;
        // 获取课程订单列表
        this.$store.dispatch('fetchLessonOrder', {lesson_sn:query.lesson_sn}).then((result) => {
          // 赋值
          this.weixin = result.paid_amount;
          this.balance = result.paid_balance;
          this.price = result.order_amount;
          this.count += 1;
        }, () => {
          this.count += 1;
          console.log('fail');
        });
        // 判断来源
        if(query.origin == 'live'){
          //
          this.$store.dispatch('fetchCourseDetail', {lesson_sn:query.lesson_sn}).then((result) => {
            // 赋值
            this.count += 1;
            this.mode = result.refund_mode;
            this.title = result.title;
            this.teacher = result.teacher.name;
            /*this.price = result.price;*/
            this.text = (this.mode == 'freely')?'申请退款':(this.mode == 'apply')? '提交申请' :'提交申诉';
          }, () => {
            this.count += 1;
            console.log('fail');
          });
          // 返回
          return;
        }
        //
        this.count += 1;
        this.mode = query.mode;
        this.title = query.title;
        this.teacher = query.teacher;
        /*this.price = query.price;*/
        this.text = (this.mode == 'freely')?'申请退款':(this.mode == 'apply')? '提交申请' :'提交申诉';

      }catch(e){}
    },
    methods: {
      submitRefund() {
        if(this.mode != 'freely' && !this.data.reason){
          return swal({
            title: '错误提醒',
            text: '请填写退款理由!',
            confirmButtonText: '知道了'
          });
        }
        // 确认退款
        if(!confirm('您确定要退款吗？'))return;
        //
        let body = {
          data: this.data,
          url: `lesson-refund-${this.mode}`,
        };
        this.refunding = true;
        // 开启退款状态
        this.$store.dispatch('fetchRefundCourse', body).then(() => {
          this.refunding = false;
          //
          this.mode == 'freely' ? swal({
            title: '',
            text: '退款成功!',
            confirmButtonText: '知道了'
          },()=>{
            this.$router.push({ name: 'enrolled' });
          }) :
          swal({
            title: '',
            text: '退款申请提交成功!',
            confirmButtonText: '知道了'
          },()=>{
            this.$router.push({ name: 'enrolled' });
          });

        }, (err) => {
          // 关闭退款状态
          this.refunding = false;
          //
          swal({
            title: '',
            text: (err.message ? err.message : '网络链接出错!'),
            confirmButtonText: '知道了'
          });
        });
      },
      blurEvent() {
        let length = trimStr(this.data.reason).length;
        if(length > 1000){
          length = 1000;
        }
        this.words = length;
      }
    },
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";
</style>
