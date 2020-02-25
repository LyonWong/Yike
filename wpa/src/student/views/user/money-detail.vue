<template>
  <section class="money-detail">
    <scroller :on-infinite="infinite" noDataText="没有更多记录" ref="my_scroller">
      <ul>
        <li v-for="list in moneyDebit">
          <div class="money-title">
            {{list.desc}}
          </div>
          <div>
            {{list.tms}}
          </div>
          <span class="price" :class="{'positive':(list.amount >= 0)}">
            <span v-if="list.amount >= 0">+</span><span v-if="list.amount < 0">-</span>{{Math.abs(list.amount)}}
          </span>
        </li>
    </ul>
    </scroller>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';

  export default{
    name: 'money-detail',
    components: {
    },
    computed: {
      ...mapGetters({
        /*moneyDebit: 'getMoneyDebit',*/
      })
    },
    data() {
      return {
        cursor: '',
        moneyDebit: [],
      }
    },
    created() {
      // 余额明细
      /*this.fetchDebit();*/
    },
    methods: {
      infinite(done) {
        // 开始
        let length = this.moneyDebit.length;
        // 是否有游标和长度
        if (!length){
          return this.fetchDebit(done);
        }
        try{
          this.fetchDebit(done);
        }catch(e){};
      },
      fetchDebit(done) {
        let query = this.cursor?{cursor:this.cursor,limit:10}:{limit:10};
        // 获得评价列表
        this.$store.dispatch('fetchMoneyDebit', query).then((data) => {
          //
          this.moneyDebit = [...this.moneyDebit, ...data.list];
          this.cursor = data.cursor;
          //
          if (done) {
            if (data.list.length < query.limit) {
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
    },
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";

  .money-detail
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
        padding: 0 28px;
        background: #fff;
        >:nth-of-type(1)
          padding: 30px 0 0;
        >:nth-of-type(2)
          color: #999;
          padding: 17px 0 28px;
          border-bottom: 1px solid #E6EAF2;
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

  @media only screen and (device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) {
    .money-detail {
      ._v-container {
        .loading-layer {
          height: 250px !important;
        }
      }
    }
  }

</style>
