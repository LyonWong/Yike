<template>
  <div class="c-board-comment">
    <div class="list" v-infinite-scroll="scroll" infinite-scroll-disabled="listEnd" infinite-scroll-distance="500">
      <board-tank v-for="item in list" :key="item.cursor" :sn="sn" :prime="item"></board-tank>
    </div>
    <popup :is-open="sn" :option="{head: false, foot: false}">
      <board-post id="post" :sn="sn"></board-post>
    </popup>
  </div>
</template>

<script>
  import BoardCell from "./board/Cell";
  import BoardTank from "./board/Tank";
  import infiniteScroll from 'vue-infinite-scroll'
  import BoardPost from "./board/Post";
  import Popup from "../../components/Popup";
  export default {
    name: 'board-comment',
    components: {Popup, BoardPost, BoardTank, BoardCell},
    directives: {infiniteScroll},
    props: ['sn'],
    data() {
      return {
        type: 'argue',
        list: [],
        scrollEnd: false
      }
    },
    created() {
    },
    methods: {
      scroll: function() {
        let limit = 10
        let cursor = '--'
        if (this.list.length) {
          cursor = this.list[this.list.length-1].cursor
        }
        this.api.get('/api/board-slice-prime', {
          sn: this.sn,
          type: 'argue',
          cursor: cursor,
          limit: limit
        }).then( (res) => {
          this.list.push(...res.data)
          if (res.data.length < limit) {
            this.listEnd = true
          }
        })
      }
    }
  }
</script>

<style scoped>

  #postt {
    position: fixed;
    bottom: 0;
    z-index: 9;
  }
</style>
