<template>
  <div class="loading">
    <div class="loading-dialog">
      <div class="loading-audio" :class="{'style2':blobRecord}">
        <img :src="`${assetsHost}static/live/_static/live/img/recorder.gif`" v-show="!blobRecord"/>
        <span v-show="blobRecord" class="audition">试听</span>
        <div class="time" v-if="!recorderStatus" v-text="recorderTimer"></div>
        <audio id="save" controls v-show="blobRecord" autoplay></audio>
        <span class="remark" v-if="blobRecord"><textarea placeholder="请输入备注~" v-model="remark"></textarea></span>
      </div>
      <div class="is-recording">
        <button @click="cancleRecording" v-if="!recorderStatus && !blobRecord">取消</button>
        <button @click="cancleBlobRecording" v-if="audioCompressComplete && blobRecord">取消</button>
        <!--取消disabled-->
        <button v-if="recorderStatus && !blobRecord" class="gray cursor-default">取消</button>
        <button v-if="blobRecord && !audioCompressComplete" class="gray cursor-default">取消</button>
        <!--开始-->
        <button class="stop" @click="stopRecording" v-if="!recorderStatus">停止</button>
        <button v-if="recorderStatus && !blobRecord" class="gray cursor-default">上传中...</button>
        <button @click="startUpload" v-if="blobRecord && !uploading && audioCompressComplete">发送</button>
        <button v-if="uploading" class="gray cursor-default">发送中...</button>
        <button v-if="blobRecord && !audioCompressComplete" class="gray cursor-default">上传中...</button>
      </div>
      <button class="cancle" @click="cancleRecording" v-if="!blobRecord"><i class="iconfont icon-guanbi"></i></button>
      <button class="cancle" @click="cancleBlobRecording" v-if="blobRecord && !uploading"><i
        class="iconfont icon-guanbi"></i></button>
      <!-- 遮罩 -->
      <div class="uploading" v-if="uploading">
        <span class="progress">
          <em v-bind:style="sendWidth"></em>
        </span>
      </div>
    </div>
  </div>
</template>
<script>
  import {toggleRecording} from '@teacher/assets/js/recorder';
  import {mapGetters} from 'vuex';
  import swal from 'sweetalert';

  // 定义滚动DOM
  let prepareScroll = null;

  export default {
    name: 'recording',
    props: ['iPoint', 'prepareList'],
    data() {
      return {
        // stop: false,
        remark: '',
        sendWidth: 0,
        uploading: false,
      };
    },
    computed: {
      ...mapGetters({
        curSeqno: 'getCurSeqno',
        blobRecord: 'getBlobRecord',
        recorderStatus: 'getRecorderStatus',
        recorderTimer: 'getRecorderTimer',
        audioCompressComplete: 'getAudioCompressComplete',
        assetsHost: 'getAssetsHost',
      }),
    },
    created() {
      this.$store.commit('UPDATE_RECORDER_STATUS', false);
      this.$store.commit('UPDATE_RECORDER_TIMER', '0:01');
    },
    methods: {
      cancleRecording() {
        this.$store.commit('UPDATE_CANCLE_RECORD', true);
      },
      cancleBlobRecording() {
        this.$store.commit('UPDATE_RECORDING', false);
        this.$store.commit('UPDATE_BLOB_RECORDING', null);
      },
      stopRecording() {
        this.$store.commit('UPDATE_RECORDER_STATUS', true);
        toggleRecording();
      },
      startUpload() {
        // 开始上传
        // 打开上传状态
        this.uploading = true;
        let body = {};
        if (this.iPoint) {
          body = {
            note: this.remark,
            ...this.audioCompressComplete,
            insert: this.prepareList[this.iPoint - 1].seqno,
          };
        } else {
          body = {
            note: this.remark,
            ...this.audioCompressComplete,
          };
        }
        // 开始
        this.$store.dispatch('fetchPrepareCreateAudio', body).then((json) => {
          // 音频的上传
          this.$store.commit('UPDATE_RECORDING', false);
          this.$store.commit('UPDATE_BLOB_RECORDING', null);
          this.$store.commit('ADD_PREPARE_LIST', json);
          console.log('上传成功!');
          // 启动滚动条
          this.$emit('getAllPrepareList');
          if (this.iPoint) {
            this.$emit('iPointAdd');
            prepareScroll = document.getElementById('prepare-body');
            prepareScroll.scrollTop = prepareScroll.querySelector('ul').children[this.iPoint - 1].offsetTop - 130;
          } else {
            this.$emit('goToScroll');
          }
        }).catch((error) => {
          // 异常
          swal({
            title: '错误提醒',
            text: error.message ? error.message : '网络链接失败!',
            confirmButtonText: '知道了',
          });
          // 关闭上传状态
          this.uploading = false;
        });
      },
    },
  };
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  .loading
    display: flex;
    -webkit-display: flex;
    align-items: center;
    justify-content: center;
    z-index: 4;
    opacity: 1;
    background-color: rgba(0, 0, 0, .3);
    px2px(font-size, 32px);
    .loading-dialog
      position: relative;
      width: 310px;
      .uploading
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        z-index: 5;
        background-color: rgba(255, 255, 255, .3);
      .loading-audio
        height: 100px;
        background: #12b7f5;
        text-align: center;
        &.style2
          height: 180px;
        .audition
          display: block;
          padding: 10px 0 20px;
          color: #fff;
        img
          margin-top: 25px;
          display: inline-block;
        .time
          color: #fff;
        .remark
          display: block;
          padding-top: 10px;
          color: #fff;
          > *
            vertical-align: middle;
          textarea
            width: 290px;
            height: 40px;
            text-indent: 5px;
            border: 1px solid #e6eaf2;
      .cancle
        position: absolute;
        right: -6px;
        top: -6px;
        padding: 6px;
        color: #fff;
        background: #000;
        border: 0 none;
        line-height: 1;
        border-radius: 15px;
        -webkit-border-radius: 15px;
        cursor: pointer;
        .icon-guanbi
          font-size: 14px;
      .is-recording
        position: relative;
        color: #03a9f4;
        display: -webkit-box;
        display: box;
        button
          display: -webkit-box;
          display: box;
          padding: 11px 0;
          width: 50%;
          font-size: 16px;
          line-height: 1;
          -webkit-box-flex: 1;
          box-flex: 1;
          text-align: center;
          box-align: center;
          -webkit-box-align: center;
          box-pack: center;
          -webkit-box-pack: center;
          background: #fff;
          border: 0 none;
          cursor: pointer;
          &:last-child {
            color: #12b7f5;
          }
          &.stop {
            color: #fb617f;
          }
          &.gray {
            color: #999;
          }
          &.cursor-default {
            cursor: default;
          }
        button + button
          border-left: 1px solid #e6eaf2;
      .progress
        position: absolute;
        bottom: 0;
        width: 100%;
        height: 2px;
        background: #fff;
        em
          display: block;
          width: 0;
          height: 100%;
          background: #12b7f5;
</style>
