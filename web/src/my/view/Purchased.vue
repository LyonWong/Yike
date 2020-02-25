<template>
  <div class="c-my-lesson">
    <Tabs id="tabs" :items="tabs" :active="activeTab" v-on:switch="switchTab">
    </Tabs>
    <div id="list">
      <div class="item" v-for="(item, index) in lists[activeTab]" :key="index">
        <div class="course flex-row">
          <div class="cover btn flex-col" @click="linkToDetail(item.sn, item.type)">
            <img :src="item.cover ? `${item.cover}!cover.s` : app.linkToAssets('/img/lesson/default-cover.png')"/>
            <span class="form" v-if="item.type==='lesson'">{{dict[item.form]}}</span>
            <span class="form" v-if="item.type==='series'">系列</span>
          </div>
          <div class="datum flex-col">
            <div class="title btn flex-row" @click="linkToDetail(item)">
              <span>{{item.title}}</span>
              <i class="icon-yike icon-arrow-r"></i>
            </div>
            <div class="desc-lesson flex-row" v-if="item.type==='lesson'">
              <div v-if="item.form==='column'">
                <span v-for="(tag,i) in tags" :key="i" v-if="item.events[tag.key]" class="datum-tag">
                  <span>{{tag.name}}</span>
                  <span :class="`datum-num-${tag.key}`">{{item.events[tag.key]}}</span>
                </span>
              </div>
              <div v-else>
                <span v-if="item.step === 'opened'" class="datum-tag">未开课</span>
                <span v-if="item.step === 'closed'" class="datum-tag">已关闭</span>
              </div>
              <div class="btn btn-go" @click="linkToLesson(item)" v-if="['live', 'repose', 'finish'].indexOf(item.step) !== -1 && ['im', 'view'].indexOf(item.form) !== -1">课堂</div>
            </div>
            <div class="desc-series flex-row" v-if="item.type==='series'">
              <div>
                <span v-for="(tag,i) in tags" :key="i" v-if="item.events[tag.key]" class="datum-tag">
                  <span>{{tag.name}}</span>
                  <span :class="`datum-num-${tag.key}`">{{item.events[tag.key]}}</span>
                </span>
              </div>
              <div @click="toggle(item.sn)">
                <div v-show="item.sn === subshow" class="flex-row btn btn-toggle">
                  <span>收起</span>
                  <!--<i class="icon-yike icon-arrow-t"></i>-->
                </div>
                <div v-show="item.sn !== subshow" class="flex-row btn btn-toggle">
                  <span>展开</span>
                  <!--<i class="icon-yike icon-arrow-b"></i>-->
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="title flex-row btn" @click="linkToDetail(item.sn, item.type)" v-if="0">
          <span class="font-bold">{{item.title}}</span>
          <i class="icon-yike icon-arrow-r"></i>
        </div>
        <div class="sublist" v-if="subshow === item.sn">
          <div class="btn sub-item flex-row" v-for="(row,idx) in sublist[item.sn]" :key="idx"
           :class="`event-${row.event}`" @click="linkToLesson(row)">
            <div class="sub-title">
              <span class="label label-refund" v-if="row.event === 'refund'">已退款</span>
              <span class="label label-reset" v-if="row.event === 'reset'">可复购</span>
              <span class="label label-pending" v-if="row.event === 'enroll' && row.step === 'opened'">未开课</span>
              <span>{{row.title}}</span>
            </div>
            <span v-if="row.event === 'access'" class="icon-yike icon-ok i-access"></span>
            <span v-if="row.event === 'enroll' && row.step !== 'opened'" class="i-enroll">●</span>
          </div>
        </div>
        <div class="toggle flex-row" v-if="0 && item.type==='series'" @click="toggle(item.sn)">
          <div class="btn btn-toggle flex-row" @click="toggle(item.sn)" v-if="0">
            点击{{subshow === item.sn ? '收起' : '展开'}}({{item.events[activeTab] || 0}}/{{item.total}})
          </div>
          <div class="btn bar-toggle flex-row">
            <!--<span v-if="item.events.access">· 已学{{item.events.access}}</span>-->
            <!--<span v-if="item.events.enroll">· 未学{{item.events.enroll}}</span>-->
            <!--<span v-if="item.events.refund">· 退款{{item.events.refund}}</span>-->
            <span v-for="(tag,i) in tags" :key="i" v-if="item.events[tag.key]">· {{tag.name}}{{item.events[tag.key]}}</span>
            <span class="icon-yike" :class="item.sn === subshow ? 'icon-arrow-t' : 'icon-arrow-b'"></span>
          </div>
        </div>
      </div>
    </div>
    <!--来自讲师店铺的，使用讲师导航栏-->
    <home-navigation v-if="home" :home="home"></home-navigation>
    <navigation v-else></navigation>
  </div>
</template>

<script>
  import MyLessonCell from "../components/LessonCell";
  import Tabs from "../../components/Tabs";
  import Navigation from "../../components/Navigation";
  import qs from "qs";
  import HomeNavigation from "../../home/components/Navigation";
  export default {
    name: 'my-purchased',
    components: {HomeNavigation, Navigation, Tabs, MyLessonCell},
    props: ['home'],
    data() {
      return {
        tabs: [
          {'key': 'todo', name: '未完成'},
          {'key': 'done', name: '已完成'},
          {'key': 'quit', name: '已退款'}
        ],
        tags: [
          {'key': 'enroll', name: '未学习'},
          {'key': 'access', name: '已学习'},
          {'key': 'refund', name: '已退款'},
          {'key': 'reset', name: '可复购'}
        ],
        dict: {
          'try': '试讲',
          'im': '直播',
          'im_hide': '直播',
          'view': '图文',
          'article': '文章',
          'column': '专栏'
        },
        activeTab: 'done',
        list: [],
        lists: {
          todo: [],
          done: [],
          quit: []
        },
        sublist: {},
        subshow: null
      }
    },
    created() {
      let tab = location.hash.match(/tab:(\w+)/)
      if (tab) {
         this.switchTab(tab[1])
      }
      this.api.get('/api/my-lesson').then((res) => {
        for (let item of res.data) {
          if (item.events.enroll) {
            if (!tab) {
              this.activeTab = 'todo'
            }
            this.lists.todo.push(item)
          }
          if (item.events.access === item.total) {
            this.lists.done.push(item)
          }
          if (item.events.refund || item.events.reset) {
            this.lists.quit.push(item)
          }
          // 此处获取不到usn，但暂时用来标记用户已登录
          window.localStorage.setItem('usn', res.data.sn)
        }
      }, this.api.onErrorSign)
    },
    methods: {
      switchTab(key) {
        this.activeTab = key
        location.hash = `tab:${key}`
      },
      toggle(sn) {
        if (this.sublist[sn]) {
          this.subshow = (this.subshow === sn) ? null : sn
        } else {
          this.api.get('/api/my-lesson-sub', {
            'sn': sn
          }).then((res) => {
            this.sublist[sn] = res.data
            this.subshow = sn
          })
        }
      },
      linkToDetail(item) {
        if (item.type === 'lesson') {
          switch (item.form) {
            case 'article':
              location.href = this.home ? `/home/${this.home}/article?sn=${item.sn}` : `/study/article?sn=${item.sn}`
              break;
            case 'column':
              location.href = this.home ? `/home/${this.home}/column?sn=${item.sn}` : `/lesson/column/${item.sn}`
              break;
            default:
              location.href = this.home ? `/home/${this.home}/course?sn=${item.sn}` : `/lesson/course/${item.sn}`
              break;
          }
        }
        if (item.type === 'series') {
          location.href = this.home ? `/home/${this.home}/series?sn=${item.sn}` : `/lesson/series/${item.sn}`
        }
      },
      linkToLesson(lesson) {
        if (lesson.event === 'access' || (lesson.event === 'enroll' && lesson.step !== 'opened')) {
          switch (lesson.form) {
            case 'im':
            case 'im_hide':
              let query = qs.stringify({
                isOwner: 'no',
                teacherEnter: 'yes',
                lesson_sn: lesson.sn,
                teach: `${lesson.sn}-T`,
                discuss: `${lesson.sn}-D`
              })
              window.location.href = `/live?${query}`
              break;
            case 'view':
              window.location.href = this.home ? `/home/${this.home}/view?sn=${lesson.sn}` : `/study/view/${lesson.sn}`
              break;
            case 'article':
              location.href = this.home ? `/home/${this.home}/article?sn=${lesson.sn}` : `/study/article/${lesson.sn}`
              break;
            case 'column':
              location.href = this.home ? `/home/${this.home}/column?sn=${lesson.sn}` : `/lesson/column/${lesson.sn}`
              break;
            default:
              alert('未知课程形式')
          }
        } else {
          window.location.href = this.home ? `/home/${this.home}/lesson?sn=${lesson.sn}` : `/lesson/detail?sn=${lesson.sn}&mark=${this.$route.query.mark|''}`
        }
      }
    }
  }
</script>

<style>
  .tab > span {
    font-size: .3rem !important;
  }
</style>

<style scoped>
  #tabs {
    z-index: 9;
    position: sticky;
    top: 0;
    height: .8rem;
    box-shadow: 0 0 5px rgba(0,0,0,0.1);
  }
  #list {
    padding-bottom: 1rem;
    background: #f0eff5;
  }
  .item {
    /*min-height: 2rem;*/
    margin-top: .2rem;
    background: #fff;
    color: #0D0D0D;
  }
  .course {
    padding: .3rem;
    justify-content: space-between;
  }
  .cover {
    position: relative;
  }
  .cover img {
    width: 2.1rem;
    height: 1.12rem;
  }
  .cover .form {
    position: absolute;
    bottom: 0;
    left: 0;
    padding: .04rem .08rem;
    font-size: .24rem;
    background: #2F57DA;
    color: #fff
  }
  .datum {
    /*background: #f00;*/
    width: 4.6rem;
    height: 1.12rem;
    justify-content: space-between;
    margin-left: 0.1rem;
  }
  .datum > div {
    width: 100%;
  }
  .desc-series, .desc-lesson {
    justify-content: space-between;
    font-size: .24rem;
  }
  .btn-go{
    background: #2F57DA;
    color: #fff;
    width: .8rem;
    font-size: .24rem;
    /*border: 1px solid #00f;*/
    text-align: center;
  }
  .btn-toggle{
    color: #2F57DA;
    background:  rgba(124,148,238,0.44);;
    width: .8rem;
    font-size: .24rem;
    text-align: center;
  }
  .btn-toggle i {
    font-size: .2rem;
  }
  .datum-tag {
    padding-right: 1em;
  }
  .title {
    justify-content: space-between;
  }
  .title > span{
    /*height: 1rem;*/
    font-size: .32rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .title > i {
    color: #ccc;
    font-size: .32rem;
  }
  .sublist {
    background: #fafafa;
  }
  .sub-item {
    padding: 0 .28rem;
    height: .8rem;
    justify-content: space-between;
    border-bottom: 1px solid #eee;
  }
  .sub-item:last-child {
    border: 0;
  }
  .sub-title {
    font-size: .27rem;
  }
  .event-access {
    color: #0d0d0d;
  }
  .event-enroll {
    font-weight: bold;
  }
  .event-refund, .event-reset {
    color: #999;
  }
  .toggle {
    font-size: .24rem;
    color: #666;
    background: #ccc;
    padding: .2em .4em;
  }
  .toggle i {
    font-size: .24rem;
  }
  .bar-toggle > span {
    margin: 0 .5em;
    border-radius: .5em;
  }
  .bar-toggle .icon-yike {
    font-size: .2rem;
  }

  .i-access {
    color: #38BB10;
    font-weight: bold;
  }
  .i-enroll {
    color: #f00;
    font-size: 12px;
  }
  .label {
    width: .88rem;
    height: .4rem;
    border-radius: .2rem;
    padding: .05rem .1rem;
    font-size: .2rem;
    font-weight: bold;
  }
  .label-refund {
    color: #F01B1B;
    background: rgba(255,172,137,0.44);
  }
  .label-reset {
    color: #07991C;
    background: rgba(140,229,130,0.44);
  }
  .label-pending {
    color: #2F57DA;
    /*font-weight: normal;*/
    /*border: 1px solid #2F57DA;*/
    background: rgba(124,148,238,0.44);
  }
  .datum-num-access {
    color: #07991c;
  }
  .datum-num-enroll {
    color: #f01b1b;
  }

</style>
