<template>
  <div class="c-my-lesson">
    <Tabs id="tabs" :items="tabs" :active="activeTab" v-on:switch="switchTab">
    </Tabs>
    <div id="list">
      <div class="item" v-for="(item, index) in lists[activeTab]" :key="index">
        <div class="title flex-row btn" @click="linkToDetail(item.sn, item.type)">
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
        <div class="toggle flex-row" v-if="item.type==='series'" @click="toggle(item.sn)">
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
    <navigation></navigation>
  </div>
</template>

<script>
  import MyLessonCell from "../components/LessonCell";
  import Tabs from "../../components/Tabs";
  import Navigation from "../../components/Navigation";
  import qs from "qs";
  export default {
    name: 'my-lesson',
    components: {Navigation, Tabs, MyLessonCell},
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
            this.activeTab = 'todo'
            this.lists.todo.push(item)
          }
          if (item.events.access === item.total) {
            this.lists.done.push(item)
          }
          if (item.events.refund) {
            this.lists.quit.push(item)
          }
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
      linkToDetail(sn, type) {
        window.location.href = `/lesson/${type==='lesson' ? 'detail' : type}?sn=${sn}`
      },
      linkToLesson(lesson) {
        if (lesson.event === 'access' || (lesson.event === 'enroll' && lesson.step !== 'opened')) {
          let query = qs.stringify({
            isOwner: 'no',
            teacherEnter: 'yes',
            lesson_sn: lesson.sn,
            teach: `${lesson.sn}-T`,
            discuss: `${lesson.sn}-D`
          })
          window.location.href = `/live?${query}`
        } else {
          window.location.href = `/lesson/detail?sn=${lesson.sn}`
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
    position: sticky;
    top: 0;
    height: .8rem;
    box-shadow: 0 0 5px rgba(0,0,0,0.1);
  }
  #list {
  }
  .item {
    margin-top: .1rem;
    background: #fff;
    color: #0D0D0D;
  }
  .title {
    height: 1rem;
    justify-content: space-between;
    font-size: .32rem;
    padding: 0 .28rem;
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
    height: .6rem;
    font-size: .24rem;
    color: #666;
  }
  .bar-toggle > span {
    margin: 0 .5em;
    border-radius: .5em;
  }
  .bar-toggle .icon-yike {
    font-size: .2rem;
  }
  .btn-toggle {
    height: .5rem;
    border: 1px solid #ccc;
    border-radius: 4px;
    color: #6c6c6c;
    padding: 0 .14rem;
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

</style>
