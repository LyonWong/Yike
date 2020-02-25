<template>
  <div class="c-board-detail">
    <div class="prime">
      <board-cell v-if="focus" :datum="focus" v-on:post="onPost"></board-cell>
    </div>
    <div class="slave">
      <div class="head" v-if="focus">
        <span v-if="mode==='assoc'">{{focus.stats.assoc}}条留言</span>
        <span v-if="mode==='refer'">{{focus.stats.reply}}条回复</span>
        <span v-if="mode==='chain'">{{focus.stats.assoc}}条对话</span>
      </div>
      <div class="body" v-show="focus" v-infinite-scroll="scroll" infinite-scroll-disabled="scrollEnd" infinite-scroll-distance="500">
        <board-cell v-if="mode==='assoc' || mode==='chain'" v-for="(item,idx) in list" :key="idx" :sn="sn" :datum="item" v-on:post="onPost"></board-cell>
        <board-tank v-if="mode==='refer'" v-for="item in list" :key="item.cursor" :sn="sn" :prime="item"></board-tank>
      </div>
    </div>
    <div class="last flex-row" v-if="scrollEnd && list.length">
      这就是底线了( ´・◡・｀)
    </div>
    <popon v-if="focus" :is-open="post" :option="{head: false, foot: false}" v-on:close="post = false">
      <board-post :sn="sn" :type="focus.type" :post="post" v-on:release="onRelease"></board-post>
    </popon>
  </div>
</template>

<script>
  import BoardCell from "../components/board/Cell";
  import BoardTank from "../components/board/Tank";
  import BoardPost from "../components/board/Post";
  import Popon from "../../components/Popon";
  import infiniteScroll from 'vue-infinite-scroll';
  export default {
    name: 'board-detail',
    components: {BoardPost, Popon, BoardCell, BoardTank},
    directives: {infiniteScroll},
    data() {
      return {
        sn: this.$route.query.sn,
        mode: this.$route.query.mode,
        target: this.$route.query.target,
        focus: null,
        list: [],
        scrollEnd: false,
        scrolling: false,
        post: null,
        modeDict: {
          'assoc': '回复',
          'refer': '回复',
          'chain': '对话'
        }
      }
    },
    created() {
      this.api.get('/api/board-focus', {
        sn: this.sn,
        target: this.target
      }).then((res) => {
        this.focus = res.data
      }, this.api.onErrorSign)
    },
    methods: {
      scroll() {
        if (this.scrolling) {
          return // 防止重复加载
        }
        this.scrolling = true
        let limit = 10
        let cursor = '--'
        if (this.list.length) {
          cursor = this.list[this.list.length-1].cursor
        }
        this.api.get(`/api/board-${this.mode}`, {
          sn: this.sn,
          mode: this.mode,
          target: this.target,
          cursor: cursor,
          limit: limit
        }).then((res) => {
          this.list.push(...res.data)
          if (res.data.length < limit) {
            this.scrollEnd = true
          }
          this.scrolling = false
        })
      },
      onPost(data) {
        this.post = data
      },
      onRelease(cursor, referer) {
        this.post = null
        this.api.get('/api/board-focus', {
          sn: this.sn,
          target: cursor
        }).then((res) => {
          if (referer === this.focus.cursor) {
            this.list.unshift(res.data)
          } else {
            for (let i=0, l=this.list.length; i<l; i++) {
              if (this.list[i].cursor === referer) {
                this.list.splice(i+1, 0, res.data)
              }
            }
          }
        })
      }
    }
  }
</script>

<style scoped>
  .c-board-detail {
    height: 100%;
  }
  .prime {
    padding: .1rem;
    background: #fff;
  }
  .slave {
    margin-top: .3rem;
    background: #fff;
  }
  .head {
    font-size: .32rem;
    padding: 0 .3rem;
    height: 1rem;
    line-height: 1rem;
    border-bottom: 1px solid #ccc;
  }
  .slave .c-board-cell {
    border-width: 0 0 1px 0;
    border-style: solid;
    border-image: linear-gradient(to right, transparent,transparent 1rem, #D3D3D3 1rem, #D3D3D3) 1;
  }
  .last {
    font-size: .3rem;
    color: #aaa;
    padding: .2rem 0;
    background: #fff;
  }
</style>
