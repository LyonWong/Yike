<template>
  <section class="handle-will">
    <p class="center" v-if="!getHandleList.total">暂无未处理退款申请</p>
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
        mode: 0,
        willUrl: '/handle/will',
      }
    },
    created() {

    },
    methods: {
    },
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  .handle-will {
    .center {
      text-align: center;
    }
  }
</style>
