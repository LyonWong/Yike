<template>
  <div class="c-bind">
    <bind-telephone class="content" v-if="contact==='telephone'"></bind-telephone>
  </div>
</template>

<script>
  import BindTelephone from "../components/BindTelephone";

  export default {
    name: 'bind',
    components: {BindTelephone},
    data() {
      return {
        contact: null,
        titles: {
          'telephone': '绑定手机号'
        }
      }
    },
    created() {
      this.contact = this.$route.params.contact
      this.app.setTitle(this.titles[this.contact])
    }
  }
</script>

<style scoped>
  .c-bind {
    height: 100%;
    background: #fff;
  }
</style>
