<template>
  <section class="group-create">
    <div class="create-order" @click="showCreateDialog">
      <i class="iconfont icon-tianjia"></i>
      创建团队订单
    </div>
    <div class="lesson-more" v-if="showMore">
      <div class="more-container">
        <div @click="backToHome">回到首页</div>
        <div @click="backToCenter">个人中心</div>
      </div>
    </div>
    <s-button :text="text" :callBack="showCreateDialog" @showMenu="showMenu"></s-button>
    <!--创建团队订单-->
    <group-order v-if="showCreate"></group-order>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';
  import { trimStr } from '@lib/js/mUtils';
  import sButton from '@student/components/button';
  import groupOrder from './order';
  import swal from 'sweetalert';

  export default{
    name: 'group-create',
    components: {
      sButton,
      groupOrder,
    },
    computed: {
      ...mapGetters({

      })
    },
    data() {
      return {
        text: '创建团队订单',
        showMore: false,
        showCreate: false,
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
      },
      showCreateDialog() {
        this.showCreate = true;
      },
    },
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";
</style>
