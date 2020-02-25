<template>
  <div class="c-board-list">
    <div class="list" v-infinite-scroll="scroll" infinite-scroll-disabled="scrollEnd" infinite-scroll-distance="500">
      <board-tank v-for="item in list" :key="item.cursor" :sn="sn" :prime="item"></board-tank>
    </div>
    <div class="init flex-row" v-if="scrollEnd && list.length===0">
      还没人来过(´｡• ᵕ •｡`)
    </div>
    <div class="last flex-row" v-if="scrollEnd && list.length>0">
      这就是底线了( ´・◡・｀)
    </div>
    <popon :is-open="post" :option="{head: false, foot: false}" v-on:close="post = false">
      <board-post :sn="sn" :type="type" :post="post" v-on:release="onRelease"></board-post>
    </popon>
  </div>
</template>

<script>
  import BoardCell from "./Cell";
  import BoardTank from "./Tank";
  import BoardPost from "./Post";
  import Popon from "../../../components/Popon";
  import infiniteScroll from 'vue-infinite-scroll'
  export default {
    name: 'board-list',
    components: {Popon, BoardPost, BoardTank, BoardCell},
    directives: {infiniteScroll},
    props: ['sn', 'type'],
    data() {
      return {
        list: [],
        scrollEnd: false,
        post: null,
        scrolling: false
      }
    },
    created() {
    },
    methods: {
      scroll: function() {
        if (this.scrolling) {
          return // 防止重复加载
        }
        this.scrolling = true
        let limit = 10
        let cursor = '--'
        if (this.list.length) {
          cursor = this.list[this.list.length-1].cursor
        }
        this.api.get('/api/board-slice-prime', {
          sn: this.sn,
          type: this.type,
          cursor: cursor,
          limit: limit
        }).then( (res) => {
          this.list.push(...res.data)
          if (res.data.length < limit) {
            this.scrollEnd = true
          }
          this.scrolling = false
        })
      },
      onPost() {
        this.api.get('/api/study-check', {
          sn: this.sn
        }).then(() => {
          this.post = true
        }, (e) => {
          if (e.error === '1') {
            this.bus.$emit('dialog', {
              info: {body: '解锁后才能评论'}
            })
          }
        })
      },
      onRelease(cursor) {
        this.post = null
        this.api.get('/api/board-focus', {
          sn: this.sn,
          target: cursor
        }).then((res) => {
          this.list.unshift(res.data)
        })
      }
    }
  }
</script>

<style scoped>
  .c-board-list {
    background: #fff;
  }
  .c-board-tank {
    padding: .1rem 0;
  }
  .init{
    font-size: .3rem;
    color: #aaa;
    padding: .5rem 0;
  }
  .last {
    font-size: .3rem;
    color: #aaa;
    padding: .2rem 0;
  }
</style>
