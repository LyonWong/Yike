<template>
  <section class="enroll">
    <enroll-list :lists="lists" v-if="canShow"></enroll-list>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';
  import enrollList from '@student/components/enrollList';

  export default{
    name: 'enrolled',
    components: {
      enrollList
    },
    data() {
      return {
        canShow: false,
      };
    },
    computed: {
      ...mapGetters({
        lists: 'getUserEnroll',
      })
    },
    created() {
      this.$store.dispatch('fetchEnrollList').then(() => {
        this.canShow = true;
        console.log('success');
      }, () => {
        this.canShow = true;
        console.log('fail');
      });
    },
    methods: {
    },
  }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";

  .enroll-tips
    margin-top: 100px;
    text-align: center;
    color: #12b7f5;
    background: #f7f9fc;
    px2px(font-size, 36px);

</style>
