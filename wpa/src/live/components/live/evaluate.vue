<template>
  <!-- evaluate -->
  <div class="live-evaluate">
    <!-- evaluate -->
    <div class="eva-body">
      <div class="eva-title">
        课程评价
      </div>
      <div class="star">
        <v-star @updateStarNum="changeStarNum"></v-star>
      </div>
      <div class="textarea">
        <textarea v-model="data.remark" placeholder="亲，写下你对课程的看法吧！"></textarea>
      </div>
      <button @click="submitEva">提交</button>
      <div class="close" @click="oncolse">
        <span><i class="iconfont icon-guanbi"></i></span>
      </div>
    </div>
  </div>
</template>

<script>
  import {mapState} from 'vuex';
  import swal from 'sweetalert';
  import vStar from '@live/components/star/index.vue';

  export default
  {
    name: 'v-evaluate',
    components: {
      vStar
    },
    data() {
      return {
        data: {
          lesson_sn: '',
          remark: '',
          score: 0,
        }
      };
    },
    computed: {
      ...mapState([
        'lessonInfo',
      ])
    },
    created() {
      this.data.lesson_sn = this.lessonInfo.sn;
    },
    methods: {
      oncolse() {
        this.$store.commit('UPDATE_EVALUATE_SHOW', false);
      },
      submitEva() {
        //
        if(!this.data.lesson_sn)return swal({
          title: '错误提醒',
          text: '参数错误',
          confirmButtonText: "知道了"
        });
        //
        if(!this.data.score)return swal({
          title: '错误提醒',
          text: '请选择星级',
          confirmButtonText: "知道了"
        });
        // 评价开始
        this.$store.dispatch('fetchEvaluate', this.data).then(() => {
          this.$store.commit('UPDATE_EVALUATE_SHOW', false);
          //this.$store.commit('UPDATE_IS_EVALUATE', true);
          //
          swal({
            title: '',
            text: '评价成功',
            confirmButtonText: "知道了"
          });
        }, (error) => {
          if(error == 1){
            return swal({
              title: '错误提醒',
              text: '未听课不能评价',
              confirmButtonText: "知道了"
            });
          }
          if(error == 2){
            return swal({
              title: '错误提醒',
              text: '您已经评价过，不能重复评价',
              confirmButtonText: "知道了"
            });
          }
          //
          swal({
            title: '错误提醒',
            text: '网络连接异常',
            confirmButtonText: "知道了"
          });
        });
      },
      changeStarNum(num) {
        this.data.score = num;
      }
    }
  };
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  @import '~@lib/css/index.styl';

  .live-evaluate
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    -webkit-display: flex;
    align-items:center;
    justify-content: center;
    z-index: 10;
    background-color: rgba(0,0,0,.3);
    .eva-body
      position: relative;
      padding: 0 40px 40px;
      width: 500px;
      background: #fff;
      border-radius: 12px;
      -webkit-border-radius: 12px;
      .star
        padding: 15px 0 20px;
      .eva-title
        padding: 38px 0;
        color: #3C4A55;
        text-align: center;
        border-bottom: 1px solid #E6EAF2;
        px2px(font-size, 34px);
      .textarea
        padding: 10px;
        background: #F4F6F9;
        textarea
          display: block;
          width: 100%;
          height: 150px;
          border: 0 none;
          background: #F4F6F9;
          outline: none;
          px2px(font-size, 32px);
      button
        display: block;
        margin-top: 40px;
        padding: 10px 0;
        width: 100%;
        color: #12B7F5;
        text-align: center;
        border-radius: 100px;
        -webkit-border-radius: 100px;
        background: #fff;
        border: 1px solid #12B7F5;
        px2px(font-size, 36px);
      .close
        position: absolute;
        left: 0;
        bottom: -180px;
        width: 100%;
        text-align: center;
        span
          display: inline-block;
          padding: 12px 18px;
          color: #fff;
          border: 3px solid #fff;
          border-radius: 100%;
          -webkit-border-radius: 100%;
          px2px(font-size, 60px);
          .iconfont
            px2px(font-size, 60px);

</style>
