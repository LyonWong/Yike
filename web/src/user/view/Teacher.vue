<template>
  <div class="c-lecturer">
    <div class="profile">
      <img class="cover" :src="app.linkToAssets('/img/lesson/lecture-cover.png')"/>
      <div class="datum flex-col">
        <div class="avatar">
          <img :src="datum.avatar"/>
        </div>
        <div class="name">
          {{datum.name}}
        </div>
        <!--<div class="follow" @click="follow">-->
          <!--<div class="follow-yes" v-if="datum.followed">已关注</div>-->
          <!--<div class="follow-no" v-else>+ 关注</div>-->
        <!--</div>-->
      </div>
    </div>
    <tabs :items="tabs" :active="activeTab" @switch="switchTab"></tabs>
    <block title="开设课程" v-if="activeTab==='lesson'">
      <course-cell v-for="(profile, index) in lesson" :key="index" :profile="profile"></course-cell>
    </block>
    <div class="about markdown" v-if="activeTab==='about'" v-html="markdown(datum.about)"></div>
  </div>
</template>

<script>
  import Block from "../../components/Block";
  import CourseCell from "../components/unit/CourseCell"
  import Tabs from "../../components/Tabs";
  const markdown = require('markdown-it')({html: true, breaks: true})
  export default {
    name: 'c-lecturer',
    components: {Tabs, Block, CourseCell},
    data() {
      return {
        usn: null,
        datum: {},
        lesson: [],
        tabs: [
          {key: 'lesson', name: '课程'},
          {key: 'about', name: '介绍'}
        ],
        activeTab: 'lesson'
      }
    },
    created() {
      this.usn = this.$route.query.usn
      /*
      this.api.get('/api/teacher', {
        'methods': ['datum', 'lesson'],
        'usn': this.usn
      }).then((res) => {
        this.datum = res.data.datum.data
        this.lesson = res.data.lesson.data
      })
      */
      this.api.get('/api/teacher-datum', {
        usn: this.usn
      }).then((res) => {
        this.datum = res.data
      })
      this.api.get('/api/teacher-lesson', {
        usn: this.usn
      }).then((res) => {
        this.lesson = res.data
      })
    },
    methods: {
      follow() {
        this.api.post('/api/follow-teacher', {
          usn: this.usn
        }).then((res) => {
          this.datum.followed = res.data.isFollow
        })
      },
      switchTab(key) {
        console.log(key)
        this.activeTab = key
      },
      markdown(text) {
        return markdown.render(text || '')
      }
    }
  }
</script>

<style scoped>
  .profile {
    position: relative;
  }
  .profile .cover {
    width: 100%;
  }
  .profile .datum {
    z-index: 9;
  }
  .datum {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
  }
  .datum .avatar img{
    /*position: absolute;*/
    border-radius: 50%;
    width: 1.27rem;
    height: 1.27rem;
    /*top: .3rem;*/
    /*left: .3rem;*/
  }
  .datum .name {
    z-index: 99;
    padding: .1rem;
    /*position: absolute;*/
    color: #fff;
    /*left: .3rem;*/
    /*top: 1.84rem;*/
    font-size: .34rem;
  }
  .datum .follow {
    /*position: absolute;*/
    /*right: .3rem;*/
    /*top: .7rem;*/
    font-size: .24rem;
  }
  .follow-no{
    color: #fff;
    border: 1px solid #fff;
    border-radius: .24rem;
    padding: .06rem .16rem;
  }
  .follow-yes {
    color: #2A4EC4;
    background: #E2E9FF;
    border: 1px solid #E2E9FF;
    padding: .06rem .16rem;
    border-radius: .24rem;
  }
  .about {
    padding: .3rem .4rem;
    background: #fff;
    color: #333;
    text-align: justify;
    word-break: break-all;
  }
</style>
