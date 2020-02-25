<template>
  <div class="cropper">
    <!-- 遮罩层 -->
    <div class="container" v-show="panel">
      <div class="container-image">
        <img id="image" :src="url" alt="Picture">
      </div>
      <div class="container-button">
        <button type="button" class="button cancle" @click="cancle">取消</button>
        <button type="button" class="button" @click="crop">确定</button>
      </div>
    </div>

    <div class="cropper-cover">
      <div class="show">
        <div class="picture" :style="'backgroundImage:url('+headerImage+')'">
        <!--<div class="picture">-->
          <!--<img :src="headerImage" alt="" style="width: 100%;">-->
          <div v-if="!headerImage">
            <i class="iconfont icon-icons01"></i><br />
            点击上传图片(750*400)
          </div>
        </div>
      </div>
      <div class="cropper-button">
        <input class="cursor" type="file" id="change" accept="image" @change="change" :title="cover?'已选择图片':'请选择图片'">
        <label for="change"></label>
      </div>
    </div>
  </div>
</template>

<script>
  import Cropper from 'cropperjs';
  import 'cropperjs/dist/cropper.css';
  import swal from 'sweetalert';
  import { checkPastePic } from '@lib/js/mUtils';
  var cFile = null;

  export default{
      name: 'v-cropper',
      props: {
        callback: {
          type: null
        },
        cover: {
          type: String
        }
      },
      data () {
        return {
          backImage:'',
          compress: false,
          type: '',
          headerImage:'',
          picValue:'',
          cropper:'',
          croppable:false,
          panel:false,
          url:''
        }
      },
      mounted () {
        //初始化裁剪框
        var image = document.getElementById('image');
        this.headerImage = this.cover || '';
        // 初始化裁剪工具
        this.cropper = new Cropper(image, {
          aspectRatio: 15/8,
          viewMode: 1,
          background:false,
          zoomable:false,
          autoCropArea: 1,
          ready: () => {
            this.croppable = true;
          }
        });
      },
      methods: {
        getObjectURL (file) {
          var url = null ;
          if (window.createObjectURL!=undefined) { // basic
            url = window.createObjectURL(file) ;
          } else if (window.URL!=undefined) { // mozilla(firefox)
            url = window.URL.createObjectURL(file) ;
          } else if (window.webkitURL!=undefined) { // webkit or chrome
            url = window.webkitURL.createObjectURL(file) ;
          }
          return url ;
        },
        change (e) {
          let files = e.target.files || e.dataTransfer.files;
          if (!files.length) return;
          let file = files[0];
          //先检查图片类型和大小
          if (!checkPastePic(files, file.size)) {
            return;
          }
          // 是否需要压缩
          this.compress = (file.size/1024 > 1024) ? true : false;
          //
          this.type = file.type;
          this.panel = true;
          this.picValue = files[0];

          this.url = this.getObjectURL(this.picValue);
          //每次替换图片要重新得到新的url
          if(this.cropper){
            this.cropper.replace(this.url);
          }
          this.panel = true;

        },
        crop () {
          this.panel = false;
          var croppedCanvas;
          var roundedCanvas;

          if (!this.croppable) {
            return;
          }
          // Crop
          croppedCanvas = this.cropper.getCroppedCanvas();
          // Round
          roundedCanvas = this.getRoundedCanvas(croppedCanvas);
          // 是否开始压缩
          if(this.compress){
            this.backImage = roundedCanvas.toDataURL(this.type, 0.92);
          }else{
            this.backImage = roundedCanvas.toDataURL(this.type);
          }
          // 开始上传图片
          this.getToken(this.backImage);

        },
        cancle () {
          if(!cFile){
            cFile = document.getElementById('change');
          }
          this.panel = false;
          cFile.value = '';
        },
        getRoundedCanvas (sourceCanvas) {
          // 创建canvas
          var canvas = document.createElement('canvas');
          var context = canvas.getContext('2d');
          var width = sourceCanvas.width;
          var height = sourceCanvas.height;

          canvas.width = width;
          canvas.height = height;

          context.imageSmoothingEnabled = true;
          context.drawImage(sourceCanvas, 0, 0, width, height);
          context.globalCompositeOperation = 'destination-in';
          context.beginPath();
          //context.arc(width / 2, height / 2, Math.min(width, height) / 2, 0, 2 * Math.PI, true);
          context.fill();

          return canvas;
        },
        getToken (base64Url) {
          // 获取七牛token
          this.$store.dispatch('fetchQiniuToken').then((data) => {
            // 图片的上传
            this.postImg(data, base64Url);
          }, (err) => {
            /*alert(err.message);*/
            swal({
              title: '错误提醒',
              text: err.message,
              confirmButtonText: "知道了"
            });
          });
        },
        postImg (data, base64Url) {
          //创建formData对象
          var formData = new FormData();
          formData.append('file', this.dataURLtoBlob(base64Url));
          formData.append('key', data.key);
          formData.append('token', data.token);
          // 开始上传
          this.$store.commit('START_LOADING');
          // 开始上传图片
          this.$http.post(data.upload, formData, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          }).then((json) => {
            if (json.ok) {
              this.headerImage = this.backImage;
              this.callback(json.body.key);
              return this.$store.commit('FINISH_LOADING');
            }
            new Error('Fetch_Open_Info failure')
          }).catch((error) => {
            /*alert('上传图片失败!');
            return this.$store.commit('FINISH_LOADING');*/
            swal({
              title: '错误提醒',
              text: '上传图片失败!',
              confirmButtonText: "知道了"
            },()=>{
              this.$store.commit('FINISH_LOADING');
            });
          });
        },
        dataURLtoBlob(dataurl) {
          var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
              bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
          while (n--) {
            u8arr[n] = bstr.charCodeAt(n);
          }
          return new Blob([u8arr], { type: mime });
        }
      }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  .cropper .container-button {
    position: absolute;
    display: -webkit-box;
    display: box;
    left: 0;
    bottom: 0;
    width: 100%;
    height: 40px;
    border-radius: 5px;
    button {
      display: -webkit-box;
      display: box;
      -webkit-box-flex: 1;
      box-flex: 1;
      box-align: center;
      -webkit-box-align: center;
      -webkit-box-pack: center;
      background: white;
      cursor: pointer;
      color: #12b7f5;
      border: none;
      &.cancle {
        color: #3c4a55;
      }
    }
    button+button{
      border-left: 1px solid #aaa;
    }
  }
  .cropper .show {
    width: 230px;
    height: 100px;
    overflow: hidden;
    position: relative;
    border: 1px solid #e6eaf2;
  }
  .cropper .picture {
    width: 100%;
    height: 100%;
    overflow: hidden;
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
  }
  .cropper .container {
    position: fixed;
    padding: 20px 20px 60px;
    width: 350px;
    height: auto;
    left: 0;
    top: 70px;
    right: 0;
    background:rgba(0,0,0,1);
    z-index: 12;
    overflow-y: hidden;
    overflow-x: hidden;
    -moz-box-shadow:0px 0px 18px #333333;
    -webkit-box-shadow:0px 0px 18px #333333;
    box-shadow:0px 0px 18px #333333;
    border-radius: 10px;
    -webkit-border-radius: 10px;
  }

  .cropper #image {
    max-width: 100%;
  }

  .cropper-view-box,.cropper-face {
    border-radius: 50%;
  }

  .cropper-container {
    font-size: 0;
    line-height: 0;

    position: relative;

    -webkit-user-select: none;

    -moz-user-select: none;

    -ms-user-select: none;

    user-select: none;

    direction: ltr;
    -ms-touch-action: none;
    touch-action: none
  }

  .cropper-container img {
    /* Avoid margin top issue (Occur only when margin-top <= -height) */
    display: block;
    min-width: 0 !important;
    max-width: none !important;
    min-height: 0 !important;
    max-height: none !important;
    width: 100%;
    height: 100%;
    image-orientation: 0deg
  }

  .cropper-wrap-box,
  .cropper-canvas,
  .cropper-drag-box,
  .cropper-crop-box,
  .cropper-modal {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
  }

  .cropper-wrap-box {
    overflow: hidden;
  }

  .cropper-drag-box {
    opacity: 0;
    background-color: #fff;
  }

  .cropper-modal {
    opacity: .5;
    background-color: #000;
  }

  .cropper-view-box {
    display: block;
    overflow: hidden;

    width: 100%;
    height: 100%;

    outline: 1px solid #39f;
    outline-color: rgba(51, 153, 255, 0.75);
  }

  .cropper-dashed {
    position: absolute;

    display: block;

    opacity: .5;
    border: 0 dashed #eee
  }

  .cropper-dashed.dashed-h {
    top: 33.33333%;
    left: 0;
    width: 100%;
    height: 33.33333%;
    border-top-width: 1px;
    border-bottom-width: 1px
  }

  .cropper-dashed.dashed-v {
    top: 0;
    left: 33.33333%;
    width: 33.33333%;
    height: 100%;
    border-right-width: 1px;
    border-left-width: 1px
  }

  .cropper-center {
    position: absolute;
    top: 50%;
    left: 50%;

    display: block;

    width: 0;
    height: 0;

    opacity: .75
  }

  .cropper-center:before,
    .cropper-center:after {
      position: absolute;
      display: block;
      content: ' ';
      background-color: #eee
    }

  .cropper-center:before {
    top: 0;
    left: -3px;
    width: 7px;
    height: 1px
  }

  .cropper-center:after {
    top: -3px;
    left: 0;
    width: 1px;
    height: 7px
  }

  .cropper-face,
  .cropper-line,
  .cropper-point {
    position: absolute;

    display: block;

    width: 100%;
    height: 100%;

    opacity: .1;
  }

  .cropper-face {
    top: 0;
    left: 0;

    background-color: #fff;
  }

  .cropper-line {
    background-color: #39f
  }

  .cropper-line.line-e {
    top: 0;
    right: -3px;
    width: 5px;
    cursor: e-resize
  }

  .cropper-line.line-n {
    top: -3px;
    left: 0;
    height: 5px;
    cursor: n-resize
  }

  .cropper-line.line-w {
    top: 0;
    left: -3px;
    width: 5px;
    cursor: w-resize
  }

  .cropper-line.line-s {
    bottom: -3px;
    left: 0;
    height: 5px;
    cursor: s-resize
  }

  .cropper-point {
    width: 5px;
    height: 5px;

    opacity: .75;
    background-color: #39f
  }

  .cropper-point.point-e {
    top: 50%;
    right: -3px;
    margin-top: -3px;
    cursor: e-resize
  }

  .cropper-point.point-n {
    top: -3px;
    left: 50%;
    margin-left: -3px;
    cursor: n-resize
  }

  .cropper-point.point-w {
    top: 50%;
    left: -3px;
    margin-top: -3px;
    cursor: w-resize
  }

  .cropper-point.point-s {
    bottom: -3px;
    left: 50%;
    margin-left: -3px;
    cursor: s-resize
  }

  .cropper-point.point-ne {
    top: -3px;
    right: -3px;
    cursor: ne-resize
  }

  .cropper-point.point-nw {
    top: -3px;
    left: -3px;
    cursor: nw-resize
  }

  .cropper-point.point-sw {
    bottom: -3px;
    left: -3px;
    cursor: sw-resize
  }

  .cropper-point.point-se {
    right: -3px;
    bottom: -3px;
    width: 20px;
    height: 20px;
    cursor: se-resize;
    opacity: 1
  }

  @media (min-width: 768px) {

    .cropper-point.point-se {
      width: 15px;
      height: 15px
    }
  }

  @media (min-width: 992px) {

    .cropper-point.point-se {
      width: 10px;
      height: 10px
    }
  }

  @media (min-width: 1200px) {

    .cropper-point.point-se {
      width: 5px;
      height: 5px;
      opacity: .75
    }
  }

  .cropper-point.point-se:before {
    position: absolute;
    right: -50%;
    bottom: -50%;
    display: block;
    width: 200%;
    height: 200%;
    content: ' ';
    opacity: 0;
    background-color: #39f
  }

  .cropper-invisible {
    opacity: 0;
  }

  .cropper-bg {
    background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQAQMAAAAlPW0iAAAAA3NCSVQICAjb4U/gAAAABlBMVEXMzMz////TjRV2AAAACXBIWXMAAArrAAAK6wGCiw1aAAAAHHRFWHRTb2Z0d2FyZQBBZG9iZSBGaXJld29ya3MgQ1M26LyyjAAAABFJREFUCJlj+M/AgBVhF/0PAH6/D/HkDxOGAAAAAElFTkSuQmCC');
  }

  .cropper-hide {
    position: absolute;

    display: block;

    width: 0;
    height: 0;
  }

  .cropper-hidden {
    display: none !important;
  }

  .cropper-move {
    cursor: move;
  }

  .cropper-crop {
    cursor: crosshair;
  }

  .cropper-disabled .cropper-drag-box,
  .cropper-disabled .cropper-face,
  .cropper-disabled .cropper-line,
  .cropper-disabled .cropper-point {
    cursor: not-allowed;
  }

  .cropper-cover {
    position: relative;
    overflow: hidden;

    .picture {
      >div {
        padding-top: 24px;
        color: #aaa;
        font-size: 12px;
        text-align: center;

        .iconfont {
          font-size: 24px;
        }
      }
    }

    .cropper-button {
      position: absolute;
      top: 0;
      left: 0;
      bottom: 0;
      right: 0;
      z-index:5;
      opacity: 0;

      input{
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
      }
    }
  }
  .cursor {
    cursor: pointer;
  }
</style>
