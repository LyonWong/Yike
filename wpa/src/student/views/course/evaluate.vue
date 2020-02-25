<template>
  <section class="content-evaluate">
    <div class="eva-header" v-if="isShow && !evaList.length">
      <div class="score" v-text="score"></div>
      <p>{{rated}}人评分</p>
    </div>
    <p class="text-center" v-if="!fetching && !rated">暂无评价内容</p>
    <div class="eva-body" v-if="evaList.length" :class="{'has-img':hasImg}">
      <scroller :on-infinite="infinite" noDataText="没有更多评价" ref="my_scroller">
        <div class="eva-header" v-if="isShow">
          <div class="score" v-text="score"></div>
          <p>{{rated}}人评分</p>
        </div>
        <evaluate-list :lists="evaList"></evaluate-list>
      </scroller>
    </div>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';
  import evaluateList from '@student/components/evaluateList.vue';

  export default{
    name: 'evaluate',
    components: {
      evaluateList,
    },
    computed: {
      ...mapGetters({
        'courseDetail': 'getCourseDetailInfo'
      })
    },
    data() {
      return {
        fetching: true,
        score: 0,
        rated: 0,
        isShow: false,
        hasImg: false,
        evaList: [],
      };
    },
    created() {
      //获取路由参数
      let query = this.$route.query;
      // 获取数据状态
      this.fetching = true;
      // 获得评价总分
      this.$store.dispatch('fetchEvaluteTotal', {lesson_sn:query.lesson_sn}).then((data) => {
        //
        this.score = data.avg_score || 0;
        this.rated = data.rated_count || 0;
        //
        this.fetching = false;
        this.$emit('isEvalute', data.rated);
        this.isShow = true;
      }, (error) => {
        //
        swal({
          title: '错误提醒',
          text: error,
          confirmButtonText: "知道了"
        });
        console.log('fail');
      });
      // 查看是否有题图
      /*try{
        this.hasImg = this.courseDetail[query.lesson_sn].cover ? true : false;
      }catch(e){};*/
      // 获得评价列表
      this.infinite();
    },
    methods: {
      fetchEva(cursor, done) {
        let query = {
          lesson_sn: this.$route.query.lesson_sn,
          cursor: cursor,
          limit: 5,
        };
        // 获得评价列表
        this.$store.dispatch('fetchEvaluteList', query).then((data) => {
          //
          this.evaList = [...this.evaList, ...data];
          //
          if (done) {
            if (data.length < query.limit) {
              done(true);
            } else {
              setTimeout(()=>{
                done();
              }, 1000);
            };
          };
          console.log('获取列表成功!');
        }, (error) => {
          done(true);
          console.log('fail');
          swal({
            title: '错误提醒',
            text: error,
            confirmButtonText: "知道了"
          });
        });
      },
      infinite(done) {
        // 开始
        let length = this.evaList.length;
        // 是否有游标和长度
        if (!length){
          return this.fetchEva(null, done);
        }
        try{
          this.fetchEva(this.evaList[length - 1].cursor, done);
        }catch(e){};
      },
    },
  };
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";

  .eva-header
    text-align: center;
    background: #fff;
    p
      margin: 0;
      padding: 15px 0 30px;
      color: #9CA7C1;
      px2px(font-size, 30px);
  .eva-body
    position: relative;
    padding-bottom: 150px;
    height: 1100px;
    &.has-img
      height: 600px;
    .no-data-text
      &.active
        top: -24px !important;
        px2px(font-size, 32px);
  ._v-container>._v-content>.loading-layer
    height: 140px !important;
    .spinner-holder .spinner
      width: 64px !important;
      height: 64px !important;
  .score
    padding: 32px 0 0;
    color: #FB6666;
    px2px(font-size, 56px);
</style>
