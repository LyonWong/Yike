<template>
  <!-- list -->
  <div class="live-list">
  </div>
</template>

<script>
  import {mapState} from 'vuex';

  export default
  {
    name: 'v-list',
    components: {
    },
    data() {
      return {
        show: false
      };
    },
    computed: {
    },
    methods: {
      isShow() {
        this.$store.commit('UPDATE_MENUSHOW');
      }
    }
  };
</script>

<style lang="stylus" rel="stylesheet/stylus">

</style>
