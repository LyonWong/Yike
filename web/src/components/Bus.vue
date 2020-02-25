<template>
  <div class="bus">
    <toast v-if="toast" :toast="toast"></toast>
    <modal-dialog v-if="dialog" width="5.6rem" :dialog="dialog" v-on:close="dialog=null"></modal-dialog>
  </div>
</template>

<script>
  import Toast from "./Toast";
  import ModalDialog from "./modal/Dialog";
  export default {
    name: 'Bus',
    components: {Toast, ModalDialog},
    data() {
      return {
        toast: null,
        dialog: null
      }
    },
    created() {
      this.bus.$on('toast', (toast) => {
        this.toast = toast
      })
      this.bus.$on('dialog', (dialog) => {
        this.dialog = dialog
      })
    }
  }
</script>

<style scoped>

</style>
