<template>
  <div class="rd-audio-player">
    <audio :id="id"></audio>
    <div class="rd-audio-cover" @click="touchCover">
      <button class="rd-audio-player-btn" transition="bounce" :class="{'pause':state.playing}"></button>
    </div>
    <div class="rd-audio-contrl">
      <div class="rd-audio-slider-container" @click="touchSlider">
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
    </div>
    <div class="buffer" v-if="buffering">
      <div class="double-bounce1"></div>
      <div class="double-bounce2"></div>
    </div>
  </div>
</template>

<script type="text/javascript">
  import { mapGetters } from 'vuex';
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

  export default
  {
    name: 'v-audio',
    props: {
      id: {
        type: Number
      },
      src: {
        type: String
      },
    },
    components: {
    },
    computed: {
      ...mapGetters([
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
        initAudio: [],
        buffering: false,
      };
    },
    mounted() {
      this.init();
    },
    methods: {
      init () {
        this.mu = new VueAudio(this.src, {
          preload: true,
          autoplay: false,
          rate: 1,
          loop: false,
          volume: 1,
          audio: document.getElementById(this.id),
          ended: ()=>{
            this.ended()
          }
        });
      },
      touchCover () {
        if (this.state.playing) {
          this.pause()
        } else {
          this.prePlay()
        }
      },
      touchSlider (e) {
        let time = e.layerX / e.target.offsetWidth * this.mu.state.duration
        this.mu.setTime(time)
      },
      prePlay () {
        this.play();
      },
      play () {
        var self = this;
        // 正在播放
        self.state.playing = true;
        self.mu.play();
      },
      pause () {
        this.state.playing = false;
        this.buffering = false;
        this.mu.pause();
      },
      ended () {
        this.pause();
      },
    },
  };
</script>
<style scoped lang="stylus" rel="stylesheet/stylus">
  .rd-container {
    width: 100%;
    height: 100%;
    font-size: 22px;
  }
  .rd-audio-player {
    position: relative;
    display: flex;
    padding: 6px 5px 7px;
    border: 1px solid #efeff4;
    border-radius: 4px;
    -webkit-border-radius: 4px;
    justify-content: flex-start;
  }
  .rd-audio-cover {
    position: relative;
    height: 100%;
    width: 30px;
  }
  .rd-audio-player-btn {
    position: absolute;
    left: 4px;
    height: auto;
    border: none;
    padding: 1px;
    margin: -2px 0 0 -2px;
    background: transparent;
    outline: none;
    px2px(top, -4px);

    &:before {
      display: block;
      font-family: "iconfont" !important;
      font-style: normal;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      color: #12b7f5;
      content: "\e60a";
      font-size: 20px;
    }
    &.pause {
      &:before{
        content: "\e60d";
      }
    }
  }
  .rd-audio-player-icon {
    width: 2px;
    height: 2px;
    position: absolute;
    top: 50%;
    left: 50%;
    margin: -1px 0 0 -.7px;
  }
  .rd-audio-contrl {
    position: relative;
    display: -webkit-box;
    display: box;
    color: #666;
    padding: .5px;
    overflow: hidden;
  }
  .rd-audio-slider-container {
    display: -webkit-box;
    display: box;
    width: 140px;
    margin-top: 6px;
    margin-right: 15px;
  }
  .rd-audio-slider {
    position: relative;
    width: 100%;
  }
  .rd-audio-slider-rail {
    width: 100%;
    height: 4px;
    background: #d0f1fd;
    border-radius: 4px;
    -webkit-border-radius: 4px;
  }
  .rd-audio-slider-rail-inner {
    height: 100%;
    width: 0%;
    background: #12b7f5;
    transition: width 16ms;
  }
  .rd-audio-time.current,
  .rd-audio-time.duration {
    display: -webkit-box;
    display: box;
    padding-top: 2px;
    color: #aaaaaa;
    font-size: 12px;
  }
  .bounce-transition {
    animation-duration: .75s;
    animation-fill-mode: both;
  }
  .bounce-enter {
    animation-name: bounceIn;
  }
  .bounce-leave {
    animation-name: bounceOut;
  }
  .buffer {
    position: absolute;
    top: 0;
    right: -70px;
    width: 50px;
    height: 50px;
    -webkit-transition: all 0.3s ease-in-out 0;
    transition: all 0.3s ease-in-out 0;

    .double-bounce1, .double-bounce2 {
      width: 100%;
      height: 100%;
      border-radius: 50%;
      background-color: #333;
      opacity: 0.3;
      position: absolute;
      top: 0;
      left: 0;
      -webkit-animation: sk-bounce 2.0s infinite ease-in-out;
      animation: sk-bounce 2.0s infinite ease-in-out;
    }
    .double-bounce2 {
      -webkit-animation-delay: -1.0s;
      animation-delay: -1.0s;
    }
  }
  @-webkit-keyframes bounceIn {
    from, 20%, 40%, 60%, 80%, to {
      -webkit-animation-timing-function: cubic-bezier(0.215, 0.610, 0.355, 1.000);
      animation-timing-function: cubic-bezier(0.215, 0.610, 0.355, 1.000);
    }
    0% {
      opacity: 0;
      -webkit-transform: scale3d(.3, .3, .3);
      transform: scale3d(.3, .3, .3);
    }
    20% {
      -webkit-transform: scale3d(1.1, 1.1, 1.1);
      transform: scale3d(1.1, 1.1, 1.1);
    }
    40% {
      -webkit-transform: scale3d(.9, .9, .9);
      transform: scale3d(.9, .9, .9);
    }
    60% {
      opacity: 1;
      -webkit-transform: scale3d(1.03, 1.03, 1.03);
      transform: scale3d(1.03, 1.03, 1.03);
    }
    80% {
      -webkit-transform: scale3d(.97, .97, .97);
      transform: scale3d(.97, .97, .97);
    }
    to {
      opacity: 1;
      -webkit-transform: scale3d(1, 1, 1);
      transform: scale3d(1, 1, 1);
    }
  }
  @keyframes bounceIn {
    from, 20%, 40%, 60%, 80%, to {
      -webkit-animation-timing-function: cubic-bezier(0.215, 0.610, 0.355, 1.000);
      animation-timing-function: cubic-bezier(0.215, 0.610, 0.355, 1.000);
    }
    0% {
      opacity: 0;
      -webkit-transform: scale3d(.3, .3, .3);
      transform: scale3d(.3, .3, .3);
    }
    20% {
      -webkit-transform: scale3d(1.1, 1.1, 1.1);
      transform: scale3d(1.1, 1.1, 1.1);
    }
    40% {
      -webkit-transform: scale3d(.9, .9, .9);
      transform: scale3d(.9, .9, .9);
    }
    60% {
      opacity: 1;
      -webkit-transform: scale3d(1.03, 1.03, 1.03);
      transform: scale3d(1.03, 1.03, 1.03);
    }
    80% {
      -webkit-transform: scale3d(.97, .97, .97);
      transform: scale3d(.97, .97, .97);
    }
    to {
      opacity: 1;
      -webkit-transform: scale3d(1, 1, 1);
      transform: scale3d(1, 1, 1);
    }
  }
  @-webkit-keyframes bounceOut {
    20% {
      -webkit-transform: scale3d(.9, .9, .9);
      transform: scale3d(.9, .9, .9);
    }
    50%, 55% {
      opacity: 1;
      -webkit-transform: scale3d(1.1, 1.1, 1.1);
      transform: scale3d(1.1, 1.1, 1.1);
    }
    to {
      opacity: 0;
      -webkit-transform: scale3d(.3, .3, .3);
      transform: scale3d(.3, .3, .3);
    }
  }
  @keyframes bounceOut {
    20% {
      -webkit-transform: scale3d(.9, .9, .9);
      transform: scale3d(.9, .9, .9);
    }
    50%, 55% {
      opacity: 1;
      -webkit-transform: scale3d(1.1, 1.1, 1.1);
      transform: scale3d(1.1, 1.1, 1.1);
    }
    to {
      opacity: 0;
      -webkit-transform: scale3d(.3, .3, .3);
      transform: scale3d(.3, .3, .3);
    }
  }

  @-webkit-keyframes sk-bounce {
    0%, 100% { -webkit-transform: scale(0.0) }
    50% { -webkit-transform: scale(1.0) }
  }

  @keyframes sk-bounce {
    0%, 100% {
      transform: scale(0.0);
      -webkit-transform: scale(0.0);
    } 50% {
        transform: scale(1.0);
  -webkit-transform: scale(1.0);
  }
  }

  .is-pc {
    .rd-audio-player {
      padding: 0;
      height: 30px;

      .icon-dot {
        top: -10px;
        right: -28px;
      }
    }
    .rd-audio-cover {
      width: 45px;
    }
    .rd-audio-player-btn {
      width: 30px;
      height: 30px;
      top: 4px;
      left: 7px;
    }
    .rd-audio-contrl {
      margin-top: -7px;
      width: 200px;
      .rd-audio-slider-container {
        margin-top: 22px;
        margin-right: 10px;
        width: 120px;
      }
      .rd-audio-time.current,
      .rd-audio-time.duration {
        margin-top: 8px;
        padding-top: 4px;
        font-size: 12px;
      }
    }
    .buffer{
      right: -32px;
      width: 20px;
      height: 20px;
    }
  }
</style>
