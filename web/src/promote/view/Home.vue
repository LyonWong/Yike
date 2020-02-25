<template>
  <div class="c-home">
    <div class="lesson-list"></div>
    <navigation/>
    <swper></swper>
  </div>
</template>

<script>
  import Navigation from "../../components/Navigation";

  export default {
    components: {Navigation},
    name: 'home',
    data() {
      return {
        banners: [],
        sections: []
      }
    },
    created() {
      this.axios
        .get('/api/')
        .then( res => {
          console.log(res)
        })
    }
  }
</script>

<style scoped>

</style>
