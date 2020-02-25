<template>
  <div class="section-audio">
    <div class="audio">
      <audio ref="audio" controlslist="nodownload" :src="content.src"
             @canplay="onCanPlay"
             @play="playing=true"
             @pause="playing=false"
             @ended="ended"
             @timeupdate="onTimeUpdate"
             >
      </audio>
      <div class="flex-row">
        <div class="ctrl flex-row">
          <i class="btn icon-yike icon-triangle-r" @click="play" v-show="!playing"></i>
          <i class="btn icon-yike icon-pause" @click="pause" v-show="playing"></i>
        </div>
        <div ref="progress" class="progress flex-item" @click="onSlide">
          <div class="passed" :style="{width: progress+'%'}">
            <span class="slider"></span>
          </div>
        </div>
        <div class="time">{{remainingTime}}</div>
        <div class="rate" v-if="1">
          <select v-model="control.playbackRate" @change="onRateChange">
            <option value="0.5">0.5x</option>
            <option value="0.75">0.75x</option>
            <option value="1">1.0x</option>
            <option value="1.25">1.25x</option>
            <option value="1.5">1.5x</option>
            <option value="2">2.0x</option>
          </select>
        </div>
      </div>
    </div>
    <section-markdown v-if="content.text" :content="content"></section-markdown>
  </div>
</template>

<script>
  import SectionMarkdown from "./Markdown";
  export default {
    name: 'section-audio',
    components: {SectionMarkdown},
    props: ['content', 'control', 'offset'],
    data() {
      return {
        playing: false,
        currentTime: 0,
        duration: null,
        progress: 0,
        slider: null,
        playbackRate: this.control.playbackRate

      }
    },
    created() {
    },
    mounted() {
    },
    methods: {
      onCanPlay(e) {
        this.duration = e.target.duration
      },
      play() {
        this.control.$emit('play', this.offset)
        this.$refs.audio.play()
      },
      pause() {
        this.control.$emit('pause')
        this.$refs.audio.pause()
      },
      ended() {
        this.control.$emit('ended')
        this.playing = false
      },
      onTimeUpdate(e) {
        this.currentTime = e.target.currentTime
        this.progress  = Math.round(this.currentTime *10000 / this.duration) / 100
      },
      onRateChange() {
        this.$refs.audio.playbackRate = this.control.playbackRate
      },
      onSlide(e) {
        let rect = e.target.getBoundingClientRect()
        this.slider = {
          x: rect.x,
          width: this.$refs.progress.clientWidth
        }
        this.progress = Math.round((e.x - this.slider.x) * 10000 / this.slider.width)/100
        this.$refs.audio.currentTime = this.duration * this.progress / 100
      },
      transTime(seconds) {
        let m = Math.floor(seconds / 60)
        let s = Math.floor(seconds % 60)
        if (s < 10) {
          s = '0' + s
        }
        return `${m}:${s}`
      }
    },
    computed: {
      totalTime() {
        return this.transTime(this.duration)
      },
      playedTime() {
        return this.transTime(this.currentTime)
      },
      remainingTime() {
        return this.transTime(Math.max(0, this.duration - this.currentTime))
      }
    }
  }
</script>

<style scoped>
  .audio {
    background: #e9ecf8;
    border-radius: 2em;
    /* padding: 1em; */
  }
  .audio, audio {
  }
  /*
  audio::-webkit-media-controls-mute-button {
    display: none !important;
  }

  audio::-webkit-media-controls-volume-slider {
    display: none !important;
  }
  */
  .progress {
    /* background: #CFD7F3; */
    background: rgba(47,87,218,0.3);
    height: 0.2em;
    border-width: 1em 0;
    border-color: #e9ecf8;
    border-style: solid;
    margin: 0 1em;
  }
  .progress > .passed {
    position: relative;
    height: 0.2em;
    background: #2F57DA;
  }
  .progress > .passed > .slider {
    position: absolute;
    right: 0;
    top: -0.2em;
    width: 0.3em;
    height: .6em;
    background: #2F57DA;
    border-radius: .1em;
    /*background: #f00;*/
  }
  .ctrl {
    /*margin-right: .5em;*/
  }
  .time {
    margin-right: 1em;
  }
  .ctrl > i{
    border-radius: 50%;
    font-size: 1em;
    padding: 1em;
    background: #2F57DA;
    color: #fff;
  }
  .rate {
    margin-right: 1em;
  }
  .rate select {
    border: none;
    color: #2F57DA;
    appearance:none;
    background: transparent;
    font-size: 1em;
    border: none;
    outline: none;
    appearance: none;
    /* background: rgba(0,0,0,0.8) */
  }
  .rate option {
    background: rgba(0,0,0,0.8);
    color: #fff;
    padding: .1em 1em;
    margin: 1em;
    width: 4em;
  }
  .section-markdown {
    margin-top: 1em;
  }

</style>
