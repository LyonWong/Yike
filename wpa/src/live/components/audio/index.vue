<template>
  <div class="rd-audio-player">
    <audio :id="id" :data-index="index" preload="none"></audio>
    <!--<audio :id="id" :data-index="index" preload="none" v-if="isWeiXin && isPC"></audio>-->
    <div class="rd-audio-cover" @click="touchCover">
      <button class="rd-audio-player-btn" transition="bounce" :class="{'pause':state.playing}"></button>
    </div>
    <div class="rd-audio-contrl">
      <div class="rd-audio-slider-container" @click="touchSlider">
        <div class="rd-audio-slider-header"></div>
        <div class="rd-audio-slider">
          <div class="rd-audio-slider-rail">
            <div class="rd-audio-slider-rail-inner" :style="{ 'width': mu.state.progress + '%' }"></div>
          </div>
        </div>
      </div>
      <div class="rd-audio-time duration">
        {{mu.state.lastTimeFormat}}
        <span v-if="mu.state.duration">/{{mu.state.durationTimerFormat}}</span>
        <span v-if="!mu.state.duration">/. . .</span>
      </div>
      <select v-if="0 && isPlay" class="playbackRate" id="rangeButton" @change="playSpeed($event)">
        <option>0.5倍速</option>
        <option>0.75倍速</option>
        <option selected>1倍速</option>
        <option>1.25倍速</option>
        <option>1.5倍速</option>
        <option>2倍速</option>
      </select>
    </div>
    <div class="buffer" v-if="buffering">
      <div class="double-bounce1"></div>
      <div class="double-bounce2"></div>
    </div>
    <div class="iconfont icon-dot" v-if="!played"></div>
  </div>
</template>

<script type="text/javascript">
  import {mapState} from 'vuex';
  import {setStore, getStore} from '@lib/js/mUtils';
  import VueAudio from './vueAudio.js';

  const pad = (val) => {
    val = Math.floor(val)
    if (val < 10) {
      return '0' + val
    }
    return val + ''
  }
  const timeParse = (sec) => {
    let min = 0
    min = Math.floor(sec / 60)
    sec = sec - min * 60
    return pad(min) + ':' + pad(sec)
  }

  export default {
    name: 'v-audio',
    props: {
      id: {
        type: String
      },
      src: {
        type: String
      },
      index: {
        type: null
      },
      history: {
        type: Boolean
      },
    },
    components: {},
    computed: {
      ...mapState([
        'audioPause',
        'videoPlaying',
      ])
    },
    data() {
      return {
        mu: {
          state: {
            startLoad: false,
            failed: false,
            try: 3,
            tried: 0,
            playing: false,
            paused: false,
            playbackRate: 1.0,
            progress: 0,
            currentTime: 0,
            duration: 0,
            volume: 1,
            loaded: '0',
            durationTimerFormat: '00:00',
            currentTimeFormat: '00:00',
            lastTimeFormat: '00:00'
          }
        },
        state: {
          liked: false,
          playing: false
        },
        isWeiXin: isWeiXin,
        isPC: isPC,
        initAudio: [],
        firstLoad: true,
        buffering: false,
        played: false,
        isEnd: false,
        isPlay: false,
      };
    },
    created() {
      // 初始化audio
      let refs = this.$parent.$refs;
      let audios = refs.audios;
      this.initAudio = Array.isArray(audios) ? [...audios] : audios;
    },
    mounted() {
      this.init();
    },
    updated() {

    },
    methods: {
      init() {
        this.mu = new VueAudio(this.src, {
          preload: false,
          autoplay: false,
          rate: 1,
          loop: false,
          volume: 1,
          audio: document.getElementById(this.id),
          ended: () => {
            this.ended()
          },
          pause: () => {
            this.pause();
          },
          error: () => {
            console.log('error+');
            this.audioError()
          },
        });
        // 获取audio对象
        let _audio = getStore(`audio-${this.id}`);
        let audios = this.initAudio;
        // 开始
        if (_audio) {
          _audio = JSON.parse(_audio);
          this.played = _audio.played || false;
        } else {
          // 是否是历史记录
          if (this.history) return;
          if (Array.isArray(audios)) {
            let audio = audios[audios.length - 1];
            if (audio.played && audio.isEnd && !this.videoPlaying) {
              // 播放
              this.play();
            }
          }
        }
        ;
      },
      touchCover() {
        if (this.state.playing) {
          this.pause()
        } else {
          this.prePlay()
        }
      },
      touchSlider(e) {
        let time = e.layerX / e.target.offsetWidth * this.mu.state.duration
        this.mu.setTime(time)
      },
      prePlay() {
        // 开始播放
        this.play();
      },
      play() {
        this.isPlay = true
        var self = this;
        // 是否需要打上预加载
        if (self.mu.$Audio && self.mu.$Audio.getAttribute('preload') == 'none') {
          console.log('preload-->auto');
          self.mu.$Audio.setAttribute('preload', 'auto');
        }
        // 所有组件暂停
        self.pausePlaying();
        // 下一个组件预加载
        if (!this.isPC) {
          self.nextLoad();
        }
        // 正在播放
        self.state.playing = true;
        // 结束标志设置
        self.isEnd = false;
        // 重置加载次数
        self.mu.state.playStateCount = 0;
        // 是否是首次加载
        if (self.firstLoad) {
          let isMobileDevice = (navigator.userAgent.match(/(iPhone|iPad|ios|Android)/i) == null) ? false : true;
          // 是否是ios设备
          if (!isMobileDevice) {
            // 是否已经播放过
            if (!self.played) {
              setStore(`audio-${self.id}`, {played: true});
              self.played = true;
            }
            //
            self.firstLoad = false;
            // 记住正在播放的audio
            // self.$store.commit('UPDATE_PLAYER', self.mu.$Audio);
            self.$store.commit('UPDATE_PLAYER', self);
            return self.mu.play();
          }
          self.buffering = true;
          // ios开始加载
          self.mu.$Audio.load();
          // 计时开始
          let timeStart = new Date().getTime();
          let count = 0;
          let reCount = 0;
          // observer 是否可以播放
          (function observerAudio() {
            let time = new Date().getTime();
            // 是否已经加载好
            if (self.mu.$Audio.readyState < 2) {
              if (isWeiXin && count < 1) {
                timeStart = new Date().getTime();
                // ios开始加载
                //self.mu.$Audio.load();
                WeixinJSBridge.invoke('getNetworkType', {}, (e) => {
                  self.mu.$Audio.play();
                  count++;
                });
              } else if ((time - timeStart) > 6000) {
                if (reCount < 10) {
                  self.mu.$Audio.load();
                } else {
                  self.buffering = false;
                  self.pause();
                  return;
                }
                reCount++;
              }
              if (self.state.playing) {
                setTimeout(function () {
                  observerAudio();
                }, 500);
              }
            } else {
              self.buffering = false;
              // 是否正在播放
              if (!self.state.playing) return;
              // 是否已经播放过
              if (!self.played) {
                setStore(`audio-${self.id}`, {played: true});
                self.played = true;
              }
              // 记住正在播放的audio
              self.$store.commit('UPDATE_PLAYER', self);
              // 开始播放
              self.mu.play();
              self.firstLoad = false;
            }
          })();
        } else {
          // 记住正在播放的audio
          self.$store.commit('UPDATE_PLAYER', self);
          self.mu.play();
        }
      },
      pause() {
        this.state.playing = false;
        this.buffering = false;
        this.mu.pause();
      },
      ended() {
        // 清楚正在播放的audio记录
        this.$store.commit('UPDATE_PLAYER', null);
        this.isEnd = true;
        this.pause();
        this.nextPlay();
      },
      nextPlay() {
        // 获取所有audio
        let refs = this.$parent.$refs;
        let audios = refs.audios;
        let canPlay = false;
        // 遍历所有audio
        if (Array.isArray(audios)) {
          for (let au of audios) {
            if (canPlay) {
              au.play();
              break;
            }
            if (au.id == this.id) {
              canPlay = true;
            }
          }
        }
      },
      nextLoad() {
        // 获取所有audio
        let refs = this.$parent.$refs;
        let audios = refs.audios;
        let canLoad = false;
        // 遍历所有audio
        if (Array.isArray(audios)) {
          for (let au of audios) {
            if (canLoad) {
              au.preLoad();
              break;
            }
            if (au.id == this.id) {
              canLoad = true;
            }
          }
        }
      },
      preLoad() {
        // 重置加载次数
        this.mu.state.playStateCount = 0;
        this.mu.state.tryPlaying = false;
        this.mu.$Audio.load();
        this.mu.preload();
      },
      pausePlaying() {
        // 获取所有audio
        let refs = this.$parent.$refs;
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
      audioError() {
        this.firstLoad = true;
//        console.log('错误回调拉!!!！');
      },
//      volplus () {
//          this.mu.setVolume(this.mu.state.volume + 0.1)
//      },
//      volminus () {
//          this.mu.setVolume(this.mu.state.volume - 0.1)
//      }
      playSpeed(e) {
        this.mu.$Audio.playbackRate = e.target.value.replace(/倍速/, '')
      }
    },
  };
</script>
<style scoped>
  .playbackRate {
    font-size: .6em;
    color: rgb(153, 153, 153);
    padding: 0.3% 2% 0.3% 1%;
    margin: 17px 0 0 8px;
    border: 1px solid #ccc;
    appearance: none;
    -moz-appearance: none;
    -webkit-appearance: none;
  }
</style>
<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";
</style>
