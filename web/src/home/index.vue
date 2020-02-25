<template>
  <div id="idx-create" class="c-index">
    <router-view/>
    <modal-dialog v-if="dialog" width="5.6rem" :dialog="dialog" v-on:close="dialog=null"></modal-dialog>
  </div>
</template>

<script>
  const ModalDialog = r => require.ensure([], r(require("../components/modal/Dialog")), 'common');
  export default {
    name: 'index',
    components: {ModalDialog},
    data() {
      return {
        dialog: null
      }
    },
    created() {
      this.bus.$on('dialog', (dialog) => {
        this.dialog = dialog
      })
    }
  }
</script>

<style>
</style>
