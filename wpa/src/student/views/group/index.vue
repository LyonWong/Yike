<template>
  <section class="group">
    <loading :show="groupLoading"></loading>
    <router-view></router-view>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';
  import { trimStr } from '@lib/js/mUtils';
  import sButton from '@student/components/button';
  import Loading from '@student/components/loading';
  import swal from 'sweetalert';

  export default{
    name: 'group',
    components: {
      sButton,
      Loading,
    },
    computed: {
      ...mapGetters({
        'groupLoading': 'getGroupLoading',
      })
    },
    data() {
      return {
        text: '',
      }
    },
    created() {
      let params = this.$route.params;

    },
    methods: {

    },
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";
</style>
