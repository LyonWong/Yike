<template>
  <section class="content-task" ref="content-task">
    <div class="tab clearfix">
      <div class="pull-left ft0">
        <router-link :to="{name:'brief', query:{lesson_sn:lesson_sn}}" replace>
          <span class="back-brief">
            <i class="iconfont icon-kechengxiangqing"></i>
          </span>
        </router-link>
        <a href="javascript:;" @click="switchMessage(key)" v-for="(map, key) in type_map">
          {{map}}
        </a>
        <router-link :to="{name:'messageTask'}" replace>评价</router-link>
      </div>
    </div>
    <evaluate @isEvalute="isEvalute"></evaluate>
    <!--底部菜单、按钮-->
    <div class="lesson-more" v-if="showMore">
      <div class="more-container">
        <div @click="backToHome">回到首页</div>
        <div @click="backToCenter">个人中心</div>
      </div>
    </div>
    <s-button :callBack="goEvaluate" @showMenu="showMenu" :text="text" v-if="!rated"></s-button>
    <s-button :disabled="rated" @showMenu="showMenu" :text="text" v-if="rated"></s-button>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';
  import sButton from '@student/components/button';
  import Evaluate from '@student/views/course/evaluate.vue';

  export default{
    name: 'message-task',
    components: {
      sButton,
      Evaluate,
    },
    data() {
      return{
        sort: '',
        rated: false,
        text: '去评价',
        showMore: false,
        type_map: null,
        lesson_sn: '',
      }
    },
    created() {
      let params = this.$route.params;
      this.lesson_sn = params.lesson_sn;
      this.$store.dispatch('fetchBoardInit', {lesson_sn: params.lesson_sn}).then((data)=>{
        this.type_map = data.type_map;
      }, (error)=>{
        //
        swal({
          title: '错误提醒',
          text: error.message,
          confirmButtonText: "知道了"
        });
      });
    },
    mounted() {
      let task = this.$refs['content-task'];
      setTimeout(()=>{
        task.style.height = `${window.innerHeight}px`;
      });
    },
    methods: {
      switchMessage(type){
        this.$router.push({ name: 'messageDiscuss',
          params: {
            lesson_sn:this.$route.params.lesson_sn
          },
          query: {
            type_now: type
          }
        });
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
      goEvaluate() {
        this.$router.push({ name: 'evaluate-lesson', params: {lesson_sn:this.lesson_sn} });
      },
      // 是否已评价
      isEvalute(rated){
        if(rated){
          this.text = '已评价';
          this.rated = rated;
        }
      },
    }
  };
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";
</style>
