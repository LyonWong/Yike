<template>
  <section class="content">
    <div class="lists" ref="lists">
      <single-list :lists="series['lesson-list']"></single-list>
    </div>
    <qr-code></qr-code>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';
  import qrCode from '@student/components/qrcode.vue';
  import singleList from '@student/components/singleList.vue';

  export default{
    name: 'series-briefList',
    components: {
      qrCode,
      singleList,
    },
    data() {
      return {
        studentHost: (process.env.NODE_ENV=='production'?process.env.STUDENT_HOST:'/student.html?'),
      };
    },
    computed: {
      ...mapGetters({
        'series': 'getSeriesDetail'
      })
    },
    created() {
    },
    methods: {
      formatTimer(value) {
        return value.replace(/^\d{4}-/g, '');
      },
    },
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";
</style>
