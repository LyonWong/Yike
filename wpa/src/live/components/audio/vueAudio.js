const pad = (val) => {
    val = Math.floor(val)
    if (val < 10) {
        return '0' + val
    }
    return val + ''
}

const Cov = {
    on (el, type, func) {
        el.addEventListener(type, func)
    },
    off (el, type, func) {
        el.removeEventListener(type, func)
    }
}

class VueAudio {
    constructor (src, options = {}) {
        let preload = true
        if (options.preload !== undefined && options.preload === false) {
            preload = false
        }
        this.tmp = {
            src: src,
            options: options
        }
        this.state = {
            preload: preload,
            startLoad: false,
            failed: false,
            try: 2,
            tried: 0,
            playing: false,
            paused: false,
            playbackRate: 1.0,
            progress: 0,
            currentTime: 0,
            duration: 0,
            volume: 0.5,
            loaded: '0',
            playStateCount: 0,
            tryPlaying: false,
            durationTimerFormat: '00:00',
            currentTimeFormat: '00:00',
            lastTimeFormat: '00:00'
        }
        this.hook = {
            playState: [],
            loadState: []
        }
        //if (preload) {
        this.init(src, options)
        //}
    }

    init (src, options = {}) {
        if (!src) throw 'src must be required'
        this.state.startLoad = true
        if (this.state.tried >= this.state.try) {
            this.state.failed = true
            /*let rexp = /([\?\&])tp=\w*!/;
            let _src = src;*/
            // 是否错误
            if(options.error){
              //
              // if(rexp.test(_src)){
              //   _src = _src.replace(rexp, `$1tp=${Math.round(new Date().getTime() / 1000)}`);
              // }else{
              //   let rexp2 = /\?/;
              //   if(rexp2.test(_src)){
              //     _src = `${_src}&tp=${Math.round(new Date().getTime() / 1000)}`;
              //   }else{
              //     _src = `${_src}?tp=${Math.round(new Date().getTime() / 1000)}`;
              //   }
              // }
              // // 重新绑定
              // this.tmp.src = _src;
              setTimeout(()=>{
                options.error();
                options.pause();
              })
            }
            return;
        }
        this.$Audio = options.audio;
        this.$Audio.setAttribute('src', this.tmp.src);
        Cov.off(this.$Audio, 'error', ()=>{});
        Cov.on(this.$Audio, 'error', () => {
            this.state.tried++;
            this.state.preload = true;
            this.init(src, options);
        });
        Cov.off(this.$Audio, 'ended', ()=>{});
        Cov.on(this.$Audio, 'ended', () => {
          options.ended();
        });
        if (options.autoplay) {
            this.play()
        }
        if (options.rate) {
            this.$Audio.playbackRate = options.rate
        }
        if (options.loop) {
            this.$Audio.loop = true
        }
        if (options.volume) {
            this.setVolume(options.volume)
        }
        // 是否有预加载
        if(this.state.preload){
          this.preload()
        }
    }

    loadState () {
        if (this.$Audio.readyState >= 2) {
            Cov.off(this.$Audio, 'progress')
            Cov.on(this.$Audio, 'progress', this.updateLoadState.bind(this))
        } else {
            Cov.off(this.$Audio, 'loadeddata')
            Cov.on(this.$Audio, 'loadeddata', () => {
                this.loadState()
            })
        }
    }

    updateLoadState (e) {
        if (!this.$Audio) return
        this.hook.loadState.forEach(func => {
            func(this.state)
        })
        this.state.duration = Math.round(this.$Audio.duration * 100) / 100
        try{
          this.state.loaded = Math.round(10000 * this.$Audio.buffered.end(0) / this.$Audio.duration) / 100
          this.state.durationTimerFormat = this.timeParse(this.state.duration)
        }catch(e){}
    }

    updatePlayState (e) {
        this.state.currentTime = Math.round(this.$Audio.currentTime * 100) / 100
        this.state.duration = Math.round(this.$Audio.duration * 100) / 100
        this.state.progress = Math.round(10000 * this.state.currentTime / this.state.duration) / 100

        this.state.durationTimerFormat = this.timeParse(this.state.duration)
        this.state.currentTimeFormat = this.timeParse(this.state.currentTime)
        this.state.lastTimeFormat = this.timeParse(this.state.duration - this.state.currentTime)

        // 判断是否是NaN
        try{
          if(isNaN(this.state.duration)){
            this.state.lastTimeFormat = '00:00';
            this.tmp.options.error();
          };
        }catch(e){};

        this.hook.playState.forEach(func => {
            func(this.state)
        })
    }

    updateHook (type, func) {
        if (!(type in this.hook)) throw 'updateHook: type should be playState or loadState'
        this.hook[type].push(func)
    }

    play () {
        // 开始尝试播放
        this.state.tryPlaying = true;
      // 设置播放速度
      let audioSpeed= document.getElementById('audio-speed');
      this.$Audio.playbackRate = audioSpeed ? audioSpeed.value : 1;
        //
        if (this.state.startLoad) {
            if (!this.state.playing && this.$Audio.readyState >= 2) {
                this.$Audio.play();
                this.state.paused = false;
                this.state.playing = true;
                Cov.off(this.$Audio, 'timeupdate');
                Cov.on(this.$Audio, 'timeupdate', this.updatePlayState.bind(this));
            } else {
                // 绑定
                Cov.off(this.$Audio, 'loadeddata');
                Cov.on(this.$Audio, 'loadeddata', () => {
                    // 叠加
                    // 判断是否继续执行
                    if (this.state.playStateCount < 20) {
                      setTimeout(() => {
                        this.play();
                      }, this.state.playStateCount * 50);
                    }else{
                      this.tmp.options.error();
                      this.tmp.options.pause();
                    }
                });
                // 加载
                if(this.state.playStateCount++ < 15){
                  this.$Audio.load();
                }
            }
        }
        /*else {
            this.state.preload = true;
            this.init(this.tmp.src, this.tmp.options)
            Cov.on(this.$Audio, 'loadeddata', () => {
                this.play()
            })
        }*/
    }

    destroyed () {
        this.$Audio.pause()
        Cov.off(this.$Audio, 'timeupdate', this.updatePlayState)
        Cov.off(this.$Audio, 'progress', this.updateLoadState)
    }

    pause () {
        this.$Audio.pause()
        this.state.paused = true
        this.state.playing = false
        this.$Audio.removeEventListener('timeupdate', this.updatePlayState)
    }

    setVolume (number) {
        if (number > -0.01 && number <= 1) {
            this.state.volume = Math.round(number * 100) / 100
            this.$Audio.volume = this.state.volume
        }
    }

    preload () {
      if (this.state.startLoad && !this.state.tryPlaying) {
        if (!this.state.playing && this.$Audio.readyState >= 2) {
          Cov.off(this.$Audio, 'timeupdate')
          Cov.on(this.$Audio, 'timeupdate', this.updateLoadState.bind(this))
        } else {
          // 绑定
          Cov.off(this.$Audio, 'loadeddata');
          Cov.on(this.$Audio, 'loadeddata', () => {
            // 叠加
            // 判断是否继续执行
            if (this.state.playStateCount < 20) {
              setTimeout(() => {
                this.preload();
              }, this.state.playStateCount * 50);
            }else{
              this.tmp.options.error();
              this.tmp.options.pause();
            }
          });
          // 加载
          if(this.state.playStateCount++ < 15){
            this.$Audio.load();
          }
        }
      }
      /*else {
        this.init(this.tmp.src, this.tmp.options);
        Cov.off(this.$Audio, 'loadeddata')
        Cov.on(this.$Audio, 'loadeddata', () => {
          this.preload()
        })
      }*/
    }

    setTime (time) {
        if (time < 0 && time > this.state.duration) {
            return false
        }
        this.$Audio.currentTime = time
    }

    timeParse (sec) {
        let min = 0
        min = Math.floor(sec / 60)
        sec = sec - min * 60
        return pad(min) + ':' + pad(sec)
    }

}

export default VueAudio
