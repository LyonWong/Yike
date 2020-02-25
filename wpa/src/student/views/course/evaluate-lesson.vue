<template>
  <section class="evaluate-lesson">
    <!-- evaluate -->
    <div class="eva-body">
      <div class="eva-title">
        课程评价
      </div>
      <div class="e-star">
        <v-star :mode="1" @updateStarNum="changeStarNum"></v-star>
      </div>
      <div class="textarea">
        <textarea v-model="data.remark" placeholder="亲，写下你对课程的看法吧！"></textarea>
      </div>
      <!--底部菜单、按钮-->
      <div class="lesson-more" v-if="showMore">
        <div class="more-container">
          <div @click="backToHome">回到首页</div>
          <div @click="backToCenter">个人中心</div>
        </div>
      </div>
      <s-button :callBack="submitEva" @showMenu="showMenu"></s-button>
    </div>
  </section>
</template>

<script>
  import vStar from '@student/components/star.vue';
  import sButton from '@student/components/button';

  export default{
    name: 'evaluate-lesson',
    components: {
      vStar,
      sButton
    },
    data() {
      return {
        showMore: false,
        data: {
          lesson_sn: null,
          remark: '',
          score: 0,
        }
      }
    },
    created() {
      this.data.lesson_sn = this.$route.params.lesson_sn;
    },
    methods: {
      changeStarNum(num) {
        this.data.score = num;
      },
      backToHome() {
        this.$router.push({ name: 'list' });
      },
      backToCenter() {
        this.$router.push({ name: 'userCenter' });
      },
      showMenu(show) {
        this.showMore = show;
      },
      submitEva() {
        if(!this.data.lesson_sn)return swal({
          title: '错误提醒',
          text: '参数错误!',
          confirmButtonText: "知道了"
        });
        if(!this.data.score){
          return swal({
            title: '错误提醒',
            text: '请选择评价星级!',
            confirmButtonText: "知道了"
          });
        }
        // 评价开始
        this.$store.dispatch('fetchEvaluate', this.data).then(() => {
          swal({
            title: '',
            text: '评价成功!',
            confirmButtonText: "知道了"
          }, ()=>{
            this.$router.replace({ name: 'messageTask', query: {lesson_sn:this.data.lesson_sn} });
          });
        }, (error) => {
          if(error == 1){
            return swal({
              title: '',
              text: '未听课不能评价!',
              confirmButtonText: "知道了"
            }, ()=>{
              this.$router.replace({ name: 'enrolled' });
            });
          }
          if(error == 2){
            return swal({
              title: '',
              text: '您已经评价过，不能重复评价!',
              confirmButtonText: "知道了"
            }, ()=>{
              this.$router.replace({ name: 'enrolled' });
            });
          }
          //
          swal({
            title: '错误提醒',
            text: '网络连接失败!',
            confirmButtonText: "知道了"
          });
        });
      },
    }
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";
  .evaluate-lesson
    div.lesson-more
      position: fixed;
      top: 0;
      bottom: 0;
      margin: 0;
      width: 100%;
      background-color: rgba(0,0,0,.3);
      .more-container
        position: absolute;
        padding: 0;
        width: 100%;
        px2px(bottom, 98px);
      div>*
        padding: 30px 0;
        color: #333;
        background: #fff;
        text-align: center;
        border-top: 1px solid #d9d9d9;

  .evaluate-lesson {
    background: #FFF;
    .eva-body {
      padding: 0 40px 40px;
    }
    .eva-title {
      padding: 39px 0;
      text-align: center;
      border-bottom: 1px solid #E6EAF2;
      px2px(font-size, 36px);
    }
    .e-star {
      padding: 40px 0 34px;
      .v-star {
        .iconfont {
          px2px(font-size, 64px);
        }
      }
    }
    .textarea {
      padding: 10px;
      background: #F4F6F9;
      textarea {
        width: 100%;
        height: 260px;
        border: 0 none;
        background: #F4F6F9;
        px2px(font-size, 32px);
      }
    }
  }

  .body-pc
    .evaluate-lesson
      div.lesson-more
        left: 0;
        bottom: 50px;
      .select-menu
        top: 65px;
        right: 30px;
        width: 100px;
        -moz-box-shadow: 0px 3px 15px #C7C7C7;
        -webkit-box-shadow: 0px 3px 15px #C7C7C7;
        box-shadow: 0px 3px 15px #C7C7C7;

  @media only screen and (device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) {
    .evaluate-lesson {
      div.lesson-more {
        .more-container {
          padding-bottom: 60px;
        }
      }
    }
  }
</style>
