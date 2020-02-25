<template>
  <div class="section-markdown" v-html="markdown(content.text)"></div>
</template>

<script>
  const mk = require('markdown-it-katex')
  // const mk = r => require.ensure([], () => r(require('markdown-it-katex')), 'markdown')
  const markdown = require('markdown-it')({html: true, breaks: true})
  // const markdown = r => require.ensure([], () => r(require('markdown-it')), 'markdown')({breaks: true})
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
        return markdown.render(text || '')
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
  .section-markdown img {
    max-width: 100%;
  }
  .section-markdown h1 {
    font-size: 1.3em;
  }
  .section-markdown h2, .section-markdown h3{
    font-size: 1em;
    /*color: #5B50EA;*/
  }
  .section-markdown pre{
    margin: 1em;
    padding: .5em 1em;
    white-space: pre-wrap;
    border-left: .02rem solid #2F57DA;
    background: #d6ddf2;
  }
  .section-markdown code {
    background: #d6ddf2;
    padding: 2px 4px;
    margin: 0 2px;
    font-size: 90%;
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
    margin: 0 1em;
    border-left: .02rem solid #ccc;
    padding: 1px 1em;
    background: #eee;
  }
  .section-markdown blockquote > p {
    word-break: break-all;
  }
</style>
