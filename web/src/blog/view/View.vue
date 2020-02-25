<template>
  <div class="c-blog-view">
    <div class="cover" v-if="data.setting">
      <img :src="data.setting.cover" v-if="data.setting.cover"/>
    </div>
    <div class="title">
      <h1>{{data.title}}</h1>
    </div>
    <div class="content">
      <div class="markdown" v-html="markdown(data.content)"></div>
    </div>
  </div>
</template>

<script>
  const mk = require('markdown-it-katex')
  const markdown = require('markdown-it')({html: true, breaks: true})
  markdown.use(mk)
  export default {
    name: 'c-blog-view',
    data() {
      return {
        sn: null,
        data: {}
      }
    },
    created() {
      this.sn = this.$route.params.sn || this.$route.query.sn
      this.api.get('/api/blog-view', {
        sn: this.sn
      }).then( (res) => {
        this.data = res.data
      })
    },
    methods: {
      markdown(text) {
        return markdown.render(text || '')
      }
    }
  }
</script>

<style scoped>
  .c-blog-view {
    background: #fff;
    padding-bottom: .3rem;
  }
  .cover img{
    width: 100%;
    height: 4rem;
  }
  .title, .content {
    padding: 0 .3rem;
  }
</style>

<style>
  .markdown img {
    max-width: 100%;
  }
</style>
