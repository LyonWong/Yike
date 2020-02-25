<template>
  <section class="group-enroll">
    <div class="enroll-wrap">
      <div class="enroll-head">
        <div class="title">
          课程信息
        </div>
        <div class="info">
          <div class="img">
            <img src="https://storage.sandbox.yike.fm/series/cover/59f1ae41ea86c" />
          </div>
          <div class="text">
            <div>
              游戏下如何创业
            </div>
            <p>
              放的学派
            </p>
          </div>
        </div>
      </div>



    </div>
    <div class="lesson-more" v-if="showMore">
    <div class="more-container">
      <div @click="backToHome">回到首页</div>
      <div @click="backToCenter">个人中心</div>
    </div>
  </div>
    <s-button :text="text" @showMenu="showMenu"></s-button>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';
  import { trimStr } from '@lib/js/mUtils';
  import sButton from '@student/components/button';
  import swal from 'sweetalert';

  export default{
    name: 'group-enroll',
    components: {
      sButton
    },
    props: {
      lessonInfo: {
        type: null,
      },
    },
    computed: {
      ...mapGetters({

      })
    },
    data() {
      return {
        text: '确认支付',
        showMore: false,
      }
    },
    created() {
      let params = this.$route.params;

    },
    methods: {
      backToHome() {
        this.$router.push({ name: 'list' });
      },
      backToCenter() {
        this.$router.push({ name: 'userCenter' });
      },
      showMenu(show) {
        this.showMore = show;
      }
    },
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";
</style>
