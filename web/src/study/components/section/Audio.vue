<template>
  <div class="section-audio">
    <div class="audio">
      <audio controls controlslist="nodownload" :src="content.src"></audio>
    </div>
    <section-markdown v-if="content.text" :content="content"></section-markdown>
  </div>
</template>

<script>
  import SectionMarkdown from "./Markdown";
  export default {
    name: 'section-audio',
    components: {SectionMarkdown},
    props: ['content', 'control'],
    data() {
      return {}
    },
    created() {
    }
  }
</script>

<style scoped>
  .audio, audio {
    width: 100%;
  }

</style>
