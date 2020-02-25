<template>
  <div class="c-course">
    <detail v-if="type==='detail'"></detail>
    <series v-if="type==='series'"></series>
  </div>
</template>

<script>
  const Detail = r => require.ensure([], () => r(require('../view/Detail')), 'lesson/detail')
  const Series = r => require.ensure([], () => r(require('../view/Series')), 'lesson/series')
  // import Detail from "./Detail"
  // import Series from "./Series"
  export default {
    name: 'c-course',
    components: {Detail, Series},
    computed: {
      type: function() {
        let sn = this.$route.query.sn || this.$route.params.sn || ''
        switch (sn[0]) {
          case 'L':
            return 'detail'
          case 'S':
            return 'series'
        }
        return undefined
      }
    }
  }
</script>

<style scoped>

</style>
