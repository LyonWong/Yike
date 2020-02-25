<template>
  <section class="dicuss-report">
    <loading :show="showLoading"></loading>
    <div class="container">
      <div class="title clearfix">
        <div class="pull-left">
          <span class="answer">举报内容</span>
        </div>
        <div class="pull-right">
          <span class="close" @click="closeReport">
            <i class="iconfont icon-cross"></i>
          </span>
        </div>
      </div>
      <div class="textarea">
        <textarea v-model="data.text" placeholder="请输入"></textarea>
      </div>
      <div class="img-list clearfix" v-if="curImage.length">
        <div class="img" v-for="(img, index) in curImage">
          <span class="img-wrap" @click="showOriginImage(img)">
            <img :src="`${img}!previews`" />
          </span>
          <span class="delete" @click="deleteImg(index)">
            <i class="iconfont icon-cross"></i>
          </span>
        </div>
      </div>
      <div class="bottom clearfix">
        <div class="pull-left">
          <span class="iconfont icon-icontupian">
            <input type="file" accept="image/*" @change="selectImage" />
          </span>
        </div>
        <div class="button pull-right">
          <button @click="startMessage">提交</button>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';
  import { checkPic } from '@lib/js/mUtils';
  import Loading from '@student/components/loading';

  export default{
    name: 'dicuss-report',
    components: {
      Loading,
    },
    props: {
      cursor: {
        type: String,
      }
    },
    data() {
      return {
        showLoading: false,
        curImage: [],
        data: {
          cursor: '',
          image: [],
          text: '',
          audio: '',
        }
      }
    },
    created() {
      // 赋值
      this.data.cursor = this.cursor;
    },
    methods: {
      closeReport() {
        this.$parent.showReport = false;
      },
      showOriginImage(img){
        this.$emit('callback', img);
      },
      startMessage(){
        // 文字不能为空
        if(!this.data.text){
          return swal({
            title: '错误提醒',
            text: '文字不能为空',
            confirmButtonText: '知道了'
          });
        }
        //
        this.showLoading = true;
        swal({
          title: '提醒',
          text: '确定要举报这条留言吗?',
          confirmButtonText: '举报',
          showCancelButton:true,
          closeOnConfirm: false,
          cancelButtonText: '取消',
        },()=>{
          swal.close();
          // 开始举报留言
          this.boardReport();
        });
      },
      boardReport() {
        // 开始发送留言
        this.showLoading = true;
        this.$store.dispatch('fetchBoardReport', {...this.data}).then((data) => {
          this.showLoading = false;
          this.closeReport();
          //
          setTimeout(()=>{
            swal({
              title: '提醒',
              text: '举报成功',
              confirmButtonText: "知道了"
            });
          });
        }, (error) => {
          //
          swal({
            title: '错误提醒',
            text: error.message,
            confirmButtonText: "知道了"
          });
          this.closeReport();
          this.showLoading = false;
          console.log('fail');
        });
      },
      selectImage(event) {
        // 开始预览
        this.imgOnChange(event);
      },
      deleteImg(index) {
        this.data.image.splice(index, 1);
        this.curImage.splice(index, 1);
      },
      imgOnChange(event) {
        //
        var uploadFiles = event.target;
        var file = uploadFiles.files[0];
        var el = this;
        // 是否有选择文件
        if(!file){
          return;
        }
        // 先检查图片类型和大小
        if (!checkPic(uploadFiles, file.size)) {
          return;
        }
        // 是否超过限制
        if(this.data.image.length>2){
          uploadFiles.value = '';
          return swal({
            title: '错误提醒',
            text: '最多只能传3张图!',
            confirmButtonText: '知道了'
          });
        }
        // 开始上传
        this.boardDraft(file);
        uploadFiles.value = '';
      },
      // 获得权限
      boardDraft(file) {
        this.showLoading = true;
        // 获取七牛token
        this.$store.dispatch('fetchBoardDraft').then((data) => {
          // 开始上传图片
          this.postImg(data, file)
        }, (err) => {
          swal({
            title: '错误提醒',
            text: err.message,
            confirmButtonText: "知道了"
          });
        });
      },
      postImg(data, file){
        //创建formData对象
        var formData = new FormData();
        formData.append('file', file);
        formData.append('key', data.key);
        formData.append('token', data.token);
        this.$http.post(data.upload, formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        }).then((json)=>{
          if (json.ok) {
            this.data.image.push(data.key);
            this.curImage.push(data.url);
            this.showLoading = false;
          }else{
            new Error('Fetch_Upload_Image failure');
          }
        }).catch((error)=>{
          swal({
            title: '错误提醒',
            text: '上传图片失败!',
            confirmButtonText: "知道了"
          },()=>{
            this.showLoading = false;
          });
        });
      },
    },
  };
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";

  .dicuss-report
    position: fixed;
    left: 0;
    top: 0;
    bottom: 0;
    margin: 0;
    width: 100%;
    background-color: rgba(0,0,0,.3);
    z-index: 8;
    .container
      position: absolute;
      padding: 0;
      width: 100%;
      background: #fff;
      border-radius: 10px 10px 0 0;
      -webkit-border-radius: 10px 10px 0 0;
      px2px(bottom, 0);
      .title
        padding: 20px 30px 10px;
        .answer
          color: #aaa;
          px2px(font-size, 34px);
      .textarea
        padding: 5px 30px 0;
        //border: 1px solid #999;
        textarea
          border-color: #999;
          width: 100%;
          height: 300px;
          px2px(font-size, 32px);
      .img-list
        font-size: 0;
        .img
          position: relative;
          display: block;
          float: left;
          margin: 25px;
          width: 120px;
          height: 120px;
          border: 1px solid #e6eaf2;
          px2px(font-size, 32px);
          .img-wrap
            display: flex;
            display: -webkit-flex;
            width: 100%;
            height: 100%;
            align-items: center;
            justify-content: center;
            -webkit-align-items: center;
            -webkit-justify-content: center;
          img
            max-width: 100%;
            max-height: 100%;
          .delete
            position: absolute;
            padding: 2px 6px;
            background: #ccc;
            color: #fff;
            border-radius: 50%;
            -webkit-border-radius: 50%;
            px2px(top, -20px);
            px2px(right, -20px);
            .iconfont
              px2px(font-size, 32px);
      .bottom
        padding: 10px 30px 15px;
        .button
          button
            padding: 12px 45px;
            background: #4da9eb;
            border: 0 none;
            color: #fff;
            border-radius: 10px;
            -webkit-border-radius: 10px;
            px2px(font-size, 32px);
        .icon-icontupian
          position: relative;
          display: inline-block;
          color: #aaa;
          cursor: pointer;
          overflow: hidden;
          px2px(font-size, 52px)
          input
            position: absolute;
            left: 0;
            top: 0;
            color: #fff;
            width: 100%;
            height: 100%;
            opacity: 0;
            font-size:0;
            cursor: pointer;
            overflow: hidden;
    .close
      display: block;
      padding: 3px 6px;
      background: #ccc;
      border-radius: 50%;
      -webkit-border-radius: 50%;
      .iconfont
        color: #fff;
        px2px(font-size, 32px);

  @media only screen and (device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) {
    .dicuss-report {
      .container {
        padding-bottom: 60px;
      }
    }
  }
</style>
