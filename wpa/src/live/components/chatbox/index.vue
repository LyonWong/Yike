<template>
  <div class="l-chatbox owner" :class="{'is-finish':(lessonInfo.step=='finish' || lessonInfo.step=='closed')}">
    <div class="box" v-if="lessonInfo.step!='finish' && lessonInfo.step!='closed'">
      <span class="box-more">
        <span class="iconfont icon-picture">
          <input type="file" @change="selectImage" />
        </span>
      </span>
      <!--<span class="box-more video">
        <span class="iconfont icon-picture">
          <input type="file" @change="selectVideo" />
        </span>
      </span>-->
      <span class="box-more recorder" v-if="isPC">
        <v-recorder class="reorder"></v-recorder>
      </span>
      <div class="box-msg">
        <textarea v-model="msgVal" placeholder="请输入文字或粘贴图片..." @blur="v_blur" @paste="v_paste" @keydown="v_keydown"></textarea>
        <v-recorder class="reorder" v-if="!isPC"></v-recorder>
      </div>
      <button class="box-send" @click="sendMsg" title="Ctrl+Enter或Alt+S" v-if="!msgSending">发送</button>
      <button class="box-send" title="Ctrl+Enter或Alt+S" v-if="msgSending">发送中...</button>
      <div class="more-choice" v-if="moduleShow">
        <button @click="showImage">上传图片</button>
        <button @click="showFile">上传文件</button>
      </div>
    </div>
    <!-- 遮罩层 -->
    <div class="modal-dialog" v-if="imgShow">
      <div class="modal-body">
        <div class="modal-img">
          <p class="modal-preview">
            <i class="iconfont icon-icons01" v-if="!imgInfo.src"></i>
            <a class="preview" href="javascript:;"><img class="cursor" v-if="imgInfo.show" v-bind:src="imgInfo.src" /></a>
            <input id="upd_pic" type="file" @change="imgOnChange" title="已选择图片" style="cursor:pointer" />
          </p>
          <button class="upload" @click="startUploadImg" v-if="!startSend">发送图片</button>
          <button class="cancle cursor" @click="cancleUploadImg" v-if="!startSend"><i class="iconfont icon-guanbi"></i></button>
          <button class="upload" v-if="startSend">正在发送...</button>
          <span class="progress">
            <em v-bind:style="widthStyle"></em>
          </span>
        </div>
      </div>
    </div>
    <!-- 截图遮罩层 -->
    <div class="modal-dialog" v-if="pasteInfo.show">
      <div class="modal-body">
        <div class="modal-img">
          <p class="modal-preview">
            <a class="preview" :href="pasteInfo.src" target="_blank"><img v-bind:src="pasteInfo.src" /></a>
          </p>
          <button class="upload" @click="startUploadPaste" v-if="!startSend">发送图片</button>
          <button class="cancle cursor" @click="canclePasteImg" v-if="!startSend"><i class="iconfont icon-guanbi"></i></button>
          <button class="upload" v-if="startSend">正在发送...</button>
          <span class="progress">
            <em v-bind:style="widthStyle"></em>
          </span>
        </div>
      </div>
    </div>
    <div class="modal-dialog" v-if="fileShow">
      <div class="modal-header">发送文件</div>
      <div class="modal-body">
        <div class="">
          <span>选择</span>
          <input id="upd_file" type="file" @change="fileOnChange" />
          <p>
            <span>发送进度:</span>
            <span class="progress">
              <em v-bind:style="widthFileStyle"></em>
            </span>
          </p>
          <button class="upload" @click="startUploadFile" v-if="!startSend">开始发送</button>
          <button class="upload" @click="cancleUploadFile" v-if="!startSend">取消发送</button>
          <button class="upload" v-if="startSend">正在发送...</button>
        </div>
      </div>
    </div>
    <recording v-if="isRecording"></recording>
    <!--<sending :show="isSending" :width="width"></sending>-->
  </div>
</template>

<script type="text/javascript">
  import { mapState } from 'vuex';
  import { vSendMsg, uploadImage, uploadFile } from '@live/assets/js/webim';
  import { checkPic, checkFile, checkPastePic, trimStr } from '@lib/js/mUtils';
  import vRecorder from '@live/components/recorder/index.vue';
  import Recording from '@live/components/loading/recording.vue';
  import swal from 'sweetalert';

  export default
  {
    name: 'v-chatbox',
    components: {
      vRecorder,
      Recording,
      //Sending
    },
    data() {
      return {
        msgVal: '',
        imgShow: false,
        fileShow: false,
        moduleShow: false,
        startSend: false,
        picture: null,
        isPC: isPC,
        msgSending: false,
        imgInfo: {
          file: null,
          src: '',
          show: false,
        },
        pasteInfo: {
          blob: null,
          show: false,
          src: '',
          filename: '',
        },
        widthStyle: {
          width: 0
        },
        widthFileStyle: {
          width: 0
        },
      };
    },
    computed: {
      ...mapState({
        lessonInfo: 'lessonInfo',
        isRecording: 'recording',
        //isSending: 'sending',
        width: 'sendWidth'
      })
    },
    methods: {
      sendMsg() {
        // 打开发送状态
        if(trimStr(this.msgVal)){
          this.msgSending = true;
        }
        // 开始发送
        vSendMsg(this.msgVal, (err, data) => {
          if(!err){
              this.msgVal = '';
          }
          // 关闭发送状态
          this.msgSending = false;
        });
      },
      v_blur() {
      },
      v_keydown(event) {
        let e = event || window.event;
        // 快捷键 ctrl+enter
        if(e.ctrlKey && e.keyCode == 13){
          this.sendMsg();
        }
        // 快捷键 alt+s
        if(e.altKey && e.keyCode == 83){
          this.sendMsg();
        }
      },
      v_paste(event) {
        // 添加到事件对象中的访问系统剪贴板的接口
        let clipboardData = event.clipboardData,
            i = 0,
            items, item, types;
        // 是否有数据
        if( clipboardData ){
          items = clipboardData.items;
          if( !items ){
            return;
          }
          item = items[0];
          // 保存在剪贴板中的数据类型
          types = clipboardData.types || [];
          for( ; i < types.length; i++ ){
            if( types[i] === 'Files' ){
              item = items[i];
              break;
            }
          }
          // 判断是否为图片数据
          if( item && item.kind === 'file' && item.type.match(/^image\//i) ){
            this.showPasteImage( item );
          }
        }
      },
      showPasteImage(item) {
        if (!window.File || !window.FileList || !window.FileReader) {
          swal({
            title: '错误提醒',
            text: '您的浏览器不支持File Api',
            confirmButtonText: "知道了"
          });
          return;
        }
        let el = this;
        let blob = item.getAsFile();
        //先检查图片类型和大小
        if (!checkPastePic(blob, blob.size)) {
          return;
        }
        //预览图片
        let reader = new FileReader();
        reader.onload = (function () {
          return function (e) {
            el.pasteInfo.show = true;
            el.pasteInfo.src = this.result;
            el.pasteInfo.blob = blob;
            el.pasteInfo.filename = blob.name;
          };
        })();
        //预览图片
        reader.readAsDataURL(blob);
      },
      showImage() {
        this.imgShow = true;
      },
      showFile() {
        this.fileShow = true;
      },
      showModule() {
        this.moduleShow = !this.moduleShow;
      },
      selectImage(event) {
        // 开始预览
        this.imgOnChange(event);
      },
      imgOnChange(event) {
        if (!window.File || !window.FileList || !window.FileReader) {
          swal({
            title: '错误提醒',
            text: '您的浏览器不支持File Api',
            confirmButtonText: "知道了"
          });
          return;
        }
        //
        var uploadFiles = event.target;
        var file = uploadFiles.files[0];
        var el = this;
        // 是否有选择文件
        if(!file){
          return;
        }
        //先检查图片类型和大小
        if (!checkPic(uploadFiles, file.size)) {
          return;
        }
        // 显示
        this.showImage();
        // 开始预览
        this.previewImg(el, file);
        uploadFiles.value = '';
      },
      previewImg(el, file) {
        //预览图片
        var reader = new FileReader();
        reader.onload = (function (file) {
          return function (e) {
            el.imgInfo.show = true;
            el.imgInfo.src = this.result;
            el.imgInfo.filename = file.name;
            el.imgInfo.file = file;
          };
        })(file);
        //预览图片
        reader.readAsDataURL(file);
      },
      startUploadImg() {
        var file = this.imgInfo.file;

        //先检查图片类型和大小
        if (!file) {
          return swal({
            title: '错误提醒',
            text: '请先上传图片',
            confirmButtonText: "知道了"
          });
        }
        // 开始上传
        this.startSend = true;
        //上传图片
        uploadImage(file, (err, data) => {
          if(err)swal({
            title: '错误提醒',
            text: err.ErrorInfo,
            confirmButtonText: "知道了"
          });
          this.imgShow = false;
          this.moduleShow = false;
          this.widthStyle.width = 0;
          this.imgInfo.src = '';
          this.startSend = false;
        }, (loadedSize, totalSize) => {
          this.widthStyle.width = `${(loadedSize / totalSize) * 100}%`;
        });
      },
      cancleUploadImg() {
        this.imgShow = false;
        this.moduleShow = false;
        this.imgInfo.show = false;
        this.widthStyle.width = 0;
        this.imgInfo.src = '';
      },
      startUploadPaste() {
        if(!this.pasteInfo.blob){
          return;
        }
        // 开始上传
        this.startSend = true;
        //上传图片
        uploadImage(this.pasteInfo.blob, (err, data) => {
          if(err)swal({
            title: '错误提醒',
            text: err.ErrorInfo,
            confirmButtonText: "知道了"
          });
          this.pasteInfo.blob = null;
          this.pasteInfo.show = false;
          this.pasteInfo.src = '';
          this.pasteInfo.filename = '';
          this.widthStyle.width = 0;
          this.startSend = false;
        }, (loadedSize, totalSize) => {
          this.widthStyle.width = `${(loadedSize / totalSize) * 100}%`;
        });
      },
      canclePasteImg() {
        this.pasteInfo.blob = null;
        this.pasteInfo.show = false;
        this.pasteInfo.src = '';
        this.pasteInfo.filename = '';
        this.widthStyle.width = 0;
      },
      fileOnChange(event) {
        if (!window.File || !window.FileList || !window.FileReader) {
          swal({
            title: '错误提醒',
            text: '您的浏览器不支持File Api',
            confirmButtonText: "知道了"
          });
          return;
        }

        var uploadFiles = event.target;
        var file = uploadFiles.files[0];
        var fileSize = file.size;

        //先检查文件类型和大小
        if (!checkFile(file, fileSize)) {
          return;
        }
      },
      startUploadFile() {
        var uploadFiles = document.getElementById('upd_file');
        var file = uploadFiles.files[0];

        if(!file){
          return swal({
            title: '错误提醒',
            text: '请选择文件',
            confirmButtonText: "知道了"
          });
        }
        // 开始上传
        this.startSend = true;
        //上传文件
        uploadFile(file, (err, data) => {
          if(err)swal({
            title: '错误提醒',
            text: err.ErrorInfo,
            confirmButtonText: "知道了"
          });
          this.fileShow = false;
          this.moduleShow = false;
          this.widthFileStyle.width = 0;
          this.startSend = false;
        }, (loadedSize, totalSize) => {
          this.widthFileStyle.width = `${(loadedSize / totalSize) * 100}%`;
        });
      },
      cancleUploadFile() {
        this.fileShow = false;
        this.moduleShow = false;
        this.widthFileStyle.width = 0;
      },
    },
  };
</script>
<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";
</style>
