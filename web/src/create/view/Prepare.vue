<template>
  <div class="v-create-prepare">
    <create-draft :sn="sn"></create-draft>
  </div>
</template>

<script>
  import CreateDraft from "../components/Draft";
  export default {
    name: 'create-prepare',
    components: {CreateDraft},
    data() {
      return {
        sn: this.$route.params.sn
      }
    }
  }
</script>

<style scoped>
  .v-create-prepare {
    background: #f0eff5;
  }

</style>
