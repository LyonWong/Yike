<template>
  <div class="loading loading-recorder" :class="{'fold':recorderFold}">
    <div class="loading-dialog">
      <div class="loading-audio">
        <img :src="`${assetsHost}static/live/_static/live/img/recorder.gif`" v-show="!blobRecord" />
        <span v-show="blobRecord" class="audition">试听</span>
        <div class="time" v-if="!recorderStatus" v-text="recorderTimer"></div>
        <audio id="save" controls v-show="blobRecord"></audio>
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
      <button class="cancle" @click="cancleBlobRecording" v-if="blobRecord && !uploading"><i class="iconfont icon-guanbi"></i></button>
      <!--<button class="fold" @click="foldRecording(true)" v-if="!recorderFold"><i class="iconfont icon-chevron-up"></i>向上折叠</button>-->
      <!--<button class="fold" @click="foldRecording(false)" v-if="recorderFold"><i class="iconfont icon-chevron-down"></i>向下展开</button>-->
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
  import { toggleRecording } from '@live/assets/js/recorder';
  import { uploadSound, sendAudioSound } from '@live/assets/js/webim';
  import { mapState } from 'vuex';
  import swal from 'sweetalert';

  export default {
    name: 'recording',
    props: {
    },
    data(){
      return {
        //stop: false,
        sendWidth: 0,
        uploading: false,
      }
    },
    computed: {
      ...mapState({
        blobRecord: 'blobRecord',
        recorderStatus: 'recorderStatus',
        recorderTimer: 'recorderTimer',
        audioCompressComplete: 'audioCompressComplete',
        assetsHost: 'assetsHost',
        recorderFold: 'recorderFold',
      })
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
        sendAudioSound(this.audioCompressComplete.file, (err)=>{
          if(err)swal({
            title: '错误提醒',
            text: 'err',
            confirmButtonText: "知道了"
          });
          this.$store.commit('UPDATE_RECORDING', false);
          this.$store.commit('UPDATE_BLOB_RECORDING', null);
          console.log('上传成功!');
        });
        /*uploadSound(this.blobRecord, (err, data) => {
          if(err)swal({
            title: '错误提醒',
            text: err.ErrorInfo,
            confirmButtonText: "知道了"
          });
          this.$store.commit('UPDATE_RECORDING', false);
          this.$store.commit('UPDATE_BLOB_RECORDING', null);
          console.log('上传成功!');
        }, (loadedSize, totalSize) => {
          this.sendWidth = `${(loadedSize / totalSize) * 100}%`;
        });*/
      },
      foldRecording(fold) {
        this.$store.commit('UPDATE_RECORDER_FOLD', fold);
      },
    }
  };
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  @import 'index.styl';
  .loading
    display: flex;
    -webkit-display: flex;
    align-items:center;
    justify-content: center;
    z-index: 4;
    opacity: 1;
    background-color: rgba(0,0,0,.3);
    px2px(font-size, 32px);
    &.fold
      height: 200px;
    &.loading-recorder
      position: fixed;
      top: 50%;
      left: 50%;
      margin-top: -60px;
      margin-left: -150px;
      width: 340px;
      height: 160px;
      border-radius: 6px;
      -webkit-border-radius: 6px;
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
        background-color: rgba(255,255,255,.3);
      .loading-audio
        height: 100px;
        background: #12b7f5;
        text-align: center;
        .audition
          display: block;
          padding: 10px 0 20px;
          color: #fff;
        img
          margin-top: 25px;
          display: inline-block;
        .time
          color: #fff;
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
          width: 100px;
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
        button+button
          border-left: 1px solid #e6eaf2;
      .fold
        position: absolute;
        bottom: -28px;
        left: 50%;
        margin-left: -32px;
        background: #fff;
        color: #12b7f5;
        border: 0 none;
        border-radius: 4px;
        -webkit-border-radius: 4px;
        cursor: pointer;
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
