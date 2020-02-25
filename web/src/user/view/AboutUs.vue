<template>
  <div class="v-about-us">
    <div class="notice">
      <c-section :content="{type: 'markdown', text: notice}"></c-section>
    </div>
  </div>
</template>

<script>
  const CSection = r => require.ensure([], () => r(require("../../components/section")), 'section')
  export default {
    name: 'about-us',
    components: {CSection},
    data() {
      return {
        notice: null
      }
    },
    created() {
      this.axios.get(this.app.linkToTeacher('/doc/aboutus.md?'+(new Date()).getTime())).then((res) => {
        this.notice = res.data
      })
    }
  }
</script>

<style scoped>
  .v-about-us {
    min-height: 100%;
    justify-content: space-between;
  }
  .notice {
    padding: .3rem;
    background: #fff;
  }

</style>
