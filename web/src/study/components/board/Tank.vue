<template>
  <div class="c-board-tank">
    <board-cell :datum="prime" v-on:post="onPost" :sn="sn"></board-cell>
    <div class="sublist" v-if="sublist.length">
      <board-cell v-for="item in sublist" :key="item.cursor" :sn="sn" :datum="item" v-on:post="onPost"></board-cell>
      <router-link class="more" v-if="prime.stats.assoc > 10" :to="{path: '/study/board-detail', query: {mode: 'assoc', sn: sn, target: prime.cursor}}">
        查看全部...
      </router-link>
      <div class="more" v-else-if="prime.stats.assoc > subshow" @click="showMore">
        展开剩余{{prime.stats.assoc - subshow}}条
      </div>
    </div>
    <popon :is-open="post" :option="{head: false, foot: false}" v-on:close="post = false">
      <board-post class="post" :sn="sn" :type="prime.type" :post="post" v-on:release="onRelease"></board-post>
    </popon>
  </div>
</template>

<script>
  import BoardCell from "./Cell";
  import Popon from "../../../components/Popon";
  import BoardPost from "./Post";
  export default {
    name: 'board-tank',
    components: {BoardPost, Popon, BoardCell},
    props: ['sn', 'prime'],
    data() {
      return {
        subshow: 2, // 最多展示两条回复，可考虑动态评估展示条数
        sublist: [],
        post: null
      }
    },
    created() {
      if (this.prime.stats.assoc) {
        this.api.get('/api/board-assoc', {
          sn: this.sn,
          target: this.prime.cursor,
          limit: Math.min(this.prime.stats.assoc, this.subshow)
        }).then((res) => {
          this.sublist = res.data
        })
      }
    },
    methods: {
      onPost(data) {
        this.api.get('/api/study-check', {
          sn: this.sn
        }).then(() => {
          this.post = data
        }, (e) => {
          if (e.error === '1') {
            this.bus.$emit('dialog', {
              info: {body: '解锁后才能评论'}
            })
          }
        })
      },
      onRelease(cursor, referer) {
        this.post = null
        if (!cursor) {
          return
        }
        this.api.get('/api/board-focus', {
          sn: this.sn,
          target: cursor
        }).then((res) => {
          if (referer === this.prime.cursor) {
            this.sublist.unshift(res.data)
          } else {
            for (let i=0, l=this.sublist.length; i<l; i++) {
              if (this.sublist[i].cursor === referer) {
                this.sublist.splice(i+1, 0, res.data)
              }
            }
          }
        })
      },
      showMore() {
          this.api.get('/api/board-assoc', {
            sn: this.sn,
            target: this.prime.cursor,
            cursor: this.sublist[this.sublist.length-1].cursor,
            limit: 10
          }).then((res) => {
            this.sublist.push(...res.data)
            this.subshow = this.sublist.length
          })
        // else {
        //   this.$router.push({name: 'BoardDetail', query: {target: this.prime.cursor}})
        // }
      }
    }
  }
</script>

<style scoped>
  .sublist {
    padding-left: .5rem;
  }
  .more {
    height: .6rem;
    line-height: .6rem;
    font-size: .28rem;
    color: #2F57DA;
  }
  .c-board-tank {
    /*border-bottom: 1px solid;*/
    border-width: 0 0 1px 0;
    border-style: solid;
    border-image: linear-gradient(to right, transparent,transparent .5rem, #D3D3D3 .5rem, #D3D3D3) 1;
  }
  .c-board-tank .c-board-cell {
    margin: .1rem 0;
  }
  </style>
<style>
  .sublist .c-board-cell .content {
    background: #F7F7F7;
    border-radius: .12rem;
  }
  .sublist .c-board-cell .avatar {
    transform: scale(0.9, 0.9);
  }
</style>
