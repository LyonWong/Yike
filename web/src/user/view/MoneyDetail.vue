<template>
  <div class="v-user-money-detail">
    <div class="list" v-infinite-scroll="scroll" infinite-scroll-disabled="isEnd" infinite-scroll-distance="500">
      <div class="item" v-for="(item, idx) in list" :key="idx">
        <div class="info flex-row">
          <div class="desc">{{item.desc}}</div>
          <div class="amount" :class="{increase: item.amount>0, decrease: item.amount<0}">{{item.amount}}</div>
        </div>
        <div class="time">{{item.tms}}</div>
      </div>
    </div>
    <div class="end">没有更多记录</div>
  </div>
</template>

<script>
  import infiniteScroll from 'vue-infinite-scroll'
  export default {
    name: 'user-money-detail',
    directives: {infiniteScroll},
    data() {
      return {
        cursor: null,
        list: [],
        isEnd: false
      }
    },
    created() {},
    methods: {
      scroll() {
        let limit = 10
        this.api.get('/api/user-money-detail', {
          cursor: this.cursor,
          limit: limit
        }).then( (res) => {
          this.list.push(...res.data.list)
          this.cursor = res.data.cursor
          this.isEnd = res.data.list.length < limit
        }, this.api.onErrorSign)
      }
    }
  }
</script>

<style scoped>
  .v-user-money-detail {
    background: #F2F2F2;
  }
  .list {
    padding: 0 .3rem;
    background: #fff;
  }
  .item {
    padding: .3rem 0;
    border-bottom: 2px solid #f7f7f7;
  }
  .item:last-child {
    border: none;
  }
  .info {
    padding: .1rem 0;
    justify-content: space-between;
  }
  .desc {
    font-size: .32rem;
    color: #222;
  }
  .amount {
    font-size: .36rem;
  }
  .time {
    font-size: .24rem;
    color: #999;
  }
  .increase {
    color: #E7521C;
  }
  .increase:before {
    content: '+';
  }
  .decrease {
    color: #696EEA;
  }
  .end {
    padding: .3rem;
    font-size: .3rem;
    text-align: center;
    color: #999;
  }

</style>
