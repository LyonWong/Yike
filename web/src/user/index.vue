<template>
  <div id="idx-user" class="c-index">
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
</style>
