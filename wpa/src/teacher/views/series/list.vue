<template>
  <section class="content-list">
    <series-list :lists="seriesList"></series-list>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex'
  import seriesList from '@teacher/components/seriesList.vue';

  export default{
    name: 'teacher-series',
    components: {
      seriesList
    },
    computed: {
      ...mapGetters({
        seriesList: 'getSeriesList',
      })
    },
    created() {
      this.$store.dispatch('fetchSeriesList').then(() => {
        console.log('success');
      }, () => {
        console.log('fail');
      });
    },
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  .content-list
    padding: 20px;
    background: #fff;
    border-radius: 4px;
    -webkit-border-radius: 4px;
</style>
