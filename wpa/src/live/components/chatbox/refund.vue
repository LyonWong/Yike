<template>
  <div class="refund" v-if="refundShow">
    <div class="refund-box" v-if="!refunding">
      <div class="refund-title">退款后将无法再次购买课程！</div>
      <div class="refund-btn">
        <span @click="close">取消</span>
        <span @click="startRefund">确定</span>
      </div>
    </div>
    <div class="refund-box" v-if="refunding">
      <div class="refund-title">退款中...</div>
    </div>
  </div>
</template>

<script type="text/javascript">
  import { mapState } from 'vuex';
  import swal from 'sweetalert';

  export default
  {
    name: 'v-refund',
    components: {

    },
    data() {
      return {
        refunding: false,
      };
    },
    computed: {
      ...mapState([
        'refundShow',
        'lessonInfo',
        'studentHost',
      ])
    },
    methods: {
      startRefund() {
        let body = {
          lesson_sn: this.lessonInfo.sn
        };
        // 开始退款
        this.refunding = true;
        this.$store.dispatch('fetchRefund', body).then(() => {
          // 关闭退款状态
          this.refunding = false;
          this.$store.commit('UPDATE_REFUND_SHOW', false);
          /*alert('退款成功!现在要将您送回课程列表!');*/
          swal({
            title: '错误提醒',
            text: '退款成功!现在要将您送回课程列表!',
            confirmButtonText: "好的"
          }, ()=>{
            window.location.href = this.studentHost;
          });

        }, (err) => {
          // 关闭退款状态
          this.refunding = false;
          this.$store.commit('UPDATE_REFUND_SHOW', false);
          // 错误类型
          switch(err){
            case '1':
              swal({
                title: '错误提醒',
                text: '退款失败!',
                confirmButtonText: "知道了"
              });
              break;
            case '2':
              swal({
                title: '错误提醒',
                text: '退款失败!',
                confirmButtonText: "知道了"
              });
              break;
            case '3':
              swal({
                title: '错误提醒',
                text: '超过退款时间范围内，不允许退款!',
                confirmButtonText: "知道了"
              });
              break;
            default:
              console.log('default');
              break;
          }
        });
      },
      close() {
        this.$store.commit('UPDATE_REFUND_SHOW', false);
      },
    },
  };
</script>
<style scoped lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";

  .refund
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100% !important;
    background-color: rgba(0,0,0,0.5);
    z-index: 8;
    display: flex;
    flex-direction: column;
    justify-content: center;
    .refund-box
      position: relative;
      margin: 0 auto;
      width: 60%;
      background: #fff;
      border-radius: 10px;
      -webkit-border-radius: 10px;
      .refund-title
        padding: 40px 32px;
        text-align: center;
        border-bottom: 1px solid #D2D3D5;
      .refund-btn
        display: flex;
        display: -webkit-flex;
        >*
          display: flex;
          display: -webkit-flex;
          padding: 17px 0;
          -ms-flex: 1;
          -webkit-flex: 1;
          flex: 1;
          justify-content:center;
        span+span
          border-left: 1px solid #D2D3D5;
          &:last-child
            color: #00C200;

</style>
