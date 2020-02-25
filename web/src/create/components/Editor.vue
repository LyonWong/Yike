<template>
  <div class="c-create-editor">
    <div v-if="area==='full' && 1">
      <link rel="stylesheet" type="text/css" :href="app.linkToAssets('/css/full-editor.css')"/>
    </div>
    <div class="editor flex-col" v-if="editing" :class="[`area-${area}`]" :style="{height: height}">
      <div class="head flex-row">
        <div class="flex-row">
          <span class="head-seqno font-bold">No.{{number}}</span>
          <span class="head-mode">[{{mode}}]</span>
        </div>
        <div class="flex-row">
          <div class="head-free flex-row">
            <span :class="{hilight: free}">免费试读</span>
            <switch-capsule size=".4rem" tone="prime" v-model="free" v-on:check="free=!free"></switch-capsule>
          </div>
          <i class="btn icon-yike icon-unfold" @click="changeArea('full')" v-show="area !== 'full'"></i>
          <i class="btn icon-yike icon-fold" @click="changeArea('fold')" v-show="area !== 'fold'"></i>
        </div>
      </div>
      <div class="body flex-col">
        <!--<div id="uploading" v-show="uploading">上传中...</div>-->
        <div id="uploading" v-show="uploading!==false" class="edit-uploading flex-row">
          <div>{{uploading === true ? '检测中' : '上传中'}}</div>
          <div class="slider" :style="uploadingStyle"></div>
        </div>
        <div class="image" v-if="type==='image'">
          <div class="image-item">
            <img :src="src"/>
            <i class="icon-yike icon-close" @click="removeDraft"></i>
          </div>
        </div>
        <div class="audio" v-if="type==='audio'">
          <div class="audio-item flex-row">
            <div v-show="recordHandle" class="edit-recorder flex-row">
              <div class="btn stop flex-row" @click="stopRecord">■</div>
              <img :src="app.linkToAssets('/img/perform/recording.gif')"/>
              <img :src="app.linkToAssets('/img/perform/recording.gif')" style="left: 2.2rem"/>
              <span class="time">{{recording | transTime}}</span>
              <!--<i class="icon-yike icon-cross" @click="removeDraft"></i>-->
            </div>
            <audio controls :src="src" class="flex-item" v-if="src" autoplay></audio>
            <i class="icon-yike icon-close" v-if="src" @click="removeDraft"></i>
          </div>
        </div>
        <div class="video" v-if="type==='video'">
          <div class="video-item">
            <video controls :src="src" class="flex-item"></video>
            <i class="icon-yike icon-close" @click="removeDraft"></i>
          </div>
          <span>视频需要转码后才能播放，请一段时间后刷新预览</span>
        </div>
        <div class="text flex-col">
          <textarea v-model="text" :placeholder="adapt[type].placeholder" v-on:scroll="onInputScroll" @keydown="onKeydown" ref="textarea"  @blur="onBlur" @paste="onPaste"></textarea>
        </div>
      </div>
      <div class="foot flex-row">
        <div class="ctrl flex-row flex-item" :class="[`type-${type}`]">
          <div class="btn ctrl-record icon-yike icon-micphone" v-show="!recordHandle" title="录音" @click="startRecord"></div>
          <div class="btn ctrl-record icon-yike icon-micphone onlive" v-show="recordHandle" @click="stopRecord"></div>
          <div class="btn ctrl-image icon-yike icon-photo" title="上传图片">
            <input id="uploadImage" type="file" accept="image/*" :disabled="type!=='markdown'" @change="chooseImage"/>
          </div>
          <div class="btn ctrl-audio icon-yike icon-music" title="上传音频">
            <input id="uploadAudio" type="file" accept="audio/*" :disabled="type!=='markdown'" @change="chooseAudio"/>
          </div>
          <div class="btn ctrl-video icon-yike icon-video" title="上传视频">
            <input id="uploadVideo" type="file" accept="video/*" :disabled="type!=='markdown'" @change="chooseVideo"/>
          </div>
        </div>
        <div class="btn update" :class="{disable: recording||uploading}" @click="update" title="快捷键Ctrl+Enter或Alt+S">
          <i class="icon-yike icon-send-i"></i>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Filters from '@/assets/js/filters';
  import SwitchCapsule from "../../components/unit/SwitchCapsule";
  const SwitchSpan = r => require.ensure([], () => r(require("../../components/unit/SwitchSpan")), 'common');
  require('@/assets/js/recorder/recorder')
  export default {
    name: 'create-editor',
    components: {SwitchCapsule, SwitchSpan},
    props: ['sn', 'cursor', 'pointer', 'number', 'content'],
    data() {
      return {
        area: 'fold',
        free: (this.content && this.content.free) || false,
        type: (this.content && this.content.type) || 'markdown',
        text: this.content && this.content.text,
        src: (this.content && this.content.src),
        draft: (this.content && this.content.draft) || null,
        scroll: 1,
        vb: 0,
        videoBits: ['240k', '1280k'],
        recorder: null,
        editing: true,
        recording: null,
        uploading: false,
        recordHandle: null,
        adapt: {
          markdown: {
            height: '4rem',
            placeholder: '请输入文本内容'
          },
          mark: {
            height: '4rem',
            placeholder: '请输入书签文本'
          },
          audio: {
            height: '5rem',
            placeholder: '可添加音频备注'
          },
          image: {
            height: '6rem',
            placeholder: '可添加图片说明'
          },
          video: {
            height: '6rem',
            placeholder: '可添加视频说明'
          }
        }
      }
    },
    created() {
      console.log('edit', this.content)
    },
    mounted() {
      this.focus()
      this.initRecord()
    },
    computed: {
      height() {
        switch (this.area) {
          case 'fold':
            return this.adapt[this.type].height
          case 'full':
            return '100vh'
          default:
            return 'auto'
        }
      },
      mode() {
        if (this.cursor) {
          return '修改'
        } else {
          if (this.pointer) {
            return '插入'
          } else {
            return '新增'
          }
        }
      },
      uploadingStyle() {
        if (this.uploading === true) {
          return null
        } else if (this.uploading) {
          let percent = this.uploading * 100
          return {
            background: `linear-gradient(to right, #2f57da ${percent}%, #fff ${percent}%)`,
            animation: 'none'
          }
        } else {
          return null
        }
      }
    },
    methods: {
      screen() {
        let editing = document.querySelector('.editing')
        let editor = document.querySelector('.editor')
        let list = document.querySelector('.list')
        let body = document.body
        let info = `
        body.clientHeight:${body.clientHeight}
        list.scrollTop:${list.scrollTop}
        editor.clientHeight:${editor.clientHeight}
        editing.clientHeigth:${editing.clientHeight}
        editing.offsetTop:${editing.offsetTop}
        `
        alert(info)
      },
      changeArea(size) {
        this.area = size
        if (this.app.os() !== 'iOS') {
          this.focus()
        }
      },
      focus() {
        this.$refs['textarea'].focus()
      },
      update() {
        if (this.recording || this.uploading) {
          return
        }
        let duration = null;
        switch (this.type) {
          case 'audio':
          case 'video':
            duration = document.querySelector(`.editor ${this.type}`).duration
            break;
        }
        this.api.post('/api/prepare-update', {
          sn: this.sn,
          type: this.type,
          note: this.text,
          free: this.free,
          draft: this.draft,
          length: (this.text || '').replace(/\s/g, '').length, // 字数统计
          duration: Math.round(duration), // 音视频市场
          cursor: this.cursor,
          pointer: this.pointer
        }).then((res) => {
          this.$emit('update', res.data)
          this.reset()
        }).catch((e) => {
          this.bus.$emit('dialog', {
            info: { body: e.message },
            btn: { prime: '知道了' }
          })
        })
      },
      chooseImage(e) {
        this.uploading = 0
        this.api.get('/api/prepare-draft', {sn: this.sn}).then((res) => {
          let qiniu = res.data
          let file = e.target.files[0]
          let form = new FormData()
          form.append('key', qiniu.key)
          form.append('token', qiniu.token)
          form.append('file', file, qiniu.key)
          let option = {
            onUploadProgress: (e) => {
              if (e.lengthComputable) {
                this.uploading = e.loaded/e.total
              }
            }
          }
          this.axios.post(qiniu.upload, form, option).then(() => {
            this.uploading = true
            this.api.post('/api/tool-imageSecurity', {
              url: `${qiniu.url}!preview`
            }).then((r) => {
              this.uploading = false
              if (r.data) {
                this.bus.$emit('dialog', {
                  info: {body: '图片违规，请重新上传'}
                })
              } else {
                this.type = 'image'
                this.draft = qiniu.key
                this.src = qiniu.url
                this.emitEditing()
              }
            })
          })
        })
      },
      chooseAudio(e) {
        this.uploading = 0
        this.api.get('/api/prepare-draft', {sn: this.sn}).then((res) => {
          let qiniu = res.data
          let file = e.target.files[0]
          let form = new FormData()
          form.append('key', qiniu.key)
          form.append('token', qiniu.token)
          form.append('file', file, qiniu.key)
          let option = {
            onUploadProgress: (e) => {
              if (e.lengthComputable) {
                this.uploading = e.loaded/e.total
              }
            }
          }
          this.axios.post(qiniu.upload, form, option).then(() => {
            this.uploading = false
            this.type = 'audio'
            this.draft = qiniu.key
            this.src = qiniu.url
            this.emitEditing()
          })
        })
      },
      chooseVideo(e) {
        this.uploading = 0
        this.api.get('/api/prepare-draft', {sn: this.sn}).then((res) => {
          let qiniu = res.data
          let file = e.target.files[0]
          let form = new FormData()
          form.append('key', qiniu.key)
          form.append('token', qiniu.token)
          form.append('file', file, qiniu.key)
          let option = {
            onUploadProgress: (e) => {
              if (e.lengthComputable) {
                this.uploading = e.loaded/e.total
              }
            }
          }
          this.axios.post(qiniu.upload, form, option).then(() => {
            this.uploading = false
            this.type = 'video'
            this.draft = qiniu.key
            this.src = qiniu.url
            this.emitEditing()
          })
        })
      },
      initRecord() {
        console.log(window.MP3Recorder)
        this.recorder = new window.MP3Recorder({
          bitRate: 64,
          WORKER_PATH: this.app.config.env==='dev' ? this.app.linkToAssets('/js/recorder/recorder-worker.js') : '/assets/source/js/recorder/recorder-worker.js',
          complete: (data, type) => {
            let blob = new Blob(data, { type: type })
            // this.src = URL.createObjectURL(blob)
            console.log('complete record', data, blob)
            if (blob.size === 0) {
              alert('录音失败，请重启浏览器尝试')
            }
            let e = {
              target: {
                files: [blob]
              }
            }
            this.chooseAudio(e)
          }
        })
      },
      startRecord() {
        console.log('start record')
        if (this.type!=='markdown') {
          return
        }
        this.type = 'audio'
        this.recording = 0
        this.recordHandle = setInterval(() => {
          this.recording++
        }, 1000)
        this.recorder.start(() => {
          console.log('start record')
        }, (e) => {
          alert(e.code+'#'+e.name+':'+e.message)
        })
      },
      stopRecord() {
        console.log('stop record')
        clearInterval(this.recordHandle)
        this.recorder.stop()
        this.recordHandle = null
        this.recording = 0
      },
      removeDraft() {
        this.draft = this.src = null
        if (this.recordHandle) {
          this.stopRecord()
        }
        this.type = 'markdown'
      },
      reset() {
        this.text = this.src = ''
        this.type = 'markdown'
      },
      onBlur() {
        let dom = document.querySelector('.editing')
        if (dom) {
          window.scrollTo({top: dom.offsetTop})
        }
      },
      onPaste(e) {
        let items = (e.clipboardData || window.clipboardData).items;
        for (let item of items) {
          if (item.type.indexOf('image')>=0) {
            let event = {
              target: {
                files: [item.getAsFile()]
              }
            }
            this.chooseImage(event)
          }
        }
      },
      onKeydown(e) {
        // ctrl+enter
        if (e.ctrlKey && e.keyCode === 13) {
          this.update();
        }
        // alt+s
        if (e.altKey && e.keyCode === 83) {
          this.update();
        }
        this.emitEditing()
      },
      onInputScroll(e) {
        this.scroll = e.target.scrollTop / e.target.scrollHeight
        this.emitEditing()
      },
      emitEditing() {
        this.$emit('editing', {
          type: this.type,
          text: this.text,
          src: this.src,
          draft: this.draft,
          scroll: this.scroll
        })
      }
    },
    watch: {
    },
    filters: Filters
  }
</script>

<style scoped>
  .editor {
    position: relative;
    z-index: 100;
    width: 100%;
    align-items: stretch;
    min-height: 50vh;
    height: 100%;
    background: #fff;
    transition: height 0.5s;
  }
  .hilight {
    color: #2F57DA;
  }
  .editor.area-fold {
    border-radius: .18rem .18rem 0 0;
    box-shadow: 0 0 .2rem .03rem rgba(22, 22, 22, 0.14);
  }
  .head {
    padding: .3rem;
    font-size: .3rem;
    color: #808080;
    justify-content: space-between;
  }
  .head i {
    font-size: .3rem;
  }
  .head-seqno {
    color: #2F57DA;
    font-size: .34rem;
  }
  .head-mode {
    color: #2F57DA;
    margin-left: .5em;
    font-size: .34rem;
  }
  .head-free {
    margin: 0 .4rem;
    font-size: .3rem;
  }
  .head-free .u-switch-capsule {
    margin: 0 .2rem;
  }
  #opt-free {
    width: .24rem;
    height: .24rem;
    cursor: pointer;
  }
  .body {
    align-items: stretch;
    flex-grow: 1;
  }
  .text{
    padding: .1rem .3rem;
    flex-grow: 1;
  }
  .text > textarea {
    outline: none;
    resize: none;
    width: 100%;
    height: 100%;
    border: none;
    flex-grow: 1;
    font-size: .3rem;
    font-family: sans-serif;
  }
  .text > textarea::-webkit-input-placeholder {
    color: #999;
  }
  .image, .video {
    padding: .1rem .3rem;
    position: relative;
    justify-content: flex-start;
  }
  .image-item {
    position: relative;
  }
  .video-item {
    position: relative;
  }
  .image-item img, .video-item video {
    max-width: 4rem;
    max-height: 2rem;
    border: 1px dashed #888;
  }
  .icon-close {
    opacity: 0.7;
    font-size: .4rem;
    color: #666;
  }
  .image-item .icon-close {
    position: absolute;
    top: 0;
  }
  .audio {
    padding: .1rem .3rem;
  }
  .video span {
    font-size: .24rem;
  }
  .video-item .icon-close {
    position: absolute;
    top: 0;
  }
  .foot {
    justify-content: space-between;
    background: #f7f7f7;
    height: 1rem;
  }
  .foot .ctrl {
    justify-content: space-around;
  }
  .ctrl .btn {
    position: relative;
    flex-grow: 1;
    font-size: .4rem;
    color: #999;
    text-align: center;
  }
  .ctrl-image > input, .ctrl-audio > input, .ctrl-video > input {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    opacity: 0;
    overflow: hidden;
    cursor: pointer;
  }
  .btn.update {
    /*color: #2F57DA;*/
    /*font-size: .3rem;*/
    padding: 0 0.5rem;
    border-left: 1px solid #999;
  }
  .btn.update i {
    font-size: .4rem;
    background: #2F57DA;
    color: #fff;
    border-radius: 50%;
    padding: .03rem;
  }
  .type-markdown .btn {
    color: #2F57DA;
  }
  /*.btn.video-bits {*/
    /*position: absolute;*/
    /*bottom: 0;*/
    /*right: 0;*/
  /*}*/
  .free-switch {
    border: 1px solid #2F57DA;
    padding: .2em .4em;
  }
  #recording {
    justify-content: flex-start;
  }
  #recording img{
    border-radius: 2em;
    background: #2F57DA;
    padding: 1em;
  }
  #recording span {
    color: #2F57DA;
    font-size: .3rem;
  }
  /*#uploading {*/
    /*padding: 0 .3rem;*/
    /*font-size: .3rem;*/
    /*color: #2F57DA;*/
  /*}*/
  .edit-uploading {
    position: relative;
    justify-content: space-between;
    background: #EAEAF8;
    width: calc(100% - 1rem);
    padding: 0 .2rem;
    margin: 0 .3rem;
    font-size: .3rem;
    height: .6rem;
    border-radius: .3rem;
  }
  .edit-recorder {
    position: relative;
    justify-content: space-between;
    background: #EAEAF8;
    width: 100%;
    /*width: calc( 100% - 1rem );*/
    /*padding: 0 .2rem;*/
    /*margin: 0 .3rem;*/
    font-size: .3rem;
    height: .6rem;
    border-radius: .3rem;
  }
  .edit-uploading > div{
    margin: 0 20px;
    color: #2F57DA;
  }
  .edit-uploading .slider {
    margin-left: 0;
    width: 4.4rem;
    height: 3px;
    background: linear-gradient( to right, #fff, #2f57da);
    animation: 1.5s linear 0s infinite normal none running slide;
  }
  @keyframes slide {
    0% {}
    100% {
      background-position:  4.4rem 0;
    }
  }
  .edit-recorder .stop {
    position: relative;
    z-index: 2;
    background: #2F57DA;
    color: #fff;
    width: .54rem;
    height: .54rem;
    border-radius: 50%;
    font-size: .24rem;
    text-align: center;
    /*line-height: .56rem;*/
  }
  .edit-recorder img {
    position: absolute;
    left: 0;
    width: 4rem;
  }
  .edit-recorder .time {
    padding: 0 1em;
  }
  .ctrl-record.onlive {
    color: #f00;
    /*animation: rolling 2s linear infinite;*/
  }
  .btn.update.disable > i{
    background: #aaa;
  }
  @keyframes beating {
    0% {
      transform: scale(1)
    }
    50% {
      opacity: 0.8;
      transform: scale(1.2)
    }
    100% {
      transform: scale(1)
    }
  }
  @keyframes rolling {
    0% {
      transform: rotate(0);
    }
    25% {
      transform: rotate(90deg);
    }
    50% {
      transform: rotate(180deg);
    }
    75% {
      transform: rotate(270deg);
    }
    100% {
      transform: rotate(360deg);
    }
  }
  /*
  @media screen and (orientation:landscape){
    .editor {
      height: 100vh !important;
    }
    .btn.icon-unfold {
      display: none;
    }
  }
  */
</style>
