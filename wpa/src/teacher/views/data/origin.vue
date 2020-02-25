<template>
  <section class="data-course course-list">
    <div class="data-title">
      <router-link to="/data">课程数据</router-link>
      <span v-if="dataOrigin.tier">
        &#124;
        <span v-for="bread in dataOrigin.tier">
          <span v-if="bread.depth">&nbsp;&#62;</span>
          <span @click="unfoldOrigin(bread.id)">{{bread.name}}</span>
        </span>
      </span>
    </div>
    <ul v-if="dataOrigin.subs">
      <li class="head">
        <span>来源</span>
        <span>报名人数</span>
        <span>听课人数</span>
        <span>退款人数</span>
        <span>课程评分</span>
        <span>课程收入</span>
        <span>分成收入</span>
        <span class="handle">&nbsp;</span>
      </li>
      <li class="total">
        <span v-text="dataOrigin.total.origin.name"></span>
        <span>{{ dataOrigin.total.data | specKey('lesson.enroll.unique') }}</span>
        <span>{{ dataOrigin.total.data | specKey('lesson.access.unique') }}</span>
        <span>{{ dataOrigin.total.data | specKey('lesson.refund.unique') }}</span>
        <span>{{ dataOrigin.total.data | specKey('lesson.rate.avg') }}</span>
        <span class="price">&#65509;{{ dataOrigin.total.data | specKey('lesson.income.sum') }}</span>
        <span class="price">&#65509;{{ dataOrigin.total.data | specKey('lesson.payoff.sum') }}</span>
        <span class="handle">
          &nbsp;
        </span>
      </li>
      <li v-for="item in dataOrigin.subs">
        <span>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          {{item.origin.name}}
        </span>
        <span>{{ item.data | specKey('lesson.enroll.unique') }}</span>
        <span>{{ item.data | specKey('lesson.access.unique') }}</span>
        <span>{{ item.data | specKey('lesson.refund.unique') }}</span>
        <span>{{ item.data | specKey('lesson.rate.avg') }}</span>
        <span class="price">&#65509;{{ item.data | specKey('lesson.income.sum') }}</span>
        <span class="price">&#65509;{{ item.data | specKey('lesson.payoff.sum') }}</span>
        <div class="handle">
          <a class="button" href="javascript:;" @click="unfoldOrigin(item.origin.id)">展开</a>
        </div>
      </li>
    </ul>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';
  import swal from 'sweetalert';

  export default{
    name: 'data-origin',
    computed: {
      ...mapGetters({
        dataOrigin: 'getDataOrigin',
      })
    },
    created() {
      //
      this.fetchOrigin();
    },
    watch: {
      '$route': 'fetchOrigin' //切换路由，调用reloadData方法
    },
    methods: {
      unfoldOrigin(id) {
        let params = {
          lesson_sn: this.$route.params.lesson_sn,
          origin_id: id,
      };
      // 跳转
      this.$router.replace({ name: 'data-origin', params: params });
      },
      fetchOrigin() {
        let params = this.$route.params;
        //
        if(params.origin_id === 'null'){
          delete params.origin_id;
        }
        // 获取数据来源
        this.$store.dispatch('fetchDataOrigin', params).then((data) => {
          console.log('success');
        }, (err) => {
          swal({
            title: '错误提醒',
            text: err.message,
            confirmButtonText: "知道了"
          });
        });
      }
    },
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";
</style>
