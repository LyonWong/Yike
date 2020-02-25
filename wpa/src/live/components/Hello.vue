<template>
  <div class="hello">
    <v-enter v-if="userInfo"></v-enter>
  </div>
</template>

<script>
  import vEnter from '@live/components/enter/index.vue';
  import SetWechatTitle from '@lib/js/setWechatTitle';
  import { setStore, getStore, removeStore, decodeQueryString } from '@lib/js/mUtils';
  import {mapState} from 'vuex';

  export default {
    name: 'hello',
    components: {
      vEnter,
    },
    computed: {
      ...mapState([
        'userInfo',
        'liveHost',
        'studentHost',
      ])
    },
    data() {
      return {
        open: {
          isOwner: decodeQueryString('isOwner'),
          teach: decodeQueryString('teach'),
          discuss: decodeQueryString('discuss'),
          lesson_sn: decodeQueryString('lesson_sn'),
          lesson_info: decodeQueryString('lesson_info'),
          user_info: decodeQueryString('user_info'),
          userSig: '',
        }
      };
    },
    mounted() {
      // usersig接口
      let userSigUrl = `${this.liveHost}/live-tim-user_sig.api`;

      // 是否有lessonInfo 存入localstorage
      if(this.open.lesson_info && this.open.lesson_sn && this.open.user_info){
        // 清除
        removeStore(this.open.lesson_sn);
        // 组装
        /*let opt = {
          user_info: JSON.parse(this.open.user_info),
          lesson_info: JSON.parse(this.open.lesson_info),
        };*/
        let opt = {
          user_info: this.open.user_info,
          lesson_info: this.open.lesson_info,
        };
        // 添加
        setStore(this.open.lesson_sn, opt);
        // 缓存历史记录
        try{
          this.$store.dispatch('fetchHistory', {lesson_sn:this.open.lesson_sn}).then(()=>{});
          // 调用接口
          // 清除
          //removeStore(`wxConfig`);
          // 调用分享
          //this.fetchWXConfig(opt.lesson_info);
        }catch(e){}
        //
        return;
      }
      // 老师端首次进入
      if(decodeQueryString('teacherEnter')){
        // 清除
        removeStore(this.open.lesson_sn);
        // 新url
        let newUrl = window.location.href.replace(/&teacherEnter=[^&]*/, '');
        window.history.pushState(null, null, newUrl);
      }

      // 数据处理
      var jsonData = getStore(this.open.lesson_sn);
      if(jsonData){
        // 解析json
        jsonData = JSON.parse(jsonData);
        // 用户sn
        this.open.sn = jsonData.user_info.sn;
        // 从storage获取
        let _userSig = getStore(this.open.sn);
        //
        if(_userSig)return this.handleStoreData(jsonData);
        // 获得userSig
        this.$http.get(userSigUrl).then((json)=>{
          if(json.ok){
            this.open.userSig = json.body.data;
            // 存储usersig
            setStore(this.open.sn, this.open.userSig);
            // 开始处理数据
            this.handleStoreData(jsonData);
            /*---end---*/
          }
        },(err)=>{
          console.log(err);
        });

      }else{
        // 不能获得localstorage的浏览器
        // 获得userSig
        this.$http.get(userSigUrl).then((json)=>{
          if(json.ok){
            this.open.userSig = json.body.data;
            // 存储usersig
            setStore(this.open.sn, this.open.userSig);
            // 开始处理数据
            this.handleAsynData();
            /*---end---*/
          }
        },(err)=>{
          console.log(err);
        });
      }
    },
    methods: {
      handleStoreData(jsonData) {
        try{
          // 直播间id
          jsonData.lesson_info.teach = this.open.teach;
          jsonData.lesson_info.discuss = this.open.discuss;
          // 改变title
          SetWechatTitle(`易灵微课-${jsonData.lesson_info.title}`);
          // 用户信息
          //this.open.sn = jsonData.user_info.sn;
          this.open.name = jsonData.user_info.name;
          this.open.avatar = jsonData.user_info.avatar;
          this.open.userSig = getStore(this.open.sn);
          this.open.groupId = this.open.teach;
          this.open.discuss = this.open.discuss;
          // 用户详情
          this.$store.commit('UPDATE_USERINFO', this.open);
          // 课程详情
          this.$store.commit('UPDATE_LESSONINFO', jsonData.lesson_info);
          // 老师详情
          this.$store.commit('UPDATE_TEACHERINFO', jsonData.lesson_info.teacher);
          // 老师头像
          this.$store.commit('UPDATE_AVATAR', jsonData.lesson_info.teacher.avatar);
          // 分享
          this.fetchWXConfig(jsonData.lesson_info);
          // 获取统计消息
          // this.fetchStats();
        }catch(e){};
      },
      handleAsynData() {
        // 获得lesson info
        let userUrl = `${this.liveHost}/user-profile.api`;
        let lessonUrl = `${this.liveHost}/lesson-detail.api?lesson_sn=${this.open.lesson_sn}`;
        //
        this.$http.get(lessonUrl).then((json)=>{
          let jsonOpt = {};
          if(json.ok){
            let data = json.body.data;
            jsonOpt.lesson_info = data;
            // 增加id
            data.teach = this.open.teach;
            data.discuss = this.open.discuss;
            // 课程详情
            this.$store.commit('UPDATE_LESSONINFO', data);
            this.$store.commit('UPDATE_TEACHERINFO', data.teacher);
            this.$store.commit('UPDATE_AVATAR', data.teacher.avatar);
            // 分享
            this.fetchWXConfig(data);
            // 获得user info
            this.$http.get(userUrl).then((json)=>{
              if(json.ok){
                let data = json.body.data;
                jsonOpt.user_info = data;
                // 存储课程数据
                setStore(this.open.lesson_sn, jsonOpt);
                // 存储usersig
                setStore(data.sn, this.open.userSig);
                // 改变title
                SetWechatTitle(`易灵微课-${jsonOpt.lesson_info.title}`);
                // 重组用户信息
                this.open.sn = data.sn;
                this.open.name = data.name;
                this.open.avatar = data.avatar;
                this.open.groupId = this.open.teach;
                this.open.discuss = this.open.discuss;
                this.$store.commit('UPDATE_USERINFO', this.open);
                // 获取统计消息
                // this.fetchStats();
              }
            },(err)=>{
              console.log(err);
            });
            /*---end---*/
          }

        },(err)=>{
          console.log(err);
        });
      },
      fetchWXConfig(data) {
        // 请求配置接口
        if(wx){
          // 判断是否有storage
          let wxConfig = null && getStore(`wxConfig`);
          //
          if(wxConfig){
            // 微信操作
            wx.config({
              debug: false,
              appId: wxConfig.appId,
              timestamp: wxConfig.timestamp,
              nonceStr: wxConfig.nonceStr,
              signature: wxConfig.signature,
              jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage']
            });
            // 分享开始
            setTimeout(()=>{
              this.handleWXConfig(data);
            });
          }else{
            //
            this.$store.dispatch('fetchWXConfig', {url:encodeURIComponent(window.location.href)}).then((result)=>{
              // 微信操作
              wx.config({
                debug: false,
                appId: result.appId,
                timestamp: result.timestamp,
                nonceStr: result.nonceStr,
                signature: result.signature,
                jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage']
              });
              // 存储wxConfig
              //setStore(`wxConfig`, result);
              // 分享
              setTimeout(()=>{
                this.handleWXConfig(data);
              });
            });
          }
        }
      },
      handleWXConfig(data) {
        // 请求配置接口
        // 微信操作
        try{
          wx.ready(() => {
            /*let shareLink = `${this.studentHost}share_url=${encodeURIComponent('?#/course/detail/brief?lesson_sn='+data.sn)}#/course/detail/brief?lesson_sn=${data.sn}&origin=share`;*/
            let shareLink = `${this.studentHost.replace(/\?$/,'')}/live?isOwner=${this.open.isOwner}&lesson_sn=${data.sn}&teach=${data.sn}-T&discuss=${data.sn}-D`;
            // 微信发送给朋友
            wx.onMenuShareAppMessage({
              title: `${data.teacher.name} | ${data.title}`, // 分享标题
              desc: data.brief, // 分享描述
              link: shareLink, // 分享链接
              imgUrl: data.cover, // 分享图标
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
              link: shareLink, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
              imgUrl: data.cover, // 分享图标
              success: () => {
                // 用户确认分享后执行的回调函数
                console.log('success');
              },
              cancel: () => {
                // 用户取消分享后执行的回调函数
                console.log('cancel');
              }
            });

          });
        }catch(e){};
      },
      fetchStats() {
        try{
          let statsUrl = `${this.liveHost}/stats-overview.api`;
          // 获取统计信息
          this.$http.get(statsUrl).then((json)=>{
            if(json.ok){
              this.$store.commit('UPDATE_STATSINFO', json.body.data);
            }
          },(err)=>{
            console.log(err);
          });
        }catch(e){};
      }
    },
  };
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="stylus" rel="stylesheet/stylus">
  .hello {
    height: 100%;
  }
  .cursor {
    cursor: pointer;
  }
</style>
