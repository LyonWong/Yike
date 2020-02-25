<template>
  <div class="v-create-guide-markdown">
    <div class="head flex-row">markdown语法简介</div>
    <markdown-guide></markdown-guide>
  </div>
</template>

<script>
  import MarkdownGuide from "../../components/MarkdownGuide";
  export default {
    name: 'create-guide-markdown',
    components: {MarkdownGuide}
  }
</script>

<style scoped>
  .v-create-guide-markdown {
    background: #fff;
  }
  .head {
    font-size: .36rem;
    padding: 1em 0;
  }

</style>
