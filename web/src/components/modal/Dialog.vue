<template>
  <modal-action :display="dialog" :width="dialog.width || width" v-on:close="$emit('close')">
    <div slot="head" v-if="info.head">{{info.head}}</div>
    <div class="body" v-html="info.body"></div>
    <div slot="foot" class="btn btn-vice" v-if="btn.vice" @click="callback('vice')">{{btn.vice}}</div>
    <div slot="foot" class="btn btn-prime" v-if="btn.prime" @click="callback('prime')">{{btn.prime}}</div>
  </modal-action>
</template>

<script>
  // demo: <modal-dialog v-if="dialog" width="5rem" :dialog="dialog" v-on:close="dialog=null"></modal-dialog
  const ModalAction = () => import('./Action')
  export default {
    name: 'modal-dialog',
    props: ['width', 'dialog'],
    components: {ModalAction},
    data() {
      return {
        info: this.dialog.info || {},
        call: this.dialog.call || {},
        btn: this.dialog.btn || {prime: '确认'}
      }
    },
    created() {
      console.log(this.dialog)
    },
    methods: {
      callback(btn) {
        this.call[btn] && this.call[btn]()
        this.$emit('close')
      }
    }
  }
</script>

<style scoped>
  .body {
    word-break: break-all;
  }
</style>
