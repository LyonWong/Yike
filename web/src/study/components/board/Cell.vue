<template>
  <div class="c-board-cell flex-row" v-if="datum">
    <div class="mask" v-show="menu" @click="menu = false"></div>
    <div class="avatar">
      <img :src="datum.user.avatar"/>
    </div>
    <div class="content flex-col">
      <div class="head flex-row">
        <div class="head-info flex-row">
          <span class="nickname font-medium">
            {{datum.user.name}}
          </span>
          <span class="nick-label" :class="datum.user.label" v-if="datum.user.label">
            {{nickLabelDict[datum.user.label]}}
          </span>
          <i class="refer icon-yike icon-triangle-r" v-if="datum.refer"></i>
          <span class="refer nickname font-medium" v-if="datum.refer">{{datum.refer.user.name}}</span>
          <div class="refer flex-row" v-if="0 && datum.refer">
            <i class="icon-yike icon-triangle-r"></i>
            <span class="nickname font-medium">{{datum.refer.user.name}}</span>
          </div>
        </div>
        <div class="head-menu">
          <i class="btn icon-yike icon-more" @click="menu = !menu"></i>
          <ul v-show="menu">
            <!--<li class="btn" v-for="item in datum.menu" :key="item" v-if="item==='chain'" @click="viewChain">查看对话</li>-->
            <li class="btn" v-for="item in datum.menu" :key="item" v-if="item==='tipoff'" @click="postOn('tipoff')">举报留言</li>
            <li class="btn" v-for="item in datum.menu" :key="item" v-if="item==='remove'" @click="remove">删除留言</li>
          </ul>
        </div>
      </div>
      <div class="body">
        <div class="body-text board-markdown" v-if="datum.message.text" v-html="markdown(datum.message.text)"></div>
        <div class="body-image flex-row" v-if="datum.message.image">
            <img :class="[`image-${datum.message.image.length}`]" v-for="(img,idx) in datum.message.image" :key="idx" :src="datum.message.image.length > 1 ? `${img}!previews` : `${img}!preview`" @click="app.previewImages(datum.message.image, img)"/>
        </div>
      </div>
      <div class="foot flex-row">
        <div class="flex-row">
          <div class="foot-time">
            {{datum.tms_create | legibleTime}}
          </div>
          <div class="foot-dot">·</div>
          <div class="foot-reply btn" @click="postOn('reply')">
            回复
          </div>
        </div>
        <div class="foot-like btn" @click="like">
          <span>{{stats.liked}}</span>
          <i class="icon-yike" :class="{'icon-like': !self.isLike, 'icon-liked': self.isLike}"></i>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Filters from '@/assets/js/filters'
  const mk = require('markdown-it-katex')
  // const mk = r => require.ensure([], () => r(require('markdown-it-katex')), 'markdown')
  const markdown = require('markdown-it')({breaks: true})
  // const markdown = r => require.ensure([], () => r(require('markdown-it')), 'markdown')({breaks: true})
  markdown.use(mk)
  export default {
    name: 'board-cell',
    props: ['sn', 'datum'],
    filters: {...Filters},
    data() {
      return {
        self: this.datum.self || {},
        stats: this.datum.stats,
        menu: false,
        nickLabelDict: {
          'self': '自己',
          'teacher': '讲师'
        }
      }
    },
    methods: {
      like() {
        this.api.post('/api/board-like', {
          cursor: this.datum.cursor
        }, {loading: false}).then( (res) => {
          this.self.isLike = res.data.isLike
          this.stats.liked = res.data.liked
        }, (err) => {
          alert(err.message)
        })
      },
      remove() {
        if (confirm('确认删除吗？')) {
          this.menu = false
          this.api.post('/api/board-remove', {
            cursor: this.datum.cursor
          }).then(() => {
            this.datum = null
          })
        }
      },
      detail() {
        this.$router.push({name: 'BoardDetail', query: {mode: 'assoc', sn: this.sn, target: this.datum.cursor}})
      },
      postOn(way) {
        this.$emit('post', {
          cursor: this.datum.cursor,
          name: this.datum.user.name,
          way: way
        })
        this.menu = false
      },
      markdown(text) {
        return markdown.render(text || '')
      },
      viewChain() {
        this.$router.push({path: '/study/board-detail', query: {mode: 'chain', sn: this.sn, target: this.datum.refer.cursor}})
      }
    }
  }
</script>

<style scoped>
  .c-board-cell {
    align-items: start;
    font-size: .3rem;
    background: #fff;
  }
  .avatar {
    padding: .1rem;
  }
  .avatar > img {
    width: .6rem;
    height: .6rem;
    border-radius: 50%;
  }
  .content {
    flex-grow: 1;
    justify-content: flex-start;
  }
  .content > div {
    width: 100%;
  }
  .head {
    justify-content: space-between;
  }
  .head > div {
    padding: .1rem;
  }
  .head-info {
    max-width: 5rem;
  }
  .head-info > div {
    margin: .05rem 0;
  }
  .nickname {
    color: #000;
    height: .4rem;
    line-height: .4rem;
    white-space: nowrap;
    max-width: 4rem;
    min-width: .5rem;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .refer.nickname {
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .head-menu {
    position: relative;
    margin-right: .1rem;
  }
  .head-menu > .btn {
    color: #ccc;
    font-size: .4rem;
  }
  .head-menu > ul {
    position: absolute;
    margin: 0;
    padding: 0 .2rem;
    list-style: none;
    right: 0;
    background: #fff;
    color: #333;
    z-index: 100;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
  }
  .head-menu li {
    padding: .1rem .05rem;
    border-bottom: 1px solid #aaa;
    white-space: nowrap;
  }
  .head-menu li:last-child {
    border-bottom: 0;
  }
  .body > div {
    padding: .1rem;
  }
  .body-text {
    color: #4D4D4D;
  }
  .body-image {
    justify-content: flex-start;
  }
  .body-image > img {
    margin-right: .15rem;
  }
  .image-1 {
    max-width: 100%;
    max-height: 3rem;
  }
  /*.image-2 {*/
    /*max-width: 45%;*/
    /*max-height: 2rem;*/
  /*}*/
  .image-2, .image-3 {
    max-width: 30%;
    max-height: 1.5rem;
  }
  .foot {
    height: .6rem;
    line-height: .6rem;
    font-size: .26rem;
    justify-content: space-between;
  }
  .foot-time {
    color: #999;
  }
  .foot-dot {
    color: #999;
    padding: 0 .1rem;
  }
  .foot-like {
    color: #999;
  }
  .foot > div {
    margin: 0 .1rem;
  }
  .foot i {
    font-size: .3rem;
  }
  .mask {
    background: rgba(0,0,0,0)
  }
  .refer {
    color: #0D0D0D;
  }
  .refer.icon-yike {
    color: #2F57DA;
    font-size: .1rem;
    padding: 0 .1rem;
  }
  .nick-label {
    font-size: .2rem;
    margin: .1rem;
    padding: .05rem .1rem;
    border-radius: .05rem;
    white-space: nowrap;
  }
  .nick-label.self {
    border: 1px solid #2F57DA;
    color: #2F57DA;
  }
  .nick-label.teacher {
    background: #2F57DA;
    color: #fff;
  }
</style>
<style>
  .board-markdown {
    text-align: justify;
    word-wrap: break-word;
  }
  .board-markdown p {
    word-break: break-word;
  }
  .board-markdown p:first-child {
    margin-top: 0;
  }
  .board-markdown p:last-child {
    margin-bottom: 0;
  }
  .board-markdown img {
    max-width: 100%;
  }
  .board-markdown h1 {
    font-size: 1.3em;
  }
  .board-markdown h2, .board-markdown h3{
    font-size: 1em;
    /*color: #5B50EA;*/
  }
  .board-markdown pre{
    margin: 1em;
    padding: .5em 1em;
    white-space: pre-wrap;
    border-left: .02rem solid #2F57DA;
    background: #d6ddf2;
  }
  .board-markdown code {
    background: #d6ddf2;
    padding: 2px 4px;
    margin: 0 2px;
    font-size: 90%;
    border-radius: 3px;
    color: #2F57DA;
  }
  .board-markdown pre code {
    padding: 0;
    margin: 0;
    color: inherit;
  }
  .board-markdown table {
    border-collapse:collapse;
  }
  .board-markdown thead > tr {
    background: #eee;
    white-space: nowrap;
  }
  .board-markdown th,td {
    border: 1px solid #ccc;
    padding: 0 5px;
  }
  .board-markdown blockquote {
    margin: 0 1em;
    border-left: .02rem solid #ccc;
    padding: 1px 1em;
    background: #eee;
  }
  .board-markdown blockquote > p {
    word-break: break-all;
  }
</style>
