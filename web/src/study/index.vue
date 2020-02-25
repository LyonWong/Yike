<template>
  <div id="idx-lesson" class="c-index">
    <router-view/>
    <bus/>
  </div>
</template>

<script>
  import Bus from "../components/Bus";
  export default {
    name: 'App',
    components: {Bus}
  }
</script>

<style scoped>

</style>
