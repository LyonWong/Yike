<template>
  <section class="content">
    <div class="brief-info">
      <h5 v-text="series.name"></h5>
      <div class="info-detail underline clearfix">
        <span>已开{{series.scheme.opened}}/{{series.scheme.total}}节课</span>
        <span>&nbsp;</span>
      </div>
      <p class="info-price">
        <span class="price">
          &#65509;{{series.scheme.price}}
          &nbsp;<em>{{series.scheme.prime}}</em>
        </span>
        <span class="reward" @click="shareReward(series.conf.activity.href)" v-if="series.conf.activity">
          <i class="iconfont icon-lihe"></i>
          邀请有奖
        </span>
      </p>
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
    <div class="lesson-brief">
      <div class="brief-title">简介</div>
      <div class="brief-con markdown-body">
        <div class="b-text break-word color-70788c" ref="b-text" :class="{'fold':(!showBrief && briefFold)}" v-html="textFormat(series.introduce.content)">
        </div>
      </div>
    </div>
    <teacher-info class="mt20" :teacher="series.teacher"></teacher-info>
    <div class="lists mt20" ref="lists">
      <div class="lists-title">目录</div>
      <single-list :lists="series['lesson-list']"></single-list>
    </div>
    <div class="ranks mt20" @click="jumpToRank">
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
    <qr-code class="mt20"></qr-code>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';
  import qrCode from '@student/components/qrcode.vue';
  import teacherInfo from '@student/components/teacherinfo.vue';
  import singleList from '@student/components/singleList.vue';

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
    name: 'series-brief',
    components: {
      qrCode,
      teacherInfo,
      singleList,
    },
    data() {
      return {
        showGuar: false,
        showBrief: true,
        briefFold: false,
        ranksList: [],
        studentHost: (process.env.NODE_ENV=='production'?process.env.STUDENT_HOST.replace(/\/$/,''):'/student.html?'),
      };
    },
    computed: {
      ...mapGetters({
        'series': 'getSeriesDetail'
      })
    },
    created() {
      this.$store.dispatch('fetchRankSlice', {target: this.series.sn, limit: 5}).then((data)=>{
        this.ranksList = data;
      }, (err)=>{
        swal({
          title: '错误提醒',
          text: err.message,
          confirmButtonText: "知道了"
        });
      });
    },
    methods: {
      refund() {
        // 组装
        let params = {
          lesson_sn: this.series.sn,
          mode: this.series.refund_mode,
          title: this.series.title,
          price: this.series.price,
          teacher: this.series.teacher.name,
        };
        //
        this.$router.push({ name: 'refund', query: {...params} });
      },
      shareReward(href) {
        //
        window.location.href = href;
        /*window.location.href = `${this.studentHost}/promote-card?series_sn=${series_sn}`;*/
      },
      textFormat(value){
        return md.render(value);
        /*return value.replace(/\n/g, '<br>');*/
      },
      timeConversion(value) {
        if(value == 0.25){
          return '15分钟';
        }else if(value == 0.5) {
          return '30分钟';
        }else {
          return `${value}小时`;
        }
      },
      toggleFold() {
        this.showBrief = !this.showBrief;
      },
      formatTimer(value) {
        return value.replace(/^\d{4}-/g, '');
      },
      jumpToRank() {
        this.$router.push({ name: 'rank', params: {target:this.series.sn} });
      }
    },
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";

  .content.series
    .mt20
      margin-top: 20px;
    .mb20
      margin-bottom: 20px;
    .lists
      .lists-title
        padding: 30px;
        color: #333;
        background: #fff;
        border-bottom: 1px solid #d9d9d9;
        px2px(font-size, 36px);
</style>
