/**
 * Author: LyonWong
 * Date: 2019-03-11
 */

import Vue from 'vue'

let ctrl = new Vue({
  data: {
    list: [],
    photos: [],
    audios: [],
    videos: [],
    playing: null,
    playbackRate: 1
  },
  methods: {
    push: function(content) {
      let offset = this.list.length
      this.list.push(content)
      if (content.type === 'image') {
        this.photos.push(offset)
      }
      return offset
    },
    next: function(offset, action) { // play next
      for (++offset; offset<this.list.length; offset++) {
        let content = this.list[offset]
        if (content.type==='audio') {
          let audio = document.querySelector(`#section-${offset} audio`)
          switch (action) {
            case 'play':
              audio.playbackRate = this.playbackRate
              this.playing = offset
              audio.currentTime = 0 // 自动连播时归零
              audio.play()
              break;
            case 'preload':
              if (!audio.currentTime) { // 未播放过的记录预加载
                audio.preload = 'auto'
                audio.load()
              }
              break;
          }
          return
        }
        if (content.type==='video') {
          let video = document.querySelector(`#section-${offset} video`)
          switch (action) {
            case 'play':
              video.playbackRate = this.playbackRate
              video.play()
              this.playing = offset
              break;
          }
          return
        }
      }
    },
    init: function() {
      console.log('auto')
      this.$on('play', (offset) => {
        console.log('play', offset)
        if (this.playing && this.playing !== offset) {
          console.log('auto pause', this.playing)
          let mtag = this.list[this.playing].type
          document.querySelector(`#section-${this.playing} ${mtag}`).pause()
        }
        this.playing = offset
        this.next(this.playing, 'preload')
      })
      this.$on('ended', () => {
        console.log('ended', this.playing)
        this.next(this.playing, 'play')
      })
      this.$on('ratechange', (rate) => {
        this.playbackRate = rate
      })
    }
    /*
    changeRate(rate) {
      this.playbackRate = rate
      if (this.playing) {
        let mtag = this.list[this.playing].type
        document.querySelector(`#section-${this.playing} ${mtag}`).playbackRate = rate
      }
    }
    */
  }
})

export default ctrl
