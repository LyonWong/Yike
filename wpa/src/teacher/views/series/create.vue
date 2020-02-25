<template>
  <section class="content-create">
    <div class="title clearfix">
      系列课列表>{{data.series_sn?"系列课编辑":"系列课创建"}}
      <span class="title-handle pull-right" @click="back">返回</span>
    </div>
    <div class="control">
      <span class="word"><em>*&nbsp;</em>标题</span>
      <div class="text">
        <input name="title" type="text" v-model="data.name" :disabled="review_status == 'start' || review_status == 'pending'" />
        <p class="limit">限36个字符</p>
      </div>
    </div>
    <div class="control" v-show="false">
      <span class="word"><em>*&nbsp;</em>分成比例</span>
      <div class="text">
        <input name="title" type="text" v-model="data.share" @blur="shareBlur" :disabled="review_status == 'start' || review_status == 'pending'" />&nbsp;&#37;
        <p class="limit">扣除平台费用后，系列课创建者分成{{data.share}}%, 讲师分成{{100-data.share}}%</p>
      </div>
    </div>
    <!--<div class="control">-->
      <!--<span class="word"><em>*&nbsp;</em>价格</span>-->
      <!--<div class="text">-->
        <!--<input name="price" type="tel" v-model="data.price" @blur="priceBlur" :disabled="review_status == 'start' || review_status == 'pending'" />&nbsp;&nbsp;<span>元</span>-->
        <!--<p class="limit">请输入0-5000之间的数，0表示免费</p>-->
      <!--</div>-->
    <!--</div>-->
    <div class="control">
      <span class="word">封面</span>
      <div class="text">
        <s-cropper v-if="canUseCrop && review_status != 'start' && review_status != 'pending'" :callback="callbackCropper" :cover="data.cover"></s-cropper>
        <div class="edit-cropper" v-if="review_status == 'start' || review_status == 'pending'">
          <div class="picture" :style="'backgroundImage:url('+data.cover+')'"></div>
        </div>
      </div>
    </div>
    <div class="control">
      <span class="word"><em>*&nbsp;</em>课程介绍</span>
      <div class="text editor">
        <!--<textarea name="intro" id="" cols="30" rows="10" v-model="data.content" :disabled="review_status == 'start' || review_status == 'pending'"></textarea>-->
        <mavon-editor v-model="data.content" :subfield="false" :toolbarsFlag="false" :toolbars="{}" :default_open="'preview'" v-if="review_status == 'start' || review_status == 'pending'" />
        <mavon-editor v-model="data.content" @imgAdd="getContentUrl" :default_open="'edit'" :subfield="true" :toolbars="{preview:true,table:true,htmlcode:false,bold:true,italic:true,header:true,strikethrough:true,code:true,imagelink:true}" v-if="review_status != 'start' && review_status != 'pending'" />
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
  import sCropper from './cropper.vue';
  import swal from 'sweetalert';

  export default{
    name: 'series-create',
    components: {
      sCropper,
    },
    data() {
      return {
        canUseCrop: false,
        coverChange: false,
        review_status: '',
        data: {
          name: '',
          share: '0',
          cover: '',
          content: '',
          price: '',
          series_sn: '',
        },
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
      this.data.series_sn = params.series_sn;
      //
      if(this.data.series_sn){
        // 开始请求
        this.$store.dispatch('fetchSeriesEdit', params).then((data)=>{
          //this.agree = true;
          this.canUseCrop = true;
          this.review_status = data.review_status;
          this.data.name = data.name || '';
          this.data.share  = data.scheme.share  || '';
          this.data.cover = data.introduce.cover || '';
          this.data.content = data.introduce.content || '';
          this.data.price = data.scheme.price || '';
          this.$store.commit('CHANGE_CALENDAR', {value: this.data.dtm_start});
        },()=>{
          console.log('fail');
        });
      }else{
          this.canUseCrop = true;
      }
    },
    methods: {
      priceBlur() {
        /*this.data.price = this.data.price.match(/\d*(\.\d{0,2})?/)[0];*/
        // 不能有小数点
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
      shareBlur() {
        if(this.data.share < 0){
          swal({
            title: '错误提醒',
            text: '价格金额不能为负数,请重新填写',
            confirmButtonText: "知道了"
          });
          return this.data.share = '';
        }
        // 不能有小数点
        this.data.share = this.data.share.match(/\d*/)[0];
        if(this.data.share && this.data.share > 100){
          swal({
            title: '错误提醒',
            text: '分成比例不可以大于100%,请重新填写',
            confirmButtonText: "知道了"
          });
          this.data.share = '';
        }
      },
      completeCreate() {
        if(!this.data.name)return swal({
          title: '错误提醒',
          text: '请填写标题',
          confirmButtonText: "知道了"
        });
        if(strlen(trimStr(this.data.name)) > 36)return swal({
          title: '错误提醒',
          text: '标题文字过长',
          confirmButtonText: "知道了"
        });
        /*
        if(this.data.share === '')return swal({
          title: '错误提醒',
          text: '请填写分成比例',
          confirmButtonText: "知道了"
        });
        */
        if(!this.data.content)return swal({
          title: '错误提醒',
          text: '请填写课程介绍',
          confirmButtonText: "知道了"
        });
        if(!this.coverChange){
          delete this.data['cover'];
        }
        //
        if(this.data.series_sn){
          this.$store.dispatch('fetchSeriesModify', { ...this.data }).then((json) => {
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
          delete this.data['series_sn'];
          this.$store.dispatch('fetchSeriesCreate', { ...this.data }).then((json) => {
            // 发起创建请求
            swal({
              title: '',
              text: '已提交审核，通过后将开放报名',
              confirmButtonText: "确定"
            },()=>{
              // 进入系列课编辑
              this.$router.push({ name: 'seriesList' });
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
        this.$router.push({ name: 'seriesList' });
      },
      getContentUrl (pos, $file) {
        // 获取七牛token
        this.$store.dispatch('fetchSeriesUrlToken').then((data) => {
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
            this.data.content = this.data.content.replace(`![图片](${pos})`, `![图片](${data.url})`);
            return this.$store.commit('FINISH_LOADING');
          }
          new Error('Fetch_Open_Info failure')
        }).catch((error) => {
          // 删除图片
          this.data.content = this.data.content.replace(`![图片](${pos})`, '');
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
        &.gray
          background: #aaa;

    .img
      img
        width: 128px;
        height: 60px;

    .title
      padding-bottom: 15px;
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
