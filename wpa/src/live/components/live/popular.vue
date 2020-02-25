<template>
  <!-- popular -->
  <div class="live-popular">
    <!-- popularity -->
    <div class="live-info">
      <i class="user-icon-fans"></i><span id="user-icon-fans">0</span>
      <i class="user-icon-like"></i><span id="user-icon-like">0</span>
    </div>
    <a href="javascript:void(0);" class="live-close" onclick="quitBigGroup()"></a>
  </div>
</template>

<script>
  import {mapState} from 'vuex';

  export default
  {
    name: 'v-popular',
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
