<template>
  <section class="teacher-follow">
    <scroller :on-infinite="infinite" noDataText="没有更多关注记录" ref="my_scroller">
      <ul>
        <li v-for="list in followList" @click="jumpBrief(list.sn)">
          <div class="follow-img">
            <img :src="list.avatar" />
          </div>
          <div>
            <div class="name">{{list.name}}</div>
            <div class="total">
              共{{ list.stats | specKey('teacher.lesson.count') }}个课程
            </div>
          </div>
          <span class="arrow"></span>
        </li>
    </ul>
    </scroller>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';

  export default{
    name: 't-follow',
    components: {
    },
    computed: {
      ...mapGetters({

      })
    },
    data() {
      return {
        cursor: '',
        followList: [],
      }
    },
    created() {
    },
    methods: {
      infinite(done) {
        // 开始
        let length = this.followList.length;
        // 是否有游标和长度
        if (!length){
          return this.fetchFollow(done);
        }
        try{
          this.fetchFollow(done);
        }catch(e){};
      },
      fetchFollow(done) {
        let query = {cursor:this.cursor,limit:10};
        // 获得评价列表
        this.$store.dispatch('fetchFollowList', query).then((data) => {
          //
          this.followList = [...this.followList, ...data];
          this.cursor = this.followList.length?this.followList[this.followList.length-1].cursor:this.cursor;
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
      jumpBrief(series_sn) {
        this.$router.push({ name: 'seriesTeacherSingle', params: {series_sn: series_sn} });
      },
    },
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";

  .teacher-follow
    px2px(font-size, 32px);
    ._v-container
      .loading-layer
        height: 190px !important;
      >._v-content>.loading-layer .spinner-holder .spinner
        width: 64px;
        height: 64px;
    .no-data-text
      px2px(font-size, 32px);
    ul
      margin: 0;
      padding: 0;
      padding-bottom: 30px;
      list-style-type: none;
      li
        position: relative;
        padding: 30px 30px 30px 150px;
        background: #fff;
        border-bottom: 1px solid #DDD;
        >:nth-of-type(1)
          position: absolute;
          &.follow-img
            width: 80px;
            height: 80px;
            px2px(top, 30px);
            px2px(left, 38px);
            img
              width: 100%;
              height: 100%;
              border-radius: 50%;
              -webkit-border-radius: 50%;
        >:nth-of-type(2)
          color: #aaa;
          line-height: 1;
          .name
            color: #000;
            px2px(font-size, 32px);
          .total
            padding-top: 20px;
            px2px(font-size, 26px);
        >.price
          position: absolute;
          px2px(top, 18px);
          px2px(right, 28px);
          px2px(font-size, 38px);
          &.positive
            color: #FCB446;
        >.money-title
          width: 560px;
    span
      color: #3c4a55;
      vertical-align: middle;
      &.arrow
        position: absolute;
        width: 40px;
        height: 40px;
        px2px(top, 54px);
        px2px(right, 20px);
        &:after
          content: '';
          display: block;
          width: 14px;
          height: 14px;
          border-right: 4px solid #9ca7c1;
          border-top: 4px solid #9ca7c1;
          -webkit-transform: rotate(45deg); /*箭头方向可以自由切换角度*/
          transform: rotate(45deg);

</style>
