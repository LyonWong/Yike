<template>
  <section class="handle-has">
    <p class="center" v-if="!getHandleList.total">暂无已处理退款申请</p>
    <router-view :mode="mode"></router-view>
    <v-paging :page="getHandleList.page" :total="getHandleList.totalPage" :url="willUrl" v-if="getHandleList && getHandleList.total"></v-paging>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';
  import vPaging from '@teacher/components/paging.vue';

  export default{
    name: 'handle-will',
    components: {
      vPaging,
    },
    computed: {
      ...mapGetters({
        getHandleList: 'getHandleList',
      })
    },
    data() {
      return {
        mode: 1,
        willUrl: '/handle/has',
      }
    },
    created() {

    },
    methods: {
    },
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  .handle-has {
    .center {
      text-align: center;
    }
  }
</style>
