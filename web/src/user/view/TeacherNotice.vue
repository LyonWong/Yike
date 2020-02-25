<template>
  <div class="v-teacher-notice flex-col">
    <div class="notice">
      <c-section :content="{type: 'markdown', text: notice}"></c-section>
    </div>
    <div class="footbar flex-row">
      <a class="btn flex-item" href="/create/posts">开始创作</a>
    </div>
  </div>
</template>

<script>
  const CSection = r => require.ensure([], () => r(require("../../components/section")), 'section')
  export default {
    name: 'teacher-notice',
    components: {CSection},
    data() {
      return {
        notice: null
      }
    },
    created() {
      this.axios.get(this.app.linkToTeacher('/doc/notice.md?'+(new Date()).getTime())).then((res) => {
        this.notice = res.data
      })
    }
  }
</script>

<style scoped>
  .v-teacher-notice {
    min-height: 100%;
    justify-content: space-between;
  }
  .notice {
    padding: .3rem;
    background: #fff;
  }
  .footbar {
    position: sticky;
    bottom: 0;
    width: 100%;
  }
  .footbar .btn {
    height: 1rem;
    line-height: 1rem;
    text-align: center;
    background: #2F57DA;
    color: #fff;
    font-size: .4rem;
  }

</style>
