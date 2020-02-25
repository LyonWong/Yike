<template>
  <div class="loading">
    <div class="loading-dialog" v-if="auditionData">
      <div class="loading-audio style2">
        <span class="audition">试听</span>
        <audio :src="auditionData.url" id="save" controls autoplay></audio>
        <span class="remark"><textarea placeholder="请输入音频备注" v-model="remark"></textarea></span>
      </div>
      <div class="is-recording">
        <button @click="cancleBlobRecording">取消</button>
        <!--开始-->
        <button @click="startUpload" v-if="!uploading">发送</button>
        <button v-if="uploading" class="gray cursor-default">发送中...</button>
      </div>
      <button class="cancle" @click="cancleBlobRecording"><i class="iconfont icon-guanbi"></i></button>
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
  import { toggleRecording } from '@teacher/assets/js/recorder';
  import { mapGetters } from 'vuex';
  import swal from 'sweetalert';

  export default {
    name: 'audition',
    props: {
      auditionData: {
        type: null,
      },
      iPoint: {
        type: String,
      },
    },
    data() {
      return {
        remark: '',
        sendWidth: 0,
        uploading: false,
      }
    },
    computed: {
      ...mapGetters({
        blobRecord: 'getBlobRecord',
      })
    },
    created() {
      console.log('record created');
    },
    mounted() {
      console.log('record mounted');
      setTimeout(() =>{
        console.log('recorded');
        document.getElementById('save').play();
      }, 500);
    },
    watch: {
      auditionData: function (n, o) {
        console.log('watch', n, o)
      }
    },
    methods: {
      cancleBlobRecording() {
        this.$emit('closeAudition', false);
      },
      startUpload() {
        // 开始上传
        // 打开上传状态
        this.uploading = true;
        let body = { note: this.remark, ...this.auditionData };
        // 开始
        this.$store.dispatch('fetchPrepareCreateAudio', body).then((json) => {
          // 音频的上传
          this.cancleBlobRecording();
          this.$store.commit('ADD_PREPARE_LIST', json);
          this.$emit('completeUpload');
          // 启动滚动条
          this.$emit('getAllPrepareList');
          if (this.iPoint) {
            this.$emit('iPointAdd');
          }
          console.log('上传成功!');
        }).catch((error) => {
          // 异常
          swal({
            title: '错误提醒',
            text: error.message ? error.message : '网络链接失败!',
            confirmButtonText: "知道了"
          });
          // 关闭上传状态
          this.uploading = false;
        });
      }
    }
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
