<template>
  <div class="c-section">
    <component :id="`section-${offset}`" :is="`section-${content.type || 'markdown'}`" :content="content" :control="control" :offset="offset"></component>
  </div>
</template>

<script>
  import Control from "./control"
  // import SectionMarkdown from "./Markdown";
  import SectionImage from "./Image";
  import SectionAudio from "./Audio";
  // import SectionVideo from "./Video";
  const SectionVideo = r => require.ensure([], () => r(require('./Video')), 'video')
  const SectionMarkdown = r => require.ensure([], () => r(require('./Markdown')), 'markdown')
  // const Control = r => require.ensure([], () => r(require('./control')), 'section')
  export default {
    name: 'c-section',
    components: {SectionVideo, SectionAudio, SectionImage, SectionMarkdown},
    props: ['content'],
    data() {
      return {
        control: Control,
        offset: null
      }
    },
    created() {
      this.offset = Control.push(this.content)
    },
    mounted() {
    },
    methods: {
    }
  }
</script>

<style scoped>

</style>
