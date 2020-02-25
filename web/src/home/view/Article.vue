<template>
  <div class="v-home-article">
    <study-article :home="$route.params.home"></study-article>
  </div>
</template>

<script>
  import StudyArticle from "../../study/view/Article";
  export default {
    name: 'home-article',
    components: {StudyArticle}
  }
</script>

<style scoped>

</style>
