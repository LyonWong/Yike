<template>
  <div class="l-comment" :class="{'big': (commentType == 2)}" v-if='isOwner || commentShow'>
    <div class="comment-title cursor" v-if="!isPC">
      <div class="pull-left" @click="hideComment">
        讨论区
        <i class="iconfont icon-chevron-down"></i>
      </div>
      <div class="pull-right" @click="updateCommentType(2)" v-if="commentType == 1">
        <i class="iconfont icon-taolunqu-da"></i>
      </div>
      <div class="pull-right" @click="updateCommentType(1)" v-if="commentType == 2">
        <i class="iconfont icon-taolunqu-xiao"></i>
      </div>
    </div>
    <div class="comment-title" v-if="isPC">
      讨论区
    </div>
    <div id="comment-body" class="comment-body">
      <div class="comment-content">
        <ul id="cScoller">
          <li>
            <div class="user-content">
              <div class="con-text" v-if="lessonInfo.step!='finish'">
                <a href="javascript:;" @click="pullHistory" v-if="canPullMsgs && !pulling"><i class="iconfont icon-click"></i>&nbsp;查看更多消息</a>
                <span class="no-msg" v-if="canPullMsgs && pulling">加载中...</span>
                <span class="no-msg" v-if="!canPullMsgs">-没有更多了-</span>
              </div>
              <div class="con-text" v-if="lessonInfo.step=='finish'">
                <span>--课程已结束--</span>
              </div>
            </div>
          </li>
          <li v-for="comment in commentMessageInfo" :class="{'is-admin': comment.isAdmin}">
            <div class="user-img">
              <img :src="`${storageHost}user/${comment.account}/avatar!avatar`" alt="">
              <!--<img v-if="userAvatar[comment.account]" :src="userAvatar[comment.account]" alt="">
              <img v-if="!userAvatar[comment.account]" :src="`${assetsHost}static/live/_static/live/img/comment_default.png`" alt="">-->
            </div>
            <div class="user-content">
              <div class="con-title" v-text="comment.nickname" :class="{'is-self':comment.isSelfSend}"></div>
              <span class="con-title admin" v-if="comment.isAdmin">讲师</span>
              <!--<div class="con-time">{{comment.time}} <a href="javascrip:;" v-if="isOwner">操作</a></div>-->
              <div class="con-text" v-for="com in comment.content">
                <span v-for="co in com.custom">
                  <span v-text="co.text"></span>
                  <span class="forbin-box" v-if="isOwner">
                    <span class="forbin" @click="fetchQuote(comment.account, co.text)">&nbsp;&nbsp;引用</span>
                    <span class="forbin" @click="fetchForbid(comment.account)">&nbsp;&nbsp;禁言</span>
                  </span>
                </span>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <div class="full-member" v-if="isPC && fullMember">
      <div>
        讨论区已满
      </div>
    </div>
    <s-chatbox class="comment-chat" v-if="isPC && isOwner"></s-chatbox>
  </div>
</template>

<script type="text/ecmascript-6">
  import { onBigGroupMsgNotify, jsonpCallback, onMsgNotify, pullHistoryGroupMsgs, exportInitData, forbidSendCommentMsg } from '@live/assets/js/webim_comment';
  import { onDestoryGroupNotify, onRevokeGroupNotify, onCustomGroupNotify, onGroupInfoChangeNotify, onKickedGroupNotify } from '@live/assets/js/webim_group_notice';
  import sChatbox from '@live/components/chatbox/sChat.vue';
  import { mapState } from 'vuex';
  import { setStore, getStore } from '@lib/js/mUtils';
  // spec
  var _prefix = process.env.NODE_ENV == 'production' ? process.env.LIVE_HOST.replace(/\/$/,'') : '/api';
  var userUrl = `${_prefix}/user-profile.api?usn=`;

  export default
  {
    name: 'v-comment',
    components: {
      sChatbox,
    },
    props: {
      inComment: {
        type: Boolean,
      },
    },
    data() {
      return {
        isPC,
        canPullMsgs: true,
        pulling: false,
        scoller: null,
        commentBody: null,
        firstLoad: true,
        storageHost: '',
      };
    },
    computed: {
      ...mapState([
        'commentShow',
        'isOwner',
        'userInfo',
        'commentMessageInfo',
        'userAvatar',
        'lessonInfo',
        'boxMoreShow',
        'assetsHost',
        'commentType',
        'fullMember',
      ])
    },
    mounted () {
      // 初始化webim数据
      let initData = this.userInfo || {};
      this.init(initData);
      // 当前storage
      this.storageHost = this.assetsHost.replace(/\/assets\./i, '/storage.');
    },
    methods: {
      hideComment() {
        this.$store.commit('UPDATE_COMMETN_SHOW', false);
        // 更多菜单
        if(this.boxMoreShow){
          this.$store.commit('UPDATE_BOX_MORE', false);
        }
      },
      updateCommentType(type){
        this.$store.commit('UPDATE_COMMETN_TYPE', type);
      },
      pullHistory() {
        // 对象是否存在
        if(!this.commentBody || !this.scoller){
          this.commentBody = document.getElementById('comment-body');
          this.scoller = document.getElementById('cScoller');
        }
        var opt = {
          msgSeq: this.commentMessageInfo.length,
          reqMsgCount: 10
        };
        // 开始拉取
        this.pulling = true;
        // loading开始
        if(this.firstLoad) {
          this.firstLoad = false;
        }else {
          this.$store.commit('UPDATE_LOADING', true);
        }
        // 回调
        pullHistoryGroupMsgs(opt, (data) => {
          if(!data.length){
            // loading结束
            this.$store.commit('UPDATE_LOADING', false);
            return (this.canPullMsgs = false);
          }
          if(data.length < opt.reqMsgCount){
            this.canPullMsgs = false
          }
          // 策略调整， 用户头像用字符串拼接方式
          // 更新列表
          let length = data.length - 1;
          this.$store.commit('UPDATE_HISTORY_COMMENT_MESSAGE', data.reverse());
          this.$nextTick(()=>{
            // 结束拉取
            this.pulling = false;
            try{
              this.resetCurScroller(length);
            }catch(e){};
            // loading结束
            this.$store.commit('UPDATE_LOADING', false);
          });
          // let i=0;
          // let length = data.length - 1;
          // 递归
          // this.recursion(i, length, data);
        }, (err) => {
          // loading结束
          this.$store.commit('UPDATE_LOADING', false);
          console.log(err);
        });
      },
      fetchForbid(account) {
        // 禁言该用户
        swal({
          title: '',
          text: '确定要禁言此用户并删除其发言记录吗？',
          confirmButtonText: '确定',
          showCancelButton:true,
          closeOnConfirm: false,
          cancelButtonText: '取消',
        }, ()=>{
          // 开始禁言
          this.$store.dispatch('fetchLiveForbid', {lesson_sn:this.lessonInfo.sn,usn:account}).then((data) => {
            console.log('禁言成功');
            swal({
             title: '',
             text: '禁言成功',
             confirmButtonText: "知道了"
            });
          }, (err) => {
            swal({
              title: '错误提醒',
              text: err.message || '网络链接失败',
              confirmButtonText: "知道了"
            });
          });
          // 开始禁言
          /*forbidSendCommentMsg(this.userInfo.discuss ,[account], (data) => {
            swal({
              title: '',
              text: '禁言成功',
              confirmButtonText: "知道了"
            });
          }, (err) => {
            if(err.ErrorCode == 10004){
              return swal({
                title: '错误提醒',
                text: '成员不在授课区',
                confirmButtonText: "知道了"
              });
            };
            swal({
              title: '错误提醒',
              text: err.SrcErrorInfo,
              confirmButtonText: "知道了"
            });
          });*/
        /*end*/
        });
      },
      fetchQuote(usn, text) {
        // 引用该用户
        swal({
          title: '',
          text: '确定要引用此发言吗？',
          confirmButtonText: '确定',
          showCancelButton:true,
          closeOnConfirm: false,
          cancelButtonText: '取消',
        }, ()=>{
          let query = {
            lesson_sn: this.lessonInfo.sn,
            usn,
            text,
          };
          // 关闭弹窗
          swal.close();
          // 开始引用
          this.$store.dispatch('fetchQuote', query).then((data) => {
            console.log('引用成功');
            /*swal({
              title: '',
              text: '引用成功',
              confirmButtonText: "知道了"
            });*/
          }, (err) => {
            swal({
              title: '错误提醒',
              text: err.message || '网络链接失败',
              confirmButtonText: "知道了"
            });
          });
          /*end*/
        });
      },
      init(data) {
        // 初始化数据
        let initData = {};
        //官方 demo appid,需要开发者自己修改（托管模式）
        initData.sdkAppID = data.sdkAppID || (process.env.SDK_APPID?process.env.SDK_APPID:1400026682); // live
        //initData.sdkAppID = data.sdkAppID || 1400026682; // sandbox
        initData.accountType = data.accountType || 12098;
        initData.avChatRoomId = data.discuss || '58f45e003d331'; //默认房间群ID//
        //initData.avChatRoomId = '58f45e003d331'; //默认房间群ID//
        initData.selType = webim.SESSION_TYPE.GROUP;
        initData.selSess = null;//当前聊天会话
        initData.selToID = initData.avChatRoomId;//当前选中聊天id（当聊天类型为私聊时，该值为好友帐号，否则为群号）
        //默认群组头像(选填)
        initData.selSessHeadUrl = 'img/2017.jpg';
        // 是否是owner
        initData.isOwner = data.isOwner == 'yes'?true:false;
        // 是否有老师id
        try{
          initData.admingID = this.lessonInfo.teacher.sn;
        }catch(e){};
        //当前用户身份
        initData.loginInfo = {
          'sdkAppID': initData.sdkAppID, //用户所属应用id,必填
          'appIDAt3rd': initData.sdkAppID, //用户所属应用id，必填
          'accountType': initData.accountType, //用户所属应用帐号类型，必填
          'identifier': data.sn, //当前用户ID,必须是否字符串类型，选填
          'identifierNick': data.name, //当前用户昵称，选填
          'userSig': data.userSig, //当前用户身份凭证，必须是字符串类型，选填
          'headurl': data.avatar || 'img/2017.jpg'//当前用户默认头像，选填
        };

        //监听（多终端同步）群系统消息方法，方法都定义在demo_group_notice.js文件中
        let onGroupSystemNotifys = {
          //"1": onApplyJoinGroupRequestNotify, //申请加群请求（只有管理员会收到,暂不支持）
          //"2": onApplyJoinGroupAcceptNotify, //申请加群被同意（只有申请人能够收到,暂不支持）
          //"3": onApplyJoinGroupRefuseNotify, //申请加群被拒绝（只有申请人能够收到,暂不支持）
          //"4": onKickedGroupNotify, //被管理员踢出群(只有被踢者接收到,暂不支持)
          "5": onDestoryGroupNotify, //群被解散(全员接收)
          //"6": onCreateGroupNotify, //创建群(创建者接收,暂不支持)
          //"7": onInvitedJoinGroupNotify, //邀请加群(被邀请者接收,暂不支持)
          //"8": onQuitGroupNotify, //主动退群(主动退出者接收,暂不支持)
          //"9": onSetedGroupAdminNotify, //设置管理员(被设置者接收,暂不支持)
          //"10": onCanceledGroupAdminNotify, //取消管理员(被取消者接收,暂不支持)
          "11": onRevokeGroupNotify, //群已被回收(全员接收)
          "255": onCustomGroupNotify.prototype.constructor.bind(this)//用户自定义通知(默认全员接收)
        };

        //监听连接状态回调变化事件
        let onConnNotify = function (resp) {
          switch (resp.ErrorCode) {
            case webim.CONNECTION_STATUS.ON:
              //webim.Log.warn('连接状态正常...');
              break;
            case webim.CONNECTION_STATUS.OFF:
              webim.Log.warn('连接已断开，无法收到新消息，请检查下你的网络是否正常');
              break;
            default:
              webim.Log.error('未知连接状态,status=' + resp.ErrorCode);
              break;
          }
        };
        //监听事件
        initData.listeners = {
          "onConnNotify": onConnNotify.prototype.constructor.bind(this), //选填
          //"jsonpCallback": jsonpCallback.prototype.constructor.bind(this), //IE9(含)以下浏览器用到的jsonp回调函数,移动端可不填，pc端必填
          "onBigGroupMsgNotify": onBigGroupMsgNotify.prototype.constructor.bind(this), //监听新消息(大群)事件，必填
          "onMsgNotify": onMsgNotify.prototype.constructor.bind(this),//监听新消息(私聊(包括普通消息和全员推送消息)，普通群(非直播聊天室)消息)事件，必填
          "onGroupSystemNotifys": onGroupSystemNotifys, //监听（多终端同步）群系统消息事件，必填
          //"onGroupInfoChangeNotify": onGroupInfoChangeNotify.prototype.constructor.bind(this),//监听群资料变化事件，选填
          "onKickedEventCall": onKickedGroupNotify.prototype.constructor.bind(this)
        };

        //其他对象，选填
        initData.options = {
          'isAccessFormalEnv': true,//是否访问正式环境，默认访问正式，选填
          'isLogOn': false//是否开启控制台打印日志,默认开启，选填
        };

        initData.curPlayAudio = null;//当前正在播放的audio对象
        initData.openEmotionFlag = false;//是否打开过表情

        // 初始化webim数据
        exportInitData(initData);
        let checkThis = this;
        // 初始化历史消息
        (function checkEnterComment() {
          if(checkThis.inComment){
            if(checkThis.fullMember){
              checkThis.$store.commit('UPDATE_LOADING', false);
              return console.log('fullMember:', checkThis.fullMember);
            }
            return checkThis.pullHistory();
          }
          //
          setTimeout(() => {
            checkEnterComment();
          }, 500);
        })();
      },
      updateAvatar(account, avatar){
        //
        let opt = {};
        opt[account] = avatar;
        this.$store.commit('UPDATE_USER_AVATAR', opt);
      },
      recursion(i, length, msgList) {
        if(i > length){
          this.pulling = false;
          this.$nextTick(()=>{
            try{
              this.resetCurScroller(length);
            }catch(e){};
            // loading结束
            this.$store.commit('UPDATE_LOADING', false);
          });
          return;
        }
        //
        let msg = msgList[i];
        //
        try{
          let avatar = this.userAvatar[msg.account];
          // 是否有头像
          if(avatar){
            msg.avatar = avatar;
            // 更新列表
            this.$store.commit('UPDATE_HISTORY_COMMENT_MESSAGE', msg);
            // 继续递归
            this.recursion(++i, length, msgList);
          }else{
            throw new Error('there is no avatar');
          }
        }catch(e){
          // 是否有做缓存
          let jsonData = getStore(`${this.lessonInfo.sn}-avatar`);
          if(jsonData){
            jsonData = JSON.parse(jsonData);
            if(jsonData[msg.account]){
              msg.avatar = jsonData[msg.account];
              this.$store.commit('UPDATE_HISTORY_COMMENT_MESSAGE', msg);
              // 更新头像存储
              this.updateAvatar(msg.account, msg.avatar);
              // 继续递归
              return this.recursion(++i, length, msgList);
            }
          }
          // 获取用户信息
          let url = `${userUrl}${msg.account}`;
          this.$http.get(url).then((json)=>{
            if(json.ok){
              //
              let data = json.body.data;
              msg.avatar = data.avatar;
              this.$store.commit('UPDATE_HISTORY_COMMENT_MESSAGE', msg);
              let opt = {};
              opt[msg.account] = data.avatar;
              // 存入缓存
              if(jsonData){
                setStore(`${this.lessonInfo.sn}-avatar`, { ...jsonData, ...opt });
              }else{
                setStore(`${this.lessonInfo.sn}-avatar`, { ...opt });
              }
              // 更新头像存储
              this.updateAvatar(msg.account, data.avatar);
              // 继续递归
              this.recursion(++i, length, msgList);
            }
          },(err)=>{});
        }
      },
      resetCurScroller(length) {
        let arr = this.scoller.querySelectorAll('li');
        let diff = arr.length - 11;
        let gap = this.isPC?8:21;
        if(diff > 0){
          let sum = 0;
          for(let i=0;i<length+1;i++){
            sum += arr[i].offsetHeight+gap;
          };
          this.commentBody.scrollTop = sum;
        }
      },
    }
  };

</script>
<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";
</style>
