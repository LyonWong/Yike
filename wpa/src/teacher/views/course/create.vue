<template>
  <section class="content-create">
    <div class="title clearfix">
      <span class="font-weight">课程列表>{{data.lesson_sn?"课程编辑":"课程创建"}}</span>
      <span class="title-handle pull-right" @click="back">返回</span>
    </div>
    <div class="control">
      <span class="word"><em>*&nbsp;</em>标题</span>
      <div class="text">
        <input name="title" type="text" v-model="data.title" :disabled="review_status == 'start' || review_status == 'pending'" />
        <p class="limit">限36个字符</p>
      </div>
    </div>
    <div class="control">
      <span class="word"><em>*&nbsp;</em>开课时间</span>
      <div class="text">
        <c-calendar :defaultValue="data.dtm_start" v-if="review_status != 'start' && review_status != 'pending'"></c-calendar>
        <input name="dtm_start" type="text" v-model="data.dtm_start" disabled v-if="review_status == 'start' || review_status == 'pending'" />
      </div>
    </div>
    <div class="control">
      <span class="word"><em>*&nbsp;</em>预计持续时长</span>
      <div class="text">
        <!--<input name="duration" type="number" min="0" step="0.5" v-model="data.duration" @blur="durationBlur" />-->
        <!--<input name="duration" type="tel" v-model="data.duration" @blur="durationBlur" :disabled="review_status == 'start' || review_status == 'pending'" />&nbsp;&nbsp;<span>小时</span>-->
        <select class="select" v-model="data.duration" :disabled="review_status == 'start' || review_status == 'pending'">
          <option value="">请选择</option>
          <option v-for="option in options" v-bind:value="option.value">
            {{ option.text }}
          </option>
        </select>
      </div>
    </div>
    <div class="control">
      <span class="word">课程类型</span>
      <div class="text">
        <select class="select" v-model="data.form">
          <option value="im" :selected="data.form==='im' ? 'selected' : ''">图文语音直播</option>
          <option value="view" :selected="data.form==='view'? 'selected': ''">文章阅读模式</option>
        </select>
      </div>
    </div>
    <div class="control">
      <span class="word">所属系列课</span>
      <div class="text">
        <!--<input name="duration" type="number" min="0" step="0.5" v-model="data.duration" @blur="durationBlur" />-->
        <!--<input name="duration" type="tel" v-model="data.duration" @blur="durationBlur" :disabled="review_status == 'start' || review_status == 'pending'" />&nbsp;&nbsp;<span>小时</span>-->
        <select class="select" v-model="data.category" :disabled="review_status == 'start' || review_status == 'pending'" v-if="series && !token">
          <option value="">无</option>
          <option v-for="option in series" v-bind:value="option.sn">
            {{ option.name }}
          </option>
        </select>
        <select class="select" v-model="data.category" disabled v-if="series && token">
          <option value="">请选择</option>
          <option v-for="option in series" v-bind:value="option.sn">
            {{ option.name }}
          </option>
        </select>
      </div>
    </div>
    <div class="control">
      <span class="word"><em>*&nbsp;</em>价格</span>
      <div class="text">
        <input name="price" type="tel" v-model="data.price" @blur="priceBlur" :disabled="review_status == 'start' || review_status == 'pending'" />&nbsp;&nbsp;<span>元</span>
        <p class="limit">请输入0-5000之间的数，0表示免费</p>
      </div>
    </div>
    <div class="control">
      <span class="word">封面</span>
      <div class="text">
        <!--<input name="cover" type="file" @change="imgOnChange" />-->
        <!--<p class="img"><img :src="data.cover"></p>-->
        <!--<p class="limit">请上传尺寸为640*300，大小在100k以内的图片</p>-->
        <v-cropper v-if="canUseCrop && review_status != 'start' && review_status != 'pending'" :callback="callbackCropper" :cover="data.cover"></v-cropper>
        <div class="edit-cropper" v-if="review_status == 'start' || review_status == 'pending'">
          <div class="picture" :style="'backgroundImage:url('+data.cover+')'"></div>
        </div>
      </div>
    </div>
    <div class="control">
      <span class="word"><em>*&nbsp;</em>课程介绍</span>
      <div class="text editor">
        <!--<textarea name="intro" id="" cols="30" rows="10" v-model="data.brief" :disabled="review_status == 'start' || review_status == 'pending'"></textarea>-->
        <mavon-editor v-model="data.brief" :subfield="false" :toolbarsFlag="false" :toolbars="{}" :default_open="'preview'" v-if="review_status == 'start' || review_status == 'pending'" />
        <mavon-editor v-model="data.brief" @imgAdd="getContentUrl" :default_open="'edit'" :subfield="true" :toolbars="{preview:true,table:true,htmlcode:false,bold:true,italic:true,header:true,strikethrough:true,code:true,imagelink:true}" v-if="review_status != 'start' && review_status != 'pending'" />
      </div>
    </div>
    <div class="protocol">
      <!--<input name="agree" type="checkbox" v-model="agree" />-->
      <!--我已阅读并同意《讲师须知》的内容-->
      <p>
        <i class="iconfont icon-dot"></i>&nbsp;&nbsp;授课内容不得违反国家法律法规<br />
        <i class="iconfont icon-dot"></i>&nbsp;&nbsp;课堂分为授课区和讨论区，学员可在讨论区交流<br />
        <i class="iconfont icon-dot"></i>&nbsp;&nbsp;课程结束后，可选择是否开放课后交流时间，与学员进一步探讨<br />
        <i class="iconfont icon-dot"></i>&nbsp;&nbsp;课程审核通过后即开放报名，修改课程信息需要再次审核
      </p>
    </div>
    <p class="button">
      <button @click="completeCreate" v-if="review_status != 'start' && review_status != 'pending'">提交审核</button>
      <button class="gray" v-if="review_status == 'start' || review_status == 'pending'">审核中</button>
    </p>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';
  import { checkPic, strlen, trimStr } from '@lib/js/mUtils';
  import vCropper from '@teacher/components/cropper.vue';
  import cCalendar from '@teacher/views/course/calendar.vue';
  import swal from 'sweetalert';

  export default{
    name: 'create',
    components: {
      cCalendar,
      vCropper,
    },
    data() {
      return {
        //agree: false,
        //cropper: null,
        //croppable: false,
        //panel: false,
        canUseCrop: false,
        coverChange: false,
        review_status: '',
        series: null,
        token: '',
        data: {
          title: '',
          dtm_start: '',
          duration: '',
          cover: '',
          brief: '',
          price: '',
          category: '',
          isPublic: '1',
          lesson_sn: '',
        },
        options: [
          {text:'15分钟',value:0.25},
          {text:'30分钟',value:0.5},
          {text:'1小时',value:1},
          {text:'1.5小时',value:1.5},
          {text:'2小时',value:2},
          {text:'3小时',value:3},
          {text:'4小时',value:4},
        ],
        callbackCropper: (url)=>{
          this.coverChange = true;
          this.data.cover = url;
        }
      }
    },
    computed: {
      ...mapGetters({
        calendarInfo: 'getCalendar',
      })
    },
    created() {
      //获取路由参数
      let params = this.$route.params;
      let query = this.$route.query;
      this.data.lesson_sn = params.lesson_sn;
      this.token = query.token || '';
      // 是否是编辑页面
      if(this.data.lesson_sn){
        // 开始请求
        this.$store.dispatch('fetchCourseEdit', params).then((data)=>{
          //this.agree = true;
          this.canUseCrop = true;
          this.review_status = data.review_status;
          this.data.title = data.title || '';
          this.data.dtm_start = data.plan.dtm_start || '';
          this.data.duration = data.plan.duration || '';
          this.data.cover = data.cover || '';
          this.data.brief = data.brief || '';
          this.data.price = data.price;
          this.data.isPublic = data.isPublic || '1';
          this.data.category = data.category || '';
          this.data.form = data.form || ''
          console.log(this.data)
          //
          this.$store.commit('CHANGE_CALENDAR', {value: this.data.dtm_start});
        },()=>{
          this.canUseCrop = true;
          console.log('fail');
        });
      }else{
          this.data.category = query.series_sn || '';
          this.canUseCrop = true;
      };
      //
      this.getSeriesList(query.series_sn);
    },
    methods: {
      imgOnChange(event) {
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
        var el = this;

        //先检查图片类型和大小
        if (!checkPic(uploadFiles, fileSize)) {
          return;
        }

        //预览图片
        var reader = new FileReader();
        reader.onload = (function (file) {
          return function (e) {
            el.cover = this.result;
            //el.imgInfo.filename = file.name;
          };
        })(file);
        //预览图片
        reader.readAsDataURL(file);
      },
      priceBlur() {
        /*this.data.price = this.data.price.match(/\d*(\.\d{0,2})?/)[0];*/
        this.data.price = this.data.price.match(/\d*/)[0];
        if(this.data.price && this.data.price > 5000){
          swal({
            title: '错误提醒',
            text: '价格金额不可以大于5000,请重新填写',
            confirmButtonText: "知道了"
          });
          this.data.price = '';
        }
      },
      durationBlur() {
        /*this.data.duration = this.data.duration.match(/\d*(\.\d{0,1})?/)[0];*/
        this.data.duration = this.data.duration.match(/\d{0,2}/)[0];
      },
      completeCreate() {
        if(!this.data.title)return swal({
          title: '错误提醒',
          text: '请填写标题',
          confirmButtonText: "知道了"
        });
        if(strlen(trimStr(this.data.title)) > 36)return swal({
          title: '错误提醒',
          text: '标题文字过长',
          confirmButtonText: "知道了"
        });
        //开播时间赋值
        this.data.dtm_start = this.calendarInfo.value;
        if(!this.data.dtm_start)return swal({
          title: '错误提醒',
          text: '请填写开播时间',
          confirmButtonText: "知道了"
        });
        if(!this.data.duration)return swal({
          title: '错误提醒',
          text: '请填写持续时长',
          confirmButtonText: "知道了"
        });
        if(this.data.price === '')return swal({
          title: '错误提醒',
          text: '请填写价格',
          confirmButtonText: "知道了"
        });
        /*if(!this.data.cover)return alert('请选择封面！');*/
        if(!this.data.brief)return swal({
          title: '错误提醒',
          text: '请填写课程介绍',
          confirmButtonText: "知道了"
        });
        if(!this.coverChange){
          delete this.data['cover'];
        }
        if(this.token){
          this.data['token'] = this.token;
        }
        //
        if(this.data.lesson_sn){
          /*if(!/^data:image/g.test(this.data.cover)){
            delete this.data['cover'];
          }*/
          this.$store.dispatch('fetchCourseModify', { ...this.data }).then((json) => {
            // 发起创建请求
            swal({
              title: '',
              text: '课程编辑信息已提交，正在审核中',
              confirmButtonText: "确定"
            },()=>{
              // 当前页面重载
              window.location.reload();
            });

          }, (err) => {
            swal({
              title: '错误提醒',
              text: err.message,
              confirmButtonText: "确定"
            });
          });

        }else{
          delete this.data['lesson_sn'];
          this.$store.dispatch('fetchCourseCreate', { ...this.data }).then((json) => {
            // 发起创建请求
            swal({
              title: '',
              text: '已提交审核，通过后将开放报名',
              confirmButtonText: "确定"
            },()=>{
              // 进入编辑课程
              this.back();
              /*this.$router.push({ name: 'edit', params: { lesson_sn: this.data.lesson_sn }});*/
            });
          }, (err) => {
            //
            swal({
              title: '错误提醒',
              text: err.message,
              confirmButtonText: "确定"
            });
          });
        }
      },
      back() {
        if(this.data.category){
          // 找出系列课名字
          let title = '';
          for(let ser of this.series) {
            if(ser.sn == this.data.category){
              title = ser.name;
              break;
            }
          }
          //
          this.$router.push({ name: 'singleList', params: { series_sn:this.data.category, title: title } });
        }else{
          this.$router.push({ name: 'list' });
        }
      },
      getContentUrl (pos, $file) {
        // 获取七牛token
        this.$store.dispatch('fetchContentUrl').then((data) => {
          // 图片的上传
          this.imgAdd(data, pos, $file);
        }, (err) => {
          /*alert(err.message);*/
          swal({
            title: '错误提醒',
            text: err.message,
            confirmButtonText: "知道了"
          });
        });
      },
      imgAdd(data, pos, imgFile) {
        //创建formData对象
        var formData = new FormData();
        formData.append('file', imgFile);
        formData.append('key', data.key);
        formData.append('token', data.token);
        // 开始上传图片
        this.$store.commit('START_LOADING');
        // 请求开始
        this.$http.post(data.upload, formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        }).then((json) => {
          if (json.ok) {
            // 替换图片
            this.data.brief = this.data.brief.replace(`![图片](${pos})`, `![图片](${data.url})`);
            return this.$store.commit('FINISH_LOADING');
          }
          new Error('Fetch_Open_Info failure')
        }).catch((error) => {
          // 删除图片
          this.data.brief = this.data.brief.replace(`![图片](${pos})`, '');
          //
          swal({
            title: '错误提醒',
            text: '上传图片失败!',
            confirmButtonText: "知道了"
          },()=>{
            this.$store.commit('FINISH_LOADING');
          });
        });
      },
      getSeriesList(series_sn){
        let opt = {
          status:1,
        };
        //
        if(series_sn){
          opt['series_sn'] = series_sn;
        }
        // 获取系列课列表接口
        this.$store.dispatch('fetchSeriesList', opt).then((data) => {
          //
          this.series = data;

        }, () => {
          console.log('fail');
        });
      },
    }
  }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  .content-create
    padding: 20px;
    background: #fff;

    .icon-dot
      font-size: 12px;

    .button
      text-align: right;
      button
        padding: 8px 20px;
        color: #fff;
        border: 0 none;
        border-radius: 10px;
        background: #12b7f5;
        cursor: pointer;
        font-size: 16px;
        &.gray
          background: #aaa;

    .img
      img
        width: 128px;
        height: 60px;

    .title
      margin-bottom: 15px;
      padding-bottom: 15px;
      border-bottom: 1px solid #e3e3e3;
      .title-handle
        color: #12b7f5;
        cursor: pointer;

    .control
      position: relative;
      padding-bottom: 10px;

      &:after
        content: ".";
        display: block;
        height: 0;
        clear: both;
        visibility: hidden;
      .word, .text
        float: left;
        em
          font-style: normal;
          color: #FB617F;
      .word
        padding-right: 20px;
        padding-top: 5px;
        width: 150px;
        font-size: 14px;
        text-align: right;
        color: #3C4A55;
      .text
        input
          padding: 5px;
          border: 1px solid #E6EAF2;
        p
          margin-bottom: 0;
        textarea
          width: 500px;
          border-color: #E6EAF2;
        span
          font-size: 12px;
        .select
          width: 181px;
          height: 28px;
          border: 1px solid #e6eaf2;
        &.editor
          padding-top: 5px;
          width: 700px;
      .limit
        font-size: 12px;
        color: #aaa;
      .edit-cropper
        width: 230px;
        height: 100px;
        overflow: hidden;
        position: relative;
        border: 1px solid #e6eaf2;
        .picture
          width: 100%;
          height: 100%;
          overflow: hidden;
          background-position: center center;
          background-repeat: no-repeat;
          background-size: cover;

    .protocol
      padding: 0 167px;
      font-size: 12px;
      line-height: 1.5;
      color: #aaa;
      p
        margin: 0;
</style>
