<template>
  <div class="v-home-index">
    <div class="profile" v-if="profile">
      <img class="cover" :src="app.linkToAssets('/img/lesson/lecture-cover.png')"/>
      <div class="info flex-col">
        <div class="avatar">
          <img :src="profile.avatar">
        </div>
        <div class="nickname font-medium" v-text="profile.name"></div>
        <div class="stats">
          <span>课程 {{stats.course}}</span>
          <span>文章 {{stats.article}}</span>
        </div>
      </div>
      <div class="about" :style="{'max-height': isFoldAout ? '2rem' : 'none'}" @click="isFoldAout=false">
        <c-section :content="{type:'markdown', text: profile.about}"></c-section>
        <div class="shading" v-show="isFoldAout"></div>
      </div>
      <a class="thome" href="/my/home">
        <span>讲师</span>
        <i class="icon-yike icon-arrow-r"></i>
      </a>
    </div>
    <!--<tabs :items="tabs" :active="activeTab" v-on:switch="switchTab"></tabs>-->
    <div class="lesson" v-show="activeTab==='lesson'">
      <div class="item" v-for="(item, index) in lessons" :key="index">
        <router-link :to="`./course?sn=${item.sn}`" class="flex-row">
          <div class="cover" v-if="item.cover">
            <img :src="`${item.cover}!cover.s`"/>
          </div>
          <div class="datum flex-item flex-col">
            <div class="title">{{item.title}}</div>
            <div class="desc desc-lesson flex-row">
              <div class="datum-tag">
                <span>报名</span>
                <span class="datum-num-enrollment">{{item.enrollment}}</span>
              </div>
              <div class="datum-tag">
                <span>￥{{item.price}}</span>
              </div>
              <div class="datum-tag" v-if="item.type==='series'">
                <span>已开</span>
                <span>{{item.progress[1]}}/{{item.progress[0]}}</span>
              </div>
            </div>
          </div>
        </router-link>
      </div>
    </div>
    <div class="article" v-show="activeTab==='article'">
      <div class="item" v-for="(item, index) in articles" :key="index">
        <router-link :to="{name: `home-${item.form}`, query: {sn: item.sn}}" class="flex-row">
          <div class="datum flex-item flex-col">
            <div class="title">
              <span v-if="item.form==='column'" class="tag-column">专栏</span>
              <span v-if="item.form==='view'" class="tag-column">课程</span>
              <span v-if="item.form==='im'" class="tag-column">直播</span>
              <span v-if="item.type==='series'" class="tag-column'">系列</span>
              {{item.title}}
            </div>
            <div class="desc desc-article flex-row">
              <div>
                <span class="datum-tag">
                  <span>￥{{item.price}}</span>
                </span>
                <span class="datum-tag" v-if="item.form==='article'">
                  <span>解锁</span>
                  <span class="datum-num-enrollment">{{item.enrollment}}</span>
                </span>
                <span class="datum-tag" v-if="item.form==='column'">
                  <span>订阅</span>
                  <span class="datum-num-enrollment">{{item.enrollment}}</span>
                </span>
                <span class="datum-tag" v-if="item.form==='article'">{{item.plan.dtm_start | date}}</span>
                <span class="datum-tag" v-if="item.form==='column'">
                  <span>更新</span>
                  <span>{{item.progress}}</span>
                </span>
              </div>
            </div>
          </div>
          <div class="cover flex-col" v-if="item.cover">
            <img :src="`${item.cover}!cover.s`"/>
          </div>
        </router-link>
      </div>
    </div>
    <div class="lessons">
      <div class="item" v-for="(item, idx) in lessons" :key="idx">
        <router-link :to="toLesson(item)" class="flex-row">
          <div class="datum flex-item flex-col">
            <div class="title" :class="{'with-cover': item.cover}">
              <span v-if="item.form==='view'" class="tag-column">课程</span>
              <span v-if="item.form==='im'" class="tag-column">直播</span>
              <span v-if="item.form==='article'" class="tag-column">文章</span>
              <span v-if="item.form==='column'" class="tag-column">专栏</span>
              <span v-if="item.type==='series'" class="tag-column">系列</span>
              <span>{{item.title}}</span>
            </div>
            <div class="desc desc-article flex-row">
              <div>
                <span class="datum-tag">
                  <span>￥{{item.price}}</span>
                </span>
                <span class="datum-tag" v-if="item.form==='article'">
                  <span>解锁</span>
                  <span class="datum-num-enrollment">{{item.enrollment}}</span>
                </span>
                <span class="datum-tag" v-else-if="item.form==='column'">
                  <span>订阅</span>
                  <span class="datum-num-enrollment">{{item.enrollment}}</span>
                </span>
                <span class="datum-tag" v-else>
                  <span>报名</span>
                  <span class="datum-num-enrollment">{{item.enrollment}}</span>
                </span>
                <span class="datum-tag" v-if="item.form==='column'">
                  <span>更新</span>
                  <span>{{item.progress}}</span>
                </span>
                <span class="datum-tag" v-else-if="item.type==='series'">
                  <span>开课</span>
                  <span>{{item.progress[1]}}/{{item.progress[0]}}</span>
                </span>
                <span class="datum-tag" v-else>{{item.plan.dtm_start | date}}</span>
              </div>
            </div>
          </div>
          <div class="cover flex-col" v-if="item.cover">
            <img :src="`${item.cover}!cover.s`"/>
          </div>
        </router-link>
      </div>
    </div>
    <div class="suggest" v-show="activeTab==='suggest'"></div>
    <home-navigation :home="home"></home-navigation>
  </div>
</template>

<script>
  import HomeNavigation from "../components/Navigation";
  const CSection = r => require.ensure([], () => r(require('../../components/section/index')), 'common')
  const Tabs = r => require.ensure([], () => r(require('../../components/Tabs')), 'common')
  export default {
    name: 'home-index',
    components: {CSection, Tabs, HomeNavigation},
    data() {
      return {
        home: this.$route.params.home,
        profile: null,
        user: null,
        lessons: [],
        articles: [],
        tabs: [],
        stats: {},
        activeTab: null,
        isFoldAout: true,
        statusDict: {
          'opened': '直播报名',
          'onlive': '直播中',
          'repose': '交流中',
          'finish': '直播回放'
        }
      }
    },
    created() {
      // 讲师资料
      this.api.get('/api/teacher-datum', {
        usn: this.home
      }).then((res) => {
        this.profile = res.data
        this.stats = {
          course: res.data.stats.lesson + res.data.stats.series,
          article: res.data.stats.article + res.data.stats.column
        }
      })
      // 讲师课程
      this.api.get('/api/teacher-lesson', {
        usn: this.home
      }).then((res) => {
        this.lessons = res.data
        /*
        for (let item of res.data) {
          if (item.type === 'series') {
            this.lessons.push(item)
            continue;
          }
          switch (item.form) {
            case 'im':
            case 'view':
              this.lessons.push(item)
              break;
            case 'article':
            case 'column':
              this.articles.push(item)
              break;
            default:
              this.lessons.push(item)
              break;
          }
        }
        if (this.lessons.length && 0) {
          this.tabs.push({key: 'lesson', name: '课程'})
        }
        if (this.articles.length) {
          this.tabs.push({key: 'article', name: '文章'})
        }
        this.activeTab = location.hash ? location.hash.substr(1) : this.tabs[0].key
        */
        // 个人信息
        // this.api.get('/api/user-profile').then((res) => {
        //   this.user = res.data
        // })
      })
    },
    methods: {
      switchTab(key) {
        this.activeTab = key
        location.hash = key
      },
      toLesson(item) {
        let route = {
          query: {
            sn: item.sn
          }
        }
        if (item.type === 'lesson') {
          if (item.form === 'im' || item.form === 'view') {
            route.name = 'home-lesson'
          } else {
            route.name = `home-${item.form}`
          }
        }
        if (item.type === 'series') {
          route.name = 'home-series'
        }
        return route
      }
    },
    computed: {},
    filters: {
      date(time) {
        return time ? time.split(' ')[0] : null
      }
    }
  }
</script>

<style scoped>
  .v-home-index {
    padding-bottom: 1rem;
    background: #f0eff5;
  }

  .c-tabs {
    position: sticky;
    top: 0;
  }

  .profile {
    position: relative;
  }

  .profile .cover {
    width: 100%;
    height: 3rem;
  }
  .profile .info {
    position: absolute;
    top: .3rem;
    left: .3rem;
    align-items: flex-start;
  }

  .avatar {
    width: 1.28rem;
    height: 1.28rem;
    border: .01rem solid #c1c1c1;
    border-radius: 50%;
  }

  .avatar > img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
  }

  .nickname {
    padding-top: .2rem;
    text-align: center;
    color: #fff;
    font-size: .32rem;
    font-weight: bold;
  }

  .about {
    overflow: hidden;
    position: relative;
    margin-bottom: .1rem;
    background: #fff;
  }

  .about .c-section {
    padding: .3rem;
  }

  .about .shading {
    position: absolute;
    bottom: 0;
    height: 1rem;
    width: 100%;
    background: linear-gradient(to bottom, transparent, #fff);
  }

  .item {
    background: #fff;
    color: #0D0D0D;
    border-bottom: 1px solid #ccc;
    padding: .55rem .3rem .45rem .3rem;
  }
  .item:last-child {
    border: none;
  }

  .item > a {
    height: .96rem;
  }

  .title {
    display:flex;
    align-items: center;
    color: #222;
    font-size: .3rem;
    /*justify-content: space-between;*/
  }

  .title > span {
    font-size: .32rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .title.with-cover > span {
    max-width: 4rem;
  }

  .title > i {
    color: #ccc;
    font-size: .32rem;
  }

  .desc {
    font-size: .24rem;
    justify-content: flex-start;
    color: #999;
  }

  .item .cover {
    height: 100%;
  }

  .item .cover img {
    width: 1.8rem;
    height: 100%;
  }

  .datum {
    justify-content: space-between;
    height: 100%;
  }
  .lesson .datum {
    margin-left: 1em;
  }
  .article .datum {
    margin-right: 1em;
  }

  .datum > div {
    width: 100%;
  }

  .datum-tag {
    padding-right: 1em;
  }

  .tag-column {
    background: #2F57DA;
    color: #fff;
    font-size: .24rem !important;
    border-radius: .04rem;
    padding: 0 .05rem;
    margin-right: 0.6em;
  }
  .stats {
    margin-top: .2rem;
    color: #fff;
    font-size: .26rem;
  }
  .stats > span {
    padding: 0 .3rem;
    border-left: 1px solid #b3b3b3;
  }
  .stats > span:first-child {
    padding-left: 0;
    border-left: none;
  }
  .profile .thome {
    position: absolute;
    right: .3rem;
    top: 2.1rem;
    color: #2F57DA;
    font-size: .26rem;
    background: rgba(255,255,255,0.8);
    border-radius: .08rem;
    padding: .16rem .2rem;
  }
  .profile .thome > i {
    font-size: .26rem;
    color: #2F57DA;
  }

</style>
