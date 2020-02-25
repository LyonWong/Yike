<template>
  <div class="c-my-lesson-cell">
    <div class="frm btn flex-row" @click="go(lesson.sn)">
      <img class="cover" :src="lesson.cover || app.linkToAssets('/img/lesson/default-cover.png')">
      <div class="flex-col detail">
        <div class="title">{{lesson.title}}</div>
        <div class="label" v-if="lesson.type === 'lesson'">
          <span v-if="lesson.event === 'enroll'">未学习</span>
          <span v-if="lesson.event === 'access'">已学习</span>
          <span v-if="lesson.event === 'refund'">已退款</span>
          <!--<span v-if="lesson.step === 'opened'">未开课</span>-->
          <!--<span v-if="lesson.step === 'onlive'">授课中</span>-->
          <!--<span v-if="lesson.step === 'repose'">交流中</span>-->
          <!--<span v-if="lesson.step === 'finish'">已完结</span>-->
        </div>
        <div class="label" v-if="lesson.type === 'series'">
          <span>学习{{lesson.events.access || 0}}/{{lesson.total}}</span>
          <!--<span>开课{{lesson.total - (lesson.steps.opened || 0)}}/{{lesson.total}}</span>-->
          <span v-if="lesson.events.refund">退款{{lesson.events.refund}}/{{lesson.total}}</span>
        </div>
      </div>
    </div>
      <div class="frm sublist" v-if="showSub">
          <ul>
            <li class="btn flex-row" v-for="(item, idx) in sublist" :key="idx" @click="go(item.sn)">
              <span class="flex-item">{{item.title}}</span>
              <span class="label">
              <span v-if="item.event==='enroll'">未学习</span>
              <span v-if="item.event==='access'">已学习</span>
              <span v-if="item.event==='refund'">已退款</span>
              <!--<span v-if="item.step === 'opened'">未开课</span>-->
              <!--<span v-if="item.step === 'onlive'">授课中</span>-->
              <!--<span v-if="item.step === 'repose'">交流中</span>-->
              <!--<span v-if="item.step === 'finish'">已完结</span>-->
            </span>
            </li>
          </ul>
      </div>
    <!--</transition>-->
    <div class="toggle flex-row">
      <i class="btn icon-yike icon-arrow-b" v-if="lesson.type === 'series' && showSub === false" @click="toggle"></i>
      <i class="btn icon-yike icon-arrow-t" v-if="lesson.type === 'series' && showSub === true" @click="toggle"></i>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'my-lesson-cell',
    props: ['lesson'],
    data() {
      return {
        'showSub': false,
        'sublist': []
      }
    },
    methods: {
      go(sn) {
        console.log('go', sn)
        if (sn[0] === 'S') {
          window.location.href = `/lesson/series?sn=${sn}`
        }
        if (sn[0] === 'L') {
          window.location.href = `/lesson/detail?sn=${sn}`
        }
      },
      toggle() {
        if (this.sublist.length === 0) {
          this.api.get('/api/my-lesson-sub', {
            'sn': this.lesson.sn
          }).then((res) => {
            this.sublist = res.data
            this.showSub = true
          })
        } else {
          this.showSub = !this.showSub
        }
      }
    }
  }
</script>

<style scoped>

  .c-my-lesson-cell {
    background: #fff;
    border-bottom: 1px solid #DDDDDD;
    padding-top: 1.6em;
    margin-bottom: .05rem;
  }

  .frm {
    justify-content: flex-start;
    padding: 0 .3rem;
  }

  .detail {
    flex: 1;
    height: 1.28rem;
  }

  img.cover {
    width: 2.4rem;
    height: 1.28rem;
    padding-right: .3rem;
  }

  .detail > div {
    width: 100%;
  }

  .label {
    padding: .5em;
    font-size: .24rem;
  }

  .label > span {
    border: 1px solid #2F57DA;
    padding: 0 .5em;
    border-radius: .5em;
    color: #2F57DA;
  }

  .sublist ul {
    background: #fafafa;
    list-style: none;
    padding: 0 1em;
    margin: 1em 0 0 0;
  }
  .sublist li {
    height: 3em;
    line-height: 3em;
    justify-content: space-between;
    border-bottom: 1px solid #eee;
  }
  .sublist li:last-child {
    border: 0;
  }

  .detail, .detail-top, .detail-bottom, .title + .flex-col {
    justify-content: space-between;
  }

  .title {
    font-size: .32rem;
    color: #0D0D0D;
  }

  .title + div {
    height: 100%;
  }

  .toggle {
    text-align: center;
    /*color: #aaa;*/
    /*background: #0f0;*/
    height: 1.6em;
  }

  .toggle > i.btn {
    width: 100%;
    /*background: #f00;*/
    height: 1.6em;
    line-height: 1.6em;
    color: #666;
  }
</style>
