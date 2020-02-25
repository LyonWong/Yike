import { trimStr } from '@lib/js/mUtils';
import swal from 'sweetalert';
import moment from 'moment';

var _webim = { ...webim };

var sdkAppID = null,
  accountType = null,
  avChatRoomId = null,//默认房间群ID，群类型必须是直播聊天室（AVChatRoom），这个为官方测试ID(托管模式)
  selType = null,
  selSess = null,//当前聊天会话
  selToID = null,
  selSessHeadUrl = '',
  loginInfo = null,
  listeners = null,
  options   = null,
  curPlayAudio = null,
  openEmotionFlag = false,
  vScroll = null,
  cScroll = null,
  vSms = null,
  admingID = '',
  isOwner = false;

//初始化数据
export const exportInitData = (data) => {
  sdkAppID       = data.sdkAppID || sdkAppID;
  accountType    = data.accountType || accountType;
  avChatRoomId   = data.avChatRoomId || avChatRoomId;
  selType        = data.selType || selType;
  selSess        = data.selSess || selSess;
  selToID        = data.selToID || selToID;
  selSessHeadUrl = data.selSessHeadUrl || selSessHeadUrl;
  loginInfo      = data.loginInfo || loginInfo;
  listeners      = data.listeners || listeners;
  options        = data.options || options;
  curPlayAudio   = data.curPlayAudio || curPlayAudio;
  openEmotionFlag= data.openEmotionFlag || openEmotionFlag;
  admingID       = data.admingID || '';
  isOwner        = data.isOwner || false;
};

//IE9(含)以下浏览器用到的jsonp回调函数
export const jsonpCallback = (rspData) => {
  //设置接口返回的数据
  _webim.setJsonpLastRspData(rspData);
};

//监听大群新消息（普通，点赞，提示，红包）
export const onBigGroupMsgNotify = function(msgList) {
  for (let i = msgList.length - 1; i >= 0; i--) {//遍历消息，按照时间从后往前
    let msg = msgList[i];
    _webim.Log.warn('receive a new avchatroom group msg: ' + msg.getFromAccountNick());
    // 组装消息
    this.$store.commit('UPDATE_COMMENT_MESSAGE', assembleMsg(msg));
  }
};

//监听新消息(私聊(包括普通消息、全员推送消息)，普通群(非直播聊天室)消息)事件
//newMsgList 为新消息数组，结构为[Msg]
export const onMsgNotify = function(newMsgList) {
  //
  for (let i = newMsgList.length - 1; i >= 0; i--) {//遍历消息，按照时间从后往前
    let msg = newMsgList[i];
    let accountNick = msg.getFromAccountNick();
    let _prefix = process.env.NODE_ENV == 'production' ? process.env.LIVE_HOST.replace(/\/$/,'') : '/api';
    let userUrl = `${_prefix}/user-profile.api?usn=`;
    // 组装消息
    // 是不是当前群的消息
    try{
      //
      if(avChatRoomId){
        let curId = avChatRoomId.split('-')[0];
        let roomId = msg.sess._impl.id.split('-')[0];
        if(curId != roomId)return;
      }
    }catch(e){
      return;
    }
    // live body area
    if(!vScroll){
      vScroll = document.getElementById('live-body');
    }
    // comment area
    if(!cScroll){
      cScroll = document.getElementById('comment-body');
    }
    // 是当前群消息 开始操作
    msg = assembleMsg(msg);
    // start
    try{
      // 合成消息
      if(msg.content[0].isComment){
        // 讨论区
        _webim.Log.warn('receive a new comment chatroom group msg: ' + accountNick);
        // 策略调整， 用户头像用字符串拼接方式
        try{
          // 更新消息
          this.$store.commit('UPDATE_COMMENT_MESSAGE', msg);
          //
          this.$nextTick(()=>{
            inspectCommentScroll.call(this);
          });
        }catch(e){};

        /*try{
          let avatar = this.$store.state.userAvatar[msg.account];
          if(avatar){
            msg.avatar = avatar;
            //
            this.$store.commit('UPDATE_COMMENT_MESSAGE', msg);
            //
            this.$nextTick(()=>{
              inspectCommentScroll.call(this);
            });
          }else{
            //
            throw new Error('there is no avatar');
          }
        }catch(e){
          // 开始发送消息
          this.$store.commit('UPDATE_USER_AVATAR', {[msg.account]:''});
          this.$store.commit('UPDATE_COMMENT_MESSAGE', msg);
          //
          this.$nextTick(()=>{
            inspectCommentScroll.call(this);
          });
          // 获取用户信息
          setTimeout(()=>{
            this.$http.get(`${userUrl}${msg.account}`).then((json)=>{
              if(json.ok){
                let data = json.body.data;
                this.$store.commit('UPDATE_USER_AVATAR', {[msg.account]:data.avatar});
              }
            },(err)=>{});
          }, 100);
        }*/

      }else{
        if(!isOwner){
          return console.log('convert to other route!');
        }
        // 老师区
        _webim.Log.warn('receive a new chatroom group msg: ' + accountNick);
        // 是否有头像
        let avatar = this.$store.state.userAvatar ? this.$store.state.userAvatar[msg.account] : null;
        if(avatar){
          msg.avatar = avatar;
          //
          console.log('cuz', msg)
          this.$store.commit('UPDATE_MESSAGE', msg);
          //
          this.$nextTick(()=>{
            inspectScroll();
          });
        }else{
          // 消息推送
          console.log('duk', msg)
          this.$store.commit('UPDATE_MESSAGE', msg);
          //
          this.$nextTick(()=>{
            inspectScroll();
          });
          //
          throw new Error('there is no avatar');
        }
      }
    }catch(e){
      // 老师区
      // 开始发送消息
      this.$store.commit('UPDATE_USER_AVATAR', {[msg.account]:''});
      // this.$store.commit('UPDATE_MESSAGE', msg);
      //
      this.$nextTick(()=>{
        inspectScroll();
      });
      // 获取用户信息
      setTimeout(()=>{
        this.$http.get(`${userUrl}${msg.account}`).then((json)=>{
          if(json.ok){
            let data = json.body.data;
            this.$store.commit('UPDATE_USER_AVATAR', {[msg.account]:data.avatar});
          }
        },(err)=>{});
      }, 100);
    }
  }
};

// @发送消息
export const vSendMsg = (str, callback) => {
  let value = trimStr(str);
  // 调用_webim base接口
  if (value) {
    sendMsgCallBack(value, function (err, msg) {
      //
      callback(err, msg);
    })
  }
};

// 导出 sdk 初始化
export const exportCommentInit = (cb) => {
  sdkInit(cb);//初始化
};

// 导出封装消息方法
export const exportAssembleMsg = (msg) => {
  let assemble = {
    MSG_ELEMENT_TYPE: {
      'TEXT': 'TIMTextElem',//文本
      'FACE': 'TIMFaceElem',//表情
      'IMAGE': 'TIMImageElem',//图片
      'CUSTOM': 'TIMCustomElem',//自定义
      'SOUND': 'TIMSoundElem',//语音,只支持显示
      'FILE': 'TIMFileElem',//文件,只支持显示
      'LOCATION': 'TIMLocationElem',//地理位置
      'GROUP_TIP': 'TIMGroupTipElem'//群提示消息,只支持显示
    },
    cursor: '',
    nickname: '',
    account: '',
    time: '',
    content: []
  };
  // assign
  assemble.account = msg.from_account || '';
  assemble.nickname = msg.accountNick || '未知用户';
  assemble.time = msg.tms;
  assemble.cursor = msg.cursor || '';
  //解析消息
  if (msg.content[0].MsgType === 'SYSTEM') {
    assemble.isSystem = true;
    assemble.content = msg.content;
  } else {
    assemble.content = convertHistoryMsg(msg);
    if (assemble.content[0] && assemble.content[0].bookmark) {
      assemble.isSystem = true
      assemble.bookmark = assemble.content[0].bookmark
      assemble.bookmark.cursor = assemble.cursor
    }
    if (assemble.content[0] && assemble.content[0].admire) {
      assemble.isSystem = true
      assemble.admire = assemble.content[0].admire
    }
  }

  return assemble;
};

// 导出滚动操作
export const exportInspectScroll = () => {
  inspectScroll();
};

// 获取最新的群历史消息,用于切换群组聊天时，重新拉取群组的聊天消息
export const pullHistoryGroupMsgs = (opt, cbOk, cbErr) => {
  //
  getGroupInfo(selToID, function (resp) {
    //拉取最新的群历史消息
    var options = {
      'GroupId': selToID,
      'ReqMsgSeq': opt.single?opt.msgSeq:(resp.GroupInfo[0].NextMsgSeq - opt.msgSeq - 1),
      'ReqMsgNumber': opt.reqMsgCount
    };
    if (options.ReqMsgSeq == null || options.ReqMsgSeq == undefined || options.ReqMsgSeq <= 0) {
      return cbOk([]);
    }
    // selSess
    selSess = null;
    _webim.MsgStore.delSessByTypeId(selType, selToID);
    //
    _webim.syncGroupMsgs(
      options,
      function (msgList) {
        if (msgList.length == 0) {
          _webim.Log.warn("该群没有历史消息了:options=" + JSON.stringify(options));
          //return;
        }
        // 重组备份
        let tempList = [];
        // 循环
        for (let i = msgList.length - 1; i >= 0; i--) {//遍历消息，按照时间从后往前
          let msg = msgList[i];
          tempList.push(assembleMsg(msg));
        }

        // 成功回调
        if (cbOk)
          cbOk(tempList);
      },
      function (err) {
        cbErr(err);
      }
    );
  });
};

// 禁言接口设置
export const forbidSendCommentMsg = (group_id, accounts, cbOk, cbErr) => {
  webim.forbidSendMsg({
    'GroupId' : group_id,
    'Members_Account': accounts,
    'ShutUpTime': 10800,
  }, cbOk, cbErr);
};

// 读取群组基本资料-高级接口
function getGroupInfo (group_id, cbOK, cbErr) {
  var options = {
    'GroupIdList': [
      group_id
    ],
    'GroupBaseInfoFilter': [
      'Type',
      'Name',
      'Introduction',
      'Notification',
      'FaceUrl',
      'CreateTime',
      'Owner_Account',
      'LastInfoTime',
      'LastMsgTime',
      'NextMsgSeq',
      'MemberNum',
      'MaxMemberNum',
      'ApplyJoinOption'
    ],
    /*'MemberInfoFilter': [
      'Account',
      'Role',
      'JoinTime',
      'LastSendMsgTime',
      'ShutUpUntil'
    ]*/
  };
  _webim.getGroupInfo(
    options,
    function (resp) {
      if (cbOK) {
        cbOK(resp);
      }
    },
    function (err) {
      swal({
        title: '错误提醒',
        text: err.ErrorInfo,
        confirmButtonText: "知道了"
      });
    }
  );
};

// 发送并接收消息
function sendMsgCallBack (msgtosend, callback) {
  // 未登录
  if (!loginInfo.identifier) {
    return swal({
      title: '错误提醒',
      text: '请填写帐号和票据',
      confirmButtonText: "知道了"
    }, ()=>{
      callback('请先登录!');
    });
  }

  // 是否成功进入房间
  if (!selToID) {
    return callback('您还没有进入房间，暂不能聊天!');
  }
  //获取消息内容
  var msgtosend = msgtosend;
  var msgLen = _webim.Tool.getStrBytes(msgtosend);

  if (msgtosend.length < 1) {
    return callback('发送的消息不能为空!');
  }

  var maxLen, errInfo;
  if (selType == _webim.SESSION_TYPE.GROUP) {
    maxLen = _webim.MSG_MAX_LENGTH.GROUP;
    errInfo = "消息长度超出限制(最多" + Math.round(maxLen / 3) + "汉字)";
  } else {
    maxLen = _webim.MSG_MAX_LENGTH.C2C;
    errInfo = "消息长度超出限制(最多" + Math.round(maxLen / 3) + "汉字)";
  }
  if (msgLen > maxLen) {
    return callback(errInfo);
  }

  if (!selSess) {
    selSess = new _webim.Session(selType, selToID, selToID, loginInfo.headurl, Math.round(new Date().getTime() / 1000));
  }
  var isSend = true;//是否为自己发送
  var seq = -1;//消息序列，-1表示sdk自动生成，用于去重
  var random = Math.round(Math.random() * 4294967296);//消息随机数，用于去重
  var msgTime = Math.round(new Date().getTime() / 1000);//消息时间戳
  var subType;//消息子类型
  if (selType == _webim.SESSION_TYPE.GROUP) {
    //群消息子类型如下：
    //_webim.GROUP_MSG_SUB_TYPE.COMMON-普通消息,
    //_webim.GROUP_MSG_SUB_TYPE.LOVEMSG-点赞消息，优先级最低
    //_webim.GROUP_MSG_SUB_TYPE.TIP-提示消息(不支持发送，用于区分群消息子类型)，
    //_webim.GROUP_MSG_SUB_TYPE.REDPACKET-红包消息，优先级最高
    subType = _webim.GROUP_MSG_SUB_TYPE.COMMON;
  } else {
    //C2C消息子类型如下：
    //_webim.C2C_MSG_SUB_TYPE.COMMON-普通消息,
    subType = _webim.C2C_MSG_SUB_TYPE.COMMON;
  }

  var msg = new _webim.Msg(selSess, isSend, seq, random, msgTime, loginInfo.identifier, subType, loginInfo.identifierNick);

  // add file
  msg.addCustom({data: msgtosend, desc: 'COMMENT'});

  _webim.sendMsg(msg, function (resp) {
    if (selType == _webim.SESSION_TYPE.C2C) {//私聊时，在聊天窗口手动添加一条发的消息，群聊时，长轮询接口会返回自己发的消息
      // showMsg(msg);
    }
    _webim.Log.info('发消息成功');
    callback(null, assembleMsg(msg));
  }, function (err) {
    _webim.Log.error('发消息失败:' + err.ErrorInfo);
    callback(err);
  });
};

// 封装消息
function assembleMsg(msg) {
  let assemble = {
    MSG_ELEMENT_TYPE: {
      'TEXT': 'TIMTextElem',//文本
      'FACE': 'TIMFaceElem',//表情
      'IMAGE': 'TIMImageElem',//图片
      'CUSTOM': 'TIMCustomElem',//自定义
      'SOUND': 'TIMSoundElem',//语音,只支持显示
      'FILE': 'TIMFileElem',//文件,只支持显示
      'LOCATION': 'TIMLocationElem',//地理位置
      'GROUP_TIP': 'TIMGroupTipElem'//群提示消息,只支持显示
    },
    nickname: '',
    account: '',
    time: '',
    isAdmin: false,
    content: []
  };
  // assign
  assemble.account = msg.getFromAccount() || '';
  //
  try{
    // assemble.time = new Date(msg.time * 1000).toLocaleString()
    assemble.time = moment(msg.time * 1000).format('YYYY-MM-DD HH:mm:ss');
    // 是否是老师或管理员
    assemble.isAdmin = assemble.account && (assemble.account == admingID);
  }catch(e){}
  // 昵称
  assemble.nickname =  msg.getFromAccountNick() || '未知用户';

  //解析消息
  //获取会话类型，目前只支持群聊
  //webim.SESSION_TYPE.GROUP-群聊，
  //webim.SESSION_TYPE.C2C-私聊，
  assemble.sessType = msg.getSession().type();
  //会话类型为群聊时，子类型为：webim.GROUP_MSG_SUB_TYPE
  //会话类型为私聊时，子类型为：webim.C2C_MSG_SUB_TYPE
  assemble.subType = msg.getSubType();
  assemble.isSelfSend = msg.getIsSend();//消息是否为自己发的

  // 封装消息
  switch (assemble.subType) {
    //群普通消息
    case webim.GROUP_MSG_SUB_TYPE.COMMON:
      assemble.content = convertMsg(msg);
      break;
    //群红包消息
    case webim.GROUP_MSG_SUB_TYPE.REDPACKET:
      assemble.content = convertMsg(msg);
      //assemble.content = `[群红包消息] ${convertMsg(msg)}`;
      break;
    //群点赞消息
    case webim.GROUP_MSG_SUB_TYPE.LOVEMSG:
      //业务自己可以增加逻辑，比如展示点赞动画效果
      assemble.content = convertMsg(msg);
      //assemble.content = `[群点赞消息] ${convertMsg(msg)}`;
      //展示点赞动画
      //showLoveMsgAnimation();
      break;
    //群提示消息
    case webim.GROUP_MSG_SUB_TYPE.TIP:
      assemble.content = convertMsg(msg);
      //assemble.content = `[群提示消息] ${convertMsg(msg)}`;
      break;
  }

  return assemble;
};

// 获取消息内容
function convertMsg(msg) {
  var contents = [], elems, elem, type, content;
  elems = msg.getElems();//获取消息包含的元素数组

  for (let i in elems) {
    elem = elems[i];
    type = elem.getType();//获取元素类型
    contents[i] = {
      type: type,
      text: '',
      audioSrc: '',
      imgArr: [],
      fileArr: [],
      custom: [],
      isComment: false,
    };
    //
    content = elem.getContent();//获取元素对象
    try{
      contents[i].isComment = msg.sess._impl.id.split('-')[1]=='D'?true:false;
    }catch(e){
      contents[i].isComment = false;
    }
    //type
    switch (type) {
      case webim.MSG_ELEMENT_TYPE.TEXT:
        contents[i].text = convertTextMsg(content);
        break;
      case webim.MSG_ELEMENT_TYPE.FACE:
        contents[i].text = convertFaceMsg(content);
        break;
      case webim.MSG_ELEMENT_TYPE.IMAGE:
        contents[i].imgArr = contents[i].imgArr.concat(convertImageMsg(content));
        break;
      case webim.MSG_ELEMENT_TYPE.SOUND:
        contents[i].audioSrc = convertSoundMsg(content);
        break;
      case webim.MSG_ELEMENT_TYPE.FILE:
        contents[i].fileArr = contents[i].imgArr.concat(convertFileMsg(content));
        break;
      case webim.MSG_ELEMENT_TYPE.LOCATION://暂不支持地理位置
        //html += convertLocationMsgToHtml(content);
        break;
      case webim.MSG_ELEMENT_TYPE.CUSTOM:
        contents[i].custom = convertCustomMsg(content);
        break;
      case webim.MSG_ELEMENT_TYPE.GROUP_TIP:
        contents[i].text = convertGroupTipMsg(content);
        break;
      default:
        webim.Log.error('未知消息元素类型: elemType=' + type);
        break;
    }
  }
  // 返回
  return contents;
};

// 获取历史消息内容
function convertHistoryMsg(msg) {
  var contents = [], elems, elem, type, content;
  elems = msg.content;//获取消息包含的元素数组
  //
  for (let i in elems) {
    elem = elems[i];
    type = elem.MsgType;//获取元素类型
    contents[i] = {
      type: type,
      text: '',
      audioSrc: '',
      imgArr: [],
      fileArr: [],
      custom: [],
      isComment: false,
      history: msg.history || false,
    };
    //
    content = elem.MsgContent;//获取元素对象
    //
    try{
      contents[i].isComment = msg.sess._impl.id.split('-')[1]=='D'?true:false;
    }catch(e){
      contents[i].isComment = false;
    }
    // type
    switch (type) {
      case webim.MSG_ELEMENT_TYPE.TEXT:
        contents[i].text = convertHistoryTextMsg(content);
        break;
      case webim.MSG_ELEMENT_TYPE.FACE:
        contents[i].text = convertFaceMsg(content);
        break;
      case webim.MSG_ELEMENT_TYPE.IMAGE:
        contents[i].imgArr = contents[i].imgArr.concat(convertHistoryImageMsg(content));
        break;
      case webim.MSG_ELEMENT_TYPE.SOUND:
        contents[i].audioSrc = convertHistorySoundMsg(content);
        break;
      case webim.MSG_ELEMENT_TYPE.FILE:
        contents[i].fileArr = contents[i].imgArr.concat(convertHistoryFileMsg(content));
        break;
      case webim.MSG_ELEMENT_TYPE.LOCATION://暂不支持地理位置
        //html += convertLocationMsgToHtml(content);
        break;
      case webim.MSG_ELEMENT_TYPE.CUSTOM:
         let ctt = convertCustomMsg(content);
         contents[i].custom = ctt;
         if (ctt[0].type == 'MARK') {
           contents[i].bookmark = ctt[0]
         }
         if (ctt[0].type == 'ADMIRE') {
           contents[i].admire = ctt[0]
         }
        break;
      case webim.MSG_ELEMENT_TYPE.GROUP_TIP:
        contents[i].text = convertHistoryGroupTipMsg(content);
        break;
      default:
        webim.Log.error('未知消息元素类型: elemType=' + type);
        break;
    }
  }
  // 返回
  return contents;
};

//解析表情消息元素
function convertFaceMsg(content) {
  var faceUrl = null;
  var data = content.getData();
  var index = webim.EmotionDataIndexs[data];

  var emotion = webim.Emotions[index];
  if (emotion && emotion[1]) {
    faceUrl = emotion[1];
  }
  if (faceUrl) {
    return faceUrl;
  } else {
    return data;
  }
};

//解析群提示消息元素
function convertGroupTipMsg(content) {
  var WEB_IM_GROUP_TIP_MAX_USER_COUNT = 10;
  var text = "";
  var maxIndex = WEB_IM_GROUP_TIP_MAX_USER_COUNT - 1;
  var opType, opUserId, userIdList;
  var memberCount;
  opType = content.getOpType();//群提示消息类型（操作类型）
  opUserId = content.getOpUserId();//操作人id
  switch (opType) {
    case webim.GROUP_TIP_TYPE.JOIN://加入群
      userIdList = content.getUserIdList();
      //text += opUserId + "邀请了";
      for (let m in userIdList) {
        text += userIdList[m] + ",";
        if (userIdList.length > WEB_IM_GROUP_TIP_MAX_USER_COUNT && m == maxIndex) {
          text += "等" + userIdList.length + "人";
          break;
        }
      }
      text = text.substring(0, text.length - 1);
      text += "进入房间";
      //房间成员数加1
      // memberCount = $('#user-icon-fans').html();
      // $('#user-icon-fans').html(parseInt(memberCount) + 1);
      break;
    case webim.GROUP_TIP_TYPE.QUIT://退出群
      text += opUserId + "离开房间";
      //房间成员数减1
      // memberCount = parseInt($('#user-icon-fans').html());
      // if (memberCount > 0) {
      //   $('#user-icon-fans').html(memberCount - 1);
      // }
      break;
    case webim.GROUP_TIP_TYPE.KICK://踢出群
      text += opUserId + "将";
      userIdList = content.getUserIdList();
      for (let m in userIdList) {
        text += userIdList[m] + ",";
        if (userIdList.length > WEB_IM_GROUP_TIP_MAX_USER_COUNT && m == maxIndex) {
          text += "等" + userIdList.length + "人";
          break;
        }
      }
      text += "踢出该群";
      break;
    case webim.GROUP_TIP_TYPE.SET_ADMIN://设置管理员
      text += opUserId + "将";
      userIdList = content.getUserIdList();
      for (let m in userIdList) {
        text += userIdList[m] + ",";
        if (userIdList.length > WEB_IM_GROUP_TIP_MAX_USER_COUNT && m == maxIndex) {
          text += "等" + userIdList.length + "人";
          break;
        }
      }
      text += "设为管理员";
      break;
    case webim.GROUP_TIP_TYPE.CANCEL_ADMIN://取消管理员
      text += opUserId + "取消";
      userIdList = content.getUserIdList();
      for (let m in userIdList) {
        text += userIdList[m] + ",";
        if (userIdList.length > WEB_IM_GROUP_TIP_MAX_USER_COUNT && m == maxIndex) {
          text += "等" + userIdList.length + "人";
          break;
        }
      }
      text += "的管理员资格";
      break;

    case webim.GROUP_TIP_TYPE.MODIFY_GROUP_INFO://群资料变更
      text += opUserId + "修改了群资料：";
      var groupInfoList = content.getGroupInfoList();
      var type, value;
      for (let m in groupInfoList) {
        type = groupInfoList[m].getType();
        value = groupInfoList[m].getValue();
        switch (type) {
          case webim.GROUP_TIP_MODIFY_GROUP_INFO_TYPE.FACE_URL:
            text += "群头像为" + value + "; ";
            break;
          case webim.GROUP_TIP_MODIFY_GROUP_INFO_TYPE.NAME:
            text += "群名称为" + value + "; ";
            break;
          case webim.GROUP_TIP_MODIFY_GROUP_INFO_TYPE.OWNER:
            text += "群主为" + value + "; ";
            break;
          case webim.GROUP_TIP_MODIFY_GROUP_INFO_TYPE.NOTIFICATION:
            text += "群公告为" + value + "; ";
            break;
          case webim.GROUP_TIP_MODIFY_GROUP_INFO_TYPE.INTRODUCTION:
            text += "群简介为" + value + "; ";
            break;
          default:
            text += "未知信息为:type=" + type + ",value=" + value + "; ";
            break;
        }
      }
      break;

    case webim.GROUP_TIP_TYPE.MODIFY_MEMBER_INFO://群成员资料变更(禁言时间)
      text += opUserId + "修改了群成员资料:";
      var memberInfoList = content.getMemberInfoList();
      var userId, shutupTime;
      for (let m in memberInfoList) {
        userId = memberInfoList[m].getUserId();
        shutupTime = memberInfoList[m].getShutupTime();
        text += userId + ": ";
        if (shutupTime != null && shutupTime !== undefined) {
          if (shutupTime == 0) {
            text += "取消禁言; ";
          } else {
            text += "禁言" + shutupTime + "秒; ";
          }
        } else {
          text += " shutupTime为空";
        }
        if (memberInfoList.length > WEB_IM_GROUP_TIP_MAX_USER_COUNT && m == maxIndex) {
          text += "等" + memberInfoList.length + "人";
          break;
        }
      }
      break;
    default:
      text += "未知群提示消息类型：type=" + opType;
      break;
  }
  return text;
};

//解析群提示消息元素
function convertHistoryGroupTipMsg(content) {
  var WEB_IM_GROUP_TIP_MAX_USER_COUNT = 10;
  var text = "";
  var maxIndex = WEB_IM_GROUP_TIP_MAX_USER_COUNT - 1;
  var opType, opUserId, userIdList;
  var memberCount;
  opType = content.getOpType();//群提示消息类型（操作类型）
  opUserId = content.getOpUserId();//操作人id
  switch (opType) {
    case webim.GROUP_TIP_TYPE.JOIN://加入群
      userIdList = content.getUserIdList();
      //text += opUserId + "邀请了";
      for (let m in userIdList) {
        text += userIdList[m] + ",";
        if (userIdList.length > WEB_IM_GROUP_TIP_MAX_USER_COUNT && m == maxIndex) {
          text += "等" + userIdList.length + "人";
          break;
        }
      }
      text = text.substring(0, text.length - 1);
      text += "进入房间";
      //房间成员数加1
      // memberCount = $('#user-icon-fans').html();
      // $('#user-icon-fans').html(parseInt(memberCount) + 1);
      break;
    case webim.GROUP_TIP_TYPE.QUIT://退出群
      text += opUserId + "离开房间";
      //房间成员数减1
      // memberCount = parseInt($('#user-icon-fans').html());
      // if (memberCount > 0) {
      //   $('#user-icon-fans').html(memberCount - 1);
      // }
      break;
    case webim.GROUP_TIP_TYPE.KICK://踢出群
      text += opUserId + "将";
      userIdList = content.getUserIdList();
      for (let m in userIdList) {
        text += userIdList[m] + ",";
        if (userIdList.length > WEB_IM_GROUP_TIP_MAX_USER_COUNT && m == maxIndex) {
          text += "等" + userIdList.length + "人";
          break;
        }
      }
      text += "踢出该群";
      break;
    case webim.GROUP_TIP_TYPE.SET_ADMIN://设置管理员
      text += opUserId + "将";
      userIdList = content.getUserIdList();
      for (let m in userIdList) {
        text += userIdList[m] + ",";
        if (userIdList.length > WEB_IM_GROUP_TIP_MAX_USER_COUNT && m == maxIndex) {
          text += "等" + userIdList.length + "人";
          break;
        }
      }
      text += "设为管理员";
      break;
    case webim.GROUP_TIP_TYPE.CANCEL_ADMIN://取消管理员
      text += opUserId + "取消";
      userIdList = content.getUserIdList();
      for (let m in userIdList) {
        text += userIdList[m] + ",";
        if (userIdList.length > WEB_IM_GROUP_TIP_MAX_USER_COUNT && m == maxIndex) {
          text += "等" + userIdList.length + "人";
          break;
        }
      }
      text += "的管理员资格";
      break;

    case webim.GROUP_TIP_TYPE.MODIFY_GROUP_INFO://群资料变更
      text += opUserId + "修改了群资料：";
      var groupInfoList = content.getGroupInfoList();
      var type, value;
      for (let m in groupInfoList) {
        type = groupInfoList[m].getType();
        value = groupInfoList[m].getValue();
        switch (type) {
          case webim.GROUP_TIP_MODIFY_GROUP_INFO_TYPE.FACE_URL:
            text += "群头像为" + value + "; ";
            break;
          case webim.GROUP_TIP_MODIFY_GROUP_INFO_TYPE.NAME:
            text += "群名称为" + value + "; ";
            break;
          case webim.GROUP_TIP_MODIFY_GROUP_INFO_TYPE.OWNER:
            text += "群主为" + value + "; ";
            break;
          case webim.GROUP_TIP_MODIFY_GROUP_INFO_TYPE.NOTIFICATION:
            text += "群公告为" + value + "; ";
            break;
          case webim.GROUP_TIP_MODIFY_GROUP_INFO_TYPE.INTRODUCTION:
            text += "群简介为" + value + "; ";
            break;
          default:
            text += "未知信息为:type=" + type + ",value=" + value + "; ";
            break;
        }
      }
      break;

    case webim.GROUP_TIP_TYPE.MODIFY_MEMBER_INFO://群成员资料变更(禁言时间)
      text += opUserId + "修改了群成员资料:";
      var memberInfoList = content.getMemberInfoList();
      var userId, shutupTime;
      for (let m in memberInfoList) {
        userId = memberInfoList[m].getUserId();
        shutupTime = memberInfoList[m].getShutupTime();
        text += userId + ": ";
        if (shutupTime != null && shutupTime !== undefined) {
          if (shutupTime == 0) {
            text += "取消禁言; ";
          } else {
            text += "禁言" + shutupTime + "秒; ";
          }
        } else {
          text += " shutupTime为空";
        }
        if (memberInfoList.length > WEB_IM_GROUP_TIP_MAX_USER_COUNT && m == maxIndex) {
          text += "等" + memberInfoList.length + "人";
          break;
        }
      }
      break;
    default:
      text += "未知群提示消息类型：type=" + opType;
      break;
  }
  return text;
};

//解析文本消息元素
function convertTextMsg(content) {
  return content.getText();
};

//解析历史文本消息元素
function convertHistoryTextMsg(content) {
  return content.Text;
};

//解析图片消息元素
function convertImageMsg(content) {
  var bigImage, oriImage;
  var smallImage = content.getImage(webim.IMAGE_TYPE.SMALL);//小图
  bigImage = content.getImage(webim.IMAGE_TYPE.LARGE);//大图
  oriImage = content.getImage(webim.IMAGE_TYPE.ORIGIN);//原图
  if (!bigImage) {
    bigImage = smallImage;
  }
  if (!oriImage) {
    oriImage = smallImage;
  }
  return `${smallImage.getUrl()}#${bigImage.getUrl()}#${oriImage.getUrl()}`;
};

//解析图片消息元素
function convertHistoryImageMsg(content) {
  var bigImage, oriImage;
  var smallImage = content.ImageInfoArray[2];//小图
  bigImage = content.ImageInfoArray[1];//大图
  oriImage = content.ImageInfoArray[0];//原图
  if (!bigImage) {
    bigImage = smallImage;
  }
  if (!oriImage) {
    oriImage = smallImage;
  }
  return `${smallImage.URL}#${bigImage.URL}#${oriImage.URL}`;
};

//解析文件消息元素
function convertFileMsg(content) {
  var fileSize = Math.round(content.getSize() / 1024);

  return [{
    url: content.getDownUrl().replace(/#((?!&).)*/g, ''),
    name: content.getName(),
    size: fileSize,
  }];
}

//解析文件消息元素
function convertHistoryFileMsg(content) {
  var fileSize = Math.round(content.FileSize / 1024);
  //
  return [{
    url: content.getDownUrl().replace(/#((?!&).)*/g, ''),
    name: content.FileName,
    size: fileSize,
  }];
  // return '<a href="' + content.getDownUrl() + '" title="点击下载文件" ><i class="glyphicon glyphicon-file">&nbsp;' + content.getName() + '(' + fileSize + 'KB)</i></a>';
}

//解析语音消息元素
function convertSoundMsg(content) {
  var second = content.getSecond();//获取语音时长
  var downUrl = content.getDownUrl();
  if (webim.BROWSER_INFO.type == 'ie' && parseInt(webim.BROWSER_INFO.ver) <= 8) {
    return '[这是一条语音消息]demo暂不支持ie8(含)以下浏览器播放语音,语音URL:' + downUrl;
  }
  return '<audio src="' + downUrl + '" controls="controls" onplay="onChangePlayAudio(this)" preload="none"></audio>';
};

//解析语音消息元素
function convertHistorySoundMsg(content) {
  var second = content.getSecond();//获取语音时长
  var downUrl = content.getDownUrl();
  if (webim.BROWSER_INFO.type == 'ie' && parseInt(webim.BROWSER_INFO.ver) <= 8) {
    return '[这是一条语音消息]demo暂不支持ie8(含)以下浏览器播放语音,语音URL:' + downUrl;
  }
  return '<audio src="' + downUrl + '" controls="controls" onplay="onChangePlayAudio(this)" preload="none"></audio>';
};

//解析自定义消息元素
function convertCustomMsg(content) {
  content.desc = content.desc || content.Desc;
  content.data = content.data || content.Data;
  content.ext = content.Ext || content.ext;
  //
  switch (content.desc) {
    case 'SOUND':
      return [{
        id: content.ext || content.data.split('filename=')[1],
        type: 'SOUND',
        src : content.data.replace(/#((?!&).)*/g, ''),
      }];
      break;
    case 'IMAGE':
      return [{
        id: content.ext || content.data.split('filename=')[1],
        type: 'IMAGE',
        src : content.data,
      }];
      break;
    case 'VIDEO':
      return [{
        id: content.ext || content.data.split('filename=')[1],
        type: 'VIDEO',
        src : content.data,
      }];
      break;
    case 'COMMENT':
      return [{
        id: Math.round(Math.random() * 4294967296),
        type: 'COMMENT',
        text : content.data,
      }];
      break;
    case 'FILE':
      return [{
        id: Math.round(Math.random() * 4294967296),
        type: 'FILE',
        src : content.data.replace(/#((?!&).)*/g, ''),
        name: content.ext,
      }];
      break;
    case 'QUOTE':
      return [{
        id: Math.round(Math.random() * 4294967296),
        type: 'QUOTE',
        text : content.data,
      }];
      break;
    case 'MARK':
      return [{
        id: Math.round(Math.random() * 4294967296),
        type: 'MARK',
        text: content.data
      }];
      break;
    case 'ADMIRE':
      return [{
        id: Math.round(Math.random() * 4294967296),
        type: 'ADMIRE',
        text: content.data
      }];
      console.log('custom', content)
      break;
    case '__SYSTEM__':
      return [{
        id: Math.round(Math.random() * 4294967296),
        type: '__SYSTEM__',
        text : JSON.parse(content.data).data,
      }];
      break;
    default:
      break;
  }
};

//进入chatRomm
function applyJoinGroup(groupId, callback) {
  var options = {
    'GroupId': groupId//群id
  };
  _webim.applyJoinGroup(
    options,
    function (resp) {
      // 可以踢人
      _canKick_ = new Date().getTime();
      //JoinedSuccess:加入成功; WaitAdminApproval:等待管理员审批
      if (resp.JoinedStatus && resp.JoinedStatus == 'JoinedSuccess') {
        _webim.Log.info('进群成功');
        selToID = groupId;
        callback(null);
      } else {
        //alert('进群失败');
        callback({ErrorInfo:'进群失败'});
      }
    },
    function (err) {
      // 可以踢人
      _canKick_ = new Date().getTime();
      //
      if(err.ErrorCode == 10013){
        _webim.Log.info('已在群里!');
        selToID = groupId;
        callback(null);
        // quitGroup(groupId, function (_err) {
        //   if(_err) return alert(_err.ErrorInfo);
        //   applyJoinGroup(groupId, callback);
        // });
      }else{
        callback(err);
      }
    }
  );
};

//退出chat room
function quitGroup(groupId, callback) {
  var options = {
    'GroupId': groupId//群id
  };
  _webim.quitGroup(
    options,
    function (resp) {
      _webim.Log.info('退群成功');
      callback(null);
    },
    function (err) {
      callback(err);
    }
  );
};

//sdk登录
function sdkInit(cb) {
  //web sdk 初始化
  _webim.init(loginInfo, listeners, options,
    function (identifierNick) {
      //identifierNick为登录用户昵称(没有设置时，为帐号)，无登录态时为空
      _webim.Log.info('webim登录成功!~开始进群!');
      //applyJoinBigGroup(avChatRoomId, cb);//加入大群
      applyJoinGroup(avChatRoomId, cb);//加入chat room
    },
    function (err) {
      cb(err);
    }
  );//
};

//监测滚动条区域
function inspectScroll() {
  try{
    // live body area
    if(!vScroll){
      vScroll = document.getElementById('live-body');
    }
    // 探测范围
    setTimeout(()=>{
      let scrollHeight = vScroll.scrollHeight;
      let offsetHeight = vScroll.offsetHeight;
      let scrollTop    = vScroll.scrollTop;
      //
      if(scrollHeight > offsetHeight){
        // 一屏
        if(scrollHeight - offsetHeight - scrollTop < offsetHeight){
          vScroll.scrollTop = scrollHeight;
        }
      }
    },200);
  }catch(e){}
}

//监测讨论区滚动条区域
function inspectCommentScroll() {
  //
  try{
    // comment area
    if(!cScroll || !cScroll.scrollHeight){
      cScroll = document.getElementById('comment-body');
    }
    let scrollHeight = cScroll.scrollHeight;
    let offsetHeight = cScroll.offsetHeight;
    let scrollTop    = cScroll.scrollTop;
    //
    if(scrollHeight > offsetHeight){
      // 三分二屏
      if(scrollHeight - offsetHeight - scrollTop < (offsetHeight*2)/3){
        cScroll.scrollTop = scrollHeight;
        // 判断是否在限制范围内
        handleOverflow.call(this);
      }
    }
  }catch(e){}
}

// 移除掉溢出的范围
function handleOverflow() {
  // 新的组合
  let temp = [...this.commentMessageInfo];
  let newMsg = [];
  let limit = 50;
  // 是否符合溢出条件
  if(temp.length > limit){
    for(let i=1,l=temp.length;i<=limit;i++){
      newMsg.unshift(temp[l-i]);
    }
    // 修改讨论区消息
    this.$store.commit('RESET_COMMENT_MESSAGE', newMsg);
    // 还可以拉取
    this.canPullMsgs = true;
  }
}
