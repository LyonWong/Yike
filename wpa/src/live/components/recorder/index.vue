<template>
  <div class="l-recorder" v-if="!isSending">
    <span class="iconfont icon-mic" @click="toggleRecorder" v-if="!isRecording"></span>
    <span class="iconfont icon-mic active" v-if="isRecording"></span>
  </div>
</template>

<script type="text/javascript">
  import { mapState } from 'vuex';
  import { initRecorder, toggleRecording } from '@live/assets/js/recorder';

  export default
  {
    name: 'v-recorder',
    components: {
    },
    data() {
      return {
        active: false,
      };
    },
    computed: {
      ...mapState({
        isRecording: 'recording',
        isSending: 'sending',
        cancleRecord: 'cancleRecord',
        playingAudio: 'playingAudio'
      })
    },
    created() {
      initRecorder();
    },
    methods: {
      toggleRecorder(){
        // 关闭正在播放的语音
        if(this.playingAudio && this.playingAudio.state.playing){
          this.playingAudio.pause();
        }
        // 开始录音
        toggleRecording(this);
      }
    },
  };
</script>
<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";
</style>
