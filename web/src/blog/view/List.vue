<template>
  <div class="c-list">
    <Tabs id="tabs" :items="tabs" :active="activeTab" v-on:switch="switchTab"></Tabs>
    <div class="item">
      <blog-cell :profile="item" v-for="(item,index) in list" :key="index"></blog-cell>
      <div class="flex-row turning">
        <a class="flex-item flex-row" :href="`./?cursor=${cursorPrev}&limit=-5`" v-if="cursorPrev">上一页</a>
        <a class="flex-item flex-row" :href="`./?cursor=${cursorNext}&limit=5`" v-if="cursorNext">下一页</a>
        <a class="flex-item flex-row" href="./" v-if="cursorPrev===null && cursorNext===null">回首页</a>
      </div>
      <div class="end flex-row" v-show="isEnd">
        <span class="btn-warning">没有更多了</span>
      </div>
    </div>
  </div>
</template>

<script>
  import Tabs from "../../components/Tabs"
  import BlogCell from "../unit/BlogCell"

  export default {
    name: 'c-blog-list',
    components: {Tabs, BlogCell},
    data() {
      return {
        title: '文章',
        list: [],
        isEnd: false,
        tabs: [
          {'key': '_', 'name': '新闻文章'}
        ],
        activeTab: '_'
      }
    },
    created() {
      this.switchTab('_')
      this.turning(this.$route.query.cursor || '--', this.$route.query.limit || 5)
    },
    computed: {
      cursorPrev() {
        if (this.list.length + this.$route.query.limit < 0 || !this.$route.query.limit) {
          return null
        } else {
          return this.list.length ? this.list[0].cursor : null
        }
      },
      cursorNext() {
        if (this.$route.query.limit && this.$route.query.limit > this.list.length) {
          return null
        } else {
          return this.list.length ? this.list[this.list.length-1].cursor : null
        }
      }
    },
    methods: {
      switchTab(key) {
        this.activeTab = key
      },
      turning: function(cursor, limit) {
        this.api.get('/api/blog-list', {
          category: this.activeTab,
          limit: limit,
          cursor: cursor
        }, {responseType: 'text'}).then((res) => {
          this.list = res.data
        })
      },
      scroll: function(cursor, limit) {
        if (this.api.loading) {
          return
        }
        cursor = cursor || '--'
        limit = limit || 5
        if (this.list.length) {
          cursor = this.list[this.list.length-1].cursor
        }
        this.api.get('/api/blog-list', {
          category: this.activeTab,
          limit: limit,
          cursor: cursor
        }).then((res) => {
          if (limit > 0) {
            this.list.push(...res.data)
            if (res.data.length < limit) {
              this.isEnd = true
            }
          } else {
            this.list = [...res.data, ...this.list]
            if (res.data.length < -limit) {
            }
          }
        })
      }
    }
  }
</script>

<style scoped>

  .c-list {
    background: #fff;
  }

  .item {
    padding: 0 .3rem;
  }

  .end {
    padding: .3rem 0;
  }

  .end span, .end > a {
    font-size: .2rem;
    color: #aaa;
    /*padding: .1rem .2rem;*/
    /*border: 1px solid #ccc;*/
    border-radius: 1em;
    cursor: pointer;
  }

  .btn-warning {
    color: #aaa;
    background: #fff;
  }
</style>

<style>
  .c-search > .block-head {
    position: sticky;
    top: 0;
    background: #fff;
    box-shadow: 0 0 5px rgba(0,0,0,0.1);
  }
  .turning {
    height: .5rem;
  }
</style>
