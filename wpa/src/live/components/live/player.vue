<template>
  <!-- popular -->
  <div class="playing-audio">
    <i class="iconfont icon-laba" @click="goToPlaying"></i>
    <select id="audio-speed" v-model="speed" @change="changeSpeed">
      <option value="0.5">0.5X</option>
      <option value="0.75">0.75X</option>
      <option value="1" selected="selected">1X</option>
      <option value="1.25">1.25X</option>
      <option value="1.5">1.5X</option>
      <option value="2">2X</option>
    </select>
  </div>
</template>

<script>
  import {mapState} from 'vuex';
  var vScroll = null;
  var vSms = null;

  export default
  {
    name: 'v-player',
    components: {
    },
    data() {
      return {
        show: false,
        speed: 1,
      };
    },
    computed: {
      ...mapState([
        'playingAudio',
      ])
    },
    methods: {
      goToPlaying() {
        if(!vScroll || !vSms){
          vScroll = document.getElementById('live-body');
          vSms = document.getElementById('live_sms_list');
        };
        let player = this.playingAudio.mu.$Audio;
        let index = player.dataset.index;
        let liHeight = vSms.children[index].offsetTop;
        if(!isPC){
          liHeight -= 100;
        }
        vScroll.scrollTop = liHeight;
      },
      changeSpeed() {
        this.playingAudio.mu.$Audio.playbackRate = this.speed
      },
    }
  };
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";

  .playing-audio {
    position: absolute;
    display: flex;
    align-items: center;
    padding: 4px 20px 2px 10px;
    /*width: 70px;*/
    height: 58px;
    background: #fff;
    cursor: pointer;
    z-index: 6;
    border-radius: 50% 0 0 50%;
    -webkit-border-radius: 50px 0 0 50px;
    /*px2px(right, 30px);*/
    right: 0;
    px2px(top, 150px);
    i {
      display: block;
      margin-left: -5px;
      padding: 6px;
      color: #fff;
      background: #12B7F5;
      border-radius: 50%;
      -webkit-border-radius: 50%;
      px2px(font-size, 38px);
      /*px2px(width, 32px);*/
      /*px2px(height, 32px);*/
    }
    select {
      border: 0;
      px2px(font-size, 24px);
      text-align:center;
      text-align-last: center;
    }
    select:focus {
      outline: 0;
    }
  }
  .is-pc {
    .playing-audio {
      top: 80px;
      left: 490px;
      padding: 2px 18px 2px 10px;
      width: 60px;
      height: 30px;
      border-radius: 30px 0 0 30px;
      -webkit-border-radius: 30px 0 0 30px;
      i {
        margin-left: -7px;
        padding: 2px 2px 2px 3px;
        font-size: 22px;
      }
      select {
        padding: 0;
      }
    }
  }
</style>
