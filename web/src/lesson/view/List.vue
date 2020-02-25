<template>
  <block class="c-list" :title="title" v-infinite-scroll="scroll" infinite-scroll-disabled="isEnd" infinite-scroll-distance="500">
    <router-link slot="more" to="/lesson/home" class="home">
      <i class="icon-yike icon-home"></i>
      <span>首页</span>
    </router-link>
    <course-cell :profile="item.profile" v-for="(item,index) in list" :key="index"></course-cell>
    <div class="end flex-row" v-show="isEnd">
      <span class="btn-warning">现在只有这么多课啦</span>
    </div>
  </block>
</template>

<script>
  import CourseCell from "../components/unit/CourseCell";
  import Block from "../../components/Block";
  import infiniteScroll from 'vue-infinite-scroll'

  export default {
    name: 'c-lesson-list',
    components: {Block, CourseCell},
    directives: {infiniteScroll},
    data() {
      return {
        title: this.$route.query.title,
        list: [],
        isEnd: false
      }
    },
    created() {
    },
    methods: {
      scroll: function() {
        if (this.api.loading) {
          return // 防止重复加载
        }
        let limit = 10
        let cursor = '--'
        if (this.list.length) {
          cursor = this.list[this.list.length-1].cursor
        }
        this.api.get('/api/lesson-list', {
          tag: this.$route.query.tag,
          limit: limit,
          cursor: cursor
        }).then((res) => {
          this.list.push(...res.data)
          if (res.data.length < limit) {
            this.isEnd = true
          }
        })
      }
    }
  }
</script>

<style scoped>
  .c-list {
    background: #f0eff5;
  }

  .icon-home {
    font-size: .24rem;
    font-weight: bold;
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
</style>

<style>
  .c-list > .block-head {
    position: sticky;
    top: 0;
    background: #fff;
    box-shadow: 0 0 5px rgba(0,0,0,0.1);
  }
</style>
