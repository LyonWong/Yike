<template>
  <section class="course-brief">
    <div v-if="course">
      <div class="brief-info">
        <h5 v-text="course.title"></h5>
        <div class="info-series" v-if="course.categoryInfo">
          <span @click="enterSeries(course.category)">
            所属系列 : <span class="info-name">{{course.categoryInfo.name}}</span>
          </span>
        </div>
        <div class="info-detail clearfix">
          <!--<span><em>{{`${course.plan.dtm_now}#${course.plan.dtm_start}` | moment}}</em>&nbsp;开课</span>-->
          <!--<span>开课时间 : {{formatTimer(course.plan.dtm_start)}}</span>-->
          <span>开课时间 : {{course.plan.dtm_start}}</span>
          <span><i class="iconfont icon-jishiqi"></i>&nbsp;{{this.timeConversion(course.plan.duration)}}</span>
        </div>
        <div class="info-detail clearfix">
          <span v-if="course.step == 'opened'">课程状态 : 报名中</span>
          <span v-if="course.step == 'onlive'">课程状态 : 授课中</span>
          <span v-if="course.step == 'repose'">课程状态 : 交流中</span>
          <span v-if="course.step == 'finish'">课程状态 : 可回放</span>
          <span v-if="course.step == 'closed'">课程状态 : 已下架</span>
          <span><i class="iconfont icon-xueyuan1"></i>&nbsp;{{course.participants}}</span>
        </div>
        <p class="info-price">
          <span v-if="course.price" class="price">&#65509;{{course.price}}&nbsp;<em></em></span>
          <span v-if="course.price == 0" class="price-free">免费&nbsp;<em></em></span>
          <span class="info-right">
            <span v-if="course.refund_mode && !course.refund_info && course.price != 0 && isEnroll != 'refund' && isEnroll != 'browse' && isEnroll != 'reset'" class="g-refund" @click="refund">申请退款</span>
            &nbsp;
            <span class="reward" @click="shareReward(course.conf.activity.href)" v-if="course.conf.activity">
              <i class="iconfont icon-lihe"></i>
              邀请有奖
            </span>
          </span>
          <!--<span class="guarantee" @click="showGuar = !showGuar" v-bind:class="{ 'g-unfold': showGuar }">课程保障</span>-->
        </p>
        <!--<div class="guarantee-text" v-show="showGuar">-->
          <!--<dl>-->
            <!--<dt>永久回放：</dt>-->
            <!--<dd>已购买课程可永久在线回放</dd>-->
          <!--</dl>-->
          <!--<dl>-->
            <!--<dt>开课通知：</dt>-->
            <!--<dd>关注【易灵微课】公众号，开课自动提醒</dd>-->
          <!--</dl>-->
          <!--<dl>-->
            <!--<dt>不满意可退款：</dt>-->
            <!--<dd>听课1小时内，可无条件退款</dd>-->
          <!--</dl>-->
          <!--<dl>-->
            <!--<dt> 未听课自动退款：</dt>-->
            <!--<dd>课程结束48小时，未听课自动退款</dd>-->
          <!--</dl>-->
        <!--</div>-->
      </div>
      <div class="lesson-brief mb20">
        <div class="brief-title">课程保障</div>
        <div class="brief-con">
          <div class="guarantee-text">
            <dl>
              <dt>永久回放：</dt>
              <dd>已购买课程可永久在线回放</dd>
            </dl>
            <dl>
              <dt>开课通知：</dt>
              <dd>关注【易灵微课】公众号，开课自动提醒</dd>
            </dl>
            <dl>
              <dt>不满意可退款：</dt>
              <dd>进入课堂起1小时内无条件退款</dd>
            </dl>
            <dl>
              <dt> 未听课自动退款：</dt>
              <dd>课程结束7天后，未听课自动退款</dd>
            </dl>
          </div>
        </div>
      </div>
      <div class="lesson-brief mb20">
        <div class="brief-title">简介</div>
        <div class="brief-con">
          <div class="b-text break-word color-70788c markdown-body" ref="b-text" :class="{'fold':(!showBrief && briefFold)}" v-html="textFormat(course.brief)">
          </div>
          <!--<span class="unfold" @click="toggleFold" v-if="briefFold">-->
            <!--<i class="iconfont icon-chevron-down" v-if="!showBrief"></i>-->
            <!--<i class="iconfont icon-chevron-up" v-if="showBrief"></i>-->
            <!--&lt;!&ndash;{{showBrief?'收起':'展开'}}&ndash;&gt;-->
          <!--</span>-->
        </div>
      </div>
      <!--评价-->
      <div class="evaluate-area">
        <div class="area-title clearfix" @click="enterEvaPage">
          <span class="e-score">
            评分:
            <span>{{evaluateData.score}}</span>
          </span>
          <span class="e-enter">
            已有{{evaluateData.rated}}人评价
            <i class="iconfont icon-open-right"></i>
          </span>
        </div>
        <evaluate-list :lists="evaluateList" v-if="evaluateList.length"></evaluate-list>
      </div>
      <teacher-info class="mb20" :teacher="course.teacher"></teacher-info>
      <!--所属系列课-->
      <div class="series" v-if="course.categoryInfo">
        <div class="series-title" @click="enterSeries(course.category)">
          所属系列课：{{course.categoryInfo.name}}
          <span class="arrow"></span>
        </div>
        <single-list :lists="course.series" v-if="course.series.length"></single-list>
      </div>
      <!--达人榜-->
      <div class="ranks" @click="jumpToRank">
        <div class="ranks-wrap clearfix">
          <div class="pull-left">邀请达人榜</div>
          <div class="pull-right">
            <div class="ranks-img">
              <span v-for="rank in ranksList">
                <img :src="rank.avatar" />
              </span>
            </div>
          </div>
          <span class="arrow"></span>
        </div>
      </div>
      <qr-code></qr-code>
      <!--<qr-code :class="{'is-border':!course.categoryInfo}"></qr-code>-->
      <!--<div class="lesson-more">-->
        <!--<div @click="backToHome">查看更多课程</div>-->
        <!--<div @click="becomeTeacher">注册成为讲师</div>-->
        <!--<div @click="spreadCourse">参与优惠活动</div>-->
      <!--</div>-->
    </div>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';
  import teacherInfo from '@student/components/teacherinfo.vue';
  import singleList from '@student/components/singleList.vue';
  import qrCode from '@student/components/qrcode.vue';
  import evaluateList from '@student/components/evaluateList.vue';
  /*import MarkdownIt from 'markdown-it';*/
  // default mode
  const markdown_config = {
    html: true,        // Enable HTML tags in source
    xhtmlOut: true,        // Use '/' to close single tags (<br />).
    breaks: true,        // Convert '\n' in paragraphs into <br>
    langPrefix: 'language-markdown',  // CSS language prefix for fenced blocks. Can be
    linkify: false,        // 自动识别url
    typographer: true,
    quotes: '“”‘’',
    /*highlight: function (str, lang) {
      return '<pre class="hljs"><code class="' + lang + '">' + markdown.utils.escapeHtml(str) + '</code></pre>';
    }*/
  };
  //
  const md = require('markdown-it')(markdown_config);

  export default{
    name: 'course-brief',
    components: {
      evaluateList,
      teacherInfo,
      singleList,
      qrCode,
    },
    props: {
      isEnroll: {
        type: null
      },
    },
    data() {
      return {
        course: null,
        showGuar: false,
        showBrief: true,
        briefFold: false,
        evaluateList: [],
        evaluateData: {score:0,rated:0},
        ranksList: [],
        studentHost: (process.env.NODE_ENV=='production'?process.env.STUDENT_HOST:'/student.html?'),
        signUpHost:(process.env.TEACHER_HOST ? process.env.TEACHER_HOST.replace(/\/$/,'') : 'https://teacher.sandbox.yike.fm/'),
      };
    },
    computed: {
      ...mapGetters({
        'courseDetail': 'getCourseDetailInfo'
      })
    },
    created() {
      // 获取路由参数
      let query = this.$route.query;
      this.course = this.courseDetail[query.lesson_sn];
      // 未购买
      if(this.course.event == 'browse' || this.course.event == 'reset'){
        this.showGuar = true;
      }
      // 评价页获取数据
      let opt = {
        lesson_sn: query.lesson_sn,
        cursor: null,
        limit: 3,
      };
      this.$store.dispatch('fetchEvaluteList', opt).then((data)=>{
        this.evaluateList = [...data];
      }, (err)=>{
        swal({
          title: '错误提醒',
          text: err.message,
          confirmButtonText: "知道了"
        });
      });
      // 评价页总分数据
      this.$store.dispatch('fetchEvaluteTotal', {lesson_sn: query.lesson_sn}).then((data)=>{
        this.evaluateData.score = data.avg_score || 0;
        this.evaluateData.rated = data.rated_count || 0;
      }, (err)=>{
        swal({
          title: '错误提醒',
          text: err.message,
          confirmButtonText: "知道了"
        });
      });
      // 获取排行榜
      this.$store.dispatch('fetchRankSlice', {target: query.lesson_sn, limit: 5}).then((data)=>{
        this.ranksList = data;
      }, (err)=>{
        swal({
          title: '错误提醒',
          text: err.message,
          confirmButtonText: "知道了"
        });
      });
    },
    mounted() {
      this.briefFold = (this.$refs['b-text'].offsetHeight>191?true:false);
    },
    methods: {
      toggleFold() {
        this.showBrief = !this.showBrief;
      },
      formatTimer(value) {
        return value.replace(/^\d{4}-/g, '');
      },
      textFormat(value){
        return md.render(value);
        /*return value.replace(/\n/g, '<br>');*/
      },
      shareReward(href) {
        window.location.href = href;
      },
      refund() {
        // 组装
        let params = {
          lesson_sn: this.course.sn,
          mode: this.course.refund_mode,
          title: this.course.title,
          price: this.course.price,
          teacher: this.course.teacher.name,
        };
        //
        this.$router.push({ name: 'refund', query: {...params} });
      },
      enterEvaPage() {
        // 进入评论页
        this.$router.push({ name: 'messageTask', params:{lesson_sn:this.course.sn}, query:{lesson_sn:this.course.sn} });
      },
      /*backToHome() {
        this.$router.push({ name: 'list' });
      },
      becomeTeacher() {
        // 跳转
        window.location.href = `${this.signUpHost}/sign-up`;
      },
      spreadCourse() {
        //
        window.location.href = `${this.studentHost}promote?target_sn=${this.course.sn}`;
      },*/
      timeConversion(value) {
        if(value == 0.25){
          return '15分钟';
        }else if(value == 0.5) {
          return '30分钟';
        }else {
          return `${value}小时`;
        }
      },
      enterSeries(series_sn){
        this.$router.push({ name: 'seriesBrief', params: {series_sn: series_sn} });
      },
      jumpToRank() {
        //
        this.$router.push({ name: 'rank', params: {target:this.course.sn} });
      }
    },
  };
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";

  .course-brief {
    .mt20 {
      margin-top: 20px;
    }
    .mb20 {
      margin-bottom: 20px;
    }
  }

  .content.detail .brief-info p.info-price {
    .info-right {
      position: absolute;
      right: 0;
      .g-refund, .reward {
        position: relative;
        top: 0;
        right: 0;
        vertical-align: middle;
      }
    }
  }
  .content.detail .markdown-body em{
    color: #666;
    font-style: italic;
  }
</style>
