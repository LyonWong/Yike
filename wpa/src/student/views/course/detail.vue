<template>
  <section class="content detail">
    <div class="detail-img" v-if="courseDetail && courseDetail.cover">
      <img :src="`${courseDetail.cover}!cover`" />
    </div>
    <div class="tab" v-if="courseDetail">
      <router-link :to="{name:'brief',query:{lesson_sn:courseDetail.sn}}" replace>介绍</router-link>
      <router-link :to="{name:'notice',query:{lesson_sn:courseDetail.sn}}" replace>须知</router-link>
      <!--<router-link :to="{name:'evaluate',query:{lesson_sn:courseDetail.sn}}" replace>评价</router-link>-->
      <!--<router-link :to="{name:'messageDiscuss',params:{lesson_sn:courseDetail.sn}}">交流</router-link>-->
      <a :href="`${host}study/board/argue?sn=${courseDetail.sn}`">交流</a>
    </div>
    <router-view :isEnroll="isEnroll" v-if="courseDetail"></router-view>
    <v-button :isEnroll="isEnroll" :courseDetail="courseDetail" :liveHost="liveHost" :enableButton="showLoading" @showMenu="showMenu"></v-button>
    <loading :show="showLoading && false"></loading>
    <v-ad :show="adShow" @updateAdShow="updateAdShow"></v-ad>
    <div class="lesson-more" v-if="showMore">
      <div class="more-container" v-if="courseDetail">
        <!--<div @click="backToHome">课程列表</div>-->
        <div v-for="menu in courseDetail.conf.menu" @click="jumpToAppoint(menu.href)">{{menu.text}}</div>
        <!--<div @click="spreadCourse">参与优惠活动</div>-->
        <!--<div @click="becomeTeacher">注册成为讲师</div>-->
      </div>
    </div>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';
  import vButton from '@student/views/course/button.vue';
  import Loading from '@student/components/loading';
  import vAd from '@student/components/ad';

  export default{
    name: 'detail',
    components: {
      vButton,
      Loading,
      vAd,
    },
    computed: {
      ...mapGetters({
        userInfo: 'getUserInfo',
        enroll: 'getLessonEnroll',
        assetsHost:'getAssetsHost',
        canWXShare:'getCanWXShare',
      })
    },
    data() {
      return {
        host: (process.env.NODE_ENV=='production'?process.env.LIVE_HOST:'/'),
        signUpHost:(process.env.TEACHER_HOST ? process.env.TEACHER_HOST.replace(/\/$/,'') : 'https://teacher.sandbox.yike.fm/'),
        liveHost: (process.env.NODE_ENV=='production'?process.env.LIVE_HOST.replace(/\/$/,'/live'):'/live.html'),
        studentHost: (process.env.NODE_ENV=='production'?process.env.STUDENT_HOST.replace(/\/$/,'?'):'/student.html?'),
        studentShareHost: (process.env.NODE_ENV=='production'?process.env.STUDENT_HOST:'/student.html?'),
        lessons: '',
        courseDetail: null,
        isEnroll: null,
        showLoading: true,
        adShow: null,
        showMore: false,
      };
    },
    created() {
      if (location.search.indexOf('?v=2') >= 0) {
        window.location.href = `${process.env.LIVE_HOST}lesson/detail?sn=${this.$route.query.lesson_sn}&origin=${this.$route.query.origin || ''}`;
      } else {
        // 初始化
        this.getDetailInfo();
        this.adShow = this.getQueryString('ad');
      }
    },
    watch: {
      '$route': 'reloadData' //切换路由，调用reloadData方法
    },
    beforeRouteLeave(to, from, next) {
      //清空现有的iframe
      try{
        document.getElementsByTagName('iframe')[0].remove();
      }catch(e){};
      next();
    },
    methods: {
      showMenu(show) {
        this.showMore = show;
      },
      backToHome() {
        this.$router.push({ name: 'list' });
      },
      becomeTeacher() {
        // 跳转
        window.location.href = `${this.signUpHost}/sign-up`;
      },
      jumpToAppoint(href) {
        //
        window.location.href = href;
      },
      spreadCourse() {
        //
        window.location.href = `${this.studentHost}promote?target_sn=${this.course.sn}`;
      },
      writeIframe(query, data){
        // 把数据写入iframe
        console.log('detail');
        let userInfo = { ...this.userInfo };
        let lesson_info = encodeURIComponent(`${JSON.stringify(data)}`);
        let user_info = encodeURIComponent(`${JSON.stringify(userInfo)}`);
        /*let lesson = `lesson_info=${lesson_info}&lesson_sn=${query.lesson_sn}&user_info=${user_info}`;*/
        let lesson = `lesson_info=lesson_info&lesson_sn=${query.lesson_sn}&user_info=user_info`;
        let iframe = document.createElement('iframe');
        let src = `${this.liveHost}?${lesson}`;
        iframe.src = src;
        iframe.style.height = 0;
        iframe.style.display = 'none';
        document.body.appendChild(iframe);
      },
      wxConfig(data) {
        // 请求配置接口
        // 微信操作
        try{
          wx.ready(() => {
            let shareAppMessageLink = (data.teacher.sn == this.userInfo.sn) ? `${this.studentShareHost}share?lesson_sn=${data.sn}&origin=teacher-${data.teacher.sn}` : `${this.studentShareHost}share?lesson_sn=${data.sn}&origin=wxShare-message`;
            let shareTimelineLink = (data.teacher.sn == this.userInfo.sn) ? `${this.studentShareHost}share?lesson_sn=${data.sn}&origin=teacher-${data.teacher.sn}` : `${this.studentShareHost}share?lesson_sn=${data.sn}&origin=wxShare-timeline`;
            let imgUrl = this.courseDetail.cover ? this.courseDetail.cover : `${this.assetsHost}/static/student/_static/student/img/default-lesson-share.png`;
            // 微信发送给朋友
            wx.onMenuShareAppMessage({
              title: `${data.teacher.name} | ${data.title}`, // 分享标题
              desc: data.brief, // 分享描述
              link: shareAppMessageLink, // 分享链接
              imgUrl: imgUrl, // 分享图标
              type: '', // 分享类型,music、video或link，不填默认为link
              dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
              success: () => {
                // 用户确认分享后执行的回调函数
                console.log('success');
              },
              cancel: () => {
                // 用户取消分享后执行的回调函数
                console.log('cancel');
              }
            });
            // 分享到朋友圈
            wx.onMenuShareTimeline({
              title: `${data.teacher.name} | ${data.title}`, // 分享标题
              link: shareTimelineLink, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
              imgUrl: imgUrl, // 分享图标
              success: () => {
                // 用户确认分享后执行的回调函数
                console.log('success');
              },
              cancel: () => {
                // 用户取消分享后执行的回调函数
                console.log('cancel');
              }
            });
            // 展示页面
            this.showLoading = false;
          });
          wx.error((res)=>{
            console.log(res.err_msg);
          });
        }catch(e){
          // 展示页面
          this.showLoading = false;
        };
      },
      getDetailInfo() {
        //获取路由参数
        let query = this.$route.query;
        //
        this.$store.dispatch('fetchCourseDetail', query).then((data) => {
          // 赋值
          this.courseDetail = data;
          // 是否报名
          this.isEnroll = data.event;
          //清空现有的iframe
          try{
            document.getElementsByTagName('iframe')[0].remove();
          }catch(e){};
          // 把数据写入iframe
          if(data.event != 'browse' && data.event != 'reset'){
            this.writeIframe(query, data);
          }
          // 开始调用分享接口
          this.wxShare();
          /*// 微信分享功能
          let self = this;
          (function inspectShare(){
            if(self.canWXShare){
              if(self.canWXShare == 1){
                return self.wxConfig(self.courseDetail);
              }else{
                return console.log('分享配置失败!');
              }
            }
            // 重新监测
            setTimeout(()=>{
              inspectShare();
            }, 300);
          })();*/
        }, () => {
          console.log('fail');
        });
      },
      wxShare() {
        // 请求配置接口
        /*console.log(`${this.studentShareHost}#/course/detail/brief?lesson_sn=${this.courseDetail.sn}`);*/
        if(isWeiXin){
          this.$store.dispatch('fetchWXConfig', {url:encodeURIComponent(window.location.href)}).then((result)=>{
            // 微信操作
            wx.config({
              debug: false,
              appId: result.appId,
              timestamp: result.timestamp,
              nonceStr: result.nonceStr,
              signature: result.signature,
              jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage', 'chooseWXPay']
            });
            // 可以分享了
            console.log('可以分享');
            this.wxConfig(this.courseDetail);
            //this.$store.commit('UPDATE_WXSHARE_SHARE', 1);
          },()=>{
            // 不能分享
            console.log('不能分享');
            // 展示页面
            this.showLoading = false;
            //this.$store.commit('UPDATE_WXSHARE_SHARE', 2);
          });
        } else {
          this.showLoading = false;
        }
      },
      getQueryString(name) {
        var reg = new RegExp('(^|&|\\?)' + name + '=([^&]*)(&|$)', 'i');
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return unescape(r[2]);
        return null;
      },
      updateAdShow(show) {
        this.adShow = show;
      },
      reloadData() {
        if (process.env.NODE_ENV!='production'){
          // 初始化
          this.courseDetail = null;
          this.getDetailInfo();
          this.adShow = this.getQueryString('ad');
        }
      },
    },
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";
  .content.detail
    div.lesson-more
      position: fixed;
      top: 0;
      bottom: 0;
      margin: 0;
      width: 100%;
      background-color: rgba(0,0,0,.3);
      .more-container
        position: absolute;
        padding: 0;
        width: 100%;
        px2px(bottom, 98px);
      div>*
        padding: 30px 0;
        color: #333;
        background: #fff;
        text-align: center;
        border-top: 1px solid #d9d9d9;
  .body-pc
    .content.detail
      div.lesson-more
        width: 640px;
        .more-container
          bottom: 100px;

  @media only screen and (device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) {
    .content.detail {
      div.lesson-more {
        .more-container {
          padding-bottom: 60px;
        }
      }
    }
  }
</style>
