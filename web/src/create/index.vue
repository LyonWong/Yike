<template>
  <div id="idx-create" class="c-index">
    <router-view/>
    <bus/>
  </div>
</template>

<script>
  // const ModalDialog = r => require.ensure([], r(require("../components/modal/Dialog")), 'common');
  import Bus from "../components/Bus";
  export default {
    name: 'index',
    components: {Bus}
  }
</script>

<style>
</style>
