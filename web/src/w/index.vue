<template>
  <div id="idx-w" class="c-index">
    <router-view/>
    <bus/>
  </div>
</template>

<script>
  import Bus from "../components/Bus";
  export default {
    name: 'index',
    components: {Bus}
  }
</script>

<style>
  html {font-size: 100px}
</style>
