<template>
  <div class="c-lesson-detail-policy">
    <div class="bar-items flex-row" @click="isOpen = true">
      <div class="flex-row">
        <div class="item" v-for="(item,index) in (policy ? (policy.hints||hints) : hints)" :key="index">
          <i class="icon-yike icon-select"></i>
          <span class="text-desc font-medium">{{item}}</span>
        </div>
      </div>
      <i class="icon-yike icon-arrow-r"></i>
    </div>

    <popup :isOpen="isOpen" v-on:close="isOpen = false">
      <div slot="head" class="profile font-medium">用户须知</div>
      <div class="items">
        <ul>
          <li v-for="(item,index) in (policy ? (policy.items||items) : items)" :key="index">
            <div class="items-head flex-row">
              <i class="icon-yike icon-select"></i>
              <span>{{item.head}}</span>
            </div>
            <div class="items-desc" v-html="item.desc"></div>
          </li>
        </ul>
      </div>
      <div class="btn btn-roger flex-row font-medium" slot="foot" @click="isOpen = false">知道了</div>
    </popup>
  </div>
</template>

<script>
  import Popup from "../../components/Popup"
  import PhotoViewer from "../components/PhotoViewer"

  export default {
    name: 'lesson-detail-policy',
    components: {Popup, PhotoViewer},
    props: ['policy'],
    data() {
      return {
        isOpen: false,
        hints: [
          '永久回放',
          '无条件退款'
        ],
        items: [
          {
            'head': '开课通知',
            'desc': `关注【易灵微课】公众号<a id="qrcode" style="color: #2F57DA;cursor: pointer">yike-fm<a/>，接收开课提醒`
          },
          {'head': '无条件退款', 'desc': '进入课堂1小时内，可无条件退款'},
          {'head': '未听课退款', 'desc': '课程结束30天后，未听课自动退款'},
          {'head': '授课形式', 'desc': '支持图文、语音、视频片段，建议在WiFi环境下观看'},
          {'head': '讨论交流', 'desc': '课堂设有讨论区，可实时与讲师或其他学员交流。禁止发布违法法律法规或与课程无关的内容'}
        ]
      }
    },
    watch: {
      policy: function() {
        let qrcode = document.getElementById('qrcode')
        if (qrcode) {
          qrcode.onclick = () => {
            this.app.previewImageOne(this.app.linkToAssets('/img/qrcode/yike-fm.png'))
          }
        }
      }
    },
    methods: {
    }
  }
</script>

<style scoped>
  .bar-items {
    justify-content: space-between;
    height: .8rem;
    padding: 0 .3rem;
    background: #FFFCF5;
    color: #caac91;
  }

  .bar-items span {
    color: #caac91;
  }

  .item {
    padding-right: .4rem;
    color: #caac91;
  }

  .icon-select {
    font-size: .24rem;
    padding-right: .11rem;
  }

  .icon-arrow-r:before {
    display: inline-block;
    color: #caac91;
    text-align: right;
  }

  .items {
    overflow-x: auto;
    overflow-y: scroll;
    padding: .3rem;
    border-bottom: .3rem solid #fff;
    max-height: 60vh;
    -webkit-overflow-scrolling: touch;
  }

  .items ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
  }

  .items li {
    padding: .1rem 0;
  }

  .items-head {
    justify-content: flex-start;
    font-size: .3rem;
    color: #666;
  }

  .items-head > i {
    color: #2F57DA;
    margin-right: .1rem;
  }

  .items-desc {
    padding: .1rem .45rem;
    color: #888;
    text-align: justify;
  }

  .btn-roger {
    width: 100%;
    height: 100%;
    color: #fff;
    background: #2F57DA;
    font-size: .32rem;
  }

  .profile {
    font-size: .32rem;
  }
</style>
