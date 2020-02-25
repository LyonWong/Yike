<template>
  <section class="prepare">
    <div class="prepare-box">
      <div class="prepare-title">
        <div>
          <router-link :to="{name:'list'}" class="back">课程列表</router-link>
          &nbsp;&#62;&nbsp;
          <span>
          备课中心
          </span>
          <span> | {{courseDetail.title}}</span>
        </div>
        <div>
          <a class="preview" :href="`${liveHost}study/preview/${lesson_sn}`" target="_blank">阅读模式预览</a>
          <a class="release" href="javascript:;" @click="releaseView">阅读模式发布</a>
        </div>
      </div>
      <div id="prepare-body" class="prepare-body">
        <div class="prepare-content">
          <v-prepare @iPointMinus="iPointMinus" :iPoint="iPoint" :prepareList="prepareList"
                     @getAllPrepareList="getAllPrepareList"></v-prepare>
        </div>
      </div>
      <div class="prepare-textarea">
        <div class="textarea-box">
          <textarea v-model="msgVal" @keydown="v_keydown" @paste="v_paste" placeholder="请输入文字或粘贴图片..."></textarea>
        </div>
        <div class="textarea-more">
          <div class="insert-point">
            <label for="i-point">插入点</label>
            <input id="i-point" v-model="iPoint" size="3" placeholder="末尾" @keyup="numberOnly($event)">
          </div>
          <button class="box-send" @click="sendMsg" title="Ctrl+Enter或Alt+S" v-if="!msgSending">发送</button>
          <button class="box-send" title="Ctrl+Enter或Alt+S" v-if="msgSending">发送中...</button>
          <div>
            <span class="box-more">
              <span class="iconfont icon-picture">
                <input type="file" @change="selectImage"/>
              </span>
            </span>
            <span class="box-more">
              <v-recorder></v-recorder>
            </span>
            <span class="box-more video">
              <span class="iconfont icon-shangchuanshipin">
                <input type="file" @change="selectVideo"/>
              </span>
            </span>
            <span class="box-more video">
              <span class="iconfont icon-audio">
                <input type="file" @change="selectAudio"/>
              </span>
            </span>
            <span class="box-more" @click="isMarkModalShow">
              <span class="iconfont icon-bookmark"></span>
            </span>
          </div>
        </div>
        <div class="textarea-hint">
          <div>
            文本内容支持markdown格式, 代码请用<code>```</code>或<code>`</code>包裹。
            示例请参考<a href="http://markdown-it.github.io" target="_blank">http://markdown-it.github.io</a>
          </div>
        </div>
      </div>
    </div>
    <recording v-if="isRecording" :iPoint="iPoint" :prepareList="prepareList" @getAllPrepareList="getAllPrepareList"
               @iPointAdd="iPointAdd" @goToScroll="goToScroll"></recording>
    <audituon :iPoint="iPoint" :auditionData="auditionData" @closeAudition="closeAudition" @completeUpload="startGoToScroll"
              v-if="startAudituon" @goToScroll="goToScroll" @getAllPrepareList="getAllPrepareList"
              @iPointAdd="iPointAdd"></audituon>
    <!-- 遮罩层 -->
    <div class="modal-dialog" v-if="markModalShow">
      <div class="modal-body-mark">
        <div class="textarea-frm">
          <div class="textarea">
            <textarea v-model="markVal" placeholder="请输入书签文字(限12字)"></textarea>
          </div>
          <button class="box-send cancel" @click="isMarkModalShow">取消</button>
          <button class="box-send" @click="sendMark" title="Ctrl+Enter或Alt+S" v-if="!msgSending">发送</button>
          <button class="box-send" title="Ctrl+Enter或Alt+S" v-if="msgSending">发送中...</button>
        </div>
      </div>
    </div>
    <!-- 遮罩层 -->
    <div class="modal-dialog" v-if="imgShow">
      <div class="modal-body">
        <div class="modal-img">
          <p class="modal-preview">
            <i class="iconfont icon-icons01" v-if="!imgInfo.src"></i>
            <a class="preview" href="javascript:"><img class="cursor" v-if="imgInfo.show"
                                                       v-bind:src="imgInfo.src"/></a>
            <input id="upd_pic" type="file" @change="imgOnChange($event)" title="已选择图片" style="cursor:pointer"/>
          </p>
          <button class="upload" @click="startUploadImg" v-if="!startSend">上传图片</button>
          <button class="cancle cursor" @click="cancleUploadImg" v-if="!startSend"><i class="iconfont icon-guanbi"></i>
          </button>
          <button class="upload" v-if="startSend">正在上传...</button>
        </div>
      </div>
    </div>
    <!-- 截图遮罩层 -->
    <div class="modal-dialog" v-if="pasteInfo.show">
      <div class="modal-body">
        <div class="modal-img">
          <p class="modal-preview">
            <a class="preview" :href="pasteInfo.src" target="_blank"><img v-bind:src="pasteInfo.src"/></a>
          </p>
          <button class="upload" @click="startUploadPaste" v-if="!startSend">发送图片</button>
          <button class="cancle cursor" @click="canclePasteImg" v-if="!startSend"><i class="iconfont icon-guanbi"></i>
          </button>
          <button class="upload" v-if="startSend">正在发送...</button>
        </div>
      </div>
    </div>
    <!-- 视频选择遮罩层 -->
    <div class="modal-dialog" v-if="videoShow">
      <div class="modal-body">
        <div class="modal-video">
          <p class="modal-preview">
            <span v-if="!startSend">
              请选择上传模式<br><br>
              <span class="tip">
                若您未自行对视频做压缩优化处理<br>
                建议选择【上传并压缩】
              </span>
            </span>
            <span v-if="startSend">
              视频正在上传，请稍作等待...<br><br>
              <span class="tip">
                压缩转码需要额外的时间<br>
                上传完成的视频无法立即预览<br>
                请几分钟后再刷新查看
              </span>
            </span>
          </p>
          <div class="modal-button">
            <button class="upload" @click="startUploadVideo(0)" v-if="!startSend">原文件上传</button>
            <button class="upload" @click="startUploadVideo(1)" v-if="!startSend">上传并压缩</button>
            <button class="upload" v-if="startSend">正在上传...</button>
          </div>
          <button class="cancle cursor" @click="cancleUploadVideo" v-if="!startSend"><i
            class="iconfont icon-guanbi"></i></button>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
  import {mapGetters} from 'vuex';
  import vPrepare from './list.vue';
  import vRecorder from '@teacher/views/prepare/recorder.vue';
  import Audituon from '@teacher/components/audition.vue';
  import Recording from '@teacher/components/recording.vue';
  import {checkVideo, checkPic, checkFile, checkPastePic, trimStr} from '@lib/js/mUtils';

  let liveHost = process.env.NODE_ENV == 'production' ? process.env.LIVE_HOST : '/';

  // 定义滚动DOM
  let prepareScroll = null;

  export default {
    name: 'earning',
    components: {
      vPrepare,
      Audituon,
      vRecorder,
      Recording,
    },
    computed: {
      ...mapGetters({
        isRecording: 'getRecording',
        prepareList: 'getPrepareList',
      }),
    },
    data() {
      return {
        iPoint: null,
        msgVal: '',
        markVal: '',
        startAudituon: false,
        auditionData: null,
        msgSending: false,
        lesson_sn: '',
        startSend: false,
        imgShow: false,
        markModalShow: false,
        videoShow: false,
        videoInfo: null,
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
        liveHost: liveHost,
        courseDetail: {},
        len: 0
      };
    },
    created() {
      this.$store.commit('CHANGE_IS_NOTICE', false);

      // 获取lesson_sn
      const params = this.$route.params;
      this.lesson_sn = params.lesson_sn;
      // 获取列表
      this.getAllPrepareList();
      // dom
      prepareScroll = document.getElementById('prepare-body');
      // detail
      this.$store.dispatch('fetchCourseDetail', {lesson_sn:this.lesson_sn}).then((data) => {
        // 赋值
        this.courseDetail = { ...data };
      }, () => {
        console.log('fail');
      });
    },
    methods: {
      getAllPrepareList() {
        // 获取课程sn
        // 获取备课列表
        this.$store.dispatch('fetchPrepareSlice', {lesson_sn: this.lesson_sn, limit: 0}).then((data) => {
          // 获取最后一个游标
          this.len = data.length;
        }, (err) => {
          // 异常
          swal({
            title: '错误提醒',
            text: err.message,
            confirmButtonText: '知道了',
          });
        });
      },
      selectImage(event) {
        // 开始预览
        this.imgOnChange(event);
      },
      selectVideo(event) {
        // 开始上传视频
        this.videoOnChange(event);
      },
      selectAudio(event) {
        // 开始上传视频
        this.audioOnChange(event);
      },
      showImage() {
        this.imgShow = true;
      },
      closeAudition(show) {
        this.startAudituon = show;
      },
      imgOnChange(event) {
        if (!window.File || !window.FileList || !window.FileReader) {
          swal({
            title: '错误提醒',
            text: '您的浏览器不支持File Api',
            confirmButtonText: '知道了',
          });
          return;
        }
        //
        const uploadFiles = event.target;
        const file = uploadFiles.files[0];
        const el = this;
        // 是否有选择文件
        if (!file) {
          return;
        }
        // 先检查图片类型和大小
        if (!checkPic(uploadFiles, file.size)) {
          return;
        }
        // 显示
        this.showImage();
        // 开始预览
        this.previewImg(el, file);
        uploadFiles.value = '';
      },
      videoOnChange(event) {
        //
        const uploadFiles = event.target;
        const file = uploadFiles.files[0];
        const el = this;
        // 是否有选择文件
        if (!file) {
          return;
        }
        // 先检查图片类型和大小
        if (!checkVideo(uploadFiles, file.size)) {
          return;
        }
        // 获取七牛token
        this.$store.dispatch('fetchPrepareDraft', {lesson_sn: this.lesson_sn}).then((data) => {
          // 开始上传视频
          // this.postVideo(data, file);
          // 开始操作
          this.videoInfo = {data, file};
          this.videoShow = true;
        }, (err) => {
          swal({
            title: '错误提醒',
            text: err.message,
            confirmButtonText: '知道了',
          });
        });
        uploadFiles.value = '';
      },
      audioOnChange(event) {
        //
        const uploadFiles = event.target;
        const file = uploadFiles.files[0];
        const el = this;
        // 是否有选择文件
        if (!file) {
          return;
        }
        // 先检查图片类型和大小
        /* if (!checkVideo(uploadFiles, file.size)) {
          return;
        } */
        // 获取七牛token
        this.$store.dispatch('fetchPrepareDraft', {lesson_sn: this.lesson_sn}).then((data) => {
          // 开始上传音频
          this.postAudio(data, file);
        }, (err) => {
          swal({
            title: '错误提醒',
            text: err.message,
            confirmButtonText: '知道了',
          });
        });
        uploadFiles.value = '';
      },
      previewImg(el, file) {
        // 预览图片
        const reader = new FileReader();
        reader.onload = (function (file) {
          return function (e) {
            el.imgInfo.show = true;
            el.imgInfo.src = this.result;
            el.imgInfo.filename = file.name;
            el.imgInfo.file = file;
          };
        }(file));
        // 预览图片
        reader.readAsDataURL(file);
      },
      startUploadImg() {
        const file = this.imgInfo.file;

        // 先检查图片类型和大小
        if (!file) {
          return swal({
            title: '错误提醒',
            text: '请先上传图片',
            confirmButtonText: '知道了',
          });
        }
        // 开始上传
        this.startSend = true;
        // 获取七牛token
        this.$store.dispatch('fetchPrepareDraft', {lesson_sn: this.lesson_sn}).then((data) => {
          // 图片的上传
          this.postImg(data, file);
        }, (err) => {
          // 弹窗
          swal({
            title: '错误提醒',
            text: err.message,
            confirmButtonText: '知道了',
          });
        });
      },
      postImg(data, file) {
        // 创建formData对象
        const formData = new FormData();
        formData.append('file', file);
        formData.append('key', data.key);
        formData.append('token', data.token);
        // 开始上传
        this.$store.commit('START_LOADING');
        // 开始上传图片
        this.$http.post(data.upload, formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        }).then((json) => {
          if (json.ok) {
            if (this.iPoint) {
              // 创建图片
              this.$store.dispatch('fetchPrepareCreateImage', {
                lesson_sn: this.lesson_sn,
                content: json.body.key,
                insert: this.prepareList[this.iPoint - 1].seqno,
              }).then((data) => {
                // 图片的上传成功
                this.$store.commit('ADD_PREPARE_LIST', data);
                this.cancleUploadImg();
                this.getAllPrepareList();
                this.iPointAdd();
                // 启动滚动条
                this.goToScroll();
              }, (err) => {
                swal({
                  title: '错误提醒',
                  text: err.message,
                  confirmButtonText: '知道了',
                });
                //
                this.cancleUploadImg();
              });
              this.$options.methods.jumpScrollTop.bind(this)(this.iPoint - 1)
              return
            }
            // 创建图片
            this.$store.dispatch('fetchPrepareCreateImage', {
              lesson_sn: this.lesson_sn,
              content: json.body.key,
            }).then((data) => {
              // 图片的上传成功
              this.$store.commit('ADD_PREPARE_LIST', data);
              this.cancleUploadImg();
              // 启动滚动条
              this.goToScroll();
            }, (err) => {
              swal({
                title: '错误提醒',
                text: err.message,
                confirmButtonText: '知道了',
              });
              //
              this.cancleUploadImg();
            });
          } else {
            new Error('Fetch_Upload_Image failure');
          }
        }).catch((error) => {
          swal({
            title: '错误提醒',
            text: '上传图片失败!',
            confirmButtonText: '知道了',
          }, () => {
            this.$store.commit('FINISH_LOADING');
          });
        });
      },
      postVideo(data, file, compress) {
        // 创建formData对象
        const formData = new FormData();
        formData.append('file', file);
        formData.append('key', data.key);
        formData.append('token', data.token);
        // 开始上传
        // this.$store.commit('START_LOADING');
        // 开始上传图片
        this.$http.post(data.upload, formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        }).then((json) => {
          if (json.ok) {
            if (this.iPoint) { // 创建video
              this.$store.dispatch('fetchPrepareCreateVideo', {
                compress,
                lesson_sn: this.lesson_sn,
                content: json.body.key,
                insert: this.prepareList[this.iPoint - 1].seqno,
              }).then((data) => {
                // 结束上传
                this.startSend = false;
                this.cancleUploadVideo();
                // video的上传成功
                this.$store.commit('ADD_PREPARE_LIST', data);
                // 启动滚动条
                this.getAllPrepareList();
                this.goToScroll();
                this.iPointAdd();
              }, (err) => {
                // 结束上传
                this.startSend = false;
                this.cancleUploadVideo();
                //
                swal({
                  title: '错误提醒',
                  text: err.message,
                  confirmButtonText: '知道了',
                });
              });
              this.$options.methods.jumpScrollTop.bind(this)(this.iPoint - 1)
              return;
            }
            // 创建video
            this.$store.dispatch('fetchPrepareCreateVideo', {
              compress,
              lesson_sn: this.lesson_sn,
              content: json.body.key,
            }).then((data) => {
              // 结束上传
              this.startSend = false;
              this.cancleUploadVideo();
              // video的上传成功
              this.$store.commit('ADD_PREPARE_LIST', data);
              // 启动滚动条
              this.goToScroll();
            }, (err) => {
              // 结束上传
              this.startSend = false;
              this.cancleUploadVideo();
              //
              swal({
                title: '错误提醒',
                text: err.message,
                confirmButtonText: '知道了',
              });
            });
          } else {
            new Error('Fetch_Upload_Video failure');
          }
        }).catch((error) => {
          swal({
            title: '错误提醒',
            text: '上传视频失败!',
            confirmButtonText: '知道了',
          }, () => {
            // 结束上传
            this.startSend = false;
            this.cancleUploadVideo();
            // this.$store.commit('FINISH_LOADING');
          });
        });
      },
      postAudio(data, file) {
        // 创建formData对象
        const formData = new FormData();
        formData.append('file', file);
        formData.append('key', data.key);
        formData.append('token', data.token);
        // 开始上传
        this.$store.commit('START_LOADING');
        // 开始上传音频
        this.$http.post(data.upload, formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        }).then((json) => {
          if (json.ok) {
            if (this.iPoint) {
              this.auditionData = {
                lesson_sn: this.lesson_sn,
                content: json.body.key,
                url: data.url,
                insert: this.prepareList[this.iPoint - 1].seqno,
              };
              this.startAudituon = true;
              this.$store.commit('FINISH_LOADING');
              this.$options.methods.jumpScrollTop.bind(this)(this.iPoint - 1)
              return;
            }
            this.auditionData = {
              lesson_sn: this.lesson_sn,
              content: json.body.key,
              url: data.url,
            };
            this.startAudituon = true;
            this.$store.commit('FINISH_LOADING');
          } else {
            new Error('Fetch_Upload_Radio failure');
          }
        }).catch((error) => {
          swal({
            title: '错误提醒',
            text: '上传音频失败!',
            confirmButtonText: '知道了',
          }, () => {
            this.$store.commit('FINISH_LOADING');
          });
        });
      },
      startGoToScroll() {
        // 滚动
        this.goToScroll();
      },
      jumpScrollTop(index) {
        const prepareBody = document.getElementById('prepare-body');
        // alert(prepareBody.scrollTop = prepareBody.querySelector('ul').children[index].clientHeight)
        // alert(prepareBody.querySelector('ul').children[index].querySelector('div').clientHeight)
        // 找到指定的children
        setTimeout(() => {
          prepareBody.scrollTop = prepareBody.querySelector('ul').children[index].offsetTop - 130;
        }, 100);
      },
      cancleUploadImg() {
        this.startSend = false;
        this.imgShow = false;
        this.moduleShow = false;
        this.imgInfo.show = false;
        this.imgInfo.src = '';
      },
      // 发布阅读模式
      releaseView() {
        swal({
          title: '阅读模式发布',
          text: '提交后不可修改，是否确认发布并向学员推送通知？',
          confirmButtonText: '发布',
          cancelButtonText: '取消',
          showCancelButton:true,
          closeOnConfirm: false,
        }, () => {
          this.$http.post('/prepare-release.api', {
            lesson_sn: this.lesson_sn
          }).then((res) => {
            if (res.body.data) {
              swal({
                title: '发布成功',
                confirmButtonText: '查看内容',
              }, () => {
                window.open(`${this.liveHost}study/view/${this.lesson_sn}`);
                this.$router.push({name: 'list'});
              })
            } else {
              swal({
                title: '发布失败',
                confirmButtonText: '知道了'
              })
            }
          })
        })
      },
      // 打开发送状态
      sendMsg() {
        if (trimStr(this.msgVal)) {
          this.msgSending = true;
        }
        // 获取备课列表
        if (this.iPoint) {
          this.msgSending = false;
          this.$store.dispatch('fetchPrepareCreateText', {
            lesson_sn: this.lesson_sn,
            content: this.msgVal,
            insert: this.prepareList[this.iPoint - 1].seqno,
          }).then((data) => {
            //  http:// web.sandbox.yike.fm:8686/teacher.html#/course/prepare/L5b14df9b584b8
            // 发送成功
            this.msgVal = '';
            // 添加
            this.$store.commit('ADD_PREPARE_LIST', data);
            // 关闭发送状态
            this.msgSending = false;
            // 启动滚动条
            this.goToScroll();
            // 重新获取列表
            this.getAllPrepareList();
            this.iPointAdd();
          }, (err) => {
            // 关闭发送状态
            this.msgSending = false;
            // 异常
            swal({
              title: '错误提醒',
              text: err.message,
              confirmButtonText: '知道了',
            });
          });
          this.$options.methods.jumpScrollTop.bind(this)(this.iPoint - 1)
          return;
        }
        this.$store.dispatch('fetchPrepareCreateText', {
          lesson_sn: this.lesson_sn,
          content: this.msgVal,
        }).then((data) => {
          // 发送成功
          this.msgVal = '';
          // 添加
          this.$store.commit('ADD_PREPARE_LIST', data);
          // 关闭发送状态
          this.msgSending = false;
          // 重新获取列表
          this.getAllPrepareList();
          // 启动滚动条
          this.goToScroll();
        }, (err) => {
          // 关闭发送状态
          this.msgSending = false;
          // 异常
          swal({
            title: '错误提醒',
            text: err.message,
            confirmButtonText: '知道了',
          });
        });
      },
      isMarkModalShow() {
        this.markModalShow = !this.markModalShow;
      },
      sendMark() {
        // 打开发送状态
        if (trimStr(this.markVal)) {
          this.msgSending = true;
        }
        if (this.iPoint) {
          this.$store.dispatch('fetchPrepareCreateMark', {
            lesson_sn: this.lesson_sn,
            content: this.markVal,
            insert: this.prepareList[this.iPoint - 1].seqno,
          }).then((data) => {
            // 发送成功
            this.markVal = '';
            // 添加
            this.$store.commit('ADD_PREPARE_LIST', data);
            // 关闭发送状态
            this.msgSending = false;
            // 启动滚动条
            this.getAllPrepareList();
            this.goToScroll();
            this.iPointAdd();
            this.markModalShow = false;
          }, (err) => {
            // 关闭发送状态
            this.msgSending = false;
            // 异常
            swal({
              title: '错误提醒',
              text: err.message,
              confirmButtonText: '知道了',
            });
          });
          this.$options.methods.jumpScrollTop.bind(this)(this.iPoint - 1)
          return;
        }
        this.$store.dispatch('fetchPrepareCreateMark', {
          lesson_sn: this.lesson_sn,
          content: this.markVal,
        }).then((data) => {
          // 发送成功
          this.markVal = '';
          // 添加
          this.$store.commit('ADD_PREPARE_LIST', data);
          // 关闭发送状态
          this.msgSending = false;
          // 启动滚动条
          this.getAllPrepareList();
          this.goToScroll();
          this.markModalShow = false;
        }, (err) => {
          // 关闭发送状态
          this.msgSending = false;
          // 异常
          swal({
            title: '错误提醒',
            text: err.message,
            confirmButtonText: '知道了',
          });
        });
      },
      v_keydown(event) {
        const e = event || window.event;
        // 快捷键 ctrl+enter
        if (e.ctrlKey && e.keyCode === 13) {
          this.sendMsg();
        }
        // 快捷键 alt+s
        if (e.altKey && e.keyCode === 83) {
          this.sendMsg();
        }
      },
      v_paste(event) {
        // 添加到事件对象中的访问系统剪贴板的接口
        let clipboardData = event.clipboardData,
          i = 0,
          items,
          item,
          types;
        // 是否有数据
        if (clipboardData) {
          items = clipboardData.items;
          if (!items) {
            return;
          }
          item = items[0];
          // 保存在剪贴板中的数据类型
          types = clipboardData.types || [];
          for (; i < types.length; i++) {
            if (types[i] === 'Files') {
              item = items[i];
              break;
            }
          }
          // 判断是否为图片数据
          if (item && item.kind === 'file' && item.type.match(/^image\//i)) {
            this.showPasteImage(item);
          }
        }
      },
      showPasteImage(item) {
        if (!window.File || !window.FileList || !window.FileReader) {
          swal({
            title: '错误提醒',
            text: '您的浏览器不支持File Api',
            confirmButtonText: '知道了',
          });
          return;
        }
        const el = this;
        const blob = item.getAsFile();
        // 先检查图片类型和大小
        if (!checkPastePic(blob, blob.size)) {
          return;
        }
        // 预览图片
        const reader = new FileReader();
        reader.onload = (function () {
          return function (e) {
            el.pasteInfo.show = true;
            el.pasteInfo.src = this.result;
            el.pasteInfo.blob = blob;
            el.pasteInfo.filename = blob.name;
          };
        }());
        // 预览图片
        reader.readAsDataURL(blob);
      },
      startUploadPaste() {
        if (!this.pasteInfo.blob) {
          return;
        }
        // 开始上传
        this.startSend = true;
        // 获取七牛token
        this.$store.dispatch('fetchPrepareDraft', {lesson_sn: this.lesson_sn}).then((data) => {
          // 图片的上传
          this.postPasteImg(data, this.pasteInfo.blob);
        }, (err) => {
          swal({
            title: '错误提醒',
            text: err.message,
            confirmButtonText: '知道了',
          });
        });
      },
      postPasteImg(data, file) {
        // 创建formData对象
        const formData = new FormData();
        formData.append('file', file);
        formData.append('key', data.key);
        formData.append('token', data.token);
        // 开始上传
        this.$store.commit('START_LOADING');
        // 开始上传图片
        this.$http.post(data.upload, formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        }).then((json) => {
          if (json.ok) {
            if (this.iPoint) {
              // 创建图片
              this.$store.dispatch('fetchPrepareCreateImage', {
                lesson_sn: this.lesson_sn,
                content: json.body.key,
                insert: this.prepareList[this.iPoint - 1].seqno,
              }).then((data) => {
                // 图片的上传成功
                this.$store.commit('ADD_PREPARE_LIST', data);
                this.getAllPrepareList();
                this.iPointAdd();
                // 启动滚动条
                this.goToScroll();
                this.pasteInfo.blob = null;
                this.pasteInfo.show = false;
                this.pasteInfo.src = '';
                this.pasteInfo.filename = '';
                this.startSend = false;
              }, (err) => {
                swal({
                  title: '错误提醒',
                  text: err.message,
                  confirmButtonText: '知道了',
                });
                //
                this.cancleUploadImg();
              });
              this.$options.methods.jumpScrollTop.bind(this)(this.iPoint - 1)
              return;
            }
            this.$store.dispatch('fetchPrepareCreateImage', {
              lesson_sn: this.lesson_sn,
              content: json.body.key,
            }).then((data) => {
              // 图片的上传成功
              this.$store.commit('ADD_PREPARE_LIST', data);
              this.pasteInfo.blob = null;
              this.pasteInfo.show = false;
              this.pasteInfo.src = '';
              this.pasteInfo.filename = '';
              this.startSend = false;
              // 启动滚动条
              this.goToScroll();
            }, (err) => {
              swal({
                title: '错误提醒',
                text: err.message,
                confirmButtonText: '知道了',
              });
            });
          } else {
            new Error('Fetch_Upload_Image failure');
          }
        }).catch((error) => {
          swal({
            title: '错误提醒',
            text: '上传图片失败!',
            confirmButtonText: '知道了',
          }, () => {
            this.$store.commit('FINISH_LOADING');
          });
        });
      },
      canclePasteImg() {
        this.startSend = false;
        this.pasteInfo.blob = null;
        this.pasteInfo.show = false;
        this.pasteInfo.src = '';
        this.pasteInfo.filename = '';
      },
      goToScroll(scrollHeight) {
        let top = 0;
        // 是否有
        if (!prepareScroll) {
          prepareScroll = document.getElementById('prepare-body');
        }
        if (this.iPoint) {
          top = prepareScroll.scrollTop;
        }

        setTimeout(() => {
          if (scrollHeight) {
            prepareScroll.scrollTop = scrollHeight;
          } else {
            prepareScroll.scrollTop = prepareScroll.scrollHeight;
          }
          if (this.iPoint) {
            prepareScroll.scrollTop = top;
          }
        }, 100);
      },
      startUploadVideo(compress) {
        if (this.videoInfo && !this.videoInfo.file) {
          // 异常
          swal({
            title: '错误提醒',
            text: '文件未选取，请选择后重试!',
            confirmButtonText: '知道了',
          });
        }
        // 开始上传
        this.startSend = true;
        this.postVideo(this.videoInfo.data, this.videoInfo.file, compress);
      },
      cancleUploadVideo() {
        this.videoShow = false;
      },
      numberOnly(e) {
        if (this.iPoint > this.prepareList.length || this.iPoint < 1) {
          e.currentTarget.value = '';
        }
        this.iPoint = e.currentTarget.value.replace(/[^\d]/g, '');
      },
      iPointAdd() {
        this.iPoint = Number(this.iPoint);
        this.iPoint += 1;
      },
      iPointMinus() {
        this.iPoint = Number(this.iPoint);
        if (this.iPoint > 1 && this.iPoint <= this.len) {
          this.iPoint -= 1;
        }
      },
    },
  };
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";
</style>
