<template>
  <!-- live start -->
  <div class="live-page">
    <!--loading-->
    <v-loading :show="showLoading"></v-loading>
    <div class="live-sms-header" v-if="isPC">授课区
      <span class="end" @click="endLesson" v-if="isOwner && lessonInfo.step != 'finish'">
        <!--<i class="iconfont icon-jieshu"></i>-->
        结束授课
      </span>
      <!--<span class="end comment" @click="startComment" v-if="isOwner && lessonInfo.step != 'repose' && lessonInfo.step != 'finish'">
        进入课后交流
      </span>-->
      <span class="end invite" @click="startInvite" v-if="isOwner">邀请嘉宾</span>
    </div>
    <div class="live-sms-left" v-if="isPC"></div>
    <div class="live-sms-right" v-if="isPC"></div>
    <div class="live-sms-bottom" v-if="isPC"></div>
    <!-- player -->
    <v-player v-show="playingAudio"></v-player>
    <!-- live entity -->
    <div class="live-body" id="live-body" :class="{'owner':isOwner}" @click="handleBody">
      <!--<p class="pullMsgs" v-if="canPullMsgs"><a href="javascript:;" @click="pullMsgs">点击拉取历史消息</a></p>-->
      <!-- header -->
      <l-header v-if="!canUp"></l-header>
      <p class="pullMsgs" v-if="!canUpPullMsgs && !firstLoad && canUp">
        <a href="javascript:;" @click="clickUpMsg"><i class="iconfont icon-click"></i>&nbsp;查看历史消息</a>
      </p>
      <p class="pullMsgs" v-if="canUpPullMsgs && !firstLoad && canUp">
        <img :src="loadImg"/>
      </p>
      <div class="btn-bookmark" @click="bookmark.show = !bookmark.show" :class="{active: bookmark.show}"
           v-if="bookmarkList.length">
        <i class="iconfont icon-list"></i>
      </div>
      <div class="frm-bookmark" v-show="bookmark.show && bookmarkList.length">
        <div v-for="item in bookmarkList" :key="item.cursor" @click="scrollBookmark(item.cursor)">
          {{item.text}}
        </div>
      </div>
      <!-- message entity -->
      <ul class="live-sms-list" id="live_sms_list" v-bind:class="{'commentShow':commentShow,'big':(commentType==2)}"
          @click="hidePop">
        <li v-for="(msg, index) in messageInfo" :id="'m-'+msg.cursor"
            :class="{'is-system':msg.isSystem, 'is-bookmark': msg.bookmark, 'is-admire': msg.admire, 'is-quote':(msg.content && msg.content[0].type==msg.MSG_ELEMENT_TYPE.CUSTOM && msg.content[0].custom && msg.content[0].custom[0].type=='QUOTE')}">
          <div class="user-img" v-if="!msg.isSystem">
            <!--<img :src="loadingImg" width="45px">-->
            <img v-if="userAvatar && userAvatar[msg.account]" :src="userAvatar[msg.account]" width="45px">
            <img v-if="!userAvatar || !userAvatar[msg.account]"
                 :src="`${assetsHost}static/live/_static/live/img/comment_default.png`" width="45px">
          </div>
          <div class="live-sms" v-if="!msg.isSystem">
            <div class="speaker-name">
              <span class="inline-block">
                <span class="nickname">{{msg.nickname}}</span>
                <span class="time">{{timeFormat(msg.time)}}</span>
                <i class="delete iconfont icon-105" v-if="isOwner" @click="deleteRecord(msg.cursor)"></i>
              </span>
            </div>
            <div class="sms-content" :class="msg.content" v-for="con in msg.content">
              <div class="content-text" v-text="con.text" v-if="con.type==msg.MSG_ELEMENT_TYPE.GROUP_TIP"></div>
              <div class="content-text markdown" v-html="textFormat(con.text)"
                   v-if="con.type==msg.MSG_ELEMENT_TYPE.TEXT"></div>
              <div class="content-text" v-if="con.type==msg.MSG_ELEMENT_TYPE.FILE">
                <a v-for="file in con.fileArr" :href="file.url" target="__blank">点击下载</a>
              </div>
              <div class="content-text custom" v-if="con.type==msg.MSG_ELEMENT_TYPE.CUSTOM">
                <div class="custom" v-for="cus in con.custom">
                  <!--<div class="bookmark" v-if="cus.type=='MARK'">-->
                  <!--mark:{{cus.text}}-->
                  <!--</div>-->
                  <div class="content-audio" :data-audio-src="cus.type" v-if="cus.type == 'SOUND'">
                    <v-audio ref="audios" :history="con.history" :id="cus.id" :src="cus.src" :index="index"></v-audio>
                  </div>
                  <div class="custom-img" v-if="cus.type == 'IMAGE'">
                    <a href="javascript:;">
                      <img ref="liveImg" v-bind:src="cus.src" @click="showPrepareImage(cus.src)">
                      <img v-bind:src="cus.src.split('#')[1]" v-show="!cacheImg" v-if="isWeiXin && isPC">
                    </a>
                  </div>
                  <div class="custom-video is-pc-video" v-if="cus.type == 'VIDEO'"
                       @click="videoClick($event, 'id-'+cus.id, isPC)">
                    <video class="video-js vjs-default-skin"
                           :id="'id-'+cus.id" :poster="parseVideoPoster(cus.src)" :data-source="cus.src"
                           :data-id="'id-'+cus.id">
                      <source :type="parseVideoType(cus.src)" :src="cus.src"/>
                    </video>
                    <!--<div class="video">-->
                    <!--<i class="iconfont icon-play" @click="playPCVideo(cus.id)"></i>-->
                    <!--<img :src="loadImg" />-->
                    <!--</div>-->
                  </div>
                  <div class="custom-video mobile" v-if="1==2 && cus.type == 'VIDEO' && !isPC">
                    <video class="video-js vjs-default-skin" :id="'id-'+cus.id"
                           :poster="`${cus.src}-preview.jpg!preview`" :data-source="cus.src" :data-id="'id-'+cus.id">
                      <source :type="parseVideoType(cus.src)" :src="cus.src"/>
                    </video>
                    <!--<video :src="cus.src" :id="cus.id" preload="none" :poster="`${cus.src}-preview.jpg!preview`" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>-->
                    <!--您的浏览器不支持 video 标签。{{check('foo')}}-->
                    <!--</video>-->
                    <!--<div class="video">-->
                    <!--<img :src="loadImg" />-->
                    <!--<button>-->
                    <!--<i class="iconfont icon-play" @click="playVideo(cus.id)"></i>-->
                    <!--<span class="tips">此视频格式不支持</span>-->
                    <!--</button>-->
                    <!--</div>-->
                  </div>
                  <div class="content-audio" :data-audio-src="cus.type" v-if="cus.type == 'FILE'">
                    <a :href="cus.src" target="__blank">点击下载{{cus.name}}</a>
                  </div>
                  <div v-if="cus.type == 'QUOTE'">
                    <!--<div class="quote">讲师引用：</div>-->
                    {{cus.text}}
                  </div>
                </div>
              </div>
              <div class="content-img" v-if="con.type==msg.MSG_ELEMENT_TYPE.IMAGE">
                <a href="javascript:;" v-for="img in con.imgArr">
                  <img ref="liveImg" v-bind:src="img" @click="showLiveImage(img)">
                  <img v-bind:src="img.split('#')[2]" v-show="!cacheImg" v-if="isWeiXin && isPC">
                </a>
              </div>
            </div>
          </div>
          <div class="live-system" v-if="msg.isSystem && !isOwner">
            <p>{{msg.message}}</p>
          </div>
          <div class="bookmark" v-if="msg.isSystem && msg.bookmark" :id="'mark-'+msg.bookmark.id">
            <!--<i>·</i>-->
            <span>{{msg.bookmark.text}}</span>
          </div>
          <div class="admire" v-if="msg.isSystem && msg.admire">
            <img v-if="userAvatar && userAvatar[msg.account]" :src="userAvatar[msg.account]">
            <span class="nickname">{{msg.nickname}}</span>
            <span>赞赏了￥{{msg.admire.text/100}}</span>
          </div>
        </li>
        <!-- 尾注 -->
        <li class="tail" v-if="lessonInfo.step =='finish' && footerConf && footerConf.footer.length">
          <div class="tail-tit">本课程已结束，课后你可以:</div>
          <div class="tail-con">
            <div class="link" v-for="foo in footerConf.footer">
              <i class="iconfont icon-dot"></i>
              <a :href="foo.href">{{foo.text}}</a>
            </div>
          </div>
        </li>
      </ul>
      <p class="pullMsgs down" v-if="!canDownPullMsgs && !firstLoad && canDown">
        <a href="javascript:;" @click="clickDownMsg"><i class="iconfont icon-click"></i>&nbsp;查看更多消息</a>
      </p>
      <p class="pullMsgs down" v-if="canDownPullMsgs && !firstLoad && canDown">
        <img :src="loadImg"/>
      </p>
    </div>
    <admire v-if="admire" :lesson="lessonInfo" v-on:cancel="admire=false" v-on:complete="completeAdmire"></admire>
    <!-- comment -->
    <v-comment :inComment="inComment"></v-comment>
    <!-- chatbox -->
    <v-chatbox v-if="isOwner"></v-chatbox>
    <s-chatbox v-if="!isOwner" v-on:admire="admire=true"></s-chatbox>
    <!-- handle -->
    <v-handle :callBack="showHandle" :show="handleShow"></v-handle>
    <show-image v-show="showImg" :img="liveImg"></show-image>
    <v-preview v-show="previewImg" :preview="previewImg" @prevPreviewImg="prevPreviewImg"
               @nextPreviewImg="nextPreviewImg"></v-preview>
    <!-- evaluate -->
    <v-evaluate v-if="!isOwner && evaluateShow"></v-evaluate>
    <!-- 上下置顶按钮 -->
    <div class="top-bottom">
      <a href="javascript:;" @click="scrollUp" v-if="upShow">
        <i class="iconfont icon-zhiding"></i>
      </a>
      <a href="javascript:;" @click="scrollDown" v-if="downShow">
        <i class="iconfont icon-zhidi"></i>
      </a>
    </div>
    <!-- 位置引导 -->
    <!--<div class="pos-guide">-->
    <!--<a href="javascript:;" @click="scrollUp" v-if="upShow">-->
    <!--<i class="iconfont icon-zhiding"></i>-->
    <!--</a>-->
    <!--<a href="javascript:;" @click="scrollDown" v-if="downShow">-->
    <!--<i class="iconfont icon-daodi"></i>-->
    <!--</a>-->
    <!--</div>-->
    <!--input域存放复制内容-->
    <input class="transparent" type="text" ref="copy" v-bind:value="invite_url" v-if="isOwner"/>
    <!--缓存图片-->
    <img :src="img" v-for="img in cacheImgList" v-show="!cacheImg"/>
  </div>
</template>

<script>
  import {mapState} from 'vuex';
  import lHeader from './header.vue';
  import vPlayer from './player.vue';
  import vHandle from './handle.vue';
  import showImage from './image.vue';
  import vPreview from './preview.vue';
  import {exportAssembleMsg, exportInspectScroll} from '@live/assets/js/webim_comment';
  import vComment from '@live/components/comment/index.vue';
  import vChatbox from '@live/components/chatbox/index.vue';
  import sChatbox from '@live/components/chatbox/sChat.vue';
  import vAudio from '@live/components/audio/index.vue';
  import vEvaluate from '@live/components/live/evaluate.vue';
  import vLoading from '@live/components/loading/index.vue';
  import {setStore, getStore, removeStore} from '@lib/js/mUtils';
  import Admire from "./admire";

  const markdown = require('markdown-it')({html: true, breaks: true});
  // spec
  var debounceTime = null;
  var curScrollTop = null;
  var _prefix = process.env.NODE_ENV == 'production' ? process.env.LIVE_HOST.replace(/\/$/, '') : '/api';
  console.log('tesing')
  console.log(process.env.FOO)
  var userUrl = `${_prefix}/user-profile.api?usn=`;
  var timer = null;
  const Cov = {
    on(el, type, func) {
      el.addEventListener(type, func)
    },
    off(el, type, func) {
      el.removeEventListener(type, func)
    }
  }

  export default {
    name: 'v-live',
    props: {
      lesson: {
        type: String,
      },
      inComment: {
        type: Boolean,
      },
      entering: {
        type: null,
      }
    },
    components: {
      Admire,
      vComment,
      lHeader,
      vChatbox,
      vLoading,
      sChatbox,
      vAudio,
      vPlayer,
      vHandle,
      vPreview,
      vEvaluate,
      showImage,
    },
    data() {
      return {
        isPC,
        isWeiXin,
        show: false,
        busy: false,
        upShow: false,
        downShow: false,
        handleShow: false,
        firstLoad: true,
        lessonStep: null,
        canUpPullMsgs: false,
        canUp: true,
        canDownPullMsgs: false,
        canDown: false,
        showImg: false,
        admire: null,
        showLoading: true,
        msgLimit: 20,
        limitTop: isPC ? 80 : 150,
        limitBottom: isPC ? 60 : 130,
        liveImg: '',
        invite_url: '',
        previewImg: '',
        previewIndex: 0,
        previewImgList: [],
        initBackUpCount: 0,
        cacheImg: true,
        cacheImgList: [],
        bookmark: {
          show: false,
          list: []
        },
        jsonData: null,
        loadImg: `${process.env.ASSETS_HOST ? process.env.ASSETS_HOST : 'https://assets.sandbox.yike.fm/'}static/student/_static/student/img/lazy-loading.gif`,
      };
    },
    computed: {
      ...mapState([
        'headerTitle',
        'messageInfo',
        'bookmarkList',
        'lessonInfo',
        'menuShow',
        'isOwner',
        'teacherInfo',
        'loadingImg',
        'playingAudio',
        'commentShow',
        'evaluateShow',
        'boxMoreShow',
        'assetsHost',
        'userAvatar',
        'commentType',
        'footerConf',
        'liveHost',
      ])
    },
    mounted() {
      let count = 0;
      let self = this;
      let visibleTimer = null;
      (function pullMessage() {
        if (!self.entering || count >= 10) {
          return self.pullMsgs();
        }
        //
        setTimeout(pullMessage, 1000);
      })();


      // 是否是移动设备
      if (navigator.userAgent.match(/(iPhone|iPad|ios|Android)/i) != null) {
        // 开始监测熄屏事件
        document.addEventListener('visibilitychange', () => {
          if (document.visibilityState == 'hidden') {
            visibleTimer = new Date().getTime();
          } else {
            if (visibleTimer && (new Date().getTime() - visibleTimer) / 60000 > 5) {
              swal({
                title: '错误提醒',
                text: '您的手机熄屏较长时间，可能导致课程内容中断或缺失，建议您刷新页面后继续收听!',
                confirmButtonText: '立即刷新',
                showCancelButton: true,
                closeOnConfirm: false,
                cancelButtonText: '继续观看',
              }, () => {
                setTimeout(() => {
                  if (/[\?\&]t=\w+/.test(location.href)) {
                    return location.href = `${location.href.replace(/([\?\&])t=\w+/, `$1t=${Math.round(new Date().getTime() / 1000)}`)}`;
                  }
                  location.href = `${location.href.replace(/(isOwner=\w+)\&/, `$1&t=${Math.round(new Date().getTime() / 1000)}&`)}`;
                }, 100);
              });
            }
          }
        });
      }
    },
    updated() {
      this.videoInit(isPC);
    },
    methods: {
      check(msg) {
        console.log('check', msg)
      },
      videoClick($event, id, isPC) {
        var player = videojs.players[id];
        if (!isPC) {
          if ($event.target.className !== 'vjs-icon-placeholder') {
            if (player.paused()) {
              player.play();
            }
          }
        }
      },
      videoInit(isPC) {
        var videos = document.getElementsByClassName('video-js');
        //  var the = this
        for (var i = 0; i < videos.length; i++) {
          var id = videos[i].getAttribute('data-id');
          if (!videojs.players[id]) {
            var newbtn = document.createElement('div');
            newbtn.className = 'flex-row playbackRate-frm'
            newbtn.innerHTML = `<select class="playbackRate">
                              <option>0.5倍速</option>
                              <option>0.75倍速</option>
                              <option selected>1倍速</option>
                              <option>1.25倍速</option>
                              <option>1.5倍速</option>
                              <option>2倍速</option>
                              </select>`;
            var player = videojs(id, {controls: true});
            player.el_.querySelector('.vjs-control-bar').appendChild(newbtn)
            player.on('play', (e) => {
              // e.target.firstElementChild.setAttribute('x5-video-player-type', null);
              e.target.firstElementChild.style.display = 'block';
              e.target.firstElementChild.parentNode.querySelector('.playbackRate').onchange = function () {
                e.target.firstElementChild.playbackRate = this.value.replace(/倍速/, '');
              }
              this.pauseAudioPlaying();
            });
            player.on('pause', (e) => {
              // player.children_[0].setAttribute('x5-video-player-type', 'h5')
              // e.target.firstElementChild.setAttribute('x5-video-player-type', 'h5');
              if (!isPC) {
                e.target.firstElementChild.style.display = 'none';
              }
            });
          }
        }
      },
      parseVideoType(src) {
        return src.match(/.m3u8$/) ? 'application/x-mpegURL' : 'video/mp4';
      },
      parseVideoPoster(src) {
        return src.replace(/\.\w+$/, '') + '-preview.jpg!preview';
      },
      isShow() {
        //this.$store.commit('UPDATE_MENUSHOW');
      },
      showLiveImage(img) {
        if (isPC) {
          //window.open(img.split('#')[2]);
          let temp = this.curImageTemp(img);
          this.previewIndex = temp.index;
          this.previewImgList = temp.data;
          this.previewImg = img.split('#')[2];
          // 开始缓存
          this.startCacheImg();
        } else if (isWeiXin) {
          let temp = this.curImageTemp(img);
          wx.previewImage({
            current: img.split('#')[2],
            urls: temp.data,
          });
        } else {
          this.showImg = true;
          this.liveImg = img.split('#')[2];
        }
      },
      showPrepareImage(img) {
        if (isPC) {
          let temp = this.curImageTemp(img);
          this.previewIndex = temp.index;
          this.previewImgList = temp.data;
          this.previewImg = img.split('#')[1];
          // 开始缓存
          this.startCacheImg();
        } else if (isWeiXin) {
          let temp = this.curImageTemp(img);
          wx.previewImage({
            current: img.split('#')[1],
            urls: temp.data,
          });
        } else {
          this.showImg = true;
          this.liveImg = img.split('#')[1];
        }
      },
      curImageTemp(img) {
        let imgArr = this.$refs['liveImg'];
        let tempArr = [];
        let index = 0;
        for (let i = 0; i <= imgArr.length - 1; i++) {
          let imgSrc = imgArr[i].getAttribute('src');
          let srcArr = imgSrc.split('#');
          if (imgSrc == img) {
            index = i;
          }
          tempArr.push(srcArr.length > 2 ? srcArr[2] : srcArr[1]);
        }
        ;
        // 返回
        return {
          index,
          data: tempArr,
        };
      },
      startCacheImg() {
        let prev = this.previewIndex - 1;
        let next = this.previewIndex + 1;
        // 清空
        this.cacheImgList = [];
        // 上一张
        if (prev >= 0) {
          this.cacheImgList.push(this.previewImgList[prev]);
        }
        // 下一张
        if (next <= this.previewImgList.length - 1) {
          this.cacheImgList.push(this.previewImgList[next]);
        }
      },
      prevPreviewImg() {
        if (this.previewIndex > 0) {
          // 开始缓存
          this.startCacheImg();
          this.previewImg = this.previewImgList[--this.previewIndex];
        }
      },
      nextPreviewImg() {
        if (this.previewIndex < this.previewImgList.length - 1) {
          // 开始缓存
          this.startCacheImg();
          this.previewImg = this.previewImgList[++this.previewIndex];
        }
      },
      clickUpMsg() {
        this.scrollLoad('prev', this.msgLimit);
      },
      clickDownMsg() {
        this.scrollLoad('next', this.msgLimit);
      },
      pullMsgs() {
        if (this.lesson) {
          let cursor = '';
          let toward = 'fore';
          let limit = -1;
          this.jsonData = getStore('scrollHeight') ? JSON.parse(getStore('scrollHeight')) : null;
          // 临时调整
          this.canUp = false;
          this.canDown = false;
          ///
          let opt = {
            cursor: cursor,
            limit: limit,
            toward: toward,
            lesson_sn: this.lesson.split('-')[0],
          };
          // 开始请求
          this.$store.dispatch('fetchHistory', opt).then((msgList) => {
            //
            if (msgList.length == 0) {
              this.canUp = false;
              this.canDown = false;
              // 请求尾注
              this.checkInitTail();
              this.$nextTick(() => {
                this.showLoading = false;
                // 轮询开始拉取消息
                setTimeout(() => {
                  if (this.lessonInfo.step != 'finish' && !this.isOwner) {
                    this.longPolling(opt.cursor, opt.lesson_sn);
                  }
                }, 100);
              });
              return;
            }
            // 不能拉消息
            if (msgList.length < this.msgLimit && this.lessonInfo.step == 'finish') {
              this.canUp = false;
              this.canDown = false;
            }
            // 重组备份
            let tempList = [];
            // 循环
            for (var i = 0; i <= msgList.length - 1; i++) {//遍历消息，按照时间从后往前
              var msg = msgList[i];
              // 是否有昵称
              msg.accountNick = msg.accountNick || this.teacherInfo.name;
              msg.history = true;
              if (msg.content[0].MsgType === 'SYSTEM') {
                switch (msg.content[0].MsgCommand) {
                  case 'bookmark':
                    this.bookmark.list.push({
                      cursor: msg.cursor,
                      text: msg.content[0].MsgContent
                    });
                    break;
                }
              }
              var _msg = exportAssembleMsg(msg)
              if (_msg.bookmark) {
                this.bookmark.list.push({cursor: _msg.cursor, text: _msg.bookmark.text})
              }

              tempList.push(_msg);
            }
            // 递归获取头像
            this.recursion(0, msgList.length, msgList);
            // 实施
            this.$store.commit('UPDATE_HISTORY_MESSAGE', tempList);
            // 请求尾注
            this.checkInitTail();
            //
            this.$nextTick(() => {
              // 开始查看图片状况
              try {
                this.checkImgLoad([...this.$refs['liveImg']]);
              } catch (e) {
                this.checkCanScroll();
              }
              ;
              // 轮询开始拉取消息
              setTimeout(() => {
                // 课程未结束，轮询开始
                if (this.lessonInfo.step != 'finish' && !this.isOwner) {
                  this.longPolling(msgList[msgList.length - 1].cursor, opt.lesson_sn);
                }
              }, 100);
            });
          }, () => {
            this.$store.commit('UPDATE_LOADING', false);
            this.showLoading = false;
            console.log('fail');
          });
        }
      },
      completeAdmire() {
        this.admire = null;
        if (this.lessonInfo.step === 'finish') {
          this.longPolling(this.messageInfo[this.messageInfo.length-1].cursor, this.lessonInfo.sn)
        }
      },
      checkImgLoad(arr) {
        if (this.initBackUpCount == 5) {
          return this.checkCanScroll();
        }
        let temp = [...arr];
        //
        for (let i = 0; i < arr.length - 1; i++) {
          if (arr[i].complete) {
            temp.splice(i, 1);
          }
        }
        // 判断是否全部加载完
        if (temp.length > 0) {
          setTimeout(() => {
            this.initBackUpCount++;
            this.checkImgLoad(temp);
          }, 300);
        } else {
          this.checkCanScroll();
        }
      },
      checkCanScroll() {
        var vScroll = document.getElementById('live-body');
        // 采用了防抖动
        this.firstLoad = false;
        vScroll.addEventListener('scroll', this.debounce(this.scrollAction, vScroll, 200));
        setTimeout(() => {
          this.showLoading = false;
          // 是否有
          let curScrollHeight = this.jsonData ? (this.jsonData[`${this.lesson}`] || 0) : 0;
          //
          if (curScrollHeight) {
            vScroll.scrollTop = curScrollHeight;
          } else if (this.lessonInfo.step == 'finish') {
            vScroll.scrollTop = 0;
          } else {
            vScroll.scrollTop = vScroll.scrollHeight;
          }

        });
      },
      checkCanScrollBackUp() {
        //
        var _self = this;
        var vScroll = document.getElementById('live-body');
        var count = 0;
        // 开始迭代
        (function nextAction() {
          if (vScroll.scrollHeight && count < 2500) {
            // 采用了防抖动
            _self.firstLoad = false;
            vScroll.addEventListener('scroll', _self.debounce(_self.scrollAction, vScroll, 200));
            // 加载后跳转
            return vScroll.scrollTop = vScroll.scrollHeight;
          }
          ;
          count = count + 500;
          setTimeout(() => {
            nextAction();
          }, 500);
        })();
      },
      checkInitTail() {
        // 是否有权限进入课堂
        this.$store.dispatch('fetchExistsTail', {lesson_sn: this.lesson.split('-')[0]}).then((data) => {
          //
          this.$store.commit('UPDATE_FOOTER_CONF', data);
        }, (err) => {
          //
          swal({
            title: '错误提醒',
            text: (err.message ? err.message : '网络链接失败'),
            confirmButtonText: "知道了"
          });
        });
      },
      checkExistsTail() {
        // 是否有权限进入课堂
        this.$store.dispatch('fetchExistsTail', {lesson_sn: this.lesson.split('-')[0]}).then((data) => {
          //
          this.$store.commit('UPDATE_FOOTER_CONF', data);
        }, (err) => {
          //
          swal({
            title: '错误提醒',
            text: (err.message ? err.message : '网络链接失败'),
            confirmButtonText: "知道了"
          });
        });
      },
      showHandle(show) {
        this.handleShow = show;
      },
      textFormat(value) {
        return markdown.render(value || '')
        // return markdown.render(value || '').replace(/([^>])\n([^<])/g, '$1<br>$2');
        // return value.replace(/\n/g, '<br>');
      },
      handleBody() {
        if (this.boxMoreShow) {
          this.$store.commit('UPDATE_BOX_MORE', false);
        }
      },
      timeFormat(value) {
        value = value.replace(/^(\d{4})-(\d{1,2})-(\d{1,2}) (\d{2}):(\d{2}):(\d{2})$/, '$2-$3 $4:$5');
        return value;
      },
      updateAvatar(account, avatar) {
        //
        let opt = {};
        opt[account] = avatar;
        this.$store.commit('UPDATE_USER_AVATAR', opt);
      },
      deleteRecord(cursor) {
        console.log('delete record', cursor)
        if (!confirm("是否删除此条内容？")) {
          return false
        }
        this.$http.post(`${this.liveHost}/live-delete`, {cursor: cursor})
          .then((res) => {
            if (res.ok && res.body.error === '0') {
              document.querySelector(`#m-${cursor}`).remove()
            }
          })
      },
      recursion(i, length, msgList) {
        if (i > length) {
          return
        }
        //
        let msg = msgList[i];
        //
        try {
          let avatar = this.userAvatar[msg.from_account];
          // 是否有头像
          if (avatar) {
            // 继续递归
            this.recursion(++i, length, msgList);
          } else {
            throw new Error('there is no avatar');
          }
        } catch (e) {
          // 获取用户信息
          if (!msg) {
            // 继续递归
            return this.recursion(++i, length, msgList);
          }
          ;
          let url = `${userUrl}${msg.from_account}`;
          this.$http.get(url).then((json) => {
            if (json.ok) {
              //
              let data = json.body.data;
              msg.avatar = data.avatar;
              // 更新头像存储
              this.updateAvatar(msg.from_account, data.avatar);
              // 继续递归
              this.recursion(++i, length, msgList);
            }
          }, (err) => {
          });
        }
      },
      startComment() {
        // 确定进入学员交流
        swal({
          title: '',
          text: '学员和讲师在3天内可继续发言交流，讲师未发言超过1小时课程不关闭',
          confirmButtonText: '确定交流',
          showCancelButton: true,
          closeOnConfirm: false,
          cancelButtonText: '取消交流',
        }, () => {
          let opt = {
            lesson_sn: this.lessonInfo.sn,
          };
          this.$store.dispatch('fetchStartComment', opt).then(() => {
            removeStore(opt.lesson_sn);
            swal({
              title: '',
              text: '可以开始与学员交流!',
              confirmButtonText: '知道了'
            }, () => {
              window.location.reload();
            });
          }, () => {
            console.log('fail');
          });
        });
      },
      startInvite() {
        // 邀请嘉宾
        let opt = {
          lesson_sn: this.lessonInfo.sn,
        };
        // 开始
        this.$store.dispatch('fetchInviteGuest', opt).then((data) => {
          //
          this.invite_url = data;
          //
          swal({
            title: '邀请嘉宾',
            text: `将此链接发送给受邀嘉宾，在PC浏览器访问即可进入课堂<p class="invite-guest">${data}</p>`,
            html: true,
            confirmButtonText: '复制链接',
            showCancelButton: true,
            closeOnConfirm: false,
            cancelButtonText: '取消',
          }, () => {
            let input = this.$refs['copy'];
            input.select(); // 选取input元素的内容
            var succeeded;
            try {
              // 将选区内容复制到剪贴板
              succeeded = document.execCommand('copy');
            } catch (e) {
              succeeded = false;
            }
            // 是否成功
            if (succeeded) {
              swal({
                title: '',
                type: 'success',
                text: '复制成功',
                timer: 1000,
                confirmButtonText: '',
                confirmButtonColor: '#fff',
              });
            } else {
              swal({
                title: '',
                type: 'error',
                text: '复制失败',
                timer: 600,
                confirmButtonText: '',
                confirmButtonColor: '#fff',
              });
            }
          });
        }, (err) => {
          swal({
            title: '错误提醒',
            text: err.message ? err.message : '网络链接失败',
            confirmButtonText: '知道了'
          });
          console.log('fail');
        });
      },
      endLesson() {
        swal({
          title: '',
          text: '课程结束，学员讲师均不可继续发言，课程回放开启',
          confirmButtonText: '确定',
          showCancelButton: true,
          closeOnConfirm: false,
          cancelButtonText: '取消',
        }, () => {
          let opt = {
            lesson_sn: this.lessonInfo.sn,
          };
          this.$store.dispatch('fetchEndLesson', opt).then(() => {
            removeStore(opt.lesson_sn);
            swal({
              title: '',
              text: '课程已结束!',
              confirmButtonText: "知道了",
            }, () => {
              window.location.reload();
            });
          }, () => {
            console.log('fail');
          });
        });
      },
      // 防抖动函数
      debounce(func, vScroll, wait, immediate) {
        // 时间
        var timeout;
        var _vScroll = vScroll;
        var self = this;
        //
        return function () {
          var context = this, args = arguments;
          // 滚动开始
          if (!curScrollTop) {
            curScrollTop = vScroll.scrollTop;
          }
          var later = function () {
            timeout = null;
            if (!immediate) func.call(context, args, _vScroll);
          };
          var callNow = immediate && !timeout;
          clearTimeout(timeout);
          timeout = setTimeout(later, wait);
          if (callNow) func.call(context, args, _vScroll);
        };
      },
      scrollBookmark(cursor) {
        console.log('scroll mark', cursor)
        var vScroll = document.getElementById('live-body');
        vScroll.scrollTop = 0
        var toHeight = document.querySelector(`#m-${cursor}`).offsetTop
        vScroll.scrollTop = toHeight
        this.bookmark.show = false
        console.log('scroll mark', cursor, toHeight)
      },
      hidePop() {
        this.bookmark.show = false
      },
      scrollAction(args, vScroll) {
        // 清除计数器
        clearTimeout(debounceTime);
        // 是否是上移
        let upMove;
        if (curScrollTop > vScroll.scrollTop) {
          upMove = 1;
        } else if (curScrollTop < vScroll.scrollTop) {
          upMove = 2;
        }
        // 显示
        // 开始判断范围
        this.upShow = true;
        this.downShow = true;
        /*if(upMove == 1 && vScroll.scrollTop > 0){
          this.upShow = true;
          this.downShow = true;
        }
        if(upMove == 2 && vScroll.scrollTop > 0 && vScroll.scrollTop <= (vScroll.scrollHeight - 3*vScroll.offsetHeight/2)){
          this.upShow = true;
          this.downShow = true;
        }*/
        // 记录当前滚动条的位置

        if (upMove == 1 && vScroll.scrollTop < this.limitTop && this.canUp && !this.canUpPullMsgs) {
          // vScroll.scrollTop = this.limitTop;
          this.canUpPullMsgs = true;
          this.scrollLoad('prev', this.msgLimit);
        }
        if (upMove == 2 && (vScroll.scrollTop >= vScroll.scrollHeight - vScroll.offsetHeight - this.limitBottom) && this.canDown && !this.canDownPullMsgs) {
          // vScroll.scrollTop = vScroll.scrollTop - 30;
          this.canDownPullMsgs = true;
          this.scrollLoad('next', this.msgLimit);
        }
        if (vScroll.scrollTop <= this.limitTop) {
          this.scrollHide();
        }
        // 重新赋值
        curScrollTop = null;
        /*if(vScroll.scrollTop <= (vScroll.scrollHeight - 2*vScroll.offsetHeight)){
          this.downShow = true;
        }
        if(vScroll.scrollTop >= vScroll.offsetHeight && vScroll.scrollTop <= (vScroll.scrollHeight - 3*vScroll.offsetHeight/2)){
          this.upShow = true;
        }*/
        // 记住滚动的位置
        if (this.jsonData) {
          setStore('scrollHeight', {...this.jsonData, ...{[`${this.lesson}`]: vScroll.scrollTop}});
        } else {
          setStore('scrollHeight', {[`${this.lesson}`]: vScroll.scrollTop});
        }
        // 隐藏
        debounceTime = setTimeout(() => {
          this.scrollHide();
        }, 4000);
      },
      scrollUp() {
        var vScroll = document.getElementById('live-body');
        if (this.canUp) {
          this.scrollLoad('prev', -1, () => {
            setTimeout(() => {
              vScroll.scrollTop = this.limitTop;
              this.canUp = false;
            }, 100);
          });
        } else {
          vScroll.scrollTop = this.limitTop;
        }
      },
      scrollDown() {
        var vScroll = document.getElementById('live-body');
        if (this.canDown) {
          this.scrollLoad('next', -1, () => {
            setTimeout(() => {
              vScroll.scrollTop = vScroll.scrollHeight;
              this.canDown = false;
            }, 100);
          });
        } else {
          vScroll.scrollTop = vScroll.scrollHeight;
        }
      },
      scrollHide() {
        this.upShow = false;
        this.downShow = false;
      },
      playVideo(id) {
        // 播放视频
        let video = document.getElementById(id);
        let videoParent = video.parentNode;
        let videoSibling = videoParent.querySelector('.video');
        let count = 0;
        // 看看是否有正在播放的语音
        this.pauseAudioPlaying();
        // 是否已经播过
        if (video.getAttribute('played')) {
          video.play();
        } else {
          video.setAttribute('preload', 'auto');
          // 加载一下
          video.load();
          // 开始加载
          videoSibling.className = videoSibling.className + ' loading';
          // 监测播放
          (function observing(video) {
            if (video.readyState > 2 && count < 10) {
              // 开始播放
              // 标记已经播过
              video.setAttribute('played', 'played');
              return video.play();
            } else if (count >= 10) {
              return video.pause();
            }
            // 再次加载
            video.load();
            count++;
            //
            setTimeout(() => {
              observing(video);
            }, 1000);
          })(video);
        }
        // 监听播放
        Cov.off(video, 'play', () => {
        });
        Cov.on(video, 'play', () => {
          // 更新播放状态
          count = 0;
          videoSibling.className = 'video';
          this.$store.commit('UPDATE_VIDEO_STATUS', true);
        });
        // 监听暂停
        Cov.off(video, 'pause', () => {
        });
        Cov.on(video, 'pause', () => {
          // 更新播放状态
          videoSibling.className = 'video';
          this.$store.commit('UPDATE_VIDEO_STATUS', false);
        });
        // 监听错误
        Cov.off(video, 'error', () => {
        });
        Cov.on(video, 'error', () => {
          videoParent.className += ' error';
          // 更新播放状态
          videoSibling.className = 'video';
          this.$store.commit('UPDATE_VIDEO_STATUS', false);
        });
      },
      playPCVideo(id) {
        // 播放视频
        let video = document.getElementById(id);
        let videoParent = video.parentNode;
        let videoSibling = videoParent.querySelector('.video');
        let count = 0;
        // 看看是否有正在播放的语音
        this.pauseAudioPlaying();
        // 是否已经播过
        if (video.getAttribute('played')) {
          video.play();
        } else {
          video.setAttribute('preload', 'auto');
          // 加载开始
          video.load();
          // 开始加载
          videoSibling.className = videoSibling.className + ' loading';
          // 监测播放
          (function observing(video) {
            if (video.readyState > 2 && count < 10) {
              // 标记已经播过
              video.setAttribute('played', 'played');
              // 开始播放
              return video.play();
            } else if (count >= 10) {
              return video.pause();
            }
            // 再次加载
            video.load();
            count++;
            //
            setTimeout(() => {
              observing(video);
            }, 1000);
          })(video);
        }
        // 监听播放
        Cov.off(video, 'play', () => {
        });
        Cov.on(video, 'play', () => {
          videoParent.querySelector('.video').style.display = 'none';
          // 更新播放状态
          count = 0;
          videoSibling.className = 'video';
          this.$store.commit('UPDATE_VIDEO_STATUS', true);
        });
        // 监听暂停
        Cov.off(video, 'pause', () => {
        });
        Cov.on(video, 'pause', () => {
          videoParent.querySelector('.video').style.display = 'flex';
          // 更新播放状态
          count = 0;
          videoSibling.className = 'video';
          this.$store.commit('UPDATE_VIDEO_STATUS', false);
        });
        // 看看是否有正在播放的语音
        this.pauseAudioPlaying();
        // 监听错误
        Cov.off(video, 'error', () => {
        });
        Cov.on(video, 'error', () => {
          videoParent.className += ' error';
          // 更新播放状态
          count = 0;
          videoSibling.className = 'video';
          this.$store.commit('UPDATE_VIDEO_STATUS', false);
        });
      },
      pauseAudioPlaying() {
        // 获取所有audio
        console.log('pause audio');
        let refs = this.$refs;
        let audios = refs.audios;
        // 遍历所有audio
        if (Array.isArray(audios)) {
          for (let au of audios) {
            if (au.state.playing) {
              au.pause();
            }
          }
        }
      },
      scrollLoad(toward, limit, callback) {
        // 开始加载
        this.$store.commit('UPDATE_LOADING', true);
        // dom
        let vScroll = document.getElementById('live-body');
        // let vSms = document.getElementById('live_sms_list');
        // 获取游标
        let cursor;
        try {
          if (toward == 'prev') {
            cursor = this.messageInfo[0].cursor;
            this.upShow = false;
          } else {
            cursor = this.messageInfo[this.messageInfo.length - 1].cursor;
            this.downShow = false;
          }
          ;
        } catch (e) {
        }
        // 滚动加载
        this.$store.dispatch('fetchHistory', {
          cursor: cursor,
          limit: limit,
          toward: toward,
          lesson_sn: this.lesson.split('-')[0]
        }).then((msgList) => {
          //
          if (msgList.length == 0) {
            //
            if (toward == 'prev') {
              this.canUp = false;
            } else if (toward == 'next') {
              this.canDown = false;
            } else {
              this.canUp = false;
              this.canDown = false;
            }
            ;
            this.$store.commit('UPDATE_LOADING', false);
            // 是否有回调
            if (callback) {
              callback();
            }
            return;
          }
          // 记录当前请求的第一条store中的cursor
          if (this.lessonInfo.step == 'finish') {
            setStore(`${this.lesson}-finish-cursor`, msgList[0].cursor);
          } else {
            //setStore(`${this.lesson}-cursor`, msgList[0].cursor);
          }
          // 不能拉消息
          if (msgList.length < this.msgLimit) {
            if (toward == 'prev') {
              this.canUp = false;
            } else if (toward == 'next') {
              this.canDown = false;
            } else {
              this.canUp = false;
              this.canDown = false;
            }
            ;
          } else {
            clearTimeout(timer);
            this.upShow = true;
            this.downShow = true;
            // 时间计数器
            timer = setTimeout(() => {
              // 判断方向
              this.upShow = true;
              this.downShow = true;
            }, 4000);
          }
          // 判断位置
          /*let liHeight = vSms.children[msgList.length].offsetTop;
          if(!isPC){
            liHeight -= 100;
          }*/
          // 重组备份
          let tempList = [];
          // 循环
          for (var i = 0; i <= msgList.length - 1; i++) {//遍历消息，按照时间从后往前
            var msg = msgList[i];
            // 是否有昵称
            msg.accountNick = msg.accountNick || this.teacherInfo.name;
            msg.history = true;
            // var _msg = exportAssembleMsg(msg);
            // console.log('foo', _msg)
            tempList.push(exportAssembleMsg(msg));
          }
          // 递归获取头像
          this.recursion(0, msgList.length, msgList);
          // 实施
          if (toward == 'prev') {
            this.$store.commit('UPDATE_HISTORY_MESSAGE', tempList);
          } else {
            this.$store.commit('UPDATE_MESSAGE', tempList);
          }
          ;
          //
          this.$nextTick(() => {
            //
            if (toward == 'prev') {
              this.canUpPullMsgs = false;
            } else {
              this.canDownPullMsgs = false;
            }
            // 是否有回调
            if (callback) {
              callback();
            } else {
              setTimeout(() => {
                if (toward == 'prev') {
                  vScroll.scrollTop = vScroll.scrollTop + this.limitTop;
                }
              }, 100);
            }
            //
            this.$store.commit('UPDATE_LOADING', false);
          });
        }, () => {
          if (toward == 'prev') {
            this.canUp = false;
          } else {
            this.canDown = false;
          }
          this.$store.commit('UPDATE_LOADING', false);
          console.log('fail');
        });
      },
      longPolling(cursor, lesson_sn) {
        // 10秒一次轮询
        if (this.lesson) {
          let opt = {
            cursor: cursor,
            limit: 1,
            toward: 'next',
            lesson_sn: lesson_sn,
          };
          // 开始请求
          this.$store.dispatch('fetchHistory', opt).then((msgList) => {
            //
            if (msgList.length) {
              // 重组备份
              let tempList = [];
              // 循环
              for (var i = 0; i <= msgList.length - 1; i++) {//遍历消息，按照时间从后往前
                var msg = msgList[i];
                // 是否有昵称
                msg.accountNick = msg.accountNick || this.teacherInfo.name;
                msg.history = false;

                if (msg.content[0].MsgType == 'SYSTEM') {
                  switch (msg.content[0].MsgCommand) {
                    case 'lessonStep':
                      this.lessonInfo.step = msg.content[0].MsgContent;
                      break;
                    case 'deleteRecord':
                      let obj = document.querySelector(`#m-${msg.content[0].MsgContent}`)
                      if (obj) {
                        obj.remove()
                      }
                      break;
                  }
                  msg.isSystem = true;
                }

                var _msg = exportAssembleMsg(msg)
                if (_msg.bookmark) {
                  this.bookmark.list.push({cursor: _msg.cursor, text: _msg.bookmark.text})
                }

                tempList.push(_msg);
              }
              // 递归获取头像
              this.recursion(0, msgList.length, msgList);
              // 实施
              this.$store.commit('UPDATE_MESSAGE', tempList[0]);
              // 滚动监测
              this.$nextTick(() => {
                exportInspectScroll();
                // 继续轮询
                setTimeout(() => {
                  this.longPolling(tempList[0].cursor, lesson_sn);
                }, 10000);
              });
            } else {
              // 继续轮询
              setTimeout(() => {
                this.longPolling(cursor, lesson_sn);
              }, 10000);
            }
          }, () => {
            // 继续轮询
            setTimeout(() => {
              this.longPolling(cursor, lesson_sn);
            }, 10000);
            console.log('fail');
          });
        }
      },
    },
  };

</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";
  .video-js
    display: flex !important;
    justify-content: center;
    align-items: center;
    width: 360px;
    height: 200px;
    .vjs-big-play-button
      top: initial !important;
      left: initial !important;
    .mobile
      width: 300px;
      height: 150px;

  video, .video-img
    width: 360px;
    height: 200px;

  .video-img
    display: none

  .sweet-alert
    .invite-guest
      padding-top: 8px;
      word-break: break-all;
      text-align: left;

  .live-page
    .transparent
      width: 1px;
      height: 1px;
      opacity: 0;
      -webkit-opacity: 0;

  .btn-bookmark
    position: fixed;
    top: 10px;
    right: 10px;
    z-index: 999;
    px2px(width, 60px);
    px2px(height, 60px);
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #fff;

  .is-pc .btn-bookmark
    top: 105px;
    right: initial;
    margin-left: 520px;

  .is-pc .owner .btn-bookmark {
    top: 175px;
  }

  .btn-bookmark > i, .bookmark > i {
    px2px(font-size, 50px);
    color: #2F57DA;
  }

  .btn-bookmark.active
    background: #2F57DA;
    .iconfont
      color: #fff !important;

  .frm-bookmark
    position: fixed;
    px2px(top, 100px);
    z-index: 99;
    background: #fff;
    width: 100%;
    px2px(max-height, 550px);
    px2px(font-size, 40px);
    overflow-y: scroll;
    overflow-x: hidden;
    -webkit-overflow-scrolling: touch;
    border: 1px solid #fff;
    border-top: 1px solid #2F57DA;
    box-shadow: 0 10px 10px #fff;

  .is-pc .frm-bookmark {
    top: 135px;
    width: 570px;
  }

  .is-pc .owner .frm-bookmark {
    top: 205px;
  }

  .frm-bookmark > div {
    px2px(height, 70px);
    px2px(padding-left, 20px);
    border-bottom: 1px solid #e5e5e5;
    border-left: 10px solid #fff;
    display: flex;
    align-items: center;
  }

  .admire {
    display: flex;
    position: relative;
    /*justify-content: center;*/
    align-items: center;
    /*px2px(height, 70px);*/
    px2px(font-size, 28px);
    padding: 0.5em 1em 0.5em 1.5em;
    text-align: center;
    background: #FFEBEB;
    border: 2px solid #FAC5BF;
    border-radius: 2em;
    img {
      position: absolute;
      left: 0;
      top: 50%;
      margin-right: 5px;
      transform: translate(-50%, -50%);
      /*px2px(height, 40px);*/
      /*px2px(width, 40px);*/
      height: 2.4em;
      width: 2.4em;
      border-radius: 50%;
    }
    .nickname {
      margin: 0 0.2em;
    }
  }
  .is-pc .admire {
    margin: 0.5em;
  }

  li.is-bookmark {
    display: flex;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 9;
    background: #fff;
    px2px(height, 70px);
    px2px(font-size, 40px);
    /*padding-left: 0 !important;*/
    border-left: 10px solid #2F57DA;
    white-space: nowrap;
    overflow: hidden;
  }

  li.is-system.is-bookmark {
    px2px(padding-left, 20px)
    px2px(margin-top, 50px)
    px2px(margin-bottom, 50px)
    box-shadow: 0 -10px 10px #ddd;
  }

  li.is-system.is-admire {
    display: flex;
    justify-content: center;
    padding: 0;
  }

  .is-mobile li.is-system.is-admire {
    padding-top: 40px;
  }


  .is-pc li.is-bookmark {
    top: 30px;
    padding-left: 10px !important;
  }

  .markdown li:before {
    padding: 0 .1rem;
    margin-left: -.2rem;
    content: "•";
    color: #5774ed;
  }

  .markdown li {
    padding: .5em !important;
  }

  .markdown a {
    color: #2f57da;
    text-decoration: underline;
  }

  .markdown img {
    max-width: 100%;
  }

  .markdown pre{
    margin: 1em;
    padding: .5em 1em;
    white-space: pre-wrap;
    border-left: .02rem solid #2F57DA;
    background: #d6ddf2;
  }
  .markdown code {
    background: #d6ddf2;
    padding: 2px 4px;
    margin: 0 2px;
    font-size: 90%;
    border-radius: 3px;
    color: #2F57DA;
  }
  .markdown pre code {
    padding: 0;
    margin: 0;
    color: inherit;
  }

  .flex-row {
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
  }

  .playbackRate-frm {
    margin-right: 1%; /*这里是选择框里面的样式*/
  }

  .playbackRate {
    width: 37px;
    font-size: 1em;
    color: #ccc;
    appearance: none;
    -moz-appearance: none;
    -webkit-appearance: none; /*这三个是隐藏默认样式*/
    background: transparent;
    border: 0;
  }
</style>
