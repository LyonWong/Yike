<template>
  <div class="section-video flex-col">
    <div class="video">
      <video :id="id" class="video-js vjs-default-skin vjs-big-play-centered" controls :poster="poster" playsinline webkit-playsinline  x5-playsinline="true">
        <source :src="content.src" :type="type">
      </video>
    </div>
    <section-markdown v-if="content.text" :content="content"></section-markdown>
  </div>
</template>

<script>
  import SectionMarkdown from "./Markdown";
  import 'video.js/dist/video-js.css';
  import videojs from 'video.js';
  import 'videojs-contrib-hls';
  import 'videojs-playbackrate-adjuster';

  export default {
    name: 'section-video',
    components: {SectionMarkdown},
    props: ['content'],
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
        playbackRates: [0.5, 0.75, 1, 1.25, 1.5, 2]
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
</style>
