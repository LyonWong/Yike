<template>
  <div class="c-search">
    <div class="search-bar flex-row">
      <div class="home"></div>
      <div class="input flex-row">
        <i class="icon-yike icon-search" @click="explore(keywords)"></i>
        <div class="tags flex-row">
          <div class="tag-active flex-row" v-for="(tag,idx) in tags" :key="idx">
            <span>{{tagDict[tag].name}}</span>
            <i class="icon-yike icon-cross" @click="delTag(idx)"></i>
          </div>
        </div>
        <div class="query flex-row">
          <input type="search" v-model="keywords" placeholder="" @keyup.enter="explore(keywords)" @keyup.esc="reset" @keyup.delete="delWords"/>
        </div>
        <i class="icon-yike icon-close" @click="reset" v-if="query || tags.length"></i>
      </div>
      <router-link class="back" to="/lesson/home">取消</router-link>
    </div>
    <!--无搜索关键词时展示-->
    <div class="search-home" v-if="!query && tags.length===0">
      <div class="frm-home">
      <div class="history" v-if="history.length">
        <div class="tags-head font-bold flex-row">
          <span>搜索历史</span>
          <i class="icon-yike icon-delete" @click="clearHistory"></i>
        </div>
        <div class="tags-body">
          <div class="tag-item" v-for="(words,index) in history" :key="index" @click="explore(words)">{{words}}</div>
        </div>
      </div>
      <div class="home-tags">
        <div v-for="type in tagTypes" :key="type.key" class="tag-section">
          <div class="tags-head font-bold">{{type.name}}</div>
          <div class="tags-body">
            <div class="tag-item" v-for="tag in tagList[type.key]" :key="tag.key" @click="addTag(type.key, tag.key)">{{tag.name}}</div>
          </div>
        </div>
      </div>
      </div>
    </div>
    <!--搜索跟随提示-->
    <div class="search-prompt" v-if="promptList.length">
      <ul>
        <li v-for="(item, index) in promptList" :key="index">
          <router-link :to="`/lesson/course/${item.tsn}`">{{item.title}}</router-link>
        </li>
      </ul>
    </div>
    <!--搜索结果详情-->
    <div class="search-explore"  v-infinite-scroll="scroll" infinite-scroll-disabled="exploreEnd"  infinite-scroll-distance="500" v-show="exploreList.length">
      <search-cell :profile="item.profile" v-for="(item,index) in exploreList" :key="index"></search-cell>
    </div>
    <div class="search-empty flex-row" v-if="empty">
      <span>没有找到您想要的课程 o(╯□╰)o</span>
    </div>
  </div>
</template>

<script>
  import SearchCell from "../components/unit/SearchCell";
  import infiniteScroll from 'vue-infinite-scroll'

  export default {
    name: 'c-lesson-search',
    components: {SearchCell},
    directives: {infiniteScroll},
    data() {
      return {
        keywords: '',
        query: '',
        tags: [],
        history: [],
        tagTypes: [
          {
            key: 'BLOCK',
            name: '板块分类'
          }
        ],
        tagList: [],
        tagDict: {},
        promptList: [],
        exploreList: [],
        exploreEnd: true,
        inputing: 0,
        empty: false
      }
    },
    created() {
      this.api.get('/api/lesson-tags').then((res) => {
        this.tagList = res.data
        for (let block in res.data) {
          for (let i in res.data[block]) {
            this.tagDict[`${block}:${res.data[block][i].key}`] = { name: res.data[block][i].name }
          }
        }
      })
      this.history = JSON.parse(window.localStorage.getItem('search-history')||'[]')
    },
    methods: {
      addTag: function(type, key) {
        this.tags.push(`${type}:${key}`)
      },
      delTag: function(i) {
        this.tags.splice(i, 1)
      },
      delWords: function() {
        if (this.keywords === null && this.tags.length) {
          this.tags.pop()
        }
        if (this.keywords === '') {
          this.keywords = null
        }
        if (this.keywords === null) {
          if (this.tags.length) {
            this.clear()
          } else {
            this.reset()
          }
        }
      },
      reset: function() {
        this.keywords = ''
        this.query = ''
        this.tags = []
        this.clear()
      },
      clear: function () {
        this.promptList = []
        this.exploreList = []
      },
      prompt: function(query) {
        this.query = query || this.keywords || this.query
        this.exploreList = []
        this.api.get('/api/lesson-search-prompt', {
          query: this.query,
          tags: this.tags,
          limit: 7
        }).then((res) => {
          this.promptList = res.data
        })
      },
      explore: function(query) {
        this.clear()
        this.query = this.keywords = query || this.keywords || this.query
        if (!this.query && this.tags.length===0) {
          return
        }
        let preidx = this.history.indexOf(this.query)
        if (preidx > -1) { // 删除原有重复记录
          this.history.splice(preidx, 1)
        }
        if (this.query) {
          this.history.unshift(this.query)
          this.history.splice(7, 7)
        }
        this.promptList = []
        this.api.get('/api/lesson-search-explore', {
          query: this.query,
          tags: this.tags,
          limit: 10
        }).then((res) => {
          this.exploreList = res.data
          this.exploreEnd = false
          this.empty = res.data.length === 0
          this.isEnd = res.data.length < 10
        })
        window.localStorage.setItem('search-history', JSON.stringify(this.history))
      },
      scroll: function () {
        if (this.api.loading) {
          return
        }
        let limit = 10
        let cursor = '--'
        if (this.exploreList.length) {
          cursor = this.exploreList[this.exploreList.length - 1].cursor
        }
        this.api.get('/api/lesson-search-explore', {
          query: this.query,
          tags: this.tags,
          limit: limit,
          cursor: cursor
        }).then((res) => {
          this.exploreList.push(...res.data)
          if (res.data.length < limit) {
            this.exploreEnd = true
          }
        })
      },
      clearHistory: function() {
        this.history = []
        window.localStorage.removeItem('search-history')
      }
    },
    watch: {
      keywords: function() {
        this.query = ''
        clearTimeout(this.inputing)
        this.inputing = setTimeout( () => {
          this.inputing = 0
        }, 500)
      },
      tags: function() {
        this.tags.length && this.explore()
      },
      inputing: function(n, o) {
        this.empty = false
        if (o && n === 0 && this.keywords !== null && this.keywords !== this.query) {
          this.prompt(this.keywords)
        }
      }
    }
  }
</script>

<style scoped>
  .c-search {
    height: 100%;
  }
  .box {
    padding: 1.8rem .3rem .4rem .3rem;
  }
  .search-bar {
    position: fixed;
    top: 0;
    z-index: 9;
    height: 1.1rem;
    width: 7.5rem;
    background: #627BCC;
  }
  .search-bar .home {
    width: .3rem;
  }
  .search-bar a.back, .search-bar .explore {
    font-size: .28rem;
    padding: .42rem .31rem;
    color: #fff;
    white-space: nowrap;
  }
  .search-bar .tag-active {
    font-size: .24rem;
    background: #627BCC;
    color: #fff;
    border-radius: .3rem;
    padding: .05rem .2rem;
    margin: 0 .1rem;
    white-space: nowrap;
  }
  .tag-active > .icon-cross {
    font-size: .24rem;
    margin-left: .05rem;
    color: rgba(221, 221, 221, 0.4);
  }
  .search-bar .input {
    flex-grow: 1;
    height: .6rem;
    background: rgba(221, 221, 221, 0.4);
    border-radius: .3rem;
  }
  .search-bar .input > .icon-search {
    font-size: .28rem;
    margin: 0 .2rem;
    color: #fefefe;
  }
  .search-bar .input > .icon-close {
    font-size: .32rem;
    color: #000;
    opacity: 0.3;
    margin: 0 .2rem;
  }
  .search-bar .query {
    flex-grow: 1;
  }
  .search-bar .query > input {
    flex-grow: 1;
    border-style: none;
    outline: none;
    background: transparent;
    width: 100%;
    color: #fff;
    height: .4rem;
    line-height: .4rem;
    font-size: .28rem;
  }
  input::-webkit-search-cancel-button{
    display: none;
  }
  input[type=search]::-ms-clear{
    display: none;
  }
  .search-bar .explore {
    opacity: 0.35;
  }
  .search-bar .explore.available {
    opacity: 1;
  }
  .search-prompt {
    position: relative;
    padding: 1.1rem .3rem 0 .3rem;
    background: #fff;
  }
  .search-prompt ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
  }
  .search-prompt li {
    height: 1rem;
    line-height: 1rem;
    margin: 0;
    border-bottom: 1px solid #f2f2f2;
  }
  .search-prompt a {
    font-size: .28rem;
    color: #0D0D0D;
    display: inline-block;
    width: 100%;
  }
  .search-explore {
    position: relative;
    padding: 1.1rem .3rem 0 .3rem;
    background: #fff;
  }
  .search-home {
    background: #fff;
    height: 100%;
  }
  .frm-home {
    padding: 1.8rem .4rem .3rem .4rem;
  }
  .home-tags {
    margin-top: .1rem;
  }
  .tags-head {
    font-size: .4rem;
    color: #0d0d0d;
  }
  .tags-body {
    display: flex;
    flex-wrap: wrap;
    padding: .38rem 0;
  }
  .tag-item {
    font-size: .24rem;
    border-radius: .32rem;
    padding: .2rem .32rem;
    margin-right: .2rem;
    margin-bottom: .3rem;
  }
  .history .tag-item {
    color: #4d4d4d;
    background: #f7f7f7;
  }
  .home-tags .tag-item {
    color: #4362C6;
    background: rgba(67,98,198,.2);
  }
  .tags-head {
    justify-content: space-between;
  }
  .history .tags-head .icon-delete {
    font-size: .32rem;
    color: #B3B3B3;
    font-weight: normal;
  }
  .search-empty {
    padding: 2.1rem;
    font-size: .28rem;
    white-space: nowrap;
    color: #999;
  }
</style>
