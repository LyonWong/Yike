<template>
  <!-- 直播入口 -->
  <div class="l-label">
    <ul>
      <li>
        <h4>课程</h4>
        <div>{{lessonInfo.title}}</div>
      </li>
      <li>
        <h4>讲师</h4>
        <div>{{teacherInfo.name}}</div>
      </li>
      <!--<li>-->
        <!--<h4>在线人数</h4>-->
        <!--<div>测试人</div>-->
      <!--</li>-->
    </ul>
  </div>

</template>

<script type="text/ecmascript-6">
  import {mapState} from 'vuex';

  export default
  {
    name: 'l-label',
    data() {
      return {
        isPC,
      }
    },
    components: {

    },
    computed: {
      ...mapState([
        'isOwner',
        'userInfo',
        'lessonInfo',
        'teacherInfo',
      ])
    },
    created() {
    },
    mounted() {
      console.log(this.lessonInfo);
    },
    methods: {

    },
  };

</script>
<style scoped lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";

  .l-label
    width: 184px;
    font-size: 16px;
    ul
      margin-top: 134px;
      li
        padding: 0 12px;
        >*
          text-align: center;
        h4
          margin-bottom: 2px;
        div
          line-height: 20px;
</style>
