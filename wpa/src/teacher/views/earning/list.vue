<template>
  <section class="earning-list">
    <div class="gap"></div>
    <!--收入列表-->
    <div class="earning-title">
      提现记录
    </div>
    <div class="earning-body">
      <ul class="e-list" v-if="earningList && earningList.pages && earningList.pages.length">
        <li class="head">
          <span>时间</span>
          <span>项目</span>
          <span>金额</span>
        </li>
        <li v-for="list in earningList.pages">
          <span v-text="list.tms">2017-02-02 15:22:40</span>
          <span>{{list.desc}}</span>
          <span>{{list.amount}}</span>
        </li>
      </ul>
      <div class="none-record" v-if="earningList && earningList.pages && (earningList.pages.length < 1)">--暂无更多记录--</div>
      <v-paging :page="earningList.page" :total="earningList.totalPage" :url="willUrl" v-if="earningList && earningList.total"></v-paging>
    </div>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';
  import vPaging from '@teacher/components/paging.vue';

  export default{
    name: 'earning-list',
    components: {
      vPaging,
    },
    computed: {
      ...mapGetters({
        earningList: 'getEarningList',
      })
    },
    data() {
      return {
        willUrl: '/earning/list',
      }
    },
    watch: {
      '$route': 'reloadData' //切换路由，调用reloadData方法
    },
    created() {
      // 获取收益记录
      this.reloadData();
    },
    methods: {
      reloadData() {
        let query = {
          page: this.$route.params.page,
          //limit: 2,
        };
        //
        this.$store.dispatch('fetchEarningList', query).then((data) => {
          console.log('fetchEarningList success');
        }, (err) => {
          swal({
            title: '错误提醒',
            text: err.message,
            confirmButtonText: "知道了"
          });
        });
      },
    },
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";
</style>
