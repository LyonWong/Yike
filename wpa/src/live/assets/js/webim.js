import { trimStr } from '@lib/js/mUtils';
import swal from 'sweetalert';

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
  openEmotionFlag = false;

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
};

//IE9(含)以下浏览器用到的jsonp回调函数
export const jsonpCallback = (rspData) => {
  //设置接口返回的数据
  webim.setJsonpLastRspData(rspData);
};

//监听大群新消息（普通，点赞，提示，红包）
export const vBigGroupMsgNotify = function(msgList) {
  for (let i = msgList.length - 1; i >= 0; i--) {//遍历消息，按照时间从后往前
    let msg = msgList[i];
    webim.Log.warn('receive a new avchatroom group msg: ' + msg.getFromAccountNick());
    // 组装消息
    console.log('foo', msg);
    this.$store.commit('UPDATE_MESSAGE', assembleMsg(msg));
  }
};

//监听新消息(私聊(包括普通消息、全员推送消息)，普通群(非直播聊天室)消息)事件
//newMsgList 为新消息数组，结构为[Msg]
export const onMsgNotify = function(newMsgList) {
  for (let i = newMsgList.length - 1; i >= 0; i--) {//遍历消息，按照时间从后往前
    let msg = newMsgList[i];
    webim.Log.warn('receive a new chatroom group msg: ' + msg.getFromAccountNick());
    // 组装消息
    console.log('bar', msg);
    this.$store.commit('UPDATE_MESSAGE', assembleMsg(msg));
  }
};

// @发送消息
export const vSendMsg = (str, callback) => {
  let value = trimStr(str);
  // 调用webim base接口
  if (value) {
    sendMsgCallBack(value, function (err, msg) {
      //
      callback(err, msg);
    })
  }
};

// 导出 sdk 登录
export const exportSdkLogin = (cb) => {
  sdkLogin(cb);
};

// 导出群组成员接口
export const exportGroupMemberInfo = (opt) => {
  // opt
  let curOpt = { 'GroupId': avChatRoomId, ...opt };
  //
  return new Promise((resolve, reject) => {
    webim.getGroupMemberInfo(curOpt, (data) => {
      resolve(data);
    }, () => {
      reject();
    });
  });
};

// 上传图片
export const uploadImage = (uploadFiles, callback, progressCallback) => {
  var file = uploadFiles;
  var businessType;//业务类型，1-发群图片，2-向好友发图片
  if (selType == webim.SESSION_TYPE.C2C) {//向好友发图片
    businessType = webim.UPLOAD_PIC_BUSSINESS_TYPE.C2C_MSG;
  } else if (selType == webim.SESSION_TYPE.GROUP) {//发群图片
    businessType = webim.UPLOAD_PIC_BUSSINESS_TYPE.GROUP_MSG;
  }
  //封装上传图片请求
  var opt = {
    'file': file, //图片对象
    'onProgressCallBack': progressCallback, //上传图片进度条回调函数
    //'abortButton': document.getElementById('upd_abort'), //停止上传图片按钮
    'To_Account': selToID, //接收者
    'businessType': businessType//业务类型
  };

  //上传图片
  webim.uploadPic(opt,
    function (resp) {
      //上传成功发送图片
      sendPic(resp, file.name);
      callback(null);
    },
    function (err) {
      callback(err);
    }
  );
};

// 上传文件
export const uploadFile = (uploadFiles, callback, progressCallback) => {
  var file = uploadFiles;

  var businessType;//业务类型，1-发群文件，2-向好友发文件
  if (selType == webim.SESSION_TYPE.C2C) {//向好友发文件
    businessType = webim.UPLOAD_PIC_BUSSINESS_TYPE.C2C_MSG;
  } else if (selType == webim.SESSION_TYPE.GROUP) {//发群文件
    businessType = webim.UPLOAD_PIC_BUSSINESS_TYPE.GROUP_MSG;
  }

  //封装上传图片请求
  var opt = {
    'file': file, //图片对象
    'onProgressCallBack': progressCallback, //上传图片进度条回调函数
    'To_Account': selToID, //接收者
    'businessType': businessType, //业务类型
    'fileType': webim.UPLOAD_RES_TYPE.FILE//表示上传文件
  };

  //上传图片
  webim.uploadFile(opt,
    function (resp) {
      //上传成功发送文件
      sendFile(resp, file.name);
      callback(null);
    },
    function (err) {
      callback(err);
    }
  );
};

// 上传语音
export const uploadSound = (uploadFiles, callback, progressCallback) => {
  var file = uploadFiles;

  var businessType;//业务类型，1-发群文件，2-向好友发文件
  if (selType == webim.SESSION_TYPE.C2C) {//向好友发文件
    businessType = webim.UPLOAD_PIC_BUSSINESS_TYPE.C2C_MSG;
  } else if (selType == webim.SESSION_TYPE.GROUP) {//发群文件
    businessType = webim.UPLOAD_PIC_BUSSINESS_TYPE.GROUP_MSG;
  }

  if(!file.name){
    var random=Math.round(Math.random() * 4294967296);
    file.name=random.toString()+'.wav';
  }

  //封装上传请求
  var opt = {
    'file': file, //对象
    'onProgressCallBack': progressCallback, //上传文件进度条回调函数
    'To_Account': selToID, //接收者
    'businessType': businessType, //业务类型
    'fileType': webim.UPLOAD_RES_TYPE.FILE//表示上传文件
  };

  //上传
  webim.uploadFile(opt,
    function (resp) {
      //上传成功发送文件
      sendSound(resp, file.name);
      callback(null);
    },
    function (err) {
      callback(err);
    }
  );
};

//获取最新的群历史消息,用于切换群组聊天时，重新拉取群组的聊天消息
export const pullHistoryGroupMsgs = (opt, cbOk, cbErr) => {
  //
  getGroupInfo(selToID, function (resp) {
    // 消息条数
    var nextMsgSeq = resp.GroupInfo[0].NextMsgSeq;
    if(opt.allMsgs){
      var reqMsgSeq = nextMsgSeq-1;
      var reqMsgNumber = nextMsgSeq;
    }else{
      var reqMsgSeq = opt.single?opt.msgSeq:(nextMsgSeq-opt.msgSeq-1);
      var reqMsgNumber = opt.reqMsgCount;
    }

    //拉取最新的群历史消息
    var options = {
      'GroupId': selToID,
      'ReqMsgSeq': reqMsgSeq,
      'ReqMsgNumber': reqMsgNumber
    };
    //
    if (options.ReqMsgSeq == null || options.ReqMsgSeq == undefined || options.ReqMsgSeq <= 0) {
      webim.Log.warn("该群还没有历史消息:options=" + JSON.stringify(options));
      return;
    }
    // selSess
    selSess = null;
    webim.MsgStore.delSessByTypeId(selType, selToID);
    //
    webim.syncGroupMsgs(
      options,
      function (msgList) {
        if (msgList.length == 0) {
          webim.Log.warn("该群没有历史消息了:options=" + JSON.stringify(options));
          //return;
        }
        // 重组备份
        let tempList = [];
        // 循环
        for (var i = 0; i <= msgList.length - 1; i++) {//遍历消息，按照时间从后往前
          var msg = msgList[i];
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

//发送语音消息
export const sendAudioSound = (file, callback) => {
  let identifier = loginInfo.identifier;
  var friendHeadUrl = 'img/friend.jpg';
  if (!selSess) {
    selSess = new webim.Session(selType, selToID, selToID, friendHeadUrl, Math.round(new Date().getTime() / 1000));
  }
  var msg = new webim.Msg(selSess, true, -1, -1, -1, identifier, 0, loginInfo.identifierNick);
  // 获取filename
  var fileName = file.replace(/(.*\/)*([^.]+).*/ig,"$2");

  // add file
  msg.addCustom({data: file, desc: 'SOUND', ext: fileName});
  //调用发送文件消息接口
  webim.sendMsg(msg, function (resp) {
    if (selType == webim.SESSION_TYPE.C2C) {//私聊时，在聊天窗口手动添加一条发的消息，群聊时，长轮询接口会返回自己发的消息
      addMsg(msg);
    }
    webim.Log.info('发消息成功');
    // 回调
    callback(null);
  }, function (err) {
    swal({
      title: '错误提醒',
      text: err.ErrorInfo,
      confirmButtonText: "知道了"
    });
    // 回调
    callback('发送失败');
  });
}

//读取群组基本资料-高级接口
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
    'MemberInfoFilter': [
      'Account',
      'Role',
      'JoinTime',
      'LastSendMsgTime',
      'ShutUpUntil'
    ]
  };
  webim.getGroupInfo(
    options,
    function (resp) {
      if (cbOK) {
        cbOK(resp);
      }
    },
    function (err) {
      /*alert(err.ErrorInfo);*/
      swal({
        title: '错误提醒',
        text: err.ErrorInfo,
        confirmButtonText: "知道了"
      });
    }
  );
};

//处理消息（私聊(包括普通消息和全员推送消息)，普通群(非直播聊天室)消息）
function handlderMsg(msg) {
  var fromAccount, fromAccountNick, sessType, subType, contentHtml;

  fromAccount = msg.getFromAccount();
  if (!fromAccount) {
    fromAccount = '';
  }
  fromAccountNick = msg.getFromAccountNick();
  if (!fromAccountNick) {
    fromAccountNick = fromAccount;
  }

  //解析消息
  //获取会话类型
  //webim.SESSION_TYPE.GROUP-群聊，
  //webim.SESSION_TYPE.C2C-私聊，
  sessType = msg.getSession().type();
  //获取消息子类型
  //会话类型为群聊时，子类型为：webim.GROUP_MSG_SUB_TYPE
  //会话类型为私聊时，子类型为：webim.C2C_MSG_SUB_TYPE
  subType = msg.getSubType();

  switch (sessType) {
    case webim.SESSION_TYPE.C2C://私聊消息
      switch (subType) {
        case webim.C2C_MSG_SUB_TYPE.COMMON://c2c普通消息
          //业务可以根据发送者帐号fromAccount是否为app管理员帐号，来判断c2c消息是否为全员推送消息，还是普通好友消息
          //或者业务在发送全员推送消息时，发送自定义类型(webim.MSG_ELEMENT_TYPE.CUSTOM,即TIMCustomElem)的消息，在里面增加一个字段来标识消息是否为推送消息
          contentHtml = convertMsg(msg);
          webim.Log.warn('receive a new c2c msg: fromAccountNick=' + fromAccountNick + ", content=" + contentHtml);
          //c2c消息一定要调用已读上报接口
          var opts = {
            'To_Account': fromAccount,//好友帐号
            'LastedMsgTime': msg.getTime()//消息时间戳
          };
          webim.c2CMsgReaded(opts);
          /*alert('收到一条c2c消息(好友消息或者全员推送消息): 发送人=' + fromAccountNick + ", 内容=" + contentHtml);*/
          swal({
            title: '错误提醒',
            text: '收到一条c2c消息(好友消息或者全员推送消息): 发送人=' + fromAccountNick + ", 内容=" + contentHtml,
            confirmButtonText: "知道了"
          });
          break;
      }
      break;
    case webim.SESSION_TYPE.GROUP://普通群消息，对于直播聊天室场景，不需要作处理
      break;
  }
};

// 发送图片
function sendPic(images, imgName) {
  var friendHeadUrl = 'img/friend.jpg';
  // selSess
  if (!selSess) {
    selSess = new webim.Session(selType, selToID, selToID, friendHeadUrl, Math.round(new Date().getTime() / 1000));
  }
  var msg = new webim.Msg(selSess, true, -1, -1, -1, loginInfo.identifier, 0, loginInfo.identifierNick);
  var images_obj = new webim.Msg.Elem.Images(images.File_UUID);
  for (var i in images.URL_INFO) {
    var img = images.URL_INFO[i];
    var newImg;
    var type;
    //
    switch (img.PIC_TYPE) {
      case 1://原图
        type = 1;//原图
        break;
      case 2://小图（缩略图）
        type = 3;//小图
        break;
      case 4://大图
        type = 2;//大图
        break;
    }
    newImg = new webim.Msg.Elem.Images.Image(type, img.PIC_Size, img.PIC_Width, img.PIC_Height, img.DownUrl);
    images_obj.addImage(newImg);
  }
  msg.addImage(images_obj);
  //调用发送图片消息接口
  webim.sendMsg(msg, function (resp) {
    if (selType == webim.SESSION_TYPE.C2C) {//私聊时，在聊天窗口手动添加一条发的消息，群聊时，长轮询接口会返回自己发的消息
      //addMsg(msg);
    }
    webim.Log.info('发消息成功');
  }, function (err) {
    //
    swal({
      title: '错误提醒',
      text: err.ErrorInfo,
      confirmButtonText: "知道了"
    });
  });
};

//发送文件消息
function sendFile(file, fileName) {
  let identifier = loginInfo.identifier;
  var friendHeadUrl = 'img/friend.jpg';
  if (!selSess) {
    selSess = new webim.Session(selType, selToID, selToID, friendHeadUrl, Math.round(new Date().getTime() / 1000));
  }
  var msg = new webim.Msg(selSess, true, -1, -1, -1, identifier, 0, loginInfo.identifierNick);
  var uuid=file.File_UUID;//文件UUID
  var fileSize=file.File_Size;//文件大小
  var senderId=identifier;
  var downloadFlag=file.Download_Flag;

  if(!fileName){
    var random=Math.round(Math.random() * 4294967296);
    fileName=random.toString();
  }
  var fileObj=new webim.Msg.Elem.File(uuid, fileName, fileSize, senderId, selToID, downloadFlag, selType);
  // msg.addFile(fileObj);
  // add customer file
  msg.addCustom({data: fileObj.downUrl, desc: 'FILE', ext: fileName});
  //调用发送文件消息接口
  webim.sendMsg(msg, function (resp) {
    if (selType == webim.SESSION_TYPE.C2C) {//私聊时，在聊天窗口手动添加一条发的消息，群聊时，长轮询接口会返回自己发的消息
      addMsg(msg);
    }
    webim.Log.info('发消息成功');
  }, function (err) {
    swal({
      title: '错误提醒',
      text: err.ErrorInfo,
      confirmButtonText: "知道了"
    });
  });
}

//发送文件消息
function sendSound(file, fileName) {
  let identifier = loginInfo.identifier;
  var friendHeadUrl = 'img/friend.jpg';
  if (!selSess) {
    selSess = new webim.Session(selType, selToID, selToID, friendHeadUrl, Math.round(new Date().getTime() / 1000));
  }
  var msg = new webim.Msg(selSess, true, -1, -1, -1, identifier, 0, loginInfo.identifierNick);
  var uuid=file.File_UUID;//文件UUID
  var fileSize=file.File_Size;//文件大小
  var senderId=identifier;
  var downloadFlag=file.Download_Flag;

  if(!fileName){
    var random=Math.round(Math.random() * 4294967296);
    fileName=random.toString();
  }
  var soundObj=new webim.Msg.Elem.File(uuid, fileName, fileSize, senderId, selToID, downloadFlag, selType);

  // add file
  msg.addCustom({data: soundObj.downUrl, desc: 'SOUND', ext: fileName});
  //调用发送文件消息接口
  webim.sendMsg(msg, function (resp) {
    if (selType == webim.SESSION_TYPE.C2C) {//私聊时，在聊天窗口手动添加一条发的消息，群聊时，长轮询接口会返回自己发的消息
      addMsg(msg);
    }
    webim.Log.info('发消息成功');
  }, function (err) {
    swal({
      title: '错误提醒',
      text: err.ErrorInfo,
      confirmButtonText: "知道了"
    });
  });
}

// 发送并接收消息
function sendMsgCallBack (msgtosend, callback) {
    // 未登录
    if (!loginInfo.identifier) {
      if (accountMode == 1) {//托管模式
        //将account_type保存到cookie中,有效期是1天
        webim.Tool.setCookie('accountType', loginInfo.accountType, 3600 * 24);
        //调用tls登录服务
        tlsLogin();
      } else {//独立模式
        swal({
          title: '错误提醒',
          text: '请填写帐号和票据',
          confirmButtonText: "知道了"
        });
      }
      return callback('请先登录!');
    }

    // 是否成功进入房间
    if (!selToID) {
      return callback('您还没有进入房间，暂不能聊天!');
    }
    //获取消息内容
    var msgtosend = msgtosend;
    var msgLen = webim.Tool.getStrBytes(msgtosend);

    if (msgtosend.length < 1) {
      return callback('发送的消息不能为空!');
    }

    var maxLen, errInfo;
    if (selType == webim.SESSION_TYPE.GROUP) {
      maxLen = webim.MSG_MAX_LENGTH.GROUP;
      errInfo = "消息长度超出限制(最多" + Math.round(maxLen / 3) + "汉字)";
    } else {
      maxLen = webim.MSG_MAX_LENGTH.C2C;
      errInfo = "消息长度超出限制(最多" + Math.round(maxLen / 3) + "汉字)";
    }
    if (msgLen > maxLen) {
      return callback(errInfo);
    }

    if (!selSess) {
      selSess = new webim.Session(selType, selToID, selToID, loginInfo.headurl, Math.round(new Date().getTime() / 1000));
    }
    var isSend = true;//是否为自己发送
    var seq = -1;//消息序列，-1表示sdk自动生成，用于去重
    var random = Math.round(Math.random() * 4294967296);//消息随机数，用于去重
    var msgTime = Math.round(new Date().getTime() / 1000);//消息时间戳
    var subType;//消息子类型
    if (selType == webim.SESSION_TYPE.GROUP) {
      //群消息子类型如下：
      //webim.GROUP_MSG_SUB_TYPE.COMMON-普通消息,
      //webim.GROUP_MSG_SUB_TYPE.LOVEMSG-点赞消息，优先级最低
      //webim.GROUP_MSG_SUB_TYPE.TIP-提示消息(不支持发送，用于区分群消息子类型)，
      //webim.GROUP_MSG_SUB_TYPE.REDPACKET-红包消息，优先级最高
      subType = webim.GROUP_MSG_SUB_TYPE.COMMON;

    } else {
      //C2C消息子类型如下：
      //webim.C2C_MSG_SUB_TYPE.COMMON-普通消息,
      subType = webim.C2C_MSG_SUB_TYPE.COMMON;
    }

    var msg = new webim.Msg(selSess, isSend, seq, random, msgTime, loginInfo.identifier, subType, loginInfo.identifierNick);

    //解析文本和表情
    var expr = /\[[^[\]]{1,3}\]/mg;
    var emotions = msgtosend.match(expr);
    var text_obj, face_obj, tmsg, emotionIndex, emotion, restMsgIndex;
    text_obj = new webim.Msg.Elem.Text(msgtosend);
    msg.addText(text_obj);
    /*if (!emotions || emotions.length < 1) {
      text_obj = new webim.Msg.Elem.Text(msgtosend);
      msg.addText(text_obj);
    }*/

    webim.sendMsg(msg, function (resp) {
      if (selType == webim.SESSION_TYPE.C2C) {//私聊时，在聊天窗口手动添加一条发的消息，群聊时，长轮询接口会返回自己发的消息
        // showMsg(msg);
      }
      webim.Log.info('发消息成功');
      callback(null, assembleMsg(msg));
    }, function (err) {
      webim.Log.error('发消息失败:' + err.ErrorInfo);
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
      content: [],
    };

    // assign
    assemble.account = msg.getFromAccount() || '';
    assemble.nickname = msg.getFromAccountNick() || '未知用户';

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
}

// 获取消息内容
function convertMsg(msg) {
  var contents = [], elems, elem, type, content;
  elems = msg.getElems();//获取消息包含的元素数组

  for (var i in elems) {
    elem = elems[i];
    type = elem.getType();//获取元素类型
    contents[i] = {
      type: type,
      text: '',
      audioSrc: '',
      imgArr: [],
      fileArr: [],
      custom: [],
    };
    //
    content = elem.getContent();//获取元素对象
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
      for (var m in userIdList) {
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
      for (var m in userIdList) {
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
      for (var m in userIdList) {
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
      for (var m in userIdList) {
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
      for (var m in groupInfoList) {
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
      for (var m in memberInfoList) {
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

//解析图片消息元素
function convertImageMsg(content) {
  var smallImage = content.getImage(webim.IMAGE_TYPE.SMALL);//小图
  var bigImage = content.getImage(webim.IMAGE_TYPE.LARGE);//大图
  var oriImage = content.getImage(webim.IMAGE_TYPE.ORIGIN);//原图
  if (!bigImage) {
    bigImage = smallImage;
  }
  if (!oriImage) {
    oriImage = smallImage;
  }
  return `${smallImage.getUrl()}#${bigImage.getUrl()}#${oriImage.getUrl()}`;
  // return "<img src='" + smallImage.getUrl() + "#" + bigImage.getUrl() + "#" + oriImage.getUrl() + "' style='CURSOR: hand' id='" + content.getImageId() + "' bigImgUrl='" + bigImage.getUrl() + "' onclick='imageClick(this)' />";
};

//解析文件消息元素
function convertFileMsg(content) {
  var fileSize = Math.round(content.getSize() / 1024);

  return [{
    url: content.getDownUrl().replace(/#((?!&).)*/g, ''),
    name: content.getName(),
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

//解析自定义消息元素
function convertCustomMsg(content) {
  switch (content.desc) {
    case 'SOUND':
      return [{
        id: content.ext || content.data.split('filename=')[1],
        type: 'SOUND',
        src : content.data.replace(/#((?!&).)*/g, ''),
      }]
      break;
    case 'FILE':
      return [{
        id: Math.round(Math.random() * 4294967296),
        type: 'FILE',
        src : content.data.replace(/#((?!&).)*/g, ''),
      }]
      break;
    default:
      break;
  }
};

//切换播放audio对象
function onChangePlayAudio(obj) {
  if (curPlayAudio) {//如果正在播放语音
    if (curPlayAudio != obj) {//要播放的语音跟当前播放的语音不一样
      curPlayAudio.currentTime = 0;
      curPlayAudio.pause();
      curPlayAudio = obj;
    }
  } else {
    curPlayAudio = obj;//记录当前播放的语音
  }
};

//进入chatRomm
function applyJoinGroup(groupId, callback) {
  var options = {
    'GroupId': groupId//群id
  };
  webim.applyJoinGroup(
    options,
    function (resp) {
      //JoinedSuccess:加入成功; WaitAdminApproval:等待管理员审批
      if (resp.JoinedStatus && resp.JoinedStatus == 'JoinedSuccess') {
        webim.Log.info('进群成功');
        selToID = groupId;
        callback(null);
      } else {
        //alert('进群失败');
        callback({ErrorInfo:'进群失败'});
      }
    },
    function (err) {
      if(err.ErrorCode == 10013){
        webim.Log.info('已在群里!');
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
  webim.quitGroup(
    options,
    function (resp) {
      webim.Log.info('退群成功');
      callback(null);
    },
    function (err) {
      callback(err);
    }
  );
}

//进入大群
function applyJoinBigGroup(groupId, callback) {
  var options = {
    'GroupId': groupId//群id
  };
  webim.applyJoinBigGroup(
    options,
    function (resp) {
      //JoinedSuccess:加入成功; WaitAdminApproval:等待管理员审批
      if (resp.JoinedStatus && resp.JoinedStatus == 'JoinedSuccess') {
        webim.Log.info('进群成功');
        selToID = groupId;
        callback(null);
      } else {
        //alert('进群失败');
        callback({ErrorInfo:'进群失败'});
      }
    },
    function (err) {
      //alert(err.ErrorInfo);
      callback(err);
    }
  );
};

//退出大群
function quitBigGroup(groupId) {
  var options = {
    'GroupId': groupId//群id
  };
  webim.quitBigGroup(
    options,
    function (resp) {
      webim.Log.info('退群成功');

    },
    function (err) {
      swal({
        title: '错误提醒',
        text: err.ErrorInfo,
        confirmButtonText: "知道了"
      });
    }
  );
}

//sdk登录
function sdkLogin(cb) {
  //web sdk 登录
  webim.login(loginInfo, listeners, options,
    function (identifierNick) {
      //identifierNick为登录用户昵称(没有设置时，为帐号)，无登录态时为空
      webim.Log.info('webim登录成功!~开始进群!');
      //applyJoinBigGroup(avChatRoomId, cb);//加入大群
      applyJoinGroup(avChatRoomId, cb);//加入chat room
    },
    function (err) {
      cb(err);
    }
  );//
};
