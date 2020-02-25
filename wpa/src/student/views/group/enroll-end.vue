<template>
  <section class="group-enroll">
    已报名
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
    name: 'group-enrollEnd',
    components: {
      sButton
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
