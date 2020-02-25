<template>
 <div class="section-image">
   <div class="image">
     <img :src="content.src" @click="app.previewImageOne(content.src)"/>
   </div>
   <section-markdown v-if="content.text" :content="content"></section-markdown>
 </div>
</template>

<script>
  import SectionMarkdown from "./Markdown";
  export default {
    name: 'section-image',
    components: {SectionMarkdown},
    props: ['content'],
    created() {
      return {}
    }
  }
</script>

<style scoped>
  img {
    width: 100%;
  }

</style>
