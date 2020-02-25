<template>
  <div class="section-markdown" v-html="markdown(content.text)"></div>
</template>

<script>
  const mk = require('markdown-it-katex')
  const markdown = require('markdown-it')({html: true, breaks: true})
  markdown.use(mk)
  export default {
    name: 'section-markdown',
    props: ['content'],
    data() {
      return {}
    },
    created() {},
    methods: {
      markdown(text) {
        // 换行处理
        return markdown.render((text || '').replace(/\n(\n+)/g, (m, p) => { return m+p.replace(/\n/g, "&nbsp;\n")+"\n" }))
      }
    }
  }
</script>

<style>
  .section-markdown {
    line-height: 2;
    text-align: justify;
    word-wrap: break-word;
  }
  .section-markdown p {
    margin: 0
  }
  .section-markdown img {
    max-width: 100%;
  }
  .section-markdown h1 {
    font-size: 1.4em;
  }
  .section-markdown h2 {
    font-size: 1.2em;
  }
  .section-markdown h3{
    font-size: 1em;
  }
  .section-markdown pre{
    margin: .1rem .3rem;
    padding: 0 .3rem;
    white-space: pre-wrap;
    border-left: .02rem solid #2F57DA;
    background: #d6ddf2;
  }
  .section-markdown code {
    background: #d6ddf2;
    padding: 2px 4px;
    margin: 0 2px;
    border-radius: 3px;
    color: #2F57DA;
  }
  .section-markdown pre code {
    padding: 0;
    margin: 0;
    color: inherit;
  }
  .section-markdown table {
    border-collapse:collapse;
  }
  .section-markdown thead > tr {
    background: #eee;
    white-space: nowrap;
  }
  .section-markdown th,td {
    border: 1px solid #ccc;
    padding: 0 5px;
  }
  .section-markdown blockquote {
    margin: .1rem .3rem;
    padding: 0 .3rem;
    border-left: .02rem solid #ccc;
    background: #eee;
  }
  .section-markdown blockquote > p {
    word-break: break-all;
  }
</style>
