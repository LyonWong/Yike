<template>
  <section class="data-course">
    <div class="data-title">数据中心</div>
    <ul>
      <li class="head">
        <span>课程</span>
        <span>报名人数</span>
        <span>听课人数</span>
        <span>退款人数</span>
        <span>课程评分</span>
        <span>课程收入</span>
        <span>分成收入</span>
        <span class="handle">&nbsp;</span>
      </li>
      <li v-for="item in dataCourse">
        <span v-text="item.lesson.title"></span>
        <span>{{ item.data | specKey('lesson.enroll.unique') }}</span>
        <span>{{ item.data | specKey('lesson.access.unique') }}</span>
        <span>{{ item.data | specKey('lesson.refund.unique') }}</span>
        <span>{{ item.data | specKey('lesson.rate.avg') }}</span>
        <span class="price">&#65509;{{ item.data | specKey('lesson.income.sum') }}</span>
        <span class="price">&#65509;{{ item.data | specKey('lesson.payoff.sum') }}</span>
        <div class="handle">
          <a class="button" href="javascript:;" @click="goToOrigin(item.lesson.sn)">来源</a>
        </div>
      </li>
    </ul>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';
  import swal from 'sweetalert';

  export default{
    name: 'data-course',
    computed: {
      ...mapGetters({
        dataCourse: 'getDataCourse',
      })
    },
    created() {
      // 获取课程数据
      this.$store.dispatch('fetchDataCourse').then((data) => {
        console.log('success');
      }, (err) => {
        swal({
          title: '错误提醒',
          text: err.message,
          confirmButtonText: "知道了"
        });
      });
    },
    methods: {
      goToOrigin(id) {
        this.$router.push({ name: 'data-origin', params: {lesson_sn:id,origin_id:'null'} });
      }
    },
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";
</style>
