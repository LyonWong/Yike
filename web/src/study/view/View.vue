<template>
  <div class="c-study-view">
    <div class="profile" v-if="profile">
      <div class="title">
        <!--<a class="icon-yike icon-enrolled-course" href="/my/lesson#tab:done"></a>-->
        <a :href="`/lesson/detail/${sn}`">{{profile.title}}</a>
      </div>
      <div class="desc">
        <div>
          <a :href="`/user/teacher?usn=${profile.teacher.sn}`">{{profile.teacher.name}}</a>
          <span>{{profile.plan.dtm_start}}</span>
        </div>
        <a v-if="individual.subscribed === true" href="/lesson/" class="icon-yike icon-home">首页</a>
        <a v-else href="https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=MzUyMDAzNDYxNw==#wechat_redirect">关注公众号</a>
      </div>
    </div>
    <div class="content">
      <div class="section" v-for="(row,idx) in records" :key="idx">
        <section-markdown v-if="row.content.type==='markdown'" :content="row.content"></section-markdown>
        <section-image v-if="row.content.type==='image'" :content="row.content"></section-image>
        <section-audio v-if="row.content.type==='audio'" :content="row.content"></section-audio>
        <section-video v-if="row.content.type==='video'" :content="row.content"></section-video>
      </div>
    </div>
    <div class="relative border" v-if="profile && profile.series && relative.length">
      <block title="相关课程">
        <a slot="more" :href="`/lesson/series/${profile.series.sn}?tab=catalog`">
          <span>查看系列</span>
          <i class="icon-yike icon-arrow-r text-desc"></i>
        </a>
        <lesson-cell :lesson="lessonStatus(item)" action='study' v-for="(item,index) in relative" :key="index"></lesson-cell>
      </block>
    </div>
    <div class="board">
      <div class="board-head flex-row">
        <div class="board-tabs">
          <div class="board-tab font-bold">交流讨论</div>
        </div>
        <div class="board-write" @click="writeBoard('argue')">写留言</div>
      </div>
      <div class="board-body">
        <board-list ref="argue" :sn="sn" type="argue"></board-list>
      </div>
    </div>
  </div>
</template>

<script>
  import SectionMarkdown from "../components/section/Markdown";
  import SectionImage from "../components/section/Image";
  import SectionAudio from "../components/section/Audio";
  import SectionVideo from "../components/section/Video";
  import Block from "../../components/Block";
  import LessonCell from "../../lesson/components/unit/LessonCell";
  import BoardList from "../components/board/List";
  export default {
    name: 'c-study-view',
    components: {BoardList, LessonCell, Block, SectionVideo, SectionAudio, SectionImage, SectionMarkdown},
    data() {
      return {
        sn: null,
        profile: null,
        individual: {},
        relative: [],
        records: {},
        iSeries: null
      }
    },
    created() {
      this.sn = this.$route.params.sn || this.$route.query.sn
      // 检查听课权限
      this.api.post('/api/study-access', {
        sn: this.sn
      }).then((res) => {
      }, (r) => {
        if (r.error === '0.1') {
          this.api.onErrorSign()
        } else {
          alert('您暂未拥有听课权限，请前往详情页报名')
          location.href = `/lesson/detail/${this.sn}`
        }
      })
      // 获取课程概要
      this.api.get('/api/lesson-profile', {
        sn: this.sn
      }).then((res) => {
        this.profile = res.data
        if (this.profile.series) {
          this.api.get('/api/individual-series', {
            sn: this.profile.series.sn
          }).then((r) => {
            this.iSeries = r.data
          })
        }
      })
      // 获取个人情况
      this.api.get('/api/individual-lesson', {
        sn: this.sn
      }).then((res) => {
        this.individual = res.data
      }, this.api.onErrorSign)
      /*
       * 获取课程内容
       * /study/preview 通过 /api/study-slice-preview 获取备课预览内容
       * /study/view 通过 /api/study-slice-view 获取阅读模式内容
       */
      this.api.get(`/api/study-slice-${this.$route.name}`, {
        sn: this.sn,
        limit: -1
      }).then( (res) => {
        this.records = res.data
      })
      // 获取相关课程
      this.api.get('/api/lesson-relative', {
        sn: this.sn
      }).then((res) => {
        this.relative = res.data
      })
    },
    methods: {
      lessonStatus(lesson) {
        if (this.iSeries && this.iSeries.events) {
          lesson.status = this.iSeries.events[lesson.sn] || lesson.status
        }
        return lesson
      },
      writeBoard(type) {
        this.$refs[type].onPost()
      }
    }
  }
</script>

<style scoped>
  .c-study-view {
    background: #fff;
    min-height: 100%;
    color: #333;
  }
  .bottom {
    border-top: 1px solid #ccc;
    padding: .3rem;
  }
  .profile {
    padding: .3rem;
  }
  .profile a {
    font-size: 1.4em;
    line-height: 1.4;
    color: #333;
  }
  .profile .title .icon-enrolled-course {
    color: #2F57DA;
  }
  .profile .desc {
    margin-top: 1em;
    color: #ccc;
    display: flex;
    justify-content: space-between;
  }
  .profile .desc a {
    font-size: 1em;
    color: #576b95;
    margin-right: 1em;
  }
  .content {
    padding: .3rem .5rem;
  }
  .section {
    margin: .1rem 0;
  }
  .board-head {
    justify-content: space-between;
    height: 1rem;
    line-height: 1rem;
    padding: 0 .3rem;
    border-top: .2rem solid #F2F2F2;
    border-bottom: 1px solid #ccc;
  }
  .board-tab {
    font-size:.3rem;
  }
  .board-write {
    color: #576B95;
    font-size: .3rem;
  }
  .board-body {
    padding: 0 .3rem;
  }

  #return-to-list {
    color: #aaa;
  }
</style>
