<template>
  <div class="l-recorder" v-if="!isSending">
    <span class="iconfont icon-mic" @click="toggleRecorder" :class="{'active':active}"></span>
  </div>
</template>

<script type="text/javascript">
  import { mapGetters } from 'vuex';
  import { initRecorder, toggleRecording } from '@teacher/assets/js/recorder';

  export default
  {
    name: 'v-recorder',
    components: {
    },
    data() {
      return {
        lesson_sn: '',
        active: false,
      };
    },
    computed: {
      ...mapGetters({
        isSending: 'getSending',
        cancleRecord: 'getCancleRecord',
      })
    },
    created() {
      this.lesson_sn = this.$route.params.lesson_sn;
      initRecorder();
    },
    methods: {
      toggleRecorder(){
        // 开始录音
        toggleRecording(this);
      }
    },
  };
</script>
<style scoped lang="stylus" rel="stylesheet/stylus">
  .l-recorder
    position: relative;
    background: #fff;
    .iconfont
      &.active
        color: red;
    .send-sound
      position: absolute;
      background: #fff;
      z-index: 10;
      px2px(left, 0px);
      px2px(bottom, 48px);
      >*
        px2px(font-size, 32px);

  .is-pc
    .owner
      .l-recorder
        position: absolute;
        left: 0px;
        top: 0px;
        .iconfont
          font-size: 24px;

</style>
