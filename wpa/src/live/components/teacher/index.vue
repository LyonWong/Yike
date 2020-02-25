<template>
  <div id="teacher-bar" class="teacher-bar">
    <div class="bar-title" :class="{'fold':recorderFold && recording}">
      <p class="prepare-title">备课区</p>
    </div>
    <ul class="bar-list" v-if="prepareShow">
      <li v-for="(list, index) in prepareList">
        <div class="list-index">
          <span>NO.{{++index}}</span>
          <span class="share" @click="shareToLive(list.seqno, index)"><i class="iconfont icon-fasongdaozhibo"></i></span>
        </div>
        <div class="list-text" v-if="list.type=='text'" v-html="textFormat(list.content)">
        </div>
        <div class="list-image" v-if="list.type=='image'">
          <a :href="list.content" target="_blank">
            <img :src="list.content" />
          </a>
        </div>
        <div class="list-audio" v-if="list.type=='audio'">
          <v-audio :id="list.seqno" :src="list.content"></v-audio>
          <p class="note" v-if="list.note" v-text="list.note"></p>
        </div>
        <div class="list-video" v-if="list.type == 'video'">
          <a :href="list.content" target="_blank">
            <video :src="list.content" controls="controls">
              您的浏览器不支持 video 标签。
            </video>
          </a>
        </div>
        <div class="list-mark" v-if="list.type== 'mark'">
          <i class="iconfont icon-bookmark"></i>
          <span>{{list.content}}</span>
        </div>
      </li>
      <li class="center" v-if="!prepareList.length">
        <span href="javascript:;">
          <i class="iconfont icon-jinggao"></i>
          未添加备课内容~
        </span>
      </li>
    </ul>
  </div>
</template>
<script>
  import {mapState} from 'vuex';
  import vAudio from './audio.vue';

  // 定义滚动DOM
  let prepareScroll = null;

  export default {
    name: 'v-teacher',
    components: {
      vAudio,
    },
    computed: {
      ...mapState([
        'recorderFold',
        'prepareList',
        'userInfo',
        'liveHost',
        'recording',
      ])
    },
    data() {
      return {
        prepareShow: false,
      }
    },
    created() {
      // 获取备课列表
      try{
        let _url = `${this.liveHost}/live-prepare-slice.api?lesson_sn=${this.userInfo.lesson_sn}&limit=0`;
        // 获取统计信息
        this.$http.get(_url).then((json)=>{
          // 打开备课列表
          this.prepareShow = true;
          //
          if(json.ok){
            this.$store.commit('UPDATE_PREPARE_LIST', json.body.data);
          }
        },(err)=>{
          console.log(err);
        });
      }catch(e){};
    },
    methods: {
      textFormat(value){
        return value.replace(/\n/g, '<br>');
      },
      shareToLive(seqno, index){
        // 开始分享到直播
        try{
          let body = {
            cursor: seqno,
            lesson_sn: this.userInfo.lesson_sn,
          };
          if(!prepareScroll){
            prepareScroll = document.getElementById('teacher-bar');
          }
          let curLi = prepareScroll.querySelector('ul').children[index-1];
          if (curLi.className == 'active' && confirm("是否重复发送此内容？") === false) {
            return false
          }
          // 开始上传
          this.$store.commit('UPDATE_LOADING', true);
          // start
          this.$store.dispatch('fetchLivePrepareSend', body).then((data) => {
            // 发送完毕
            this.$store.commit('UPDATE_LOADING', false);
            //
            if(!prepareScroll){
              prepareScroll = document.getElementById('teacher-bar');
            }
            let curLi = prepareScroll.querySelector('ul').children[index-1];
            // 找到指定的children
            setTimeout(()=>{
              curLi.className = 'active';
              /*prepareScroll.scrollTop = curLi.offsetTop-10;*/
            }, 100);
            console.log('fetchLivePrepareSend success');
          }, (err) => {
            // 发送完毕
            this.$store.commit('UPDATE_LOADING', false);
            //
            swal({
              title: '错误提醒',
              text: err.message,
              confirmButtonText: "知道了"
            });
          });
        }catch(e){};
      },
    }
  };
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  @import 'index.styl';

  .is-pc
    .teacher-bar
      height: 745px;
      overflow-y: auto;
      overflow-x: hidden;
      .bar-title
        &.fold
          margin-bottom: 90px;
      .prepare-title
        margin: 4px 0;
      .bar-list
        padding: 0 10px 20px;
        li
          margin-bottom: 9px;
          padding: 10px;
          border: 1px solid #E6EAF2;
          a
            padding: 0;
          &.center
            margin-top: 320px;
            border: 0 none;
            text-align: center;
            span
              color: #aaa;
          .list-index
            position: relative;
            padding-bottom: 7px;
            color: #999;
            font-size: 14px;
            .share
              position: absolute;
              top: 0;
              right: 0;
              cursor: pointer;
              .iconfont
                color: #12B7F5;
                font-size: 14px;
                &:hover
                  opacity: 0.6;
          &.active
            .list-index
              .share
                .iconfont
                  color: #999;
          .list-text
            word-break:break-all;
          .list-image
            background: #E6E6E6;
            text-align: center;
            img
              max-width: 100%;
              max-height: 150px;
          .list-video
            text-align: center;
            video
              max-width: 100%;
              max-height: 150px;
          .list-audio
            .note
              margin: 6px 0;
              color: #999;
              font-size: 14px;
              overflow: hidden;
              word-break: break-all;
          .list-mark
            i
              px2px(font-size, 40px)
              color: #2F57DA
</style>
