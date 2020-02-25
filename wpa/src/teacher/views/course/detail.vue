<template>
  <section class="content detail" v-if="courseDetail">
    <div class="lesson">
      <div class="title clearfix">
        <span class="font-weight">课程列表>课程详情</span>
        <span class="title-handle pull-right" @click="backToCourseList">返回</span>
        <!--<span class="title-handle pull-right" @click="startLesson" v-if="courseDetail.step=='opened'">点击开课</span>
        <span class="title-handle pull-right" @click="startLesson" v-if="courseDetail.step=='onlive' || courseDetail.step=='repose' || courseDetail.step=='finish'">进入直播</span>-->
        <!--<span class="title-handle pull-right" @click="shareLesson(courseDetail.sn)">分享课程</span>-->
      </div>
      <div class="lesson-content clearfix">
        <div class="lesson-text">
          <h4>{{ courseDetail.title }}</h4>
          <p class="time clearfix">
            <span>课程预计耗时&nbsp;&nbsp;{{ courseDetail.plan.duration }}小时</span>
          </p>
          <div class="open clearfix">
            <div class="pull-left">
              <p>开课时间</p>
              <!--<p v-text="formatTimer(courseDetail.plan.dtm_start)"></p>-->
              <p v-text="courseDetail.plan.dtm_start"></p>
            </div>
            <div class="pull-left">
              <p>课程状态</p>
              <p v-if="courseDetail.step == 'submit'">审核中</p>
              <p v-if="courseDetail.step == 'denied'">未通过</p>
              <p v-if="courseDetail.step == 'closed'">已下架</p>
              <p v-if="courseDetail.step == 'opened'">报名中</p>
              <p v-if="courseDetail.step == 'onlive'">授课中</p>
              <p v-if="courseDetail.step == 'repose'">交流中</p>
              <p v-if="courseDetail.step == 'finish'">已结束</p>
            </div>
          </div>
          <div class="price">
            <!--<span class="pull-right gray"><i class="iconfont icon-people">&nbsp;{{ courseDetail.participants }}</i></span>-->
            <em>&#65509;{{ courseDetail.price }}</em>
          </div>
        </div>
        <div class="lesson-img">
          <img :src="courseDetail.cover" />
        </div>
      </div>
    </div>
    <div class="d-stats">
      <div>
        <p>报名人数&nbsp;<i class="iconfont icon-wenhao" @mouseover="showQues" title="包括退款人数的累计已报名人数"></i></p>
        <span>{{ courseDetail.stats | specKey('lesson.enroll.unique') }}</span>
      </div>
      <div>
        <p>课程收入&nbsp;<i class="iconfont icon-wenhao" title="不包括已退款金额的课程收益"></i></p>
        <span>&#65509;{{ courseDetail.stats | specKey('lesson.income.sum') }}</span>
      </div>
      <div>
        <p>退款人数&nbsp;<i class="iconfont icon-wenhao" title="退款成功人数"></i></p>
        <span>{{ courseDetail.stats | specKey('lesson.refund.unique') }}</span>
      </div>
      <div>
        <p>退款金额&nbsp;<i class="iconfont icon-wenhao" title="实际退款成功金额"></i></p>
        <span>&#65509;{{ courseDetail.stats | specKey('lesson.refund.sum') }}</span>
      </div>
      <div>
        <p>分成收入&nbsp;<i class="iconfont icon-wenhao" title="课程收入扣除1%服务费后按比例分成，讲师导入用户分成100%，平台导入用户分成50%"></i></p>
        <span>{{ courseDetail.stats | specKey('lesson.payoff.sum') }}</span>
      </div>
    </div>
    <div class="d-teacher">
      <!--<div class="title title-style">-->
        <!--<span>讲师介绍</span>-->
      <!--</div>-->
      <!--<div class="teacher-content clearfix">-->
        <!--<div class="teacher-img pull-left">-->
          <!--<img :src="courseDetail.teacher.avatar" />-->
        <!--</div>-->
        <!--<div class="teacher-text pull-left">-->
          <!--<div class="name" v-text="courseDetail.teacher.name"></div>-->
          <!--<div>简介</div>-->
        <!--</div>-->
      <!--</div>-->
      <div class="teacher-content clearfix">
        <div class="lesson">
          <div class="title title-style">
            <span>课程介绍</span>
          </div>
          <!--<p class="teacher-brief break-word" v-html="textFormat(courseDetail.brief)"></p>-->
          <mavon-editor class="markdown-body" v-model="courseDetail.brief" :subfield="false" :toolbarsFlag="false" :toolbars="{}" :default_open="'preview'" />
          <p></p>
        </div>
      </div>
    </div>
    <div class="d-evaluate">
      <div class="title title-style">
        <span>课程评价</span>
      </div>
      <div class="evaluate-sum">
        <div>
          <p class="score"><em>{{ courseDetail.stats | specKey('lesson.rate.avg') }}</em>&nbsp;分</p>
          <span>共&nbsp;<em>{{ courseDetail.stats | specKey('lesson.rate.count') }}</em>&nbsp;人评价</span>
        </div>
        <div class="eva-star spec">
          <div><v-star class="flipx" :mode="2" :number="5"></v-star>&nbsp;&nbsp;&nbsp;<span>{{ courseDetail.stats | specKey('lesson.rate-s5.count') }}人</span></div>
          <div><v-star class="flipx" :mode="2" :number="4"></v-star>&nbsp;&nbsp;&nbsp;<span>{{ courseDetail.stats | specKey('lesson.rate-s4.count') }}人</span></div>
          <div><v-star class="flipx" :mode="2" :number="3"></v-star>&nbsp;&nbsp;&nbsp;<span>{{ courseDetail.stats | specKey('lesson.rate-s3.count') }}人</span></div>
          <div><v-star class="flipx" :mode="2" :number="2"></v-star>&nbsp;&nbsp;&nbsp;<span>{{ courseDetail.stats | specKey('lesson.rate-s2.count') }}人</span></div>
          <div><v-star class="flipx" :mode="2" :number="1"></v-star>&nbsp;&nbsp;&nbsp;<span>{{ courseDetail.stats | specKey('lesson.rate-s1.count') }}人</span></div>
        </div>
      </div>
      <div class="eva-list">
        <evaluate-list :lists="evaList.pages"></evaluate-list>
        <v-paging :page="evaList.page" :total="evaList.totalPage" :url="curUrl" v-if="evaList && evaList.total"></v-paging>
      </div>
    </div>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';
  import vStar from '@teacher/components/star.vue';
  import evaluateList from '@teacher/components/evaluateList.vue';
  import vPaging from '@teacher/components/paging.vue';
//  import marked from 'mavon-editor/src/lib/core/markdown';
  import swal from 'sweetalert';
  // 获得config
  let liveHost = process.env.NODE_ENV == 'production' ? process.env.LIVE_HOST.replace(/\/$/,'/live') : '/live.html';

  export default{
    name: 'detail',
    components: {
      vStar,
      vPaging,
      evaluateList,
    },
    computed: {
      ...mapGetters({
        userInfo: 'getUserInfo',
        originCourseDetail: 'getCourseDetailInfo',
        evaList: 'getEvaluateList',
      })
    },
    data() {
      return {
        query: '',
        curUrl: '',
        courseDetail: null,
      }
    },
    created() {
      // 获取路由参数
      let query = this.$route.params;
      this.query = query;
      // 当前地址
      this.curUrl = `/course/${query.lesson_sn}/detail`;
      // 获取课程详情
      this.$store.dispatch('fetchCourseDetail', {lesson_sn:query.lesson_sn}).then((data) => {
        // 赋值
        this.courseDetail = { ...data };
      }, () => {
        console.log('fail');
      });
      // 获取评价列表
      this.fetchEvaList();
    },
    watch: {
      '$route': 'fetchEvaList' //切换路由，调用reloadData方法
    },
    methods: {
      startLesson() {
        // 开始课程
        // 获得开课信息
        let query = this.query;
        this.$store.dispatch('fetchOpenInfo', query).then((data) => {
          let params = `?isOwner=yes&lesson_sn=${query.lesson_sn}`;
          for(let d in data){
            params = `${params}&${d}=${data[d]}`;
          };
          //this.lessons = params;
          // 开始进入课堂
          window.location.href = `${liveHost}${params}`;
        }, (err) => {
          swal({
            title: '错误提醒',
            text: err.message,
            confirmButtonText: "知道了"
          });
        });
      },
      shareLesson(lesson_sn){
        //
        this.$router.push({ name: 'share', params: {lesson_sn:lesson_sn} });
      },
      backToCourseList() {
        this.$router.push({ name: 'list' });
      },
      fetchEvaList() {
        this.query = this.$route.params
        let query = {
          page: this.query.page,
          lesson_sn: this.query.lesson_sn,
          //limit: 2,
        };
        // 获得评价列表
        this.$store.dispatch('fetchEvaluteList', query).then((data) => {
          // console.log('获取列表成功!');
        }, (error) => {
          swal({
            title: '错误提醒',
            text: error,
            confirmButtonText: "知道了"
          });
          console.log('fail');
        });
      },
      formatTimer(value) {
        return value.replace(/^\d{4}-|:\d{2}$/g, '');
      },
      textFormat(value){
        return value.replace(/\n/g, '<br>');
      },
      showQues(){
        //console.log(1212);
      },
    },
  };
</script>

<style lang="stylus" rel="stylesheet/stylus">
  &.detail
    .title
      margin: 0 20px;
      padding: 10px 0;
      border-bottom: 1px solid #e6eaf2;
      .title-handle
        padding-left: 10px;
        color: #12b7f5;
        cursor: pointer;
    .lesson
      background: #fff;
      .lesson-content
        padding: 10px 20px;
        .lesson-img
          float: right;
          width: 400px;
          img
            width: 400px;
            height: 190px;
        .lesson-text
          position: relative;
          float: left;
          width: 532px;
          h4
            margin: 0 0 10px;
            color: #3c4a55;
            font-weight: normal;
          .time
            margin: 0;
            padding-bottom: 47px;
            color: #aaa;
            font-size: 12px;
          .open
            background: #fafafb;
            >*
              width: 50%;
              text-align: center;
              >:first-child
                margin: 30px 0 5px;
              >:last-child
                margin: 5px 0 30px;
                font-weight: bold;
          .price
            position: absolute;
            top: 0;
            right: 0;
            em
              font-style: normal;
              color: #fb617f;
            span
              font-size: 12px;
            .iconfont
              font-size: 14px;
    .d-stats, .evaluate-sum
      display: flex;
      -webkit-display: flex;
      margin-top: 10px;
      padding: 25px 0;
      background: #fff;
      justify-content: center;
      -webkit-justify-content: center;
      align-items:center;
      -webkit-align-items:center;
      >*
        -ms-flex: 1;
        -webkit-flex: 1;
        flex: 1;
        text-align: center;
        border-right: 1px solid #e6eaf2;
        .iconfont
          font-size: 18px;
          color: #12b7f5;
          vertical-align: middle;
        p
          margin: 0 0 15px;
        span
          color: #757f98;
      .eva-star
        .iconfont
          color: #fafafb;
          font-size: 16px;
          &.active
            color: #feb64d;
    .d-teacher
      margin-top: 10px;
      background: #fff;
      .teacher-content
        padding: 10px 20px;

        p {
          font-size: 12px;
        }
        .title {
          margin: 0;
        }
        .teacher-brief {
          padding: 10px;
        }
        .teacher-img
          width: 80px;
          img
            width: 80px;
            height: 80px;
            border-radius: 40px;
            -webkkit-border-radius: 40px;
      .teacher-text
        margin-left: 15px;
        .name
          margin: 0;
          padding-bottom: 15px;
          color: #12b7f5;
    .d-evaluate
      margin-top: 10px;
      background: #fff;
    .evaluate-sum
      background: #fafafb;
      .score
        em
          font-size: 42px;
      .iconfont
        color: #e4e8ef;
      .eva-star
        >div>*
          display: inline-block;
          vertical-align: middle;
    .eva-list
      padding: 10px 28px;
    .gray
      color: #aaa;
    .title-style
      border-bottom: 0 none;
      span
        padding-left: 10px;
        font-size: 14px;
        border-left: 3px solid #12b7f5;
    em
      color: #feb64d;
      font-style: normal;
    .flipx
      -moz-transform:scaleX(-1);
      -webkit-transform:scaleX(-1);
      -o-transform:scaleX(-1);
      transform:scaleX(-1);
      /*IE*/
      filter:FlipH;
    .v-note-wrapper
      .v-note-panel
        box-shadow: none;
        .v-note-show
          .v-show-content
            padding: 0;
            background: transparent;
</style>
