<template>
  <div class="section-video flex-col">
    <div class="video">
      <!--x5-video-player-type="h5"  可解决层级过高问题，但导致播放问题-->
      <video :id="id" class="video-js vjs-default-skin vjs-big-play-centered" controls :poster="poster" playsinline webkit-playsinline  x5-playsinline="true"
        @play="control.$emit('play', offset)"
        @pause="control.$emit('pause')"
        @ended="control.$emit('ended')"
        @ratechange="onRateChange"
      >
        <source :src="content.src" :type="type">
      </video>
    </div>
    <!--<video src="https://storage.sandbox.yike.fm/lesson/record/5ccead358cf2e" controls playsinline x5-playsinline="true" x5-video-player-type="h5"></video>-->
    <section-markdown v-if="content.text" :content="content"></section-markdown>
  </div>
</template>

<script>
  import 'video.js/dist/video-js.css';
  import videojs from 'video.js';
  import 'videojs-contrib-hls';
  import 'videojs-playbackrate-adjuster';
  // import SectionMarkdown from "./Markdown";
  const SectionMarkdown = r => require.ensure([], () => r(require('./Markdown')), 'markdown')
  export default {
    name: 'section-video',
    components: {SectionMarkdown},
    props: ['content', 'control', 'offset'],
    data() {
      return {
        id: 'video-id',
        type: 'video/mp4',
        poster: null,
        video: null,
        playing: false
      }
    },
    created() {
      let res = this.content.src.match(/\/(\w+)\.?(\w+)?$/)
      if (res) {
        this.id = `video-${res[1]}`
        switch (res[2]) {
          case undefined:
            this.type = 'video/mp4'
            break
          case 'm3u8':
            this.type = 'application/x-mpegURL'
            break
          default:
            this.type = `video/${res[2]}`
        }
      }
      this.poster = this.content.poster || this.content.src.replace(/(\.\w+)?$/, '-preview.jpg')
    },
    mounted() {
      this.video = videojs(this.id, {
        textTrackDisplay: false,
        posterImage: true,
        errorDisplay: false,
        controlBar: true,
        playbackRates: [0.5, 0.75, 1, 1.5, 2]
      })
      this.video.on('playing', () => {
        this.playing = true
        document.onkeydown = (e) => {
          switch (e.code) {
            case 'Space':
              if (this.playing) {
                this.video.pause()
              } else {
                this.video.play()
              }
              e.preventDefault()
              break;
          }
        }
      })
      this.video.on(['pause'], () => {
        this.playing = false
      })
    },
    computed: {},
    methods: {
      onRateChange(e) {
        this.control.$emit('ratechange', e.target.playbackRate)
      }
    }
  }
</script>

<style scoped>
  video, .video, .video-js {
    width: 6.4rem;
    height: 3.6rem;
  }
  .video {
    box-shadow: #ccc 0 0 .04rem;
  }
  .section-markdown {
    margin-top: 1em;
  }
</style>
