/* webim javascript SDK
 * VER 1.7.0
 */

/* webim API definitions
 */
var webim = { // namespace object webim

    /* function init
     *   sdk登录
     * params:
     *   loginInfo      - Object, 登录身份相关参数集合，详见下面
     *   {
     *     sdkAppID     - String, 用户标识接入SDK的应用ID，必填
     *     accountType  - int, 账号类型，必填
     *     identifier   - String, 用户帐号,必须是字符串类型，必填
     *     identifierNick   - String, 用户昵称，选填
     *     userSig      - String, 鉴权Token，必须是字符串类型，必填
     *   }
     *   listeners      - Object, 事件回调函数集合, 详见下面
     *   {
     *     onConnNotify - function(connInfo), 用于收到连接状态相关通知的回调函数,目前未使用
     *     jsonpCallback -function(rspData),//IE9(含)以下浏览器用到的jsonp回调函数
     *     onMsgNotify  - function(newMsgList), 用于收到消息通知的回调函数,
     *      newMsgList为新消息数组，格式为[Msg对象]
     *      使用方有两种处理回调: 1)处理newMsgList中的增量消息,2)直接访问webim.MsgStore获取最新的消息
     *     onGroupInfoChangeNotify  - function(groupInfo), 用于监听群组资料变更的回调函数,
     *          groupInfo为新的群组资料信息
     *     onGroupSystemNotifys - Object, 用于监听（多终端同步）群系统消息的回调函数对象
     *
     *   }
     *   options        - Object, 其它选项, 目前未使用
     * return:
     *   (无)
     */
    login: function (loginInfo, listeners, options) {
    },

    /* function syncMsgs
     *   拉取最新C2C消息
     *   一般不需要使用方直接调用, SDK底层会自动同步最新消息并通知使用方, 一种有用的调用场景是用户手动触发刷新消息
     * params:
     *   cbOk   - function(msgList)类型, 当同步消息成功时的回调函数, msgList为新消息数组，格式为[Msg对象],
     *            如果此参数为null或undefined则同步消息成功后会像自动同步那样回调cbNotify
     *   cbErr  - function(err)类型, 当同步消息失败时的回调函数, err为错误对象
     * return:
     *   (无)
     */
    syncMsgs: function (cbOk, cbErr) {
    },


    /* function getC2CHistoryMsgs
     * 拉取C2C漫游消息
     * params:
     *   options    - 请求参数
     *   cbOk   - function(msgList)类型, 成功时的回调函数, msgList为消息数组，格式为[Msg对象],
     *   cbErr  - function(err)类型, 失败时回调函数, err为错误对象
     * return:
     *   (无)
     */
    getC2CHistoryMsgs: function (options, cbOk, cbErr) {
    },

    /* function syncGroupMsgs
     * 拉取群漫游消息
     * params:
     *   options    - 请求参数
     *   cbOk   - function(msgList)类型, 成功时的回调函数, msgList为消息数组，格式为[Msg对象],
     *   cbErr  - function(err)类型, 失败时回调函数, err为错误对象
     * return:
     *   (无)
     */
    syncGroupMsgs: function (options, cbOk, cbErr) {
    },

    /* function sendMsg
     *   发送一条消息
     * params:
     *   msg    - webim.Msg类型, 要发送的消息对象
     *   cbOk   - function()类型, 当发送消息成功时的回调函数
     *   cbErr  - function(err)类型, 当发送消息失败时的回调函数, err为错误对象
     * return:
     *   (无)
     */
    sendMsg: function (msg, cbOk, cbErr) {
    },

    /* function logout
     *   sdk登出
     * params:
     *   cbOk   - function()类型, 成功时回调函数
     *   cbErr  - function(err)类型, 失败时回调函数, err为错误对象
     * return:
     *   (无)
     */
    logout: function (cbOk, cbErr) {
    },

    /* function setAutoRead
     * 设置会话自动已读上报标志
     * params:
     *   selSess    - webim.Session类型, 当前会话
     *   isOn   - boolean, 将selSess的自动已读消息标志改为isOn，同时是否上报当前会话已读消息
     *   isResetAll - boolean，是否重置所有会话的自动已读标志
     * return:
     *   (无)
     */
    setAutoRead: function (selSess, isOn, isResetAll) {
    },

    /* function getProfilePortrait
     *   拉取资料（搜索用户）
     * params:
     *   cbOk   - function()类型, 成功时回调函数
     *   cbErr  - function(err)类型, 失败时回调函数, err为错误对象
     * return:
     *   (无)
     */
    getProfilePortrait: function (options, cbOk, cbErr) {
    },

    /* function setProfilePortrait
     *   设置个人资料
     * params:
     *   cbOk   - function()类型, 成功时回调函数
     *   cbErr  - function(err)类型, 失败时回调函数, err为错误对象
     * return:
     *   (无)
     */
    setProfilePortrait: function (options, cbOk, cbErr) {
    },

    /* function applyAddFriend
     *   申请添加好友
     * params:
     *   cbOk   - function()类型, 成功时回调函数
     *   cbErr  - function(err)类型, 失败时回调函数, err为错误对象
     * return:
     *   (无)
     */
    applyAddFriend: function (options, cbOk, cbErr) {
    },

    /* function getPendency
     *   拉取好友申请
     * params:
     *   cbOk   - function()类型, 成功时回调函数
     *   cbErr  - function(err)类型, 失败时回调函数, err为错误对象
     * return:
     *   (无)
     */
    getPendency: function (options, cbOk, cbErr) {
    },

    /* function deletePendency
     *   删除好友申请
     * params:
     *   cbOk   - function()类型, 成功时回调函数
     *   cbErr  - function(err)类型, 失败时回调函数, err为错误对象
     * return:
     *   (无)
     */
    deletePendency: function (options, cbOk, cbErr) {
    },

    /* function responseFriend
     *   响应好友申请
     * params:
     *   cbOk   - function()类型, 成功时回调函数
     *   cbErr  - function(err)类型, 失败时回调函数, err为错误对象
     * return:
     *   (无)
     */
    responseFriend: function (options, cbOk, cbErr) {
    },

    /* function getAllFriend
     *   拉取我的好友
     * params:
     *   cbOk   - function()类型, 成功时回调函数
     *   cbErr  - function(err)类型, 失败时回调函数, err为错误对象
     * return:
     *   (无)
     */
    getAllFriend: function (options, cbOk, cbErr) {
    },

    /* function deleteFriend
     *   删除好友
     * params:
     *   cbOk   - function()类型, 成功时回调函数
     *   cbErr  - function(err)类型, 失败时回调函数, err为错误对象
     * return:
     *   (无)
     */
    deleteFriend: function (options, cbOk, cbErr) {
    },

    /* function addBlackList
     *   增加黑名单
     * params:
     *   cbOk   - function()类型, 成功时回调函数
     *   cbErr  - function(err)类型, 失败时回调函数, err为错误对象
     * return:
     *   (无)
     */
    addBlackList: function (options, cbOk, cbErr) {
    },

    /* function getBlackList
     *   删除黑名单
     * params:
     *   cbOk   - function()类型, 成功时回调函数
     *   cbErr  - function(err)类型, 失败时回调函数, err为错误对象
     * return:
     *   (无)
     */
    getBlackList: function (options, cbOk, cbErr) {
    },

    /* function deleteBlackList
     *   我的黑名单
     * params:
     *   cbOk   - function()类型, 成功时回调函数
     *   cbErr  - function(err)类型, 失败时回调函数, err为错误对象
     * return:
     *   (无)
     */
    deleteBlackList: function (options, cbOk, cbErr) {
    },

    /* function uploadPic
     *   上传图片
     * params:
     *   options    - 请求参数，详见api文档
     *   cbOk   - function()类型, 成功时回调函数
     *   cbErr  - function(err)类型, 失败时回调函数, err为错误对象
     * return:
     *   (无)
     */
    uploadPic: function (options, cbOk, cbErr) {
    },

    /* function createGroup
     *   创建群
     * params:
     *   options    - 请求参数，详见api文档
     *   cbOk   - function()类型, 成功时回调函数
     *   cbErr  - function(err)类型, 失败时回调函数, err为错误对象
     * return:
     *   (无)
     */
    createGroup: function (options, cbOk, cbErr) {
    },

    /* function applyJoinGroup
     *   申请加群
     * params:
     *   options    - 请求参数，详见api文档
     *   cbOk   - function()类型, 成功时回调函数
     *   cbErr  - function(err)类型, 失败时回调函数, err为错误对象
     * return:
     *   (无)
     */
    applyJoinGroup: function (options, cbOk, cbErr) {
    },

    /* function handleApplyJoinGroup
     *   处理申请加群(同意或拒绝)
     * params:
     *   options    - 请求参数，详见api文档
     *   cbOk   - function()类型, 成功时回调函数
     *   cbErr  - function(err)类型, 失败时回调函数, err为错误对象
     * return:
     *   (无)
     */
    handleApplyJoinGroup: function (options, cbOk, cbErr) {
    },

    /* function deleteApplyJoinGroupPendency
     *   删除加群申请
     * params:
     *   options    - 请求参数，详见api文档
     *   cbOk   - function()类型, 成功时回调函数
     *   cbErr  - function(err)类型, 失败时回调函数, err为错误对象
     * return:
     *   (无)
     */
    deleteApplyJoinGroupPendency: function (options, cbOk, cbErr) {
    },


    /* function quitGroup
     *  主动退群
     * params:
     *   options    - 请求参数，详见api文档
     *   cbOk   - function()类型, 成功时回调函数
     *   cbErr  - function(err)类型, 失败时回调函数, err为错误对象
     * return:
     *   (无)
     */
    quitGroup: function (options, cbOk, cbErr) {
    },

    /* function getGroupPublicInfo
     *   读取群公开资料-高级接口
     * params:
     *   options    - 请求参数，详见api文档
     *   cbOk   - function()类型, 成功时回调函数
     *   cbErr  - function(err)类型, 失败时回调函数, err为错误对象
     * return:
     *   (无)
     */
    getGroupPublicInfo: function (options, cbOk, cbErr) {
    },

    /* function getGroupInfo
     *   读取群详细资料-高级接口
     * params:
     *   options    - 请求参数，详见api文档
     *   cbOk   - function()类型, 成功时回调函数
     *   cbErr  - function(err)类型, 失败时回调函数, err为错误对象
     * return:
     *   (无)
     */
    getGroupInfo: function (options, cbOk, cbErr) {
    },

    /* function modifyGroupBaseInfo
     *   修改群基本资料
     * params:
     *   options    - 请求参数，详见api文档
     *   cbOk   - function()类型, 成功时回调函数
     *   cbErr  - function(err)类型, 失败时回调函数, err为错误对象
     * return:
     *   (无)
     */
    modifyGroupBaseInfo: function (options, cbOk, cbErr) {
    },

    /* function destroyGroup
     *  解散群
     * params:
     *   options    - 请求参数，详见api文档
     *   cbOk   - function()类型, 成功时回调函数
     *   cbErr  - function(err)类型, 失败时回调函数, err为错误对象
     * return:
     *   (无)
     */
    destroyGroup: function (options, cbOk, cbErr) {
    },

    /* function getJoinedGroupListHigh
     *   获取我的群组-高级接口
     * params:
     *   options    - 请求参数，详见api文档
     *   cbOk   - function()类型, 成功时回调函数
     *   cbErr  - function(err)类型, 失败时回调函数, err为错误对象
     * return:
     *   (无)
     */
    getJoinedGroupListHigh: function (options, cbOk, cbErr) {
    },

    /* function getGroupMemberInfo
     *   获取群组成员列表
     * params:
     *   options    - 请求参数，详见api文档
     *   cbOk   - function()类型, 成功时回调函数
     *   cbErr  - function(err)类型, 失败时回调函数, err为错误对象
     * return:
     *   (无)
     */
    getGroupMemberInfo: function (options, cbOk, cbErr) {
    },

    /* function addGroupMember
     *   邀请好友加群
     * params:
     *   options    - 请求参数，详见api文档
     *   cbOk   - function()类型, 成功时回调函数
     *   cbErr  - function(err)类型, 失败时回调函数, err为错误对象
     * return:
     *   (无)
     */
    addGroupMember: function (options, cbOk, cbErr) {
    },

    /* function modifyGroupMember
     *   修改群成员资料（角色或者群消息提类型示）
     * params:
     *   options    - 请求参数，详见api文档
     *   cbOk   - function()类型, 成功时回调函数
     *   cbErr  - function(err)类型, 失败时回调函数, err为错误对象
     * return:
     *   (无)
     */
    modifyGroupMember: function (options, cbOk, cbErr) {
    },

    /* function forbidSendMsg
     *   设置群成员禁言时间
     * params:
     *   options    - 请求参数，详见api文档
     *   cbOk   - function()类型, 成功时回调函数
     *   cbErr  - function(err)类型, 失败时回调函数, err为错误对象
     * return:
     *   (无)
     */
    forbidSendMsg: function (options, cbOk, cbErr) {
    },

    /* function deleteGroupMember
     *   删除群成员
     * params:
     *   options    - 请求参数，详见api文档
     *   cbOk   - function()类型, 成功时回调函数
     *   cbErr  - function(err)类型, 失败时回调函数, err为错误对象
     * return:
     *   (无)
     */
    deleteGroupMember: function (options, cbOk, cbErr) {
    },

    /* function sendCustomGroupNotify
     *   发送自定义群通知
     * params:
     *   options    - 请求参数，详见api文档
     *   cbOk   - function()类型, 成功时回调函数
     *   cbErr  - function(err)类型, 失败时回调函数, err为错误对象
     * return:
     *   (无)
     */
    sendCustomGroupNotify: function (options, cbOk, cbErr) {
    },

    /* class webim.Msg
     *   一条消息的描述类, 消息发送、接收的API中都会涉及此类型的对象
     * properties:
     *   sess   - Session object-ref, 消息所属的会话(e.g:我与好友A的C2C会话，我与群组G的GROUP会话)
     *   isSend - Boolean, true表示是我发出消息, false表示是发给我的消息)
     *   seq    - Integer, 消息序列号, 用于判断消息是否同一条
     *   random - Integer, 消息随机数,用于判断消息是否同一条
     *   time   - Integer, 消息时间戳, 为unix timestamp
     *   fromAccount -String,  消息发送者帐号
     *   subType -Integer,  消息子类型，c2c消息时，0-表示普通消息；群消息时，0-普通消息，1-点赞消息，2-提示消息
     *   fromAccountNick -String,  消息发送者昵称
     *   elems  - Array of webim.Msg.Elem, 描述消息内容的元素列表
     * constructor:
     *   Msg(sess, isSend, seq,random time,fromAccount) - 构造函数, 参数定义同上面properties中定义
     * methods:
     *   addText(text)  - 向elems中添加一个TEXT元素
     *   addFace(face)  - 向elems中添加一个FACE元素
     *   toHtml()       - 转成可展示的html String
     *addFace
     * sub-class webim.Msg.Elem
     *   消息中一个组成元素的描述类, 一条消息的内容被抽象描述为N个元素的有序列表
     * properties:
     *   type   - 元素类型, 目前有TEXT(文本)、FACE(表情)、IMAGE(图片)等
     *   content- 元素内容体, 当TEXT时为String, 当PIC时为UrlString
     * constructor:
     *   Elem(type, content) - 构造函数, 参数定义同上面properties中定义
     *
     * sub-class webim.Msg.Elem.TextElem
     *   文本
     * properties:
     *   text  - String 内容
     * constructor:
     *   TextElem(text) - 构造函数, 参数定义同上面properties中定义
     *
     * sub-class webim.Msg.Elem.FaceElem
     *   表情
     * properties:
     *   index  - Integer 表情索引, 用户自定义
     *   data   - String 额外数据，用户自定义
     * constructor:
     *   FaceElem(index,data) - 构造函数, 参数定义同上面properties中定义
     *
     *
     */
    Msg: function (sess, isSend, seq, random, time, fromAccount, subType, fromAccountNick) {/*Class constructor*/
    },

    /* singleton object MsgStore
     * webim.MsgStore是消息数据的Model对象(参考MVC概念), 它提供接口访问当前存储的会话和消息数据
     * 下面说明下会话数据类型: Session
     *
     * class Session
     *   一个Session对象描述一个会话，会话可简单理解为最近会话列表的一个条目，它由两个字段唯一标识:
     *     type - String, 会话类型(如"C2C", "GROUP", ...)
     *     id   - String, 会话ID(如C2C类型中为对方帐号,"C2C"时为好友ID,"GROUP"时为群ID)
     * properties:
     *   (Session对象未对外暴露任何属性字段, 所有访问通过下面的getter方法进行)
     * methods:
     *   type()     - String, 返回会话类型,"C2C"表示与好友私聊，"GROUP"表示群聊
     *   id()       - String, 返回会话ID
     *   name()     - String, 返回会话标题(如C2C类型中为对方的昵称,暂不支持)
     *   icon()     - String, 返回会话图标(对C2C类型中为对方的头像URL，暂不支持)
     *   unread()           - Integer, 返回会话未读条数
     *   time()     - Integer, 返回会话最后活跃时间, 为unix timestamp
     *   curMaxMsgSeq() - Integer, 返回会话最大消息序列号
     *   msgCount() - Integer, 返回会话中所有消息条数
     *   msg(index) - webim.Msg, 返回会话中第index条消息
     */
    MsgStore: {
        /* function sessMap
         *   获取所有会话
         * return:
         *   所有会话对象
         */
        sessMap: function () {
            return {/*Object*/};
        },
        /* function sessCount
         *   获取当前会话的个数
         * return:
         *   Integer, 会话个数
         */
        sessCount: function () {
            return 0;
        },

        /* function sessByTypeId
         *   根据会话类型和会话ID取得相应会话
         * params:
         *   type   - String, 会话类型(如"C2C", "GROUP", ...)
         *   id     - String, 会话ID(如对方ID)
         * return:
         *   Session, 会话对象(说明见上面)
         */
        sessByTypeId: function (type, id) {
            return {/*Session Object*/};
        },
        /* function delSessByTypeId
         *   根据会话类型和会话ID删除相应会话
         * params:
         *   type   - String, 会话类型(如"C2C", "GROUP", ...)
         *   id     - String, 会话ID(如对方ID)
         * return:
         *   Boolean, 布尔类型
         */
        delSessByTypeId: function (type, id) {
            return true;
        },

        /* function resetCookieAndSyncFlag
         *   重置上一次读取新c2c消息Cookie和是否继续拉取标记
         * return:
         *
         */
        resetCookieAndSyncFlag: function () {
        },

        downloadMap: {}
    }

};

/* webim API implementation
 */
(function (webim) {
    //sdk版本
    var SDK = {
        'VERSION': '1.7.0',//sdk版本号
        'APPID': '537048168'//web im sdk 版本 APPID
    };

    //是否启用正式环境，默认启用
    var isAccessFormaEnvironment = true;
    // var isAccessFormaEnvironment = false;

    //后台接口主机
    var SRV_HOST = {
        'FORMAL': {
            'COMMON': 'https://webim.tim.qq.com',
            'PIC': 'https://pic.tim.qq.com'
        },
        'TEST': {
            'COMMON': 'https://test.tim.qq.com',
            'PIC': 'https://pic.tim.qq.com'
        }
    };

    //浏览器版本信息
    var BROWSER_INFO = {};
    //是否为ie9（含）以下
    var lowerBR = false;

    //服务名称
    var SRV_NAME = {
        'OPEN_IM': 'openim',//私聊（拉取未读c2c消息，长轮询，c2c消息已读上报等）服务名
        'GROUP': 'group_open_http_svc',//群组管理（拉取群消息，创建群，群成员管理，群消息已读上报等）服务名
        'FRIEND': 'sns',//关系链管理（好友管理，黑名单管理等）服务名
        'PROFILE': 'profile',//资料管理（查询，设置个人资料等）服务名
        'RECENT_CONTACT': 'recentcontact',//最近联系人服务名
        'PIC': 'openpic',//图片（或文件）服务名
        'BIG_GROUP': 'group_open_http_noauth_svc',//直播大群 群组管理（申请加大群）服务名
        'BIG_GROUP_LONG_POLLING': 'group_open_long_polling_http_noauth_svc',//直播大群 长轮询（拉取消息等）服务名
        'IM_OPEN_STAT': 'imopenstat'//质量上报，统计接口错误率
    };

    //不同服务对应的版本号
    var SRV_NAME_VER = {
        'openim': 'v4',
        'group_open_http_svc': 'v4',
        'sns': 'v4',
        'profile': 'v4',
        'recentcontact': 'v4',
        'openpic': 'v4',
        'group_open_http_noauth_svc': 'v1',
        'group_open_long_polling_http_noauth_svc': 'v1',
        'imopenstat': 'v4'
    };

    //不同的命令名对应的上报类型ID，用于接口质量上报
    var CMD_EVENT_ID_MAP = {
        'login': 1,//登录
        'pic_up': 3,//上传图片
        'apply_join_group': 9,//申请加入群组
        'create_group': 10,//创建群组
        'longpolling': 18,//普通长轮询
        'send_group_msg': 19,//群聊
        'sendmsg': 20//私聊
    };

    //聊天类型
    var SESSION_TYPE = {
        'C2C': 'C2C',//私聊
        'GROUP': 'GROUP'//群聊
    };

    //最近联系人类型
    var RECENT_CONTACT_TYPE = {
        'C2C': 1,//好友
        'GROUP': 2//群
    };

    //消息最大长度（字节）
    var MSG_MAX_LENGTH = {
        'C2C': 12000,//私聊消息
        'GROUP': 8898//群聊
    };

    //后台接口返回类型
    var ACTION_STATUS = {
        'OK': 'OK',//成功
        'FAIL': 'FAIL'//失败
    };

    var ERROR_CODE_CUSTOM = 99999;//自定义后台接口返回错误码

    //消息元素类型
    var MSG_ELEMENT_TYPE = {
        'TEXT': 'TIMTextElem',//文本
        'FACE': 'TIMFaceElem',//表情
        'IMAGE': 'TIMImageElem',//图片
        'CUSTOM': 'TIMCustomElem',//自定义
        'SOUND': 'TIMSoundElem',//语音,只支持显示
        'FILE': 'TIMFileElem',//文件,只支持显示
        'LOCATION': 'TIMLocationElem',//地理位置
        'GROUP_TIP': 'TIMGroupTipElem'//群提示消息,只支持显示
    };

    //图片类型
    var IMAGE_TYPE = {
        'ORIGIN': 1,//原图
        'LARGE': 2,//缩略大图
        'SMALL': 3//缩略小图
    };

    //上传资源包类型
    var UPLOAD_RES_PKG_FLAG = {
        'RAW_DATA': 0,//原始数据
        'BASE64_DATA': 1//base64编码数据
    };

    //下载文件配置
    var DOWNLOAD_FILE = {
        'BUSSINESS_ID': '10001',//下载文件业务ID
        'AUTH_KEY': '617574686b6579',//下载文件authkey
        'SERVER_IP': '182.140.186.147'//下载文件服务器IP
    };

    //下载文件类型
    var DOWNLOAD_FILE_TYPE = {
        "SOUND": 2106,//语音
        "FILE": 2107//普通文件
    };

    //上传资源类型
    var UPLOAD_RES_TYPE = {
        "IMAGE": 1,//图片
        "FILE": 2,//文件
        "SHORT_VIDEO": 3,//短视频
        "SOUND": 4//语音，PTT
    };

    //版本号，用于上传图片或文件接口
    var VERSION_INFO = {
        'APP_VERSION': '2.1',//应用版本号
        'SERVER_VERSION': 1//服务端版本号
    };

    //长轮询消息类型
    var LONG_POLLINNG_EVENT_TYPE = {
        "C2C": 1//新的c2c消息通知
        ,"GROUP_COMMON": 3//新的群普通消息
        ,"GROUP_TIP": 4//新的群提示消息
        ,"GROUP_SYSTEM": 5//新的群系统消息
        ,"GROUP_TIP2": 6//新的群提示消息2
        ,"FRIEND_NOTICE": 7//好友系统通知
        ,"PROFILE_NOTICE": 8//资料系统通知
        ,"C2C_COMMON": 9//新的C2C消息
        ,"C2C_EVENT": 10
    };

    //c2c消息子类型
    var C2C_MSG_SUB_TYPE = {
        "COMMON": 0//普通消息
    };
    //c2c消息子类型
    var C2C_EVENT_SUB_TYPE = {
        "READED": 92//已读消息同步
    };

    //群消息子类型
    var GROUP_MSG_SUB_TYPE = {
        "COMMON": 0,//普通消息
        "LOVEMSG": 1,//点赞消息
        "TIP": 2,//提示消息
        "REDPACKET": 3//红包消息
    };

    //群消息优先级类型
    var GROUP_MSG_PRIORITY_TYPE = {
        "REDPACKET": 1,//红包消息
        "COMMON": 2,//普通消息
        "LOVEMSG": 3//点赞消息
    };

    //群提示消息类型
    var GROUP_TIP_TYPE = {
        "JOIN": 1,//加入群组
        "QUIT": 2,//退出群组
        "KICK": 3,//被踢出群组
        "SET_ADMIN": 4,//被设置为管理员
        "CANCEL_ADMIN": 5,//被取消管理员
        "MODIFY_GROUP_INFO": 6,//修改群资料
        "MODIFY_MEMBER_INFO": 7//修改群成员信息
    };

    //群提示消息-群资料变更类型
    var GROUP_TIP_MODIFY_GROUP_INFO_TYPE = {
        "FACE_URL": 1,//修改群头像URL
        "NAME": 2,//修改群名称
        "OWNER": 3,//修改群主
        "NOTIFICATION": 4,//修改群公告
        "INTRODUCTION": 5//修改群简介
    };

    //群系统消息类型
    var GROUP_SYSTEM_TYPE = {
        "JOIN_GROUP_REQUEST": 1,//申请加群请求（只有管理员会收到）
        "JOIN_GROUP_ACCEPT": 2,//申请加群被同意（只有申请人能够收到）
        "JOIN_GROUP_REFUSE": 3,//申请加群被拒绝（只有申请人能够收到）
        "KICK": 4,//被管理员踢出群(只有被踢者接收到)
        "DESTORY": 5,//群被解散(全员接收)
        "CREATE": 6,//创建群(创建者接收, 不展示)
        "INVITED_JOIN_GROUP_REQUEST": 7,//邀请加群(被邀请者接收)
        "QUIT": 8,//主动退群(主动退出者接收, 不展示)
        "SET_ADMIN": 9,//设置管理员(被设置者接收)
        "CANCEL_ADMIN": 10,//取消管理员(被取消者接收)
        "REVOKE": 11,//群已被回收(全员接收, 不展示)
        "READED": 15,//群消息已读同步
        "CUSTOM": 255//用户自定义通知(默认全员接收)
    };

    //好友系统通知子类型
    var FRIEND_NOTICE_TYPE = {
        "FRIEND_ADD": 1,//好友表增加
        "FRIEND_DELETE": 2,//好友表删除
        "PENDENCY_ADD": 3,//未决增加
        "PENDENCY_DELETE": 4,//未决删除
        "BLACK_LIST_ADD": 5,//黑名单增加
        "BLACK_LIST_DELETE": 6,//黑名单删除
        "PENDENCY_REPORT": 7,//未决已读上报
        "FRIEND_UPDATE": 8//好友数据更新
    };

    //资料系统通知子类型
    var PROFILE_NOTICE_TYPE = {
        "PROFILE_MODIFY": 1//资料修改
    };

    //腾讯登录服务错误码（用于托管模式）
    var TLS_ERROR_CODE = {
        'OK': 0,//成功
        'SIGNATURE_EXPIRATION': 11//用户身份凭证过期
    };

    //长轮询连接状态
    var CONNECTION_STATUS = {
        'INIT': -1,//初始化
        'ON': 0,//连接正常
        'RECONNECT': 1,//连接恢复正常
        'OFF': 9999//连接已断开,可能是用户网络问题，或者长轮询接口报错引起的
    };

    var UPLOAD_PIC_BUSSINESS_TYPE = {//图片业务类型
        'GROUP_MSG': 1,//私聊图片
        'C2C_MSG': 2,//群聊图片
        'USER_HEAD': 3,//用户头像
        'GROUP_HEAD': 4//群头像
    };

    var FRIEND_WRITE_MSG_ACTION = {//好友输入消息状态
        'ING': 14,//正在输入
        'STOP': 15//停止输入
    };

    //ajax默认超时时间，单位：毫秒
    var ajaxDefaultTimeOut = 15000;

    //大群长轮询接口返回正常时，延时一定时间再发起下一次请求
    var OK_DELAY_TIME = 1000;

    //大群长轮询接口发生错误时，延时一定时间再发起下一次请求
    var ERROR_DELAY_TIME = 5000;

    //群提示消息最多显示人数
    var GROUP_TIP_MAX_USER_COUNT = 10;

    //长轮询连接状态
    var curLongPollingStatus = CONNECTION_STATUS.INIT;

    //当长轮询连接断开后，是否已经回调过
    var longPollingOffCallbackFlag = false;

    //当前长轮询返回错误次数
    var curLongPollingRetErrorCount = 0;

    //长轮询默认超时时间，单位：毫秒
    var longPollingDefaultTimeOut = 60000;

    //长轮询返回错误次数达到一定值后，发起新的长轮询请求间隔时间，单位：毫秒
    var longPollingIntervalTime = 5000;

    //没有新消息时，长轮询返回60008错误码是正常的
    var longPollingTimeOutErrorCode = 60008;

    //多实例登录被kick的错误码
    var longPollingKickedErrorCode = 91101;

    var LongPollingId = null;

    //当前大群长轮询返回错误次数
    var curBigGroupLongPollingRetErrorCount = 0;

    //最大允许长轮询返回错误次数
    var LONG_POLLING_MAX_RET_ERROR_COUNT = 10;

    //上传重试累计
    var Upload_Retry_Times = 0;
    //最大上传重试
    var Upload_Retry_Max_Times = 20;

    //ie7/8/9采用jsonp方法解决ajax跨域限制
    var jsonpRequestId = 0;//jsonp请求id
    //最新jsonp请求返回的json数据
    var jsonpLastRspData = null;
    //兼容ie7/8/9,jsonp回调函数
    var jsonpCallback = null;

    var uploadResultIframeId = 0;//用于上传图片的iframe id

    var ipList = [];//文件下载地址
    var authkey = null;//文件下载票据
    var expireTime = null;//文件下载票据超时时间

    //错误码
    var ERROR = {};
    //当前登录用户
    var ctx = {
        sdkAppID: null,
        appIDAt3rd: null,
        accountType: null,
        identifier: null,
        tinyid: null,
        identifierNick: null,
        userSig: null,
        a2: null,
        contentType: 'json',
        apn: 1
    };
    var opt = {};
    var xmlHttpObjSeq = 0;//ajax请求id
    var xmlHttpObjMap = {};//发起的ajax请求
    var curSeq = 0;//消息seq
    var tempC2CMsgList = [];//新c2c消息临时缓存
    var tempC2CHistoryMsgList = [];//漫游c2c消息临时缓存

    var maxApiReportItemCount = 20;//一次最多上报条数
    var apiReportItems = [];//暂存api接口质量上报数据

    var Resources = {
        downloadMap : {}
    };

    //表情标识字符和索引映射关系对象，用户可以自定义
    var emotionDataIndexs = {
        "[惊讶]": 0,
        "[撇嘴]": 1,
        "[色]": 2,
        "[发呆]": 3,
        "[得意]": 4,
        "[流泪]": 5,
        "[害羞]": 6,
        "[闭嘴]": 7,
        "[睡]": 8,
        "[大哭]": 9,
        "[尴尬]": 10,
        "[发怒]": 11,
        "[调皮]": 12,
        "[龇牙]": 13,
        "[微笑]": 14,
        "[难过]": 15,
        "[酷]": 16,
        "[冷汗]": 17,
        "[抓狂]": 18,
        "[吐]": 19,
        "[偷笑]": 20,
        "[可爱]": 21,
        "[白眼]": 22,
        "[傲慢]": 23,
        "[饿]": 24,
        "[困]": 25,
        "[惊恐]": 26,
        "[流汗]": 27,
        "[憨笑]": 28,
        "[大兵]": 29,
        "[奋斗]": 30,
        "[咒骂]": 31,
        "[疑问]": 32,
        "[嘘]": 33,
        "[晕]": 34
    };

    //表情对象，用户可以自定义
    var emotions = {
        "0": ["[惊讶]", "data:image/gif;base64,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"],
        "1": ["[撇嘴]", "data:image/gif;base64,R0lGODlhGAAYAPf/AP/7mv7nT//+0P/tV//aOf/pT//SLv/XNP/+x//mSP/5iP/9uP/0bf7ePtKMGv/3eP/3d//cOv/KJf/iQv7FIf/8sv/PKv/MJ//2dNKOH/+7FOOWCv+2D/20EP/4e/7hRf/gQu7Wdv3dQ+7Vb/vUONqNC//LKe7MQu7FN/TCLv+8FP+3Ef/6lvGsEuumFvCjCtedMeafEP3fRdKOItWOEc6EDv+4Efi3G/myE+SYCrhfAMp7EP/vWoczAP/xX/bm0NfRzOi0Mf/lR//FH3wtANfSzf/qUP/wXf/LJv/DHf/sU//GIP/7lf/VMf/AGv/RLf/pTfv6+tzX0+Pf3P/SLf/oTF0jAP/dPd3Y1Orn5NKPKf/rUP/ePP/AGf/dPP/rU8urmdKNHP+5Ev/DHP+5E//UMf/GIdKMGf/ePf/89eXDau+zMNKPLdixa713FPjKX8t9CPXBS/fIW+fe0P79/P3XOcmphriESf+6E/bGWfe8N+7CUtLFuP38/P/+/PfKZ+bi3/CwH+24S9KPJv//1/jbmv/899/a1tjTzq5wLO7TZdKLGPPesMivltiVFbd7MruRZfzPNPvhpfDu7NKWM+G/jNeeOMeCFrFzK/Ty8erj3KxtJfvLMPrNN9eaI9ecKtmYIf346/nEJfry5/fGKvW3KfbEL8iQPsCXZ/bYmeGgIeWmJvHIWN7Z1ffBRb1/LvuvDO7Wg8KKPujl4vzw1u65Nt2cGN2cH+CfJM2sg+3HU6hdCfW5M+ro5enIkbNvGuTg3dylRP/xYP/oTcuJGs2JH/zlr//bOdmkQsyLIqdgEKpjE7SAR7eCRcWebfncnPOpDbmIU/jGMPnGMK5mD8J8FNnUz86ACPOxF//3e+aqH//RLv7JJfSnC//gQf/CHf/HIPjHMf/wXP/1bf/9sv2yDP/xXf/3fP7dPv2xC//2c//QK/mzFPSnDPi4Gv/HIf7JJv/uV//pTPOxGO7JPf/ZOeaqIP/qUf/CHP64E/65Ev65E+7DM//UMP///////yH/C05FVFNDQVBFMi4wAwEAAAAh+QQFDwD/ACwAAAAAGAAYAAAI/wD/CRxIsKDBgwgTKkxj7I2rUrzi/HlGS+FASXo6ifjgDV2dSNJYMVJoSM60AFCGpUwwIUI/TrX2IPSTJ9yXHvGO+DgSz0gPLk24BdJ1sJCpAj2EjYPgAcI4HzcjPKGAS03BUGtk9AizY8YIAEwUZdjhoMcBJDeQzSGYKsXWH/5+aFlAbhDcH4t6WBjjok2UgYJIFNjhr/AOAQgIG76ybQg7SrMGrroChTAYIjsICbCMGY2BJfmISRmo6kqVMD+IWGGDWEtqK2fQUBki5lKRgbeOJTjxqZilWHRDwMjgCcUxC0lsVAMyENSBCVt8qFPABKwCDMK2TDjbpYMb5gKDkbmKIESJj3EPzjmFKkTqOw3dXt0W6EtbGS5CjOTc2VOIlyZIOMFBCc5gMdAoyYhCgA5BBKEDgw4yeME3ZLzwCB+9EFSJLUjw8CAPHuoAog5JaJAONagc8tdAdJziiBkgUmEAiEiA4wQesFxzRxFZHCQLDfOYMMQSQ3zThQoc5LBMNEBMkVAuv9QQQwtidADNBnAk0og1gFikiR3NYLKLMpswAwkQrWRikUB9TDIFFkAgIgUwPa5pZ0EBAQAh+QQFDwD/ACwDAAMAEgASAAAIhQD/CRwoEEQDgggH9vhXpUCVgU0SCuxx75+5gVsEloGHsIcSHwKzPfgnTMm/Y1QI9qAnsSVDlwTXJYGJ0MuTfzhoJtSHRuc/LzQrSByzQqCRfxgS+jgq0InAYwQheBhoUqAZFQKbXBE4QNw/rwK5/JOAsB7NmQTJwsTjUyc2gjM1tCXjMiAAIfkEBQ8A/wAsAwADABIAEgAACJAA/wkcKNAbwYMEAwgcNrAHQoHSvjg8InDAPyE9mnA7WMAhgwcDfVx0SFAGyYEsBp7h59CdwJMPBR6zgC+mzX/s/jH85++mwH3/5Pn8x2XbUIRJbAzdMrBLuX8R/n0hCJKggSUa2tkr4+XflngUKQ4sg+QgAZ9jDpa1OUYDwiUCnwy8YKbL0CECnag4+k+MzYAAOw=="],
        "2": ["[色]", "data:image/gif;base64,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"],
        "3": ["[发呆]", "data:image/gif;base64,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"],
        "4": ["[得意]", "data:image/gif;base64,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"],
        "5": ["[流泪]", "data:image/gif;base64,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"],
        "6": ["[害羞]", "data:image/gif;base64,R0lGODlhGAAYAPfPAMqSQP/fRf/9yLmFSueZCplDA8h9Ef/qUKBPCPv06NaiSf20Ef+KU//2dPzjqv/wXf/+/dfSzfjIXP/+0P/lSern5P/5h/Xz8uqlFv7ZOfjamv/PK9y0bf+yd9KEFOi5N0oiCuWpJdTHu/StEcSca/+5E93Y1PnHK//WNP5uL/95TSINAO+6S/bJZ96XFP/oTdmNC/jGMOvJleKkIrZwFv/SLeji3f/uWf/9suquI//7mv6yDf/3d8mFGP/LJv/ePb6TY//lav/8uf/1bfW6Nf/hQrR1LP3UOP/rdqtkE/GjC/+nJv/DHfPdr//XVP+caf+mS//lR//7lf/VMeKyUv+kOv+3Rv/nif/89v/GIP/lnN6dI8eZP+TDjNGXN/zZP9GSIv/TZP/KMOfd0f+YQOCfIbFpEP+JKP/cOs6BCf/uV/+/Gf+qWf3QMePf27VkC//tvP+aJvnCJvWpDfv6+v7TNOCoRvyvDejAZP6GOOi+UOiqLf6zIv+8Icqxlv/BOP/7oP/LOf/EH/+3VP+7Mv/0p//AGv+zGv/Kcv/VfLl+M//iVf27K//pV//rU//DQv/NS//dS/6xK/fLN/SzGP/KQf/iSP/3e8ZpKLRzHL2BLv/ETMWBPP/xqf/xX/rFJv/BIP//2P/YSP/3nP+2MuivMdCvhNKZJr54FP/UQf6hE+fjh//CKtKKIatWHOS1XP/HJv/wkcBxEseVLP/4rseNKOy8WM68qPzw1Mile/fEMOm2Ua9tIfC1MfS2KfW7JvbARPTAS+SsKue0MOfZVKpaC//XNP/7+//Mi+yiIOenKf38/LdtJv/Om/+xP////wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQFMgDPACwAAAAAGAAYAAAI/wCfCRxIsKDBgwgTKsTiQAIwX0SCtdCAS+FAB0RiHMnwI0ObE79sNVkoIUaAFwcOvHgRpaOcPbsQQpCg64AjNQ88PVBzIEoRjzmoHNSgy9EHTx94XOIxBCmFHDVQbOFQMEGvYQdoNKChQ4cUPVuzGvMhR8EYghomgfFQoIAHPEJweGHrNscGJhg40BnI4guNBCtWyKAxQcDfwINrCFrgxcZAZQFoQAABAgKNUBMkU7ZcI0uJHiYGzvghjJYAAaFeFaZymlahEIo/RxhYBg0FNUMsAMIRF5CFIWoooLlbwsBsgVtQFDngqYEFKV4tNPB0oMhYQztQHX9m5wSaKI48DbpRytSToyhoOpeYo2m7DKg/ohy4kXNnzx9TfBhaAINEaIEJgPHJFGgUQcFKL1BQBBr5MVGCEoqIUAFBXeTgQw3GoPHDhmgYU4MPDu5ghn97DQQBACFk4UMABiCAgAEBZGFICXekMUAEExoEwAyUMBGACy4EsEYJJRCQxI1uJGRKLTCMEEAAC8xBQBpG+BGBYwrZkIsRMMBAAy8DABGBCRdYJNAyYwABhB+3iOBGjmYOREcFFVxwQYkGBQQAIfkEBQoAzwAsAwAGABIACgAACHoAnwl09GyIwIOOoqB5VucgBUeenlmQItDCs4hFDgqkoGaIBUA4BAKyMOQGhYUH33zgIUWBLIEe9PB4cKrYwSLDCugsoEAgnp0FND7L9CCJlCQTBOJIwiPJiyRgmGjk8ozqQaqzogg0JFSgjq4aF4Adq3HKQTUHb4wNCAAh+QQFMgDPACwEAAQAEAAKAAAIcACfCRw40BLBgwgTCvzwjCHBDxRyPEPxbNgBGs9o6BCoJ6NHgWA8FCjgAY9ALyJJSsyYYMUqGRhZriAGUyCNZStAQIhJ4xgInQIFCaPVQECoVwKpCBBSqFCIDYKe3RjyDBBBC8/UUBDI5KAUhc/WBAQAIfkEBQgAzwAsBAAEABEADAAACDkAnwkcODAKQYIxDio8eGDhwToOI0qc+GzFQosHIYBQCCGigIUCcHR6VkNgFDUSDVJcuGMlRZQLAwIAIfkEBQgAzwAsBAAFABEACwAACDsAn+l6RrCgwYPPHCF89mChw4cQIa5aSKxgFoLLViA8dvAiwQYLhXQqZJDCDYgUCBqKeNDQApYwTy4MCAAh+QQFCgDPACwEAAQAEAAMAAAIfACfCRw4kALBg89uIFzo6NkQgp4cRUFT0JGnZxakCLTw7GKRgmqGWACEQyAgC0PUUKD47M0HHlIUyBLoQQ+PB6eKbXg2rIDPAgoE4vlZYGCmB0mkJJkgEEcSHkleJAHD5JkaLs+wDsQ6K4rAqs8eCNSxUOCasmVrLFSzMCAAIfkEBQgAzwAsBAAEABAADAAACHUAnwkcODAKwYExDio8+OFZQ4IfKOR4huLZsAM0ntHQIVCPxo8CwXgoUMADHoFeRpacqDHBilUyMrZcQSxmDUE0lq0AAUEmjWMgeAoUJIxWAwGhXgmkIkBIoUIhbj67MeQZIIIWplI46EmglIVgwwqcsvDGwoAAIfkEBQgAzwAsBQAFABAADwAACDQAnwlUI7CgwYMIEypcyFDhioQPDUIAgRCCQgEJBdA6SFBhx4YJd4Bs+LFgiZEoU6Y0lDAgACH5BAUIAM8ALAUABQAPAA8AAAg1AJ8JHPjsBsGBBw4qfFZnocOHD1ctJEZw2QqFxw5medZgoZBOEB2CCkmypMMFJlOqdLhGYUAAIfkEBTIAzwAsBAAEABAADAAACDIAnwkcOJACwYMH1SAU6Gihw4cQIxJcsZAiQQggEEJwKGChAFoEo0C8MZCJxJMoHZYICAAh+QQFFADPACwEAAYAEQAKAAAIdQCfPXP0bIjAg46ioDn4jMIzT88sSBFo4eGzIgwpqBliARAOgYAsDFFDYaHANx94SFEgS6AHPTwenCq2QeCwAjgLKBCIJ2cBhpkeJJGSZIJAHEl4JHmRBAyTg1yeRYX6bNZBQwcfCNTBsOuzBV7DimV4wyvYgAAh+QQFFADPACwDAAQAEgAQAAAIWQCfCRxIMArBgwgTKlzIsKHDZzceJqwhcWAbgh2eaUGI5FnGVoKefZHQgVMzjpyePLECSqAoRGGQxJJC8MEiCZv+PNsh0FKjBwgbWfpyMWHEikiTKl1oKGFAACH5BAUUAM8ALAMABQARAA8AAAh+AJ8JFHhjoMGDCBM+Q6Gw4cCCDhEiiYWwQSOBGyIeFPMsw7Mnz9g8gyOQ1rMWzxgIBJVhEANMDDhpMTkKwCBMKhhU6VMHEhsobBBdGSUwyKYqZKqQ6rMQEiQnQRo8dFLpzx9QAutkCLCoEUQ1zyxlaANrDcIoGtOqXfvM7MGAACH5BAUUAM8ALAEABQAUAA0AAAi/AJ8JfKZLoJqBNwYqXMiwYZ2GEAeiYBilkcBRDBtZyhDxyrM3AmU9Q7JoIKiBP0opHDBBwMIkz04KPFLsGYIOCBzgEtKpJoJnxWj0eQbhWSWBrp4kQdaJVixFz1w9U/GMz4KiYqxAIUOGTaIrsZCEsUImD5klfAbKCfTIGZtBYZA0QOLkUZUqcSQtUCIwhBi2lSAtanSjUaRKfyTxObRDoRwxdVKJsoTyiBhQfRYwhLWhTgY0AjOg2ADr5B2BAQEAIfkEBRQAzwAsAwAEABEAEAAACI8AnwkcSNASQYIxBN4YqOagw4cQIz6k4AiJxIPDgkQEI1DQl4sDWQlEIPCJQDgCniGQMBLVs0BsVDxj4OqZg07PjEAZSIZnHgZkEBGEkidFnmeHnv2xUqUKlBYaNVp5dubZkqRK/zza5OTZg0UCSS1ZcjCQwEjPKFgK0OYZI6wOfwhE40KgoWc7QOodePdgQAAh+QQFyADPACwBAAQAFAAQAAAI5wCfCRxIUGCUggh1IbyBsKHDh88sPXvQkGKAhpYOPLv0jJbAUQIXBTjiA2EpgQAI7griRKAYUANTKSPICdezVwSLMeqzAEKZQM8YBH3W7BkcLc+gPFNRjAafBce2/CGTAkGKZx00aEnELM+zFCnyLLlz7BkpqmBVsEl0JdGgPFdTnBlblk8VuGGhhAkSxkocsHJVDUwmKc6ZPHmcOVnk5FGVM5BVCRbYio+kKkucPRIVQFQgUnEMqyJAUFkfRoT+JPuSIcSbSnwkq3oDRGDZGX1YpRr4RhSoQzsmE4Qw0BDBNc92EBwTEAA7"],
        "7": ["[闭嘴]", "data:image/gif;base64,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"],
        "8": ["[睡]", "data:image/gif;base64,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"],
        "9": ["[大哭]", "data:image/gif;base64,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"],
        "10": ["[尴尬]", "data:image/gif;base64,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"],
        "11": ["[发怒]", "data:image/gif;base64,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"],
        "12": ["[调皮]", "data:image/gif;base64,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"],
        "13": ["[龇牙]", "data:image/gif;base64,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"],
        "14": ["[微笑]", "data:image/gif;base64,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"],
        "15": ["[难过]", "data:image/gif;base64,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"],
        "16": ["[酷]", "data:image/gif;base64,R0lGODlhGAAYAPf/ANWiOtWkVP60Dv/89v/cOvjGMP+5E//XNf/1beXEav/wXdnUz//VMbhrEv/wX2loS/+7FDIsDf/GIOSXCv/aOf/DHf/tV//pT//9sf/ePf/AGv/FIP/+0P/6lf/KJf/4e//5iP/3eP/3d//dPf/EH//9uQ4LA9ShMtSfLP7hRf/gQf3dQ/7dP97bgv/TLv/PK//LKdXHSvfGK/y1Ef2xDPGsEtSmRvCjC9ilGdqOC5+SM7+HEnJuNmZjLmZaGxgUBdfRzP/fRf/lR6WclKpSBr5pCdfSzf/sU//RLf/qUP/mSL2Xdern5OPf3NzX0/////v6+t3Y1I5lQf/iQv/LJv/oTP/pTf/oTf+3EP/xX//uV8t+CPe8N9LFuFdXR65wLPncnOG/jOrj3NOYEPWyEs2sg+fe0P+/GcKKPlpZNv/ePr13FKxtJfbEL/XBS5SMhffIW8iQPvry50g4CYV+ePW3KeGgIe24SyQiDLiESfzw1suJGqdgEMyLIu7CUntvI86ACP/7mzkyDqhdCf/hQrSAR/jbmrd7MvPesOC0N++zMPjKX/fKZ/3XOcivltiVFf/TMOnIkfOpDYczAPTy8bmIU5OQTruRZcCXZ/Du7PbGWczHbdmYIZyTOv38/P79/Pi3GvnEJfvhpfzlr65mD//MJ+afELFzK+HZZuWmJvvLMPrNN/zPNP346/C/JMJ8FP/+x+ro5cWebdWOEfbYmfHIWG9tRv//197Z1d/a1v7IJXVwMmZeIPfBReCfJMeCFvuvDP7nT+jl4t2cH/3fRMmphu65Nu3HU/W5M/SnC/CwH86EDr1/Lv/2dGZgJrNvGtixa+Tg3ebi3//+/PzfQf/qUY13Hf/rUfPRNtmkQtylRKpjE9KWM7eCRbVpHeC3PTw4Nfz3k3p0bv/rU//8mtejTdKUINKYJeaqH+XFW+aqIP/3e/OyGO7YbeCoJOC7T7hsGO7Zgfn4woRzIMaHK92bGN2cGMaJNf/XNP/7n9ahQbVpGf/RLv/SLQAAAP///yH/C05FVFNDQVBFMi4wAwEAAAAh+QQFFAD/ACwAAAAAGAAYAAAI/wD/CRxIsKDBgwgTKhwwalGvOsjcMAKjR+FAUVxWrUhBiEUjVgVqIVoIp0CwK1esWFFCiAADVcb8IJymqcC4JFoUZFGgJYmQDAx0KTt20FCbC4lOIBDx4R2CLEfOueO3wVeCgq0UEUuSBZ6NDuTugWh2ogEhfFRAZTNDkJYJf3h2bQpAJIG3eEQAxPgTwZ+/HdCgDLzzw6/fB3RhEdHHw7DfMdyEDUwlyPASKV5ucSiRxp+UJYbJ7HEy0M48y08c+30C2t8cA7+MDByGra/jBxhsqf6BA8srIAM54aOmw1kPSy0CdQCBqhMvH9ZcnRGwBrhAbTIICDmSBUGIdSKeHrYRQgCJBAjJmMkWGEkdgwxCruXc2fMnAyoasOSQFWWgnD6hUEDAFEpUYcUVSgThUikVGHDDIV3EQlAY9VBxAD4UjKBGEUUEgQQVFUBAAymY5CLYQJ/E8YgEkLyARD9FTBKEBBoYAAwgeRjBxEFozMIODCRsEEQQZ0CAxQTbVAJEEwmV8cwyptRgwAySTLDFF44sII1FYhTTzSmD8MFGIZcAgQslFgnkSSZNRAHEAk5Es2OadBYUEAAh+QQFFAD/ACwDAAMAEQARAAAIhAD/CRwoUAXBgwQvVPm3cArCgYlQDPxmodo/c+4QHmkAQCC9fw5QNCCABGGzf0TS7WtH5F8Shw/zlSOCgQgABA+HgBMoj8M/DOEGihsy8M0QojmHvCFIBynCIXQeSp1K9Z+Ghx9CHOwnEN1UiyP+eZh6oUgRqfYGEqj60AXbgRsGXj0YEAA7"],
        "17": ["[冷汗]", "data:image/gif;base64,R0lGODlhGAAYAPf/APzjqm0pAPymN//rXf+0S+WnJv/4h93Y1Ojj3f7TNf7dRPjGMfx6Rfz16cOaappFA//wXdaDFfvNeeqYKvjamv/qUPu0ErBqFv96a7dlDf/ubdubKfuuDf/oTf/lSvfKZ/+ZMdLFufbm0P/+x9y1bf/6nODEVv7aOf/2df7VYern5NuZIP+5E//8suCoIteZM7aCR//89vSVF8+hMP/DHbl3TtfRzMOKPcurhf7ZQPa7NenTxv/SPP/+0f79/J07AP/RLs6CC/+/UMitlf/IMfOfMP/dPf/6lcSIJv/SUvbicLuQZv9rTqhhEsN8FP+XTf+1YfbLNf+bJeumFvfLX/+SYvflmKlbCf+mKf/3eNulQ/+DN/fIW/qwLu24S6FIBf9ZXMmDG/CwH/6MK/+PXP/FIP/1bf/MOP+LRv+8PP/gQtnUzv/LJ9ChiL51E+LGtOSXCv/cTv+hXf+/Gv/OTf/7oP/dUfrUeP9zU8t+Cf/NL//OK/+yGKFTD/6jG//lR//FXP/9uPnCJv/CKP+5Iv/uV/22Mv2rEf/nTP+tUvfBRchaHP+wPP+wJv+JU6tYJP/HS8eCFuK9jOC1MfKqD//FKP/BIf/jTf+iQt+gIfbeW+C8Pva8Jv+nc7t4NeafEP+rTv9zXf/mXtGPGPOxGP9fZf+2D//pWP97Xv/aXP/TTu3SrOPf3Pv6+axtJa5wLPbEL/Ty8enIkfDu7O+zMPXBS+msLfW3KciQPvzw1vXGWbeBMO2+WLFzK7d9IfvIK/nFL7mHVP/FQPPesOm4Uv/4e9CFJ/9gaPaAVuKyUv+vR9qymuje0L5/LsuJGv/xX//jRv/rU923obBjELJrQPm6Ke3Cb+WFRe2jS+fIqLlQEOnTvO3IcNWqka5mO/bmsLJmJ/LEMP+xWv/DOPNxOPvjff/bOd6HEP++Mf/LR+JaN+2WNOihJPO/KP/0lP/RYOa6MP+3cdnOx8SVgf+oZLlPFblXC/rDJv6nF+2AO/+qHP+kSf37+oczAP///////yH/C05FVFNDQVBFMi4wAwEAAAAh+QQFCAD/ACwAAAAAGAAYAAAI/wD/CRxIsKDBgwgTKowBgIqiWzpqfaCQS+FAADqAJThh5ASQX5x4DVvIBZiCDhVSIoKm5sQ9W8QQ+tC1oEK0QhCeQShU4U9LIGKSHaQAK9qmfv12Fcti5pkvpC4SZCJRsAEtDzP6mTBwpMQRAyieVZjUb5QgLcwIUojioZAZA3VaBGpRx4CZQn/M7aExhUSrgV5ydICQ5UiLET1GtDiSBUIHI0DKWHiBYGABNR2eZSkRqIfnQCWKPXsc2YKzAwMzGfEwAIW7w4kXoxhwKYceGiwi2UjNI06qaRKsULjSosS0FKkUTUNHyIKT3QJXnIEkBNuXAH24fdX1APs6AVgOucWB/k9LNQKg6NGDIqFcFg2iACV68gSEHxnNyMtil0ZZInGAvCMKBKfYkQ4BmIBg3zkOoCZQA8ZUM44w6agSxyUd/KEAD+MY0og+MngSggoESVJAJXpslIMRHSUARCVz8MHBNA3+NZAPuLhQBht7AAEEUmyUMQcLHAQBgw0kGnRDJqTQUAZSSLFgChxNBGMDKwnhgEQQn1DCggWUwJHHK0OsUZlCCOAAQy9XNOEKDEvYcEAsFgnkwyysHGDDGgewkmSdgBIUEAAh+QQFCAD/ACwCAAQAEwAOAAAIngD/Cfy3QM3ADgMTKly4UA9DhSgeSpw48A9FDxQzDoQgEQKihZdEfRkZ4IHAkQ9K/rP0b8U/VUkubPMXIINAN8xoZjDUiM+/cQTkXLvy5Z81gcmIXsmHRgq+f132OUKFoUq8OwJTiCODBw+DMQInMHpChgw9QCkExiHwxBGDLVIGdklDQAgkVXb+YUxniBEWPwuJ6OGhcM8/Qv8OCQwIACH5BAUIAP8ALAMABQASAA0AAAiTAP8JHEiQIJCCCLMgXMiwocM/DiMOfEYFSZ8m3wSWC9OnD7x/NFgMDCMiwL8wAkmaDENkIB1AYVb9W4XypEyajASmAUUG2YUHFyQIpPLzAgMGIPwIeBKq1DEMnSSg+PdOTigwYJhsEYjJESpUVaC8E5gkkSM8TBiMiSCQkTICQpIMzCGMkQAQUggO+ncGoZ69BAMCACH5BAUIAP8ALAYABAAMABAAAAhKAIEp+EeQ4J+CCBMW1KOwocOCmjZEeGGFoBKJK6KEEREgQBiCGzuGyeCvYwaCJE2S9Ofv5L+VLRXWeUizZsEUNePY3MnzHwuCAQEAIfkECRAA/wAsBAADABQADgAACFcA/wkcSHDglzYFBS5QUxCaQEH/vjxCmLCiQCBOutXoZtHijx3+MuzomPCHP387fvDzQFJgOJEof7RqOTDTxwzz/iGiKXDUDzfyeAoVWGKo0aNIW9rhGRAAIfkECRAA/wAsAAAAABgAGAAACP8A/wkcSLCgwYMIEypcyBCADmAJThg5AeQXJ17DFMbgskBNhwoVOiCCpubEPVvGWiHUtaBCtEIQnkEoVOFPST0/mvE7CCvapn79dhXLYuaZL6AuMv14pMKghxn9TBg4UuKIARTPKkzql+HNmx8G/xTStCHCCyst6igpuyLKD3/+li3xQbADhDAiAgQIM6IFXr1h3sJ9tHNgh2cZ/OnN0CNQ4sWC/YEleElUYrgZ+l72l2HUj2w/lhSkAyjMKn+rwrQoYRp1GEYZfmSwURAUGWQXHlyQYJVK7gsMGIDw44Y2wSehSh3D0EkCCg3v5IQCA4bJFhnNjA/E5AgVqipQUkA0OJUkkSM8TBiMieDgQMEujJQREJIkTgcPOYQxEgBCigxPITTF0IAEFpiQKQYmqOCCDBIYEAAh+QQFEAD/ACwAAAAAGAAYAAAI/wD/CRxIsKDBgwgTKowBgIqiWzpqfaCQS+FAADqAJThh5ASQX5x4DVvIBZiaCig7IIKm5sQ9W8QQ+tC1oEK0QhCeQShU4U9LIGKSHaQAK9qmfv12Fcti5pkvpC4SZCJRsAEtDzP6mTBwpMQRAyieVZjUb5SYL28IUojyp5CmDRFeWGlRRwncFVH25JEGrtVALzk6QAgjIkCAMCNaEDYcpt0Pfz/WDCygpsOzDP4MZ+gRCLNmN8v8LasxMJORS6Iw+/OXIbFq1o9X/xi4ggcdQGFW+VsVpkWJ3LvDxIZMOw0oMsguPLgg4SsV5RcY1Hvj7423gVoEPAlV6hiGThJQaLl4JycUGDDqtP34MmSgrAmYHKFCVQVKCginkiRyhIcJgzEROHDAQA0Y0wUjyhAgRBJxdOBBDsIwIgAIUsjgSQgqECRJAYMQcQYPORjRUQJ6DEIIH4dMI6BfA/mAiwtlsLEHEEAgxUYZc7DAQRAw2JChQTdkQgoNZSCFFAumwNFEMDawkhAOSATxCSUsWEAJHHm8MsQaCFiEAA4w9HJFE67AsIQNB8RikUA+zMLKATascQArP65pJ0EBAQAh+QQFEAD/ACwEAAQAEwAOAAAIYAD//Vvwr4PACv/+CFzIsKHDhxAjSlwIgaEgLcz+eZi4kMYUEhwXAilj4QWCkP+AkPri5ABKInmkXbGBssiPfz9CoFy07N+yGih/+Bt6M6RQoijtvfH3hhrKc19+UHsTEAAh+QQJEAD/ACwLAAQADAARAAAIWAA7/IP2r6DBgwgTKlz4bKHDhxALlmHhzKElFpEWMmrEx4kNhQzGPHDzMSGTRdL6lERI7se/HyEUZlj2b1mwf+YQ/vDH02XCnT0V9nnj700NhUOo/ajxJiAAIfkEBTIA/wAsAAAAABgAGAAACPoA/wkcSLCgwYMIEypcyLChQ4EdEEFTc+KeLWIP/0EoVOEPRSBikj3MYuaZr379XCTIRMLhEQMonlWY1G+UIC3MDP4x2KKOkg0RVkTZQ2MKiVYMR7QIIyJAgDBAylh4gYBhj0AZ/DnNAIQGC2cHkrbI6s9fBiKWWESywbBFiTCr/K0Kw6gRHydsF76kcuHBBQYMQPhxk1chCg3v5IQCA4bJFhnNCieEcCpJIkd4mDAYE8FBWIV2OkDLIYyRABBSZHgKoYKhkRMJ9AwixOfQNM9IF6JkU2YOCw5BYNhozRBlPxam4DQJZoOVQxYWKMHJ82rImqoZs2vfXjAgADs="],
        "18": ["[抓狂]", "data:image/gif;base64,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"],
        "19": ["[吐]", "data:image/gif;base64,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"],
        "20": ["[偷笑]", "data:image/gif;base64,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"],
        "21": ["[可爱]", "data:image/gif;base64,R0lGODlhGAAYAPfPAPKmDP/3ebeEStfSzf+2Vv38+//Cevbcsf+Tev+heP+Vh//qeP/kRv/li8J8FP/NMP/2iP+2Zf/oZN3Y1P21ELF9KP+OgvrWVv/caeSaDP+0Rv/jSujj3uOnJP/qUP/aeeXJSfz16f/Ghf/71P+5Ev/mxdy1bf7ZOf/7srhfAP/osv/LJurn5MObav/VP/+xef+4qf+Xlv+Ld8iKJKRRCP/zb//wXv+qlvywDP/5oPjKX8mGGP/Grv/JMv/jW/TSQ//RLfncm//nmv+7of/VMf/0sf+lZv/8uMurhf+iSf/dPf/XNPa7Nc2BCv/fQv+mg//SQf+YVf/Hn+WrI//ylP/Sgf/BZP/90Lt3Ff/eTf+sW//pWv99h/7mr//DHf/9x/+6FtulQ/TOP/CwH//6mqlgDv/1n/+1iffDLv/89vzhpf+WZv/oTf/ff/+1NP/2zv/AGvnGL//KVa9yLK1tJP+yLHgqAP/FWv/GIP/0xf3TNv/7lvrDJ/+8LP/ERP/BJv+4JP/LYf3HTf+/V/3RMv/uV//FH//aPP/Ulv7LTP/el//ObZJSGv/FcePObOPMXP/rU/nEJf/AIOnVY+O3L/OxF9qOC/GrEuulFuPDP//nVv/9SOPf3P/bOruRZefe0PDu7OG/jOmsLcKKPsiQPtmYIfTy8e+zMeq4UsivltLFuOnIkd2hH65mD/bGWffIW+24S+GgI/fKZ7p9MN2cH/zw1vbYmfjARfXBS/W3Kfa8JtKWM+KyUu2+WIczAKp1MvbIOap2Qb9rC/+Ckf/JsP++Nv/RUf/Rof/JQv/4uPTJNP/EPP/NPP+tQv+6l////wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQFFADPACwAAAAAGAAYAAAI/wCfCRxIsKDBgwgTKkzTRcetXExwyQpSS+FANUzi6Dmh5AShOLp6HVj4Ko4TNh48sGHDwEknPqJQISzgKg4kSIVs6CzkoWUnIGN4HQyC5qaNGgGS1rABicFPPbFMFAxxaoOHCnbsMCKzBwKjrFj0rOAT5hNBW8Cy+FgAwQyKIyjIQKjhIwuUHpIwmSgwEJagO41EIOqS58oXFFTaLLJCQIMbCrs4DOwQIYECBTd4lBhxJZkiEQlkyFiThMSOCQNjGZHBZVgMGAfefCmi6IwFLlxkRKGwY8BAWloSWLBwQ0qXZCjMNDAQenQSCg58CyzlZ5CVRlXaUDHTdQGGOxE0NMCrgwOL9Gdh0EBRKwGpUhua7PboAwbArPOrphA5tIFNoRQ/2PBDCgwoQcgKcFBgSQuoCRTCDJEQoYQTTmxShi9lbNIJESt4QYJ9qrBAUChTrADEEp0oscmKSwDRIRg4tMIgXwMVQEoHeJgIxI4u4gEHCTg0IcAAIho0CiuVeGEIHoZ4AQcYFGRQxpCcJITEDE1kcAkJFACQQRNzpDKAZApxgIQAc5RRBh0CeDLABKZYJFABoHAywQBvclKknHwSFBAAIfkEBRQAzwAsAwADABIADgAACKkAnwkc+OwQwYMIE55IyLChQBB27EwaCNEOpYR2HjmyM9AOiEx2lhDcsKXGsxwDUT7bssHFA4FQLmBoI6TIwQUYLiRa1ueZHCsGRBzr8magmTaLIkQg4AbMoAhnzkg5kEcgCiFVDLwwoqUZCUFADSBSU+SIQCofGill+oyZMZltqDwjI7CGhAvG/BQDJNCgJhspLgz8kaLlgz8ElTDwsOlgYyIOn9GQzDAgACH5BAUUAM8ALAMAAwASABEAAAiMAJ8JHPjsBMGDCBMqXDjwAcOHEBX6mPPMTLCBvwTSedZDYKJAi6ooUoFwUaA7t55VsvLiyY0hx2oJPCKkypkECYw0IxEhQYwYN3iUEFjkmYgnFiwgSPKMQMuXx1Qke5ajQZUXOHU+QyYH5LMGZgYuwHBykJs6FJ65eOZDQsIsHCPKPXhort27zwwxDAgAIfkEBRQAzwAsAwADABIAEQAACHoAnwkc+OwQwYMIE3ZK+MwDw4cQI0p8lgWipmdQBgp61ugZoi4H2zyzQkCDGwoREiiIcYNYCYHJnol4ggDBmiTPjFgYNiwGDIKKzijgwkVGFIEqFdyQAvKZmWcGaNrE6eeOlUZVnlFBOIhAs4kCM4IdO3Ah2bNj8UAMCAAh+QQFZADPACwFAAQAEAAQAAAITwCfCRxI8ARBgZAOKlzIsKFDgjkU+nhIsSKYigMRxHAog+CihxoIpvgRYKANMSkOHtrwY6SNZ4VQKlPYSYkLYThx6lnS8JDPQwYxVjTkMCAAOw=="],
        "22": ["[白眼]", "data:image/gif;base64,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"],
        "23": ["[傲慢]", "data:image/gif;base64,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"],
        "24": ["[饿]", "data:image/gif;base64,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"],
        "25": ["[困]", "data:image/gif;base64,R0lGODlhGAAYAPfPAO21Kv///ua1UuTf2Nm3bbeESv/OK8h7EeqmFtfSzejWV//9s/7pXfqzEfzjqt3Y1OrSSP/7lv/CHPzXOt2iG+fi3c+HDt3IV//GINGkJfyxDOOcEPz16fm9HP/KJv/dPffIXPW3Kf/lSO7DSern5MObavjamvzw1vS8Nuu2Sf/5ismGGOOjJN3Nev/2cv/0bP/bOf65E//oTPbBSP/VMf77rOy9Os23WMyACeqxHP/xXv/RLurMPsurhPvSQN2cHP/uV9K2Rf79xfbrcPrML/uuC/LVRufbeurbZ//hQtO3VvXELNOuNtGzPOvhhdulQ+mxPM2uOaliEvXolf/qUP7iRf/3d//89unDM//+z/C2HfbLNt6oH+3BWf3dQfGlDM+oLvTZT/vZPvrQMf/4fP/TMNKbHP/lR/3bPtaOD71/LuegEP/WM/XGMvrYQ/CxGP/cOv/yYqxtJs20SPnVN92yKvjgSvnfRdWSFf3gSva/JeWpIPrFJuKXCu/KNfGhCLNvGv7fS/7nS/3jTemsMv/8oOvinPjGMfzSNPbzw/bxrs6fIfvrWe/ib+fdl/vmTufbiNiVFd3APvbxp+OhMdKNHLxxDfz7++nHjunIkfTy8cyLIsKKPrd7MsiQPtmYIdKWM65mD9LFuL13FPPesPfKZ/Du7Mivll0jAKhdCfjGPruRZe65NgAAAOG/jOauLrByK//bR//YQ/njTP/fPvnnVO/SQPTdY/bpZ+vjr//sVPvaPPHdWPblWPi3Geq8K/biUvbaRO2rE/3dR+7GZ+7Gce3gePzlS/DQPPzPNufENufGOejHOdWXGN2fFP///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQFZADPACwAAAAAGAAYAAAI/wCfCRxIsKDBgwgTKrziAMSMEChmlDJxQuFAB6p8ePGS5MOEZIe6kFoIIk8VETJSikgCAwYRVgIQBgAx6AwVIDpyAqFy5gMND21iGjTh5owRGzZukbHyQsdRG3owvCJQkAMKLzagUKKUoksEFSO0UiIEwNeTAQRNbBGxtS2lBYXcbpWAgMClgSnECJJLSUgNvhIagKowkAUaO5UCtEIVoFIiRYkXN+4QY8WDwhOMVMIUIACmSrkMbe78WUuDFQkG/qDDg0mlAwcqEXAEScnr2GZyNDiQWuAnRMgkBblxg0ALJ8YuBIkCJgOXNxpG9X72ZAkdWxAUKEDSaAguXhCYYaz5ZfqLmumZ9pTZdWdWLUbwGT26M4EInw4N0pS4LJDDJiI0wJCECIJQIcNKMPwkQQxfdCIKCQS58sMOO7ABwwe/WGABADt4sKAGoex310CXeBKJAR4YsIMlnVmCwYJF4FBAAhAaxEkaekgQCx6WWJKGLDH0IcWMaCHUAyAWrOEDAwz40AcOsJzyAGEKVdBDAbCkIoUcBaySwAOaWCTQJaYM8EACX1ZQo5hsEhQQACH5BAUyAM8ALAMABAASABAAAAjKAJ8JHEjwGZyCCBMqfKZLxwsrBHWdgbOjIJUwNmwYiyDQBUYbSzwMFGQDisAUxAR2MfmMEACBaGQkXIAQw7MPjyjp3Jllwc6dEp7tsvMsQCtUAZ4lmlT0aNIOMSbYqoQpQABMlQw5oWoVq5ZnY3gwqXTgQCUlkI4EIWvWjLAiz/xIajJnzo0LSBRcaAImQwYKwgQS8cODBwQFvHoBM8IDy68cbwiOmRDszqxjx6p4oTOGT4cGfxTS+gCDzcKCACxYyHGaoCWBrxEGBAAh+QQFZADPACwDAAMAEgASAAAI/wCfCRSYbAKbg2OILBnIUCAaGB9ofYDBZscOPg2f5YGTRISMjyKSwKHhQULDD2eoANHBEgiVMx/KlBw44YwuHS+skLHyQoeuM3B2YGggMAkVHS5URFiqwoUOKknYeHi2RowIILxs2JiyYEEhFVlttDFg8pmgEVAECigm5FmXtM8IAcDw7IMMSnjzZlmQNy/dD4L64hVSQzAlCTEm3DE8aYrhDg3aIKvUt5ITY5TzVnojEAuYSgcOVAqCRAET0KKbCetch0mUKEEkKVAgiQmYRWacbRBIwQ8WZjx4GLETDBmWXzlyrH4W4FkzRGPoTNi1awIiInwgZ8SzxEAgAAJzxAySsDvjQARuBpZnGBAAIfkEBQoAzwAsAwADABIAFAAACP8AnwkciMiAQT589Axc+GzYhDJs2NAoY8CDB4UMn5WBQatKlSS0YJTxICHGkoE+aHwQQUUXEF1URNBiQ7LBwB20BAGJ88LFizhABNEqgyFGkWdtYIjQ9YKMimcqyLwAIgKGAQk2ESWhEodMhBrPakQgE4fK0KJF2CQB8kJFDSFZwqp4oasKG7RszgBxEWFBlrjPIrioSzOGBjZVmLqFK3CuriR3jeLk6rXGArFkzRI1ygeG3qYqIkSdesZqyT8aP8jY2fMnEBkfiErQIJAIG1qUcleCAKFSbkqzUT/7UabOgQOVwDBTlqHS8T60n116FsmAmUUZ6mD5xcUMHgtHGaYh4ZIjhxY9Hd4gWGMhVEaBODYIi9HgS58Dct7r389/AMOAACH5BAUyAM8ALAMAAwASABQAAAj/AJ8JFOjDi5ckHyYkOzSw4bM8VUTImCgiCRwYRBw+G3SGChAdIIFQOfOBhoc2A92cMWLDxi0yVl7oYGlDD4aBXmxAoUQpRZcIKkbspEQIgK9nW0TwXEppQSGmPCUgECMIKiUhNaxKaIDGTqUArVAFqJRI0dewYzvEmGCkEqYAATBVymXILVy5WhrQ4cGk0oEDlQg4gqTEL2AzORogQiYpyI0bBFo4MXYhSBQwGbi80bCEji0IChQgaTQEFy8IPLD8yvtF4K47s2oxms3o0Z0JY/h0aDCQBowkIgRRkVERhslnMVoL3LGDDZwPvyxYAFDGg4QYGp95MLDDElxLGK5nIX+mR0IsPJYspZEVo4+U8RbW+GDAwMczHLDG69+fvYLDgAAh+QQFHgDPACwDAAQADwANAAAIKwCfCXyWZ+BAGAYTKlzIsKHDhxAbUppIySBFSrsEXrRIMaLHj8w+eiwjMCAAOw=="],
        "26": ["[惊恐]", "data:image/gif;base64,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"],
        "27": ["[流汗]", "data:image/gif;base64,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"],
        "28": ["[憨笑]", "data:image/gif;base64,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"],
        "29": ["[大兵]", "data:image/gif;base64,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"],
        "30": ["[奋斗]", "data:image/gif;base64,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"],
        "31": ["[咒骂]", "data:image/gif;base64,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"],
        "32": ["[疑问]", "data:image/gif;base64,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"],
        "33": ["[嘘]", "data:image/gif;base64,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"],
        "34": ["[晕]", "data:image/gif;base64,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"]
    };
    //工具类
    var tool = new function () {

        //格式化时间戳
        //format格式如下：
        //yyyy-MM-dd hh:mm:ss 年月日时分秒(默认格式)
        //yyyy-MM-dd 年月日
        //hh:mm:ss 时分秒
        this.formatTimeStamp = function (timestamp, format) {
            if (!timestamp) {
                return 0;
            }
            var formatTime;
            format = format || 'yyyy-MM-dd hh:mm:ss';
            var date = new Date(timestamp * 1000);
            var o = {
                "M+": date.getMonth() + 1, //月份
                "d+": date.getDate(), //日
                "h+": date.getHours(), //小时
                "m+": date.getMinutes(), //分
                "s+": date.getSeconds() //秒
            };
            if (/(y+)/.test(format)) {
                formatTime = format.replace(RegExp.$1, (date.getFullYear() + "").substr(4 - RegExp.$1.length));
            } else {
                formatTime = format;
            }
            for (var k in o) {
                if (new RegExp("(" + k + ")").test(formatTime))
                    formatTime = formatTime.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
            }
            return formatTime;
        };

        //根据群类型英文名转换成中文名
        this.groupTypeEn2Ch = function (type_en) {
            var type_ch = null;
            switch (type_en) {
                case 'Public':
                    type_ch = '公开群';
                    break;
                case 'ChatRoom':
                    type_ch = '聊天室';
                    break;
                case 'Private':
                    type_ch = '讨论组';
                    break;
                case 'AVChatRoom':
                    type_ch = '直播聊天室';
                    break;
                default:
                    type_ch = type_en;
                    break;
            }
            return type_ch;
        };
        //根据群类型中文名转换成英文名
        this.groupTypeCh2En = function (type_ch) {
            var type_en = null;
            switch (type_ch) {
                case '公开群':
                    type_en = 'Public';
                    break;
                case '聊天室':
                    type_en = 'ChatRoom';
                    break;
                case '讨论组':
                    type_en = 'Private';
                    break;
                case '直播聊天室':
                    type_en = 'AVChatRoom';
                    break;
                default:
                    type_en = type_ch;
                    break;
            }
            return type_en;
        };
        //根据群身份英文名转换成群身份中文名
        this.groupRoleEn2Ch = function (role_en) {
            var role_ch = null;
            switch (role_en) {
                case 'Member':
                    role_ch = '成员';
                    break;
                case 'Admin':
                    role_ch = '管理员';
                    break;
                case 'Owner':
                    role_ch = '群主';
                    break;
                default:
                    role_ch = role_en;
                    break;
            }
            return role_ch;
        };
        //根据群身份中文名转换成群身份英文名
        this.groupRoleCh2En = function (role_ch) {
            var role_en = null;
            switch (role_ch) {
                case '成员':
                    role_en = 'Member';
                    break;
                case '管理员':
                    role_en = 'Admin';
                    break;
                case '群主':
                    role_en = 'Owner';
                    break;
                default:
                    role_en = role_ch;
                    break;
            }
            return role_en;
        };
        //根据群消息提示类型英文转换中文
        this.groupMsgFlagEn2Ch = function (msg_flag_en) {
            var msg_flag_ch = null;
            switch (msg_flag_en) {
                case 'AcceptAndNotify':
                    msg_flag_ch = '接收并提示';
                    break;
                case 'AcceptNotNotify':
                    msg_flag_ch = '接收不提示';
                    break;
                case 'Discard':
                    msg_flag_ch = '屏蔽';
                    break;
                default:
                    msg_flag_ch = msg_flag_en;
                    break;
            }
            return msg_flag_ch;
        };
        //根据群消息提示类型中文名转换英文名
        this.groupMsgFlagCh2En = function (msg_flag_ch) {
            var msg_flag_en = null;
            switch (msg_flag_ch) {
                case '接收并提示':
                    msg_flag_en = 'AcceptAndNotify';
                    break;
                case '接收不提示':
                    msg_flag_en = 'AcceptNotNotify';
                    break;
                case '屏蔽':
                    msg_flag_en = 'Discard';
                    break;
                default:
                    msg_flag_en = msg_flag_ch;
                    break;
            }
            return msg_flag_en;
        };
        //将空格和换行符转换成HTML标签
        this.formatText2Html = function (text) {
            var html = text;
            if (html) {
                html = this.xssFilter(html);//用户昵称或群名称等字段会出现脚本字符串
                html = html.replace(/ /g, "&nbsp;");
                html = html.replace(/\n/g, "<br/>");
            }
            return html;
        };
        //将HTML标签转换成空格和换行符
        this.formatHtml2Text = function (html) {
            var text = html;
            if (text) {
                text = text.replace(/&nbsp;/g, " ");
                text = text.replace(/<br\/>/g, "\n");
            }
            return text;
        };
        //获取字符串(UTF-8编码)所占字节数
        //参考：http://zh.wikipedia.org/zh-cn/UTF-8
        this.getStrBytes = function (str) {
            if (str == null || str === undefined) return 0;
            if (typeof str != "string") {
                return 0;
            }
            var total = 0, charCode, i, len;
            for (i = 0, len = str.length; i < len; i++) {
                charCode = str.charCodeAt(i);
                if (charCode <= 0x007f) {
                    total += 1;//字符代码在000000 – 00007F之间的，用一个字节编码
                } else if (charCode <= 0x07ff) {
                    total += 2;//000080 – 0007FF之间的字符用两个字节
                } else if (charCode <= 0xffff) {
                    total += 3;//000800 – 00D7FF 和 00E000 – 00FFFF之间的用三个字节，注: Unicode在范围 D800-DFFF 中不存在任何字符
                } else {
                    total += 4;//010000 – 10FFFF之间的用4个字节
                }
            }
            return total;
        };


        //防止XSS攻击
        this.xssFilter = function (val) {
            val = val.toString();
            val = val.replace(/[<]/g, "&lt;");
            val = val.replace(/[>]/g, "&gt;");
            val = val.replace(/"/g, "&quot;");
            //val = val.replace(/'/g, "&#039;");
            return val;
        };

        //去掉头尾空白符
        this.trimStr = function (str) {
            if (!str) return '';
            str = str.toString();
            return str.replace(/(^\s*)|(\s*$)/g, "");
        };
        //判断是否为8位整数
        this.validNumber = function (str) {
            str = str.toString();
            return str.match(/(^\d{1,8}$)/g);
        };
        this.getReturnError = function (errorInfo, errorCode) {
            if (!errorCode) {
                errorCode = -100;
            }
            var error = {
                'ActionStatus': ACTION_STATUS.FAIL,
                'ErrorCode': errorCode,
                'ErrorInfo': errorInfo + "[" + errorCode + "]"
            };
            return error;
        };
        //设置cookie
        //name 名字
        //value 值
        //expires 有效期(单位：秒)
        //path
        //domain 作用域
        this.setCookie = function (name, value, expires, path, domain) {
            var exp = new Date();
            exp.setTime(exp.getTime() + expires * 1000);
            document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString();
        };
        //获取cookie
        this.getCookie = function (name) {
            var result = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));
            if (result != null) {
                return unescape(result[2]);
            }
            return null;
        };
        //删除cookie
        this.delCookie = function (name) {
            var exp = new Date();
            exp.setTime(exp.getTime() - 1);
            var value = this.getCookie(name);
            if (value != null)
                document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString();
        };
        //根据名字获取url参数值
        this.getQueryString = function (name) {
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
            var r = window.location.search.substr(1).match(reg);
            if (r != null) return unescape(r[2]);
            return null;
        };
        //判断IE版本号，ver表示版本号
        this.isIE = function (ver) {
            var b = document.createElement('b')
            b.innerHTML = '<!--[if IE ' + ver + ']><i></i><![endif]-->'
            return b.getElementsByTagName('i').length === 1;
        };
        //判断浏览器版本
        this.getBrowserInfo = function () {
            var Sys = {};
            var ua = navigator.userAgent.toLowerCase();
            log.info('navigator.userAgent=' + ua);
            var s;
            (s = ua.match(/msie ([\d.]+)/)) ? Sys.ie = s[1] :
                (s = ua.match(/firefox\/([\d.]+)/)) ? Sys.firefox = s[1] :
                    (s = ua.match(/chrome\/([\d.]+)/)) ? Sys.chrome = s[1] :
                        (s = ua.match(/opera.([\d.]+)/)) ? Sys.opera = s[1] :
                            (s = ua.match(/version\/([\d.]+).*safari/)) ? Sys.safari = s[1] : 0;
            if (Sys.ie) {//Js判断为IE浏览器
                return {
                    'type': 'ie',
                    'ver': Sys.ie
                };
            }
            if (Sys.firefox) {//Js判断为火狐(firefox)浏览器
                return {
                    'type': 'firefox',
                    'ver': Sys.firefox
                };
            }
            if (Sys.chrome) {//Js判断为谷歌chrome浏览器
                return {
                    'type': 'chrome',
                    'ver': Sys.chrome
                };
            }
            if (Sys.opera) {//Js判断为opera浏览器
                return {
                    'type': 'opera',
                    'ver': Sys.opera
                };
            }
            if (Sys.safari) {//Js判断为苹果safari浏览器
                return {
                    'type': 'safari',
                    'ver': Sys.safari
                };
            }
            return {
                'type': 'unknow',
                'ver': -1
            };
        };

    };

    //日志对象
    var log = new function () {

        var on = true;

        this.setOn = function (onFlag) {
            on = onFlag;
        };

        this.getOn = function () {
            return on;
        };

        this.error = function (logStr) {
            try {
                on && console.error(logStr);
            } catch (e) {
            }
        };
        this.warn = function (logStr) {
            try {
                on && console.warn(logStr);
            } catch (e) {
            }
        };
        this.info = function (logStr) {
            try {
                on && console.info(logStr);
            } catch (e) {
            }
        };
        this.debug = function (logStr) {
            try {
                on && console.debug(logStr);
            } catch (e) {
            }
        };
    };
    //获取unix时间戳
    var unixtime = function (d) {
        if (!d) d = new Date();
        return Math.round(d.getTime() / 1000);
    };
    //时间戳转日期
    var fromunixtime = function (t) {
        return new Date(t * 1000);
    };
    //获取下一个消息序号
    var nextSeq = function () {
        if (curSeq) {
            curSeq = curSeq + 1;
        } else {
            curSeq = Math.round(Math.random() * 10000000);
        }
        return curSeq;
    };
    //产生随机数
    var createRandom = function () {
        return Math.round(Math.random() * 4294967296);
    };

    //获取ajax请求对象
    var getXmlHttp = function () {
        var xmlhttp = null;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            try {
                xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                    return null;
                }
            }
        }
        return xmlhttp;
    }
    //发起ajax请求
    var ajaxRequest = function (meth, url, req, timeout, isLongPolling, cbOk, cbErr) {

        var xmlHttpObj = getXmlHttp();

        var error, errInfo;
        if (!xmlHttpObj) {
            errInfo = "创建请求失败";
            var error = tool.getReturnError(errInfo, -1);
            log.error(errInfo);
            if (cbErr) cbErr(error);
            return;
        }
        //保存ajax请求对象
        xmlHttpObjSeq++;
        xmlHttpObjMap[xmlHttpObjSeq] = xmlHttpObj;

        xmlHttpObj.open(meth, url, true);
        xmlHttpObj.onreadystatechange = function () {
            if (xmlHttpObj.readyState == 4) {
                xmlHttpObjMap[xmlHttpObjSeq] = null;//清空
                if (xmlHttpObj.status == 200) {
                    if (cbOk) cbOk(xmlHttpObj.responseText);
                    xmlHttpObj = null;
                    curLongPollingRetErrorCount = curBigGroupLongPollingRetErrorCount = 0;
                } else {
                    xmlHttpObj = null;
                    //避免刷新的时候，由于abord ajax引起的错误回调
                    setTimeout(function(){
                        var errInfo = "请求服务器失败,请检查你的网络是否正常";
                        var error = tool.getReturnError(errInfo, -2);
                        //if (!isLongPolling && cbErr) cbErr(error);
                        if (cbErr) cbErr(error);
                    },16);
                }
            }
        };
        xmlHttpObj.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        //设置超时时间
        if (!timeout) {
            timeout = ajaxDefaultTimeOut;//设置ajax默认超时时间
        }
        if (timeout) {
            xmlHttpObj.timeout = timeout;
            xmlHttpObj.ontimeout = function (event) {
                xmlHttpObj = null;
                //var errInfo = "请求服务器超时";
                //var error = tool.getReturnError(errInfo, -3);
                //if (cbErr) cbErr(error);
            };
        }
        //
        xmlHttpObj.send(req);
    }
    //发起ajax请求（json格式数据）
    var ajaxRequestJson = function (meth, url, req, timeout, isLongPolling, cbOk, cbErr) {
        ajaxRequest(meth, url, JSON.stringify(req), timeout, isLongPolling, function (resp) {
            var json = null;
            //if (resp) eval('json=('+resp+');');//将返回的json字符串转换成json对象
            //if (resp) json=eval('('+resp+')');//将返回的json字符串转换成json对象
            if (resp) json = JSON.parse(resp);//将返回的json字符串转换成json对象
            if (cbOk) cbOk(json);
        }, cbErr);
    }
    //判断用户是否已登录
    var isLogin = function () {
        return ctx.sdkAppID && ctx.identifier;
    };
    //检查是否登录
    var checkLogin = function (cbErr, isNeedCallBack) {
        if (!isLogin()) {
            if (isNeedCallBack) {
                var errInfo = "请登录";
                var error = tool.getReturnError(errInfo, -4);

                if (cbErr) cbErr(error);
            }
            return false;
        }
        return true;
    };

    //检查是否访问正式环境
    var isAccessFormalEnv = function () {
        return isAccessFormaEnvironment;
    };

    //根据不同的服务名和命令，获取对应的接口地址
    var getApiUrl = function (srvName, cmd, cbOk, cbErr) {
        var srvHost = SRV_HOST;
        if (isAccessFormalEnv()) {
            srvHost = SRV_HOST.FORMAL.COMMON;
        } else {
            srvHost = SRV_HOST.TEST.COMMON;
        }

        //if (srvName == SRV_NAME.RECENT_CONTACT) {
        //    srvHost = SRV_HOST.TEST.COMMON;
        //}

        if (srvName == SRV_NAME.PIC) {
            if (isAccessFormalEnv()) {
                srvHost = SRV_HOST.FORMAL.PIC;
            } else {
                srvHost = SRV_HOST.TEST.PIC;
            }
        }

        var url = srvHost + '/' + SRV_NAME_VER[srvName] + '/' + srvName + '/' + cmd + '?websdkappid=' + SDK.APPID + "&v=" + SDK.VERSION;

        if (isLogin()) {
            if (cmd == 'login') {
                url += '&identifier=' + encodeURIComponent(ctx.identifier) + '&usersig=' + ctx.userSig;
            } else {
                if (ctx.tinyid && ctx.a2) {
                    url += '&tinyid=' + ctx.tinyid + '&a2=' + ctx.a2;
                } else {
                    if (cbErr) {
                        log.error("tinyid或a2为空[" + srvName + "][" + cmd + "]");
                        cbErr(tool.getReturnError("tinyid或a2为空[" + srvName + "][" + cmd + "]", -5));
                        return false;
                    }
                }
            }
            url += '&contenttype=' + ctx.contentType;
        }
        url += '&sdkappid=' + ctx.sdkAppID + '&accounttype=' + ctx.accountType + '&apn=' + ctx.apn + '&reqtime=' + unixtime();
        return url;
    };

    //获取语音下载url
    var getSoundDownUrl = function (uuid, senderId) {
        var soundUrl = null;
        if (authkey && ipList[0]) {
            soundUrl = "http://" + ipList[0] + "/asn.com/stddownload_common_file?authkey=" + authkey + "&bid=" + DOWNLOAD_FILE.BUSSINESS_ID + "&subbid=" + ctx.sdkAppID + "&fileid=" + uuid + "&filetype=" + DOWNLOAD_FILE_TYPE.SOUND + "&openid=" + senderId + "&ver=0";
        } else {
            log.error("拼接语音下载url不报错：ip或者authkey为空");
        }
        return soundUrl;
    };

    //获取文件下载地址
    var getFileDownUrl = function (uuid, senderId, fileName) {
        var fileUrl = null;
        if (authkey && ipList[0]) {
            fileUrl = "http://" + ipList[0] + "/asn.com/stddownload_common_file?authkey=" + authkey + "&bid=" + DOWNLOAD_FILE.BUSSINESS_ID + "&subbid=" + ctx.sdkAppID + "&fileid=" + uuid + "&filetype=" + DOWNLOAD_FILE_TYPE.FILE + "&openid=" + senderId + "&ver=0&filename=" + encodeURIComponent(fileName);
        } else {
            log.error("拼接文件下载url不报错：ip或者authkey为空");
        }
        Resources.downloadMap["uuid_"+uuid] = fileUrl;
        return fileUrl;
    };

    //获取文件下载地址
    var getFileDownUrlV2 = function (uuid, senderId, fileName, downFlag, receiverId, busiId, type) {
            var options = {
                "From_Account": senderId,//"identifer_0",       // 类型: String, 发送者tinyid
                "To_Account": receiverId,//"identifer_1",         // 类型: String, 接收者tinyid
                "os_platform": 10,                      // 类型: Number, 终端的类型 1(android) 2(ios) 3(windows) 10(others...)
                "Timestamp": unixtime().toString(),     // 类型: Number, 时间戳
                "Random": createRandom().toString(),    // 类型: Number, 随机值
                "request_info": [                       // 类型: Array
                    {
                        "busi_id": busiId,                   // 类型: Number, 群(1) C2C(2) 其他请联系sdk开发者分配
                        "download_flag": downFlag,      // 类型: Number, 申请下载地址标识  0(申请架平下载地址)  1(申请COS平台下载地址)  2(不需要申请, 直接拿url下载(这里应该不会为2))
                        "type": type,                      // 类型: Number, 0(短视频缩略图), 1(文件), 2(短视频), 3(ptt), 其他待分配
                        "uuid": uuid,                   // 类型: Number, 唯一标识一个文件的uuid
                        "version": VERSION_INFO.SERVER_VERSION, // 类型: Number, 架平server版本
                        "auth_key": authkey,            // 类型: String, 认证签名
                        "ip": ipList[0]                 // 类型: Number, 架平IP
                    }
                ]
            };
            //获取下载地址
            proto_applyDownload(options,function(resp){
                if(resp.error_code == 0 && resp.response_info){
                    Resources.downloadMap["uuid_"+options.uuid] = resp.response_info.url;
                }
                if(onAppliedDownloadUrl){
                    onAppliedDownloadUrl({
                        uuid : options.uuid,
                        url : resp.response_info.url,
                        maps : Resources.downloadMap
                    });
                }
            }, function(resp){
                log.error("获取下载地址失败",options.uuid)
            });
    };


    //重置ajax请求
    var clearXmlHttpObjMap = function () {
        //遍历xmlHttpObjMap{}
        for (var seq in xmlHttpObjMap) {
            var xmlHttpObj = xmlHttpObjMap[seq];
            if (xmlHttpObj) {
                xmlHttpObj.abort();//中断ajax请求(长轮询)
                xmlHttpObjMap[xmlHttpObjSeq] = null;//清空
            }
        }
        xmlHttpObjSeq = 0;
        xmlHttpObjMap = {};
    };

    //重置sdk全局变量
    var clearSdk = function () {

        clearXmlHttpObjMap();

        //当前登录用户
        ctx = {
            sdkAppID: null,
            appIDAt3rd: null,
            accountType: null,
            identifier: null,
            identifierNick: null,
            userSig: null,
            contentType: 'json',
            apn: 1
        };
        opt = {};

        curSeq = 0;

        //ie8,9采用jsonp方法解决ajax跨域限制
        jsonpRequestId = 0;//jsonp请求id
        //最新jsonp请求返回的json数据
        jsonpLastRspData = null;

        apiReportItems = [];

        MsgManager.clear();
    };

    //登录
    var _login = function (loginInfo, listeners, options, cbOk, cbErr) {

        clearSdk();

        if (options) opt = options;
        if (opt.isAccessFormalEnv == false) {
            isAccessFormaEnvironment = opt.isAccessFormalEnv;
        }
        if (opt.isLogOn == false) {
            log.setOn(opt.isLogOn);
        }
        /*
         if(opt.emotions){
         emotions=opt.emotions;
         webim.Emotions= emotions;
         }
         if(opt.emotionDataIndexs){
         emotionDataIndexs=opt.emotionDataIndexs;
         webim.EmotionDataIndexs= emotionDataIndexs;
         }*/

        if (!loginInfo) {
            if (cbErr) {
                cbErr(tool.getReturnError("loginInfo is empty", -6));
                return;
            }
        }
        if (!loginInfo.sdkAppID) {
            if (cbErr) {
                cbErr(tool.getReturnError("loginInfo.sdkAppID is empty", -7));
                return;
            }
        }
        if (!loginInfo.accountType) {
            if (cbErr) {
                cbErr(tool.getReturnError("loginInfo.accountType is empty", -8));
                return;
            }
        }

        if (loginInfo.identifier) {
            ctx.identifier = loginInfo.identifier.toString();
        }
        if (loginInfo.identifier && !loginInfo.userSig) {
            if (cbErr) {
                cbErr(tool.getReturnError("loginInfo.userSig is empty", -9));
                return;
            }
        }
        if (loginInfo.userSig) {
            ctx.userSig = loginInfo.userSig.toString();
        }
        ctx.sdkAppID = loginInfo.sdkAppID;
        ctx.accountType = loginInfo.accountType;

        if (ctx.identifier && ctx.userSig) {//带登录态
            //登录
            proto_login(
                function (identifierNick) {
                    MsgManager.init(
                        listeners,
                        function (mmInitResp) {
                            if (cbOk) {
                                mmInitResp.identifierNick = identifierNick;
                                cbOk(mmInitResp);
                            }
                        }, cbErr
                    );
                },
                cbErr
            );
        } else {//不带登录态，进入直播场景sdk
            MsgManager.init(
                listeners,
                cbOk,
                cbErr
            );
        }
    };

    //初始化浏览器信息
    var initBrowserInfo = function () {
        //初始化浏览器类型
        BROWSER_INFO = tool.getBrowserInfo();
        log.info('BROWSER_INFO: type=' + BROWSER_INFO.type + ', ver=' + BROWSER_INFO.ver);
        if (BROWSER_INFO.type == "ie") {
            if (parseInt(BROWSER_INFO.ver) < 10) {
                lowerBR = true;
            }
        }
    };

    //接口质量上报
    var reportApiQuality = function (cmd, errorCode, errorInfo) {
        if (cmd == 'longpolling' && (errorCode == longPollingTimeOutErrorCode || errorCode == longPollingKickedErrorCode)) {//longpolling 返回60008错误可以视为正常,可以不上报
            return;
        }
        var eventId = CMD_EVENT_ID_MAP[cmd];
        if (eventId) {
            var reportTime = unixtime();
            var uniqKey = null;
            var msgCmdErrorCode = {
                'Code': errorCode,
                'ErrMsg': errorInfo
            };
            if (ctx.a2) {
                uniqKey = ctx.a2.substring(0, 10) + "_" + reportTime + "_" + createRandom();
            } else if (ctx.userSig) {
                uniqKey = ctx.userSig.substring(0, 10) + "_" + reportTime + "_" + createRandom();
            }

            if (uniqKey) {

                var rptEvtItem = {
                    "UniqKey": uniqKey,
                    "EventId": eventId,
                    "ReportTime": reportTime,
                    "MsgCmdErrorCode": msgCmdErrorCode
                };

                if (cmd == 'login') {
                    var loginApiReportItems = [];
                    loginApiReportItems.push(rptEvtItem);
                    var loginReportOpt = {
                        "EvtItems": loginApiReportItems,
                        "MainVersion": SDK.VERSION,
                        "Version": "0"
                    };
                    proto_reportApiQuality(loginReportOpt,
                        function (resp) {
                            loginApiReportItems = null;//
                        },
                        function (err) {
                            loginApiReportItems = null;//
                        }
                    );
                } else {
                    apiReportItems.push(rptEvtItem);
                    if (apiReportItems.length >= maxApiReportItemCount) {//累计一定条数再上报
                        var reportOpt = {
                            "EvtItems": apiReportItems,
                            "MainVersion": SDK.VERSION,
                            "Version": "0"
                        };
                        proto_reportApiQuality(reportOpt,
                            function (resp) {
                                apiReportItems = [];//清空
                            },
                            function (err) {
                                apiReportItems = [];//清空
                            }
                        );
                    }
                }

            }
        }
    };

    // REST API calls
    //上线
    var proto_login = function (cbOk, cbErr) {
        ConnManager.apiCall(SRV_NAME.OPEN_IM, "login", {"State": "Online"},
            function (loginResp) {
                if (loginResp.TinyId) {
                    ctx.tinyid = loginResp.TinyId;
                } else {
                    if (cbErr) {
                        cbErr(tool.getReturnError("TinyId is empty", -10));
                        return;
                    }
                }
                if (loginResp.A2Key) {
                    ctx.a2 = loginResp.A2Key;
                } else {
                    if (cbErr) {
                        cbErr(tool.getReturnError("A2Key is empty", -11));
                        return;
                    }
                }
                var tag_list = [
                    "Tag_Profile_IM_Nick"
                ];
                var options = {
                    'From_Account': ctx.identifier,
                    'To_Account': [ctx.identifier],
                    'LastStandardSequence': 0,
                    'TagList': tag_list
                };
                proto_getProfilePortrait(
                    options,
                    function (resp) {
                        var nick, gender, allowType;
                        if (resp.UserProfileItem && resp.UserProfileItem.length > 0) {
                            for (var i in resp.UserProfileItem) {
                                for (var j in resp.UserProfileItem[i].ProfileItem) {
                                    switch (resp.UserProfileItem[i].ProfileItem[j].Tag) {
                                        case 'Tag_Profile_IM_Nick':
                                            nick = resp.UserProfileItem[i].ProfileItem[j].Value;
                                            if (nick) ctx.identifierNick = nick;
                                            break;
                                    }
                                }
                            }
                        }
                        if (cbOk) cbOk(ctx.identifierNick);//回传当前用户昵称
                    }, cbErr);
            }
            , cbErr);
    };
    //下线
    var proto_logout = function (type , cbOk, cbErr) {
        if (!checkLogin(cbErr, false)) {//不带登录态
            clearSdk();
            if (cbOk) cbOk({
                'ActionStatus': ACTION_STATUS.OK,
                'ErrorCode': 0,
                'ErrorInfo': 'logout success'
            });
            return;
        }
        if(type == "all"){
            ConnManager.apiCall(SRV_NAME.OPEN_IM, "logout", {},
                function (resp) {
                    clearSdk();
                    if (cbOk) cbOk(resp);
                },
                cbErr);
        }else{
            ConnManager.apiCall(SRV_NAME.OPEN_IM, "longpollinglogout", { LongPollingId  : LongPollingId },
                function (resp) {
                    clearSdk();
                    if (cbOk) cbOk(resp);
                },
                cbErr);
        }
    };
    //发送消息，包括私聊和群聊
    var proto_sendMsg = function (msg, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;
        var msgInfo = null;

        switch (msg.sess.type()) {
            case SESSION_TYPE.C2C:
                msgInfo = {
                    'From_Account': ctx.identifier,
                    'To_Account': msg.sess.id().toString(),
                    'MsgTimeStamp': msg.time,
                    'MsgSeq': msg.seq,
                    'MsgRandom': msg.random,
                    'MsgBody': []
                };
                break;
            case SESSION_TYPE.GROUP:
                var subType = msg.getSubType();
                msgInfo = {
                    'GroupId': msg.sess.id().toString(),
                    'From_Account': ctx.identifier,
                    'Random': msg.random,
                    'MsgBody': []
                };
                switch (subType) {
                    case GROUP_MSG_SUB_TYPE.COMMON:
                        msgInfo.MsgPriority = "COMMON";
                        break;
                    case GROUP_MSG_SUB_TYPE.REDPACKET:
                        msgInfo.MsgPriority = "REDPACKET";
                        break;
                    case GROUP_MSG_SUB_TYPE.LOVEMSG:
                        msgInfo.MsgPriority = "LOVEMSG";
                        break;
                    case GROUP_MSG_SUB_TYPE.TIP:
                        log.error("不能主动发送群提示消息,subType=" + subType);
                        break;
                    default:
                        log.error("发送群消息时，出现未知子消息类型：subType=" + subType);
                        return;
                        break;
                }
                break;
            default:
                break;
        }

        for (var i in msg.elems) {
            var elem = msg.elems[i];
            var msgContent = null;
            var msgType = elem.type;
            switch (msgType) {
                case MSG_ELEMENT_TYPE.TEXT://文本
                    msgContent = {'Text': elem.content.text};
                    break;
                case MSG_ELEMENT_TYPE.FACE://表情
                    msgContent = {'Index': elem.content.index, 'Data': elem.content.data};
                    break;
                case MSG_ELEMENT_TYPE.IMAGE://图片
                    var ImageInfoArray = [];
                    for (var j in elem.content.ImageInfoArray) {
                        ImageInfoArray.push(
                            {
                                'Type': elem.content.ImageInfoArray[j].type,
                                'Size': elem.content.ImageInfoArray[j].size,
                                'Width': elem.content.ImageInfoArray[j].width,
                                'Height': elem.content.ImageInfoArray[j].height,
                                'URL': elem.content.ImageInfoArray[j].url
                            }
                        );
                    }
                    msgContent = {'UUID': elem.content.UUID, 'ImageInfoArray': ImageInfoArray};
                    break;
                case MSG_ELEMENT_TYPE.SOUND://
                    log.warn('web端暂不支持发送语音消息');
                    continue;
                    break;
                case MSG_ELEMENT_TYPE.LOCATION://
                    log.warn('web端暂不支持发送地理位置消息');
                    continue;
                    break;
                case MSG_ELEMENT_TYPE.FILE://
                    msgContent = {
                        'UUID': elem.content.uuid,
                        'FileName': elem.content.name,
                        'FileSize': elem.content.size,
                        'DownloadFlag' : elem.content.downFlag
                    };
                    break;
                case MSG_ELEMENT_TYPE.CUSTOM://
                    msgContent = {'Data': elem.content.data, 'Desc': elem.content.desc, 'Ext': elem.content.ext};
                    msgType = MSG_ELEMENT_TYPE.CUSTOM;
                    break;
                default :
                    log.warn('web端暂不支持发送' + elem.type + '消息');
                    continue;
                    break;
            }
            msgInfo.MsgBody.push({'MsgType': msgType, 'MsgContent': msgContent});
        }
        if (msg.sess.type() == SESSION_TYPE.C2C) {//私聊
            ConnManager.apiCall(SRV_NAME.OPEN_IM, "sendmsg", msgInfo, cbOk, cbErr);
        } else if (msg.sess.type() == SESSION_TYPE.GROUP) {//群聊
            ConnManager.apiCall(SRV_NAME.GROUP, "send_group_msg", msgInfo, cbOk, cbErr);
        }
    };
    //长轮询接口
    var proto_longPolling = function (options, cbOk, cbErr) {
        if(!isAccessFormaEnvironment && typeof stopPolling !="undefined" && stopPolling == true){
            return;
        }
        if (!checkLogin(cbErr, true)) return;
        ConnManager.apiCall(SRV_NAME.OPEN_IM, "longpolling", options, cbOk, cbErr, longPollingDefaultTimeOut, true);
    };

    //长轮询接口(拉取直播聊天室新消息)
    var proto_bigGroupLongPolling = function (options, cbOk, cbErr, timeout) {
        ConnManager.apiCall(SRV_NAME.BIG_GROUP_LONG_POLLING, "get_msg", options, cbOk, cbErr, timeout);
    };

    //拉取未读c2c消息接口
    var proto_getMsgs = function (cookie, syncFlag, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;
        ConnManager.apiCall(SRV_NAME.OPEN_IM, "getmsg", {'Cookie': cookie, 'SyncFlag': syncFlag},
            function (resp) {

                if (resp.MsgList && resp.MsgList.length) {
                    for (var i in resp.MsgList) {
                        tempC2CMsgList.push(resp.MsgList[i]);
                    }
                }
                if (resp.SyncFlag == 1) {
                    proto_getMsgs(resp.Cookie, resp.SyncFlag, cbOk, cbErr);
                } else {
                    resp.MsgList = tempC2CMsgList;
                    tempC2CMsgList = [];
                    if (cbOk) cbOk(resp);
                }
            },
            cbErr);
    };
    //C2C消息已读上报接口
    var proto_c2CMsgReaded = function (cookie, c2CMsgReadedItem, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;
        var tmpC2CMsgReadedItem = [];
        for (var i in c2CMsgReadedItem) {
            var item = {
                'To_Account': c2CMsgReadedItem[i].toAccount,
                'LastedMsgTime': c2CMsgReadedItem[i].lastedMsgTime
            };
            tmpC2CMsgReadedItem.push(item);
        }
        ConnManager.apiCall(SRV_NAME.OPEN_IM, "msgreaded", {
            C2CMsgReaded: {
                'Cookie': cookie,
                'C2CMsgReadedItem': tmpC2CMsgReadedItem
            }
        }, cbOk, cbErr);
    };

    //删除c2c消息
    var proto_deleteC2CMsg = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;

        ConnManager.apiCall(SRV_NAME.OPEN_IM, "deletemsg", options,
            cbOk, cbErr);
    };

    //拉取c2c历史消息接口
    var proto_getC2CHistoryMsgs = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;
        ConnManager.apiCall(SRV_NAME.OPEN_IM, "getroammsg", options,
            function (resp) {
                var reqMsgCount = options.MaxCnt;
                var complete = resp.Complete;
                var rspMsgCount = resp.MaxCnt;
                var msgKey = resp.MsgKey;
                var lastMsgTime = resp.LastMsgTime;

                if (resp.MsgList && resp.MsgList.length) {
                    for (var i in resp.MsgList) {
                        tempC2CHistoryMsgList.push(resp.MsgList[i]);
                    }
                }
                var netxOptions = null;
                if (complete == 0) {//还有历史消息可拉取
                    if (rspMsgCount < reqMsgCount) {
                        netxOptions = {
                            'Peer_Account': options.Peer_Account,
                            'MaxCnt': reqMsgCount - rspMsgCount,
                            'LastMsgTime': lastMsgTime,
                            'MsgKey': msgKey
                        };
                    }
                }

                if (netxOptions) {//继续拉取
                    proto_getC2CHistoryMsgs(netxOptions, cbOk, cbErr);
                } else {
                    resp.MsgList = tempC2CHistoryMsgList;
                    tempC2CHistoryMsgList = [];
                    if (cbOk) cbOk(resp);
                }
            },
            cbErr);
    };

    //群组接口
    //创建群组
    //协议参考：https://www.qcloud.com/doc/product/269/1615
    var proto_createGroup = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;
        var opt = {
            //必填    群组形态，包括Public（公开群），Private（私密群），ChatRoom（聊天室），AVChatRoom（互动直播聊天室）。
            'Type': options.Type,
            //必填    群名称，最长30字节。
            'Name': options.Name
        };
        var member_list = [];

        //Array 选填  初始群成员列表，最多500个。成员信息字段详情参见：群成员资料。
        for (var i = 0; i < options.MemberList.length; i++) {
            member_list.push({'Member_Account': options.MemberList[i]})
        }
        opt.MemberList = member_list;
        //选填    为了使得群组ID更加简单，便于记忆传播，腾讯云支持APP在通过REST API创建群组时自定义群组ID。详情参见：自定义群组ID。
        if (options.GroupId) {
            opt.GroupId = options.GroupId;
        }
        //选填    群主id，自动添加到群成员中。如果不填，群没有群主。
        if (options.Owner_Account) {
            opt.Owner_Account = options.Owner_Account;
        }
        //选填    群简介，最长240字节。
        if (options.Introduction) {
            opt.Introduction = options.Introduction;
        }
        //选填    群公告，最长300字节。
        if (options.Notification) {
            opt.Notification = options.Notification;
        }
        //选填    最大群成员数量，最大为10000，不填默认为2000个。
        if (options.MaxMemberCount) {
            opt.MaxMemberCount = options.MaxMemberCount;
        }
        //选填    申请加群处理方式。包含FreeAccess（自由加入），NeedPermission（需要验证），DisableApply（禁止加群），不填默认为NeedPermission（需要验证）。
        if (options.ApplyJoinOption) {//
            opt.ApplyJoinOption = options.ApplyJoinOption;
        }
        //Array 选填  群组维度的自定义字段，默认情况是没有的，需要开通，详情参见：自定义字段。
        if (options.AppDefinedData) {
            opt.AppDefinedData = options.AppDefinedData;
        }
        //选填    群头像URL，最长100字节。
        if (options.FaceUrl) {
            opt.FaceUrl = options.FaceUrl;
        }
        ConnManager.apiCall(SRV_NAME.GROUP, "create_group", opt,
            cbOk, cbErr);
    };

    //创建群组-高级接口
    //协议参考：https://www.qcloud.com/doc/product/269/1615
    var proto_createGroupHigh = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;
        ConnManager.apiCall(SRV_NAME.GROUP, "create_group", options,
            cbOk, cbErr);
    };

    //修改群组基本资料
    //协议参考：https://www.qcloud.com/doc/product/269/1620
    var proto_modifyGroupBaseInfo = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;

        ConnManager.apiCall(SRV_NAME.GROUP, "modify_group_base_info", options,
            cbOk, cbErr);
    };

    //申请加群
    var proto_applyJoinGroup = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;

        ConnManager.apiCall(SRV_NAME.GROUP, "apply_join_group", {
                'GroupId': options.GroupId,
                'ApplyMsg': options.ApplyMsg,
                'UserDefinedField': options.UserDefinedField
            },
            cbOk, cbErr);
    };

    //申请加入大群
    var proto_applyJoinBigGroup = function (options, cbOk, cbErr) {
        var srvName;
        if (!checkLogin(cbErr, false)) {//未登录
            srvName = SRV_NAME.BIG_GROUP;
        } else {//已登录
            srvName = SRV_NAME.GROUP;
        }
        ConnManager.apiCall(srvName, "apply_join_group", {
                'GroupId': options.GroupId,
                'ApplyMsg': options.ApplyMsg,
                'UserDefinedField': options.UserDefinedField
            },
            function (resp) {
                if (resp.JoinedStatus && resp.JoinedStatus == 'JoinedSuccess') {
                    if (resp.LongPollingKey) {
                        MsgManager.setBigGroupLongPollingOn(true);//开启长轮询
                        MsgManager.setBigGroupLongPollingKey(resp.LongPollingKey);//更新大群长轮询key
                        MsgManager.setBigGroupLongPollingMsgMap(options.GroupId, 0);//收到的群消息置0
                        MsgManager.bigGroupLongPolling();//开启长轮询
                    } else {//没有返回LongPollingKey，说明申请加的群不是直播聊天室(AVChatRoom)
                        cbErr && cbErr(tool.getReturnError("Join Group succeed; But the type of group is not AVChatRoom: groupid=" + options.GroupId, -12));
                        return;
                    }
                }
                if (cbOk) cbOk(resp);
            }
            , function (err) {

                if (cbErr) cbErr(err);
            });
    };

    //处理加群申请(同意或拒绝)
    var proto_handleApplyJoinGroupPendency = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;

        ConnManager.apiCall(SRV_NAME.GROUP, "handle_apply_join_group", {
                'GroupId': options.GroupId,
                'Applicant_Account': options.Applicant_Account,
                'HandleMsg': options.HandleMsg,
                'Authentication': options.Authentication,
                'MsgKey': options.MsgKey,
                'ApprovalMsg': options.ApprovalMsg,
                'UserDefinedField': options.UserDefinedField
            },
            cbOk,
            function (err) {
                if (err.ErrorCode == 10024) {//apply has be handled
                    if (cbOk) {
                        var resp = {
                            'ActionStatus': ACTION_STATUS.OK,
                            'ErrorCode': 0,
                            'ErrorInfo': '该申请已经被处理过'
                        };
                        cbOk(resp);
                    }
                } else {
                    if (cbErr) cbErr(err);
                }
            }
        );
    };

    //主动退群
    var proto_quitGroup = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;

        ConnManager.apiCall(SRV_NAME.GROUP, "quit_group", {
                'GroupId': options.GroupId
            },
            cbOk, cbErr);
    };

    //退出大群
    var proto_quitBigGroup = function (options, cbOk, cbErr) {
        var srvName;
        if (!checkLogin(cbErr, false)) {//未登录
            srvName = SRV_NAME.BIG_GROUP;
        } else {//已登录
            srvName = SRV_NAME.GROUP;
        }
        ConnManager.apiCall(srvName, "quit_group",
            {'GroupId': options.GroupId},
            function (resp) {
                //重置当前再请求中的ajax
                //clearXmlHttpObjMap();
                //退出大群成功之后需要重置长轮询信息
                MsgManager.resetBigGroupLongPollingInfo();
                if (cbOk) cbOk(resp);
            },
            cbErr);
    };
    //查找群(按名称)
    var proto_searchGroupByName = function (options, cbOk, cbErr) {
        ConnManager.apiCall(SRV_NAME.GROUP, "search_group", options, cbOk, cbErr);
    };

    //获取群组公开资料
    var proto_getGroupPublicInfo = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;

        ConnManager.apiCall(SRV_NAME.GROUP, "get_group_public_info", {
                'GroupIdList': options.GroupIdList,
                'ResponseFilter': {
                    'GroupBasePublicInfoFilter': options.GroupBasePublicInfoFilter
                }
            },
            function (resp) {
                resp.ErrorInfo = '';
                if (resp.GroupInfo) {
                    for (var i in resp.GroupInfo) {
                        var errorCode = resp.GroupInfo[i].ErrorCode;
                        if (errorCode > 0) {
                            resp.ActionStatus = ACTION_STATUS.FAIL;
                            resp.GroupInfo[i].ErrorInfo = "[" + errorCode + "]" + resp.GroupInfo[i].ErrorInfo;
                            resp.ErrorInfo += resp.GroupInfo[i].ErrorInfo + '\n';
                        }
                    }
                }
                if (resp.ActionStatus == ACTION_STATUS.FAIL) {
                    if (cbErr) {
                        cbErr(resp);
                    }
                } else if (cbOk) {
                    cbOk(resp);
                }

            },
            cbErr);
    };

    //获取群组详细资料--高级
    //请求协议参考：https://www.qcloud.com/doc/product/269/1616
    var proto_getGroupInfo = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;

        var opt = {
            'GroupIdList': options.GroupIdList,
            'ResponseFilter': {
                'GroupBaseInfoFilter': options.GroupBaseInfoFilter,
                'MemberInfoFilter': options.MemberInfoFilter
            }
        };
        if (options.AppDefinedDataFilter_Group) {
            opt.ResponseFilter.AppDefinedDataFilter_Group = options.AppDefinedDataFilter_Group;
        }
        if (options.AppDefinedDataFilter_GroupMember) {
            opt.ResponseFilter.AppDefinedDataFilter_GroupMember = options.AppDefinedDataFilter_GroupMember;
        }
        ConnManager.apiCall(SRV_NAME.GROUP, "get_group_info", opt,
            cbOk, cbErr);
    };

    //获取群组成员-高级接口
    //协议参考：https://www.qcloud.com/doc/product/269/1617
    var proto_getGroupMemberInfo = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;

        ConnManager.apiCall(SRV_NAME.GROUP, "get_group_member_info", {
                'GroupId': options.GroupId,
                'Offset': options.Offset,
                'Limit': options.Limit,
                'MemberInfoFilter': options.MemberInfoFilter,
                'MemberRoleFilter': options.MemberRoleFilter,
                'AppDefinedDataFilter_GroupMember': options.AppDefinedDataFilter_GroupMember
            },
            cbOk, cbErr);
    };


    //增加群组成员
    //协议参考：https://www.qcloud.com/doc/product/269/1621
    var proto_addGroupMember = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;

        ConnManager.apiCall(SRV_NAME.GROUP, "add_group_member", {
                'GroupId': options.GroupId,
                'Silence': options.Silence,
                'MemberList': options.MemberList
            },
            cbOk, cbErr);
    };
    //修改群组成员资料
    //协议参考：https://www.qcloud.com/doc/product/269/1623
    var proto_modifyGroupMember = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;
        var opt = {};
        if (options.GroupId) {
            opt.GroupId = options.GroupId;
        }
        if (options.Member_Account) {
            opt.Member_Account = options.Member_Account;
        }
        //Admin或者Member
        if (options.Role) {
            opt.Role = options.Role;
        }
        // AcceptAndNotify代表解收并提示消息，Discard代表不接收也不提示消息，AcceptNotNotify代表接收消息但不提示
        if (options.MsgFlag) {
            opt.MsgFlag = options.MsgFlag;
        }
        if (options.ShutUpTime) {//禁言时间
            opt.ShutUpTime = options.ShutUpTime;
        }
        if (options.NameCard) {//群名片,最大不超过50个字节
            opt.NameCard = options.NameCard;
        }
        if (options.AppMemberDefinedData) {//群成员维度的自定义字段，默认情况是没有的，需要开通
            opt.AppMemberDefinedData = options.AppMemberDefinedData;
        }
        ConnManager.apiCall(SRV_NAME.GROUP, "modify_group_member_info", opt,
            cbOk, cbErr);
    };
    //删除群组成员
    //协议参考：https://www.qcloud.com/doc/product/269/1622
    var proto_deleteGroupMember = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;

        ConnManager.apiCall(SRV_NAME.GROUP, "delete_group_member", {
                'GroupId': options.GroupId,
                'Silence': options.Silence,
                'MemberToDel_Account': options.MemberToDel_Account,
                'Reason': options.Reason
            },
            cbOk, cbErr);
    };
    //解散群组
    //协议参考：https://www.qcloud.com/doc/product/269/1624
    var proto_destroyGroup = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;

        ConnManager.apiCall(SRV_NAME.GROUP, "destroy_group", {
                'GroupId': options.GroupId
            },
            cbOk, cbErr);
    };
    //转让群组
    //协议参考：https://www.qcloud.com/doc/product/269/1633
    var proto_changeGroupOwner = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;
        ConnManager.apiCall(SRV_NAME.GROUP, "change_group_owner", options, cbOk, cbErr);
    };
    //获取用户所加入的群组-高级接口
    //协议参考：https://www.qcloud.com/doc/product/269/1625
    var proto_getJoinedGroupListHigh = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;

        ConnManager.apiCall(SRV_NAME.GROUP, "get_joined_group_list", {
                'Member_Account': options.Member_Account,
                'Limit': options.Limit,
                'Offset': options.Offset,
                'GroupType': options.GroupType,
                'ResponseFilter': {
                    'GroupBaseInfoFilter': options.GroupBaseInfoFilter,
                    'SelfInfoFilter': options.SelfInfoFilter
                }
            },
            cbOk, cbErr);
    };
    //查询一组UserId在群中的身份
    //协议参考：https://www.qcloud.com/doc/product/269/1626
    var proto_getRoleInGroup = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;

        ConnManager.apiCall(SRV_NAME.GROUP, "get_role_in_group", {
                'GroupId': options.GroupId,
                'User_Account': options.User_Account
            },
            cbOk, cbErr);
    };
    //设置取消成员禁言时间
    //协议参考：https://www.qcloud.com/doc/product/269/1627
    var proto_forbidSendMsg = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;

        ConnManager.apiCall(SRV_NAME.GROUP, "forbid_send_msg", {
                'GroupId': options.GroupId,
                'Members_Account': options.Members_Account,
                'ShutUpTime': options.ShutUpTime//单位为秒，为0时表示取消禁言
            },
            cbOk, cbErr);
    };

    //发送自定义群系统通知
    var proto_sendCustomGroupNotify = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;
        ConnManager.apiCall(SRV_NAME.GROUP, "send_group_system_notification", options,
            cbOk, cbErr);
    };

    //拉取群消息接口
    var proto_getGroupMsgs = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;
        ConnManager.apiCall(SRV_NAME.GROUP, "group_msg_get", {
                "GroupId": options.GroupId,
                "ReqMsgSeq": options.ReqMsgSeq,
                "ReqMsgNumber": options.ReqMsgNumber
            },
            cbOk, cbErr);
    };
    //群消息已读上报接口
    var proto_groupMsgReaded = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;
        ConnManager.apiCall(SRV_NAME.GROUP, "msg_read_report", {
                'GroupId': options.GroupId,
                'MsgReadedSeq': options.MsgReadedSeq
            },
            cbOk, cbErr);
    };
    //end

    //好友接口
    //处理好友接口返回的错误码
    var convertErrorEn2ZhFriend = function (resp) {
        var errorAccount = [];
        if (resp.Fail_Account && resp.Fail_Account.length) {
            errorAccount = resp.Fail_Account;
        }
        if (resp.Invalid_Account && resp.Invalid_Account.length) {
            for (var k in resp.Invalid_Account) {
                errorAccount.push(resp.Invalid_Account[k]);
            }
        }
        if (errorAccount.length) {
            resp.ActionStatus = ACTION_STATUS.FAIL;
            resp.ErrorCode = ERROR_CODE_CUSTOM;
            resp.ErrorInfo = '';
            for (var i in errorAccount) {
                var failCount = errorAccount[i];
                for (var j in resp.ResultItem) {
                    if (resp.ResultItem[j].To_Account == failCount) {

                        var resultCode = resp.ResultItem[j].ResultCode;
                        resp.ResultItem[j].ResultInfo = "[" + resultCode + "]" + resp.ResultItem[j].ResultInfo;
                        resp.ErrorInfo += resp.ResultItem[j].ResultInfo + "\n";
                        break;
                    }
                }
            }
        }
        return resp;
    };
    //添加好友
    var proto_applyAddFriend = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;
        ConnManager.apiCall(SRV_NAME.FRIEND, "friend_add", {
                'From_Account': ctx.identifier,
                'AddFriendItem': options.AddFriendItem
            },
            function (resp) {
                var newResp = convertErrorEn2ZhFriend(resp);
                if (newResp.ActionStatus == ACTION_STATUS.FAIL) {
                    if (cbErr) cbErr(newResp);
                } else if (cbOk) {
                    cbOk(newResp);
                }
            }, cbErr);
    };
    //删除好友
    var proto_deleteFriend = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;
        ConnManager.apiCall(SRV_NAME.FRIEND, "friend_delete", {
                'From_Account': ctx.identifier,
                'To_Account': options.To_Account,
                'DeleteType': options.DeleteType
            },
            function (resp) {
                var newResp = convertErrorEn2ZhFriend(resp);
                if (newResp.ActionStatus == ACTION_STATUS.FAIL) {
                    if (cbErr) cbErr(newResp);
                } else if (cbOk) {
                    cbOk(newResp);
                }
            }, cbErr);
    };
    //获取好友申请
    var proto_getPendency = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;
        ConnManager.apiCall(SRV_NAME.FRIEND, "pendency_get", {
                "From_Account": ctx.identifier,
                "PendencyType": options.PendencyType,
                "StartTime": options.StartTime,
                "MaxLimited": options.MaxLimited,
                "LastSequence": options.LastSequence
            },
            cbOk, cbErr);
    };
    //删除好友申请
    var proto_deletePendency = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;
        ConnManager.apiCall(SRV_NAME.FRIEND, "pendency_delete", {
                "From_Account": ctx.identifier,
                "PendencyType": options.PendencyType,
                "To_Account": options.To_Account

            },
            function (resp) {
                var newResp = convertErrorEn2ZhFriend(resp);
                if (newResp.ActionStatus == ACTION_STATUS.FAIL) {
                    if (cbErr) cbErr(newResp);
                } else if (cbOk) {
                    cbOk(newResp);
                }
            }, cbErr);
    };
    //处理好友申请
    var proto_responseFriend = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;
        ConnManager.apiCall(SRV_NAME.FRIEND, "friend_response", {
                'From_Account': ctx.identifier,
                'ResponseFriendItem': options.ResponseFriendItem
            },
            function (resp) {
                var newResp = convertErrorEn2ZhFriend(resp);
                if (newResp.ActionStatus == ACTION_STATUS.FAIL) {
                    if (cbErr) cbErr(newResp);
                } else if (cbOk) {
                    cbOk(newResp);
                }
            }, cbErr);
    };
    //我的好友
    var proto_getAllFriend = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;
        ConnManager.apiCall(SRV_NAME.FRIEND, "friend_get_all", {
                'From_Account': ctx.identifier,
                'TimeStamp': options.TimeStamp,
                'StartIndex': options.StartIndex,
                'GetCount': options.GetCount,
                'LastStandardSequence': options.LastStandardSequence,
                'TagList': options.TagList
            },
            cbOk, cbErr);
    };

    //资料接口
    //查看个人资料
    var proto_getProfilePortrait = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;
        ConnManager.apiCall(SRV_NAME.PROFILE, "portrait_get", {
                'From_Account': ctx.identifier,
                'To_Account': options.To_Account,
                //'LastStandardSequence':options.LastStandardSequence,
                'TagList': options.TagList
            },
            function (resp) {
                var errorAccount = [];
                if (resp.Fail_Account && resp.Fail_Account.length) {
                    errorAccount = resp.Fail_Account;
                }
                if (resp.Invalid_Account && resp.Invalid_Account.length) {
                    for (var k in resp.Invalid_Account) {
                        errorAccount.push(resp.Invalid_Account[k]);
                    }
                }
                if (errorAccount.length) {
                    resp.ActionStatus = ACTION_STATUS.FAIL;
                    resp.ErrorCode = ERROR_CODE_CUSTOM;
                    resp.ErrorInfo = '';
                    for (var i in errorAccount) {
                        var failCount = errorAccount[i];
                        for (var j in resp.UserProfileItem) {
                            if (resp.UserProfileItem[j].To_Account == failCount) {
                                var resultCode = resp.UserProfileItem[j].ResultCode;
                                resp.UserProfileItem[j].ResultInfo = "[" + resultCode + "]" + resp.UserProfileItem[j].ResultInfo;
                                resp.ErrorInfo += "账号:" + failCount + "," + resp.UserProfileItem[j].ResultInfo + "\n";
                                break;
                            }
                        }
                    }
                }
                if (resp.ActionStatus == ACTION_STATUS.FAIL) {
                    if (cbErr) cbErr(resp);
                } else if (cbOk) {
                    cbOk(resp);
                }
            },
            cbErr);
    };

    //设置个人资料
    var proto_setProfilePortrait = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;
        ConnManager.apiCall(SRV_NAME.PROFILE, "portrait_set",
            {
                'From_Account': ctx.identifier,
                'ProfileItem': options.ProfileItem
            },
            function (resp) {
                for (var i in options.ProfileItem) {
                    var profile = options.ProfileItem[i];
                    if (profile.Tag == 'Tag_Profile_IM_Nick') {
                        ctx.identifierNick = profile.Value;//更新昵称
                        break;
                    }
                }
                if (cbOk) cbOk(resp);
            }
            , cbErr);
    };

    //增加黑名单
    var proto_addBlackList = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;
        ConnManager.apiCall(SRV_NAME.FRIEND, "black_list_add", {
                'From_Account': ctx.identifier,
                'To_Account': options.To_Account
            },
            function (resp) {
                var newResp = convertErrorEn2ZhFriend(resp);
                if (newResp.ActionStatus == ACTION_STATUS.FAIL) {
                    if (cbErr) cbErr(newResp);
                } else if (cbOk) {
                    cbOk(newResp);
                }
            }, cbErr);
    };

    //删除黑名单
    var proto_deleteBlackList = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;
        ConnManager.apiCall(SRV_NAME.FRIEND, "black_list_delete", {
                'From_Account': ctx.identifier,
                'To_Account': options.To_Account
            },
            function (resp) {
                var newResp = convertErrorEn2ZhFriend(resp);
                if (newResp.ActionStatus == ACTION_STATUS.FAIL) {
                    if (cbErr) cbErr(newResp);
                } else if (cbOk) {
                    cbOk(newResp);
                }
            }, cbErr);
    };

    //我的黑名单
    var proto_getBlackList = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;
        ConnManager.apiCall(SRV_NAME.FRIEND, "black_list_get", {
                'From_Account': ctx.identifier,
                'StartIndex': options.StartIndex,
                'MaxLimited': options.MaxLimited,
                'LastSequence': options.LastSequence
            },
            cbOk, cbErr);
    };

    //获取最近联系人
    var proto_getRecentContactList = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;
        ConnManager.apiCall(SRV_NAME.RECENT_CONTACT, "get", {
                'From_Account': ctx.identifier,
                'Count': options.Count
            },
            cbOk, cbErr);
    };

    //上传图片或文件
    var proto_uploadPic = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;
        if (isAccessFormalEnv()) {
            cmdName = 'pic_up';
        } else {
            cmdName = 'pic_up_test';
        }
        ConnManager.apiCall(SRV_NAME.PIC, cmdName, {
                'App_Version': VERSION_INFO.APP_VERSION,
                'From_Account': ctx.identifier,
                'To_Account': options.To_Account,
                'Seq': options.Seq,
                'Timestamp': options.Timestamp,
                'Random': options.Random,
                'File_Str_Md5': options.File_Str_Md5,
                'File_Size': options.File_Size,
                'File_Type': options.File_Type,
                'Server_Ver': VERSION_INFO.SERVER_VERSION,
                'Auth_Key': authkey,
                'Busi_Id': options.Busi_Id,
                'PkgFlag': options.PkgFlag,
                'Slice_Offset': options.Slice_Offset,
                'Slice_Size': options.Slice_Size,
                'Slice_Data': options.Slice_Data
            },
            cbOk, cbErr);
    };

    //获取语音和文件下载IP和authkey
    var proto_getIpAndAuthkey = function (cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;
        ConnManager.apiCall(SRV_NAME.OPEN_IM, "authkey", {}, cbOk, cbErr);
    };

    //接口质量上报
    var proto_reportApiQuality = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;
        ConnManager.apiCall(SRV_NAME.IM_OPEN_STAT, "web_report", options, cbOk, cbErr);
    };


    var proto_getLongPollingId = function (options, cbOk, cbErr) {
        if (!checkLogin(cbErr, true)) return;
        ConnManager.apiCall( SRV_NAME.OPEN_IM, "getlongpollingid",{},
            function (resp) {
                cbOk && cbOk(resp);
            }, cbErr);
    }


    var proto_applyDownload = function (options, cbOk, cbErr) {
        //把下载地址push到map中
        ConnManager.apiCall( SRV_NAME.PIC, "apply_download", options, cbOk, cbErr);
    }

    //end
    initBrowserInfo();
    // singleton object ConnManager
    var ConnManager = lowerBR == false
        ? new function () {
        var onConnCallback = null;        //回调函数
        this.init = function (onConnNotify, cbOk, cbErr) {
            if (onConnNotify) onConnCallback = onConnNotify;
        };
        this.callBack = function (info) {
            if (onConnCallback) onConnCallback(info);
        };
        this.clear = function () {
            onConnCallback = null;
        };
        //请求后台服务接口
        this.apiCall = function (type, cmd, data, cbOk, cbErr, timeout, isLongPolling) {
            //封装后台服务接口地址
            var url = getApiUrl(type, cmd, cbOk, cbErr);
            if (url == false) return;
            //发起ajax请求
            ajaxRequestJson("POST", url, data, timeout, isLongPolling, function (resp) {
                var errorCode = null, tempErrorInfo = '';
                if(cmd=='pic_up'){
                    data.Slice_Data='';
                }
                var info = "\n request url: \n" + url + "\n request body: \n" + JSON.stringify(data) + "\n response: \n" + JSON.stringify(resp);
                //成功
                if (resp.ActionStatus == ACTION_STATUS.OK) {
                    log.info("[" + type + "][" + cmd + "]success: " + info);
                    if (cbOk) cbOk(resp);//回调
                    errorCode = 0;
                    tempErrorInfo = '';
                } else {
                    errorCode = resp.ErrorCode;
                    tempErrorInfo = resp.ErrorInfo;
                    //报错
                    if (cbErr) {
                        resp.SrcErrorInfo = resp.ErrorInfo;
                        resp.ErrorInfo = "[" + type + "][" + cmd + "]failed: " + info;
                        if (cmd != 'longpolling' || resp.ErrorCode != longPollingTimeOutErrorCode) {
                            log.error(resp.ErrorInfo);
                        }
                        cbErr(resp);
                    }
                }
                reportApiQuality(cmd, errorCode, tempErrorInfo);//接口质量上报
            }, function (err) {
                cbErr && cbErr(err);
                reportApiQuality(cmd, err.ErrorCode, err.ErrorInfo);//接口质量上报
            });
        };
    }
        : new function () {
        var onConnCallback = null;        //回调函数
        this.init = function (onConnNotify, cbOk, cbErr) {
            if (onConnNotify) onConnCallback = onConnNotify;
        };
        this.callBack = function (info) {
            if (onConnCallback) onConnCallback(info);
        };
        this.clear = function () {
            onConnCallback = null;
        };
        //请求后台服务接口
        this.apiCall = function (type, cmd, data, cbOk, cbErr, timeout, isLongPolling) {
            //封装后台服务接口地址
            var url = getApiUrl(type, cmd, cbOk, cbErr);
            if (url == false) return;
            //发起jsonp请求
            var reqId = jsonpRequestId++,
                cbkey = 'jsonpcallback', // the 'callback' key
                cbval = 'jsonpRequest' + reqId, // the 'callback' value
                script = document.createElement('script'),
                loaded = 0;

            window[cbval] = jsonpCallback;
            script.type = 'text/javascript';
            url = url + "&" + cbkey + "=" + cbval + "&jsonpbody=" + encodeURIComponent(JSON.stringify(data));
            script.src = url;
            script.async = true;

            if (typeof script.onreadystatechange !== 'undefined') {
                // need this for IE due to out-of-order onreadystatechange(), binding script
                // execution to an event listener gives us control over when the script
                // is executed. See http://jaubourg.net/2010/07/loading-script-as-onclick-handler-of.html
                script.event = 'onclick';
                script.htmlFor = script.id = '_jsonpRequest_' + reqId;
            }

            script.onload = script.onreadystatechange = function () {
                if ((this.readyState && this.readyState !== 'complete' && this.readyState !== 'loaded') || loaded) {
                    return false;
                }
                script.onload = script.onreadystatechange = null;
                script.onclick && script.onclick();
                // Call the user callback with the last value stored and clean up values and scripts.
                var resp = jsonpLastRspData;
                var info = "\n request url: \n" + url + "\n request body: \n" + JSON.stringify(data) + "\n response: \n" + JSON.stringify(resp);
                if (resp.ActionStatus == ACTION_STATUS.OK) {
                    log.info("[" + type + "][" + cmd + "]success: " + info);
                    cbOk && cbOk(resp);
                } else {
                    resp.ErrorInfo = "[" + type + "][" + cmd + "]failed " + info;
                    if (cmd != 'longpolling' || resp.ErrorCode != longPollingTimeOutErrorCode) {
                        log.error(resp.ErrorInfo);
                    } else {
                        log.warn("[" + type + "][" + cmd + "]success: " + info);
                    }
                    cbErr && cbErr(resp);
                }
                jsonpLastRspData = undefined;
                document.body.removeChild(script);
                loaded = 1;
            };

            // Add the script to the DOM head
            document.body.appendChild(script);
        };
    };
    // class Session
    var Session = function (type, id, name, icon, time, seq) {
        this._impl = {
            skey: Session.skey(type, id),
            type: type,
            id: id,
            name: name,
            icon: icon,
            unread: 0,//未读消息数
            isAutoRead: false,
            time: time >= 0 ? time : 0,
            curMaxMsgSeq: seq >= 0 ? seq : 0,
            msgs: [],
            isFinished : 1
        };
    };
    Session.skey = function (type, id) {
        return type + id;
    };
    Session.prototype.type = function () {
        return this._impl.type;
    };
    Session.prototype.id = function () {
        return this._impl.id;
    };
    Session.prototype.name = function () {
        return this._impl.name;
    };
    Session.prototype.icon = function () {
        return this._impl.icon;
    };
    Session.prototype.unread = function (val) {
        if(typeof val !== 'undefined'){
            this._impl.unread = val;
        }else{
            return this._impl.unread;
        }
    };
    Session.prototype.isFinished = function (val) {
        if(typeof val !== 'undefined'){
            this._impl.isFinished = val;
        }else{
            return this._impl.isFinished;
        }
    };
    Session.prototype.time = function () {
        return this._impl.time;
    };
    Session.prototype.curMaxMsgSeq = function (seq) {
        if(typeof seq !== 'undefined'){
            this._impl.curMaxMsgSeq = seq;
        }else{
            return this._impl.curMaxMsgSeq;
        }
    };
    Session.prototype.msgCount = function () {
        return this._impl.msgs.length;
    };
    Session.prototype.msg = function (index) {
        return this._impl.msgs[index];
    };
    Session.prototype.msgs = function () {
        return this._impl.msgs;
    };
    Session.prototype._impl_addMsg = function (msg) {
        this._impl.msgs.push(msg);
        //if (!msg.isSend && msg.time > this._impl.time)
        if (msg.time > this._impl.time)
            this._impl.time = msg.time;
        //if (!msg.isSend && msg.seq > this._impl.curMaxMsgSeq)
        if (msg.seq > this._impl.curMaxMsgSeq)
            this._impl.curMaxMsgSeq = msg.seq;
        //自己发送的消息不计入未读数
        if (!msg.isSend && !this._impl.isAutoRead) {
            this._impl.unread++;
        }
    };
    //class C2CMsgReadedItem
    var C2CMsgReadedItem = function (toAccount, lastedMsgTime) {
        this.toAccount = toAccount;
        this.lastedMsgTime = lastedMsgTime;
    }
    // class Msg
    var Msg = function (sess, isSend, seq, random, time, fromAccount, subType, fromAccountNick) {
        this.sess = sess;
        this.subType = subType >= 0 ? subType : 0;//消息类型,c2c消息时，type取值为0；group消息时，type取值0和1，0-普通群消息，1-群提示消息
        this.fromAccount = fromAccount;
        this.fromAccountNick = fromAccountNick ? fromAccountNick : fromAccount;
        this.isSend = Boolean(isSend);
        this.seq = seq >= 0 ? seq : nextSeq();
        this.random = random >= 0 ? random : createRandom();
        this.time = time >= 0 ? time : unixtime();
        this.elems = [];
    };
    Msg.prototype.getSession = function () {
        return this.sess;
    };
    Msg.prototype.getType = function () {
        return this.subType;
    };
    Msg.prototype.getSubType = function () {
        return this.subType;
    };
    Msg.prototype.getFromAccount = function () {
        return this.fromAccount;
    };
    Msg.prototype.getFromAccountNick = function () {
        return this.fromAccountNick;
    };
    Msg.prototype.getIsSend = function () {
        return this.isSend;
    };
    Msg.prototype.getSeq = function () {
        return this.seq;
    };
    Msg.prototype.getTime = function () {
        return this.time;
    };
    Msg.prototype.getRandom = function () {
        return this.random;
    };
    Msg.prototype.getElems = function () {
        return this.elems;
    };
    //文本
    Msg.prototype.addText = function (text) {
        this.addElem(new webim.Msg.Elem(MSG_ELEMENT_TYPE.TEXT, text));
    };
    //表情
    Msg.prototype.addFace = function (face) {
        this.addElem(new webim.Msg.Elem(MSG_ELEMENT_TYPE.FACE, face));
    };
    //图片
    Msg.prototype.addImage = function (image) {
        this.addElem(new webim.Msg.Elem(MSG_ELEMENT_TYPE.IMAGE, image));
    };
    //地理位置
    Msg.prototype.addLocation = function (location) {
        this.addElem(new webim.Msg.Elem(MSG_ELEMENT_TYPE.LOCATION, location));
    };
    //文件
    Msg.prototype.addFile = function (file) {
        this.addElem(new webim.Msg.Elem(MSG_ELEMENT_TYPE.FILE, file));
    };
    //自定义
    Msg.prototype.addCustom = function (custom) {
        this.addElem(new webim.Msg.Elem(MSG_ELEMENT_TYPE.CUSTOM, custom));
    };
    Msg.prototype.addElem = function (elem) {
        this.elems.push(elem);
    };
    Msg.prototype.toHtml = function () {
        var html = "";
        for (var i in this.elems) {
            var elem = this.elems[i];
            html += elem.toHtml();
        }
        return html;
    };

    // class Msg.Elem
    Msg.Elem = function (type, value) {
        this.type = type;
        this.content = value;
    };
    Msg.Elem.prototype.getType = function () {
        return this.type;
    };
    Msg.Elem.prototype.getContent = function () {
        return this.content;
    };
    Msg.Elem.prototype.toHtml = function () {
        var html;
        html = this.content.toHtml();
        return html;
    };

    // class Msg.Elem.Text
    Msg.Elem.Text = function (text) {
        this.text = tool.xssFilter(text);
    };
    Msg.Elem.Text.prototype.getText = function () {
        return this.text;
    };
    Msg.Elem.Text.prototype.toHtml = function () {
        return this.text;
    };

    // class Msg.Elem.Face
    Msg.Elem.Face = function (index, data) {
        this.index = index;
        this.data = data;
    };
    Msg.Elem.Face.prototype.getIndex = function () {
        return this.index;
    };
    Msg.Elem.Face.prototype.getData = function () {
        return this.data;
    };
    Msg.Elem.Face.prototype.toHtml = function () {
        var faceUrl = null;
        var index = emotionDataIndexs[this.data];
        var emotion = emotions[index];
        if (emotion && emotion[1]) {
            faceUrl = emotion[1];
        }
        if (faceUrl) {
            return "<img src='" + faceUrl + "'/>";
        } else {
            return this.data;
        }
    };

    // 地理位置消息 class Msg.Elem.Location
    Msg.Elem.Location = function (longitude, latitude, desc) {
        this.latitude = latitude;//纬度
        this.longitude = longitude;//经度
        this.desc = desc;//描述
    };
    Msg.Elem.Location.prototype.getLatitude = function () {
        return this.latitude;
    };
    Msg.Elem.Location.prototype.getLongitude = function () {
        return this.longitude;
    };
    Msg.Elem.Location.prototype.getDesc = function () {
        return this.desc;
    };
    Msg.Elem.Location.prototype.toHtml = function () {
        return '经度=' + this.longitude + ',纬度=' + this.latitude + ',描述=' + this.desc;
    };

    //图片消息
    // class Msg.Elem.Images
    Msg.Elem.Images = function (imageId) {
        this.UUID = imageId;
        this.ImageInfoArray = [];
    };
    Msg.Elem.Images.prototype.addImage = function (image) {
        this.ImageInfoArray.push(image);
    };
    Msg.Elem.Images.prototype.toHtml = function () {
        var smallImage = this.getImage(IMAGE_TYPE.SMALL);
        var bigImage = this.getImage(IMAGE_TYPE.LARGE);
        var oriImage = this.getImage(IMAGE_TYPE.ORIGIN);
        if (!bigImage) {
            bigImage = smallImage;
        }
        if (!oriImage) {
            oriImage = smallImage;
        }
        return "<img src='" + smallImage.getUrl() + "#" + bigImage.getUrl() + "#" + oriImage.getUrl() + "' style='CURSOR: hand' id='" + this.getImageId() + "' bigImgUrl='" + bigImage.getUrl() + "' onclick='imageClick(this)' />";

    };
    Msg.Elem.Images.prototype.getImageId = function () {
        return this.UUID;
    };
    Msg.Elem.Images.prototype.getImage = function (type) {
        for (var i in this.ImageInfoArray) {
            if (this.ImageInfoArray[i].getType() == type) {
                return this.ImageInfoArray[i];
            }
        }
        return null;
    };
    // class Msg.Elem.Images.Image
    Msg.Elem.Images.Image = function (type, size, width, height, url) {
        this.type = type;
        this.size = size;
        this.width = width;
        this.height = height;
        this.url = url;
    };
    Msg.Elem.Images.Image.prototype.getType = function () {
        return this.type;
    };
    Msg.Elem.Images.Image.prototype.getSize = function () {
        return this.size;
    };
    Msg.Elem.Images.Image.prototype.getWidth = function () {
        return this.width;
    };
    Msg.Elem.Images.Image.prototype.getHeight = function () {
        return this.height;
    };
    Msg.Elem.Images.Image.prototype.getUrl = function () {
        return this.url;
    };

    // class Msg.Elem.Sound
    Msg.Elem.Sound = function (uuid, second, size, senderId, receiverId, downFlag,  chatType) {
        this.uuid = uuid;//文件id
        this.second = second;//时长，单位：秒
        this.size = size;//大小，单位：字节
        this.senderId = senderId;//发送者
        this.receiverId = receiverId;//接收方id
        this.downFlag = downFlag;//下载标志位
        this.busiId = chatType == SESSION_TYPE.C2C ? 2 : 1;//busi_id ( 1：群    2:C2C)

        //根据不同情况拉取数据
        //是否需要申请下载地址  0:到架平申请  1:到cos申请  2:不需要申请, 直接拿url下载
        if(downFlag !== undefined && busiId !== undefined){
            getFileDownUrlV2(uuid, senderId, second, downFlag,receiverId,  this.busiId , UPLOAD_RES_TYPE.SOUND);
        }else{
            this.downUrl = getSoundDownUrl(uuid, senderId, second);//下载地址
        }
    };
    Msg.Elem.Sound.prototype.getUUID = function () {
        return this.uuid;
    };
    Msg.Elem.Sound.prototype.getSecond = function () {
        return this.second;
    };
    Msg.Elem.Sound.prototype.getSize = function () {
        return this.size;
    };
    Msg.Elem.Sound.prototype.getSenderId = function () {
        return this.senderId;
    };
    Msg.Elem.Sound.prototype.getDownUrl = function () {
        return this.downUrl;
    };
    Msg.Elem.Sound.prototype.toHtml = function () {
        if (BROWSER_INFO.type == 'ie' && parseInt(BROWSER_INFO.ver) <= 8) {
            return '[这是一条语音消息]demo暂不支持ie8(含)以下浏览器播放语音,语音URL:' + this.downUrl;
        }
        return '<audio id="uuid_'+this.uuid+'" src="' + this.downUrl + '" controls="controls" onplay="onChangePlayAudio(this)" preload="none"></audio>';
    };

    // class Msg.Elem.File
    Msg.Elem.File = function (uuid, name, size, senderId, receiverId, downFlag, chatType) {
        this.uuid = uuid;//文件id
        this.name = name;//文件名
        this.size = size;//大小，单位：字节
        this.senderId = senderId;//发送者
        this.receiverId = receiverId;//接收方id
        this.downFlag = downFlag;//下载标志位

        this.busiId = chatType == SESSION_TYPE.C2C ? 2 : 1;//busi_id ( 1：群    2:C2C)
        //根据不同情况拉取数据
        //是否需要申请下载地址  0:到架平申请  1:到cos申请  2:不需要申请, 直接拿url下载
        if(downFlag !== undefined && busiId !== undefined){
            getFileDownUrlV2(uuid, senderId, name, downFlag,receiverId,  this.busiId , UPLOAD_RES_TYPE.FILE);
        }else{
            this.downUrl = getFileDownUrl(uuid, senderId, name);//下载地址
        }
    };
    Msg.Elem.File.prototype.getUUID = function () {
        return this.uuid;
    };
    Msg.Elem.File.prototype.getName = function () {
        return this.name;
    };
    Msg.Elem.File.prototype.getSize = function () {
        return this.size;
    };
    Msg.Elem.File.prototype.getSenderId = function () {
        return this.senderId;
    };
    Msg.Elem.File.prototype.getDownUrl = function () {
        return this.downUrl;
    };
    Msg.Elem.File.prototype.getDownFlag = function () {
        return this.downFlag;
    };
    Msg.Elem.File.prototype.toHtml = function () {
        var fileSize, unitStr;
        fileSize = this.size;
        unitStr = "Byte";
        if (this.size >= 1024) {
            fileSize = Math.round(this.size / 1024);
            unitStr = "KB";
        }
        return '<a href="javascript" onclick="webim.onDownFile("'+this.uuid+'")" title="点击下载文件" ><i class="glyphicon glyphicon-file">&nbsp;' + this.name + '(' + fileSize + unitStr + ')</i></a>';
    };

    // class Msg.Elem.GroupTip 群提示消息对象
    Msg.Elem.GroupTip = function (opType, opUserId, groupId, groupName, userIdList) {
        this.opType = opType;//操作类型
        this.opUserId = opUserId;//操作者id
        this.groupId = groupId;//群id
        this.groupName = groupName;//群名称
        this.userIdList = userIdList ? userIdList : [];//被操作的用户id列表
        this.groupInfoList = [];//新的群资料信息，群资料变更时才有值
        this.memberInfoList = [];//新的群成员资料信息，群成员资料变更时才有值
        this.groupMemberNum = null;//群成员数，操作类型为加群或者退群时才有值
    };
    Msg.Elem.GroupTip.prototype.addGroupInfo = function (groupInfo) {
        this.groupInfoList.push(groupInfo);
    };
    Msg.Elem.GroupTip.prototype.addMemberInfo = function (memberInfo) {
        this.memberInfoList.push(memberInfo);
    };
    Msg.Elem.GroupTip.prototype.getOpType = function () {
        return this.opType;
    };
    Msg.Elem.GroupTip.prototype.getOpUserId = function () {
        return this.opUserId;
    };
    Msg.Elem.GroupTip.prototype.getGroupId = function () {
        return this.groupId;
    };
    Msg.Elem.GroupTip.prototype.getGroupName = function () {
        return this.groupName;
    };
    Msg.Elem.GroupTip.prototype.getUserIdList = function () {
        return this.userIdList;
    };
    Msg.Elem.GroupTip.prototype.getGroupInfoList = function () {
        return this.groupInfoList;
    };
    Msg.Elem.GroupTip.prototype.getMemberInfoList = function () {
        return this.memberInfoList;
    };
    Msg.Elem.GroupTip.prototype.getGroupMemberNum = function () {
        return this.groupMemberNum;
    };
    Msg.Elem.GroupTip.prototype.setGroupMemberNum = function (groupMemberNum) {
        return this.groupMemberNum = groupMemberNum;
    };
    Msg.Elem.GroupTip.prototype.toHtml = function () {
        var text = "[群提示消息]";
        var maxIndex = GROUP_TIP_MAX_USER_COUNT - 1;
        switch (this.opType) {
            case GROUP_TIP_TYPE.JOIN://加入群
                text += this.opUserId + "邀请了";
                for (var m in this.userIdList) {
                    text += this.userIdList[m] + ",";
                    if (this.userIdList.length > GROUP_TIP_MAX_USER_COUNT && m == maxIndex) {
                        text += "等" + this.userIdList.length + "人";
                        break;
                    }
                }
                text += "加入该群";
                break;
            case GROUP_TIP_TYPE.QUIT://退出群
                text += this.opUserId + "主动退出该群";
                break;
            case GROUP_TIP_TYPE.KICK://踢出群
                text += this.opUserId + "将";
                for (var m in this.userIdList) {
                    text += this.userIdList[m] + ",";
                    if (this.userIdList.length > GROUP_TIP_MAX_USER_COUNT && m == maxIndex) {
                        text += "等" + this.userIdList.length + "人";
                        break;
                    }
                }
                text += "踢出该群";
                break;
            case GROUP_TIP_TYPE.SET_ADMIN://设置管理员
                text += this.opUserId + "将";
                for (var m in this.userIdList) {
                    text += this.userIdList[m] + ",";
                    if (this.userIdList.length > GROUP_TIP_MAX_USER_COUNT && m == maxIndex) {
                        text += "等" + this.userIdList.length + "人";
                        break;
                    }
                }
                text += "设为管理员";
                break;
            case GROUP_TIP_TYPE.CANCEL_ADMIN://取消管理员
                text += this.opUserId + "取消";
                for (var m in this.userIdList) {
                    text += this.userIdList[m] + ",";
                    if (this.userIdList.length > GROUP_TIP_MAX_USER_COUNT && m == maxIndex) {
                        text += "等" + this.userIdList.length + "人";
                        break;
                    }
                }
                text += "的管理员资格";
                break;


            case GROUP_TIP_TYPE.MODIFY_GROUP_INFO://群资料变更
                text += this.opUserId + "修改了群资料：";
                for (var m in this.groupInfoList) {
                    var type = this.groupInfoList[m].getType();
                    var value = this.groupInfoList[m].getValue();
                    switch (type) {
                        case GROUP_TIP_MODIFY_GROUP_INFO_TYPE.FACE_URL:
                            text += "群头像为" + value + "; ";
                            break;
                        case GROUP_TIP_MODIFY_GROUP_INFO_TYPE.NAME:
                            text += "群名称为" + value + "; ";
                            break;
                        case GROUP_TIP_MODIFY_GROUP_INFO_TYPE.OWNER:
                            text += "群主为" + value + "; ";
                            break;
                        case GROUP_TIP_MODIFY_GROUP_INFO_TYPE.NOTIFICATION:
                            text += "群公告为" + value + "; ";
                            break;
                        case GROUP_TIP_MODIFY_GROUP_INFO_TYPE.INTRODUCTION:
                            text += "群简介为" + value + "; ";
                            break;
                        default:
                            text += "未知信息为:type=" + type + ",value=" + value + "; ";
                            break;
                    }
                }
                break;

            case GROUP_TIP_TYPE.MODIFY_MEMBER_INFO://群成员资料变更(禁言时间)
                text += this.opUserId + "修改了群成员资料:";
                for (var m in this.memberInfoList) {
                    var userId = this.memberInfoList[m].getUserId();
                    var shutupTime = this.memberInfoList[m].getShutupTime();
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
                    if (this.memberInfoList.length > GROUP_TIP_MAX_USER_COUNT && m == maxIndex) {
                        text += "等" + this.memberInfoList.length + "人";
                        break;
                    }
                }
                break;

            case GROUP_TIP_TYPE.READED://消息已读
                /**/
                Log.info("消息已读同步")
                break;
            default:
                text += "未知群提示消息类型：type=" + this.opType;
                break;
        }
        return text;
    };

    // class Msg.Elem.GroupTip.GroupInfo，变更的群资料信息对象
    Msg.Elem.GroupTip.GroupInfo = function (type, value) {
        this.type = type;//群资料信息类型
        this.value = value;//对应的值
    };
    Msg.Elem.GroupTip.GroupInfo.prototype.getType = function () {
        return this.type;
    };
    Msg.Elem.GroupTip.GroupInfo.prototype.getValue = function () {
        return this.value;
    };

    // class Msg.Elem.GroupTip.MemberInfo，变更的群成员资料信息对象
    Msg.Elem.GroupTip.MemberInfo = function (userId, shutupTime) {
        this.userId = userId;//群成员id
        this.shutupTime = shutupTime;//群成员被禁言时间，0表示取消禁言，大于0表示被禁言时长，单位：秒
    };
    Msg.Elem.GroupTip.MemberInfo.prototype.getUserId = function () {
        return this.userId;
    };
    Msg.Elem.GroupTip.MemberInfo.prototype.getShutupTime = function () {
        return this.shutupTime;
    };

    // 自定义消息类型 class Msg.Elem.Custom
    Msg.Elem.Custom = function (data, desc, ext) {
        this.data = data;//数据
        this.desc = desc;//描述
        this.ext = ext;//扩展字段
    };
    Msg.Elem.Custom.prototype.getData = function () {
        return this.data;
    };
    Msg.Elem.Custom.prototype.getDesc = function () {
        return this.desc;
    };
    Msg.Elem.Custom.prototype.getExt = function () {
        return this.ext;
    };
    Msg.Elem.Custom.prototype.toHtml = function () {
        return this.data;
    };

    // singleton object MsgStore
    var MsgStore = new function () {
        var sessMap = {};//跟所有用户或群的聊天记录MAP
        var sessTimeline = [];//按时间降序排列的会话列表
       window.msgCache = {};//消息缓存，用于判重
        //C2C
        this.cookie = "";//上一次拉取新c2c消息的cookie
        this.syncFlag = 0;//上一次拉取新c2c消息的是否继续拉取标记

        var visitSess = function (visitor) {
            for (var i in sessMap) {
                visitor(sessMap[i]);
            }
        };
        // window.msgCache = msgCache;
        //消息查重
        var checkDupMsg = function (msg) {
            var dup = false;
            var first_key = msg.sess._impl.skey;
            var second_key = msg.isSend + msg.seq + msg.random;
            var tempMsg = msgCache[first_key] && msgCache[first_key][second_key];
            if (tempMsg){
                dup = true;
            }
            if (msgCache[first_key]) {
                msgCache[first_key][second_key] = {time: msg.time};
            } else {
                msgCache[first_key] = {};
                msgCache[first_key][second_key] = {time: msg.time};
            }
            return dup;
        };

        this.sessMap = function () {
            return sessMap;
        };
        this.sessCount = function () {
            return sessTimeline.length;
        };
        this.sessByTypeId = function (type, id) {
            var skey = Session.skey(type, id);
            if (skey === undefined || skey == null) return null;
            return sessMap[skey];
        };
        this.delSessByTypeId = function (type, id) {
            var skey = Session.skey(type, id);
            if (skey === undefined || skey == null) return false;
            if (sessMap[skey]) {
                delete sessMap[skey];
                delete msgCache[skey];
            }
            return true;
        };
        this.resetCookieAndSyncFlag = function () {
            this.cookie = "";
            this.syncFlag = 0;
        };

        //切换将当前会话的自动读取消息标志为isOn,重置其他会话的自动读取消息标志为false
        this.setAutoRead = function (selSess, isOn, isResetAll) {
            if (isResetAll)
                visitSess(function (s) {
                    s._impl.isAutoRead = false;
                });
            if (selSess) {
                selSess._impl.isAutoRead = isOn;//
                if (isOn) {//是否调用已读上报接口
                    selSess._impl.unread = 0;

                    if (selSess._impl.type == SESSION_TYPE.C2C) {//私聊消息已读上报
                        var tmpC2CMsgReadedItem = [];
                        tmpC2CMsgReadedItem.push(new C2CMsgReadedItem(selSess._impl.id, selSess._impl.time));
                        //调用C2C消息已读上报接口
                        proto_c2CMsgReaded(MsgStore.cookie,
                            tmpC2CMsgReadedItem,
                            function (resp) {
                                log.info("[setAutoRead]: c2CMsgReaded success");
                            },
                            function (err) {
                                log.error("[setAutoRead}: c2CMsgReaded failed:" + err.ErrorInfo);
                            });
                    } else if (selSess._impl.type == SESSION_TYPE.GROUP) {//群聊消息已读上报
                        var tmpOpt = {
                            'GroupId': selSess._impl.id,
                            'MsgReadedSeq': selSess._impl.curMaxMsgSeq
                        };
                        //调用group消息已读上报接口
                        proto_groupMsgReaded(tmpOpt,
                            function (resp) {
                                log.info("groupMsgReaded success");

                            },
                            function (err) {
                                log.error("groupMsgReaded failed:" + err.ErrorInfo);

                            });
                    }
                }
            }
        };

        this.c2CMsgReaded = function (opts, cbOk, cbErr) {
            var tmpC2CMsgReadedItem = [];
            tmpC2CMsgReadedItem.push(new C2CMsgReadedItem(opts.To_Account, opts.LastedMsgTime));
            //调用C2C消息已读上报接口
            proto_c2CMsgReaded(MsgStore.cookie,
                tmpC2CMsgReadedItem,
                function (resp) {
                    if (cbOk) {
                        log.info("c2CMsgReaded success");
                        cbOk(resp);
                    }
                },
                function (err) {
                    if (cbErr) {
                        log.error("c2CMsgReaded failed:" + err.ErrorInfo);
                        cbErr(err);
                    }
                });
        };

        this.addSession = function (sess) {
            sessMap[sess._impl.skey] = sess;
        };
        this.delSession = function (sess) {
            delete sessMap[sess._impl.skey];
        };
        this.addMsg = function (msg) {
            if (checkDupMsg(msg)) return false;
            var sess = msg.sess;
            if (!sessMap[sess._impl.skey]) this.addSession(sess);
            sess._impl_addMsg(msg);
            return true;
        };
        this.updateTimeline = function () {
            var arr = new Array;
            visitSess(function (sess) {
                arr.push(sess);
            });
            arr.sort(function (a, b) {
                return b.time - a.time;
            });
            sessTimeline = arr;
        };
    };
    // singleton object MsgManager
    var MsgManager = new function () {

        var onMsgCallback = null;//新消息(c2c和group)回调

        var onGroupInfoChangeCallback = null;//群资料变化回调
        //收到新群系统消息回调列表
        var onGroupSystemNotifyCallbacks = {
            "1": null,
            "2": null,
            "3": null,
            "4": null,
            "5": null,
            "6": null,
            "7": null,
            "8": null,
            "9": null,
            "10": null,
            "11": null,
            "15": null,
            "255": null
        };
        //监听好友系统通知函数
        var onFriendSystemNotifyCallbacks={
            "1":null,
            "2":null,
            "3":null,
            "4":null,
            "5":null,
            "6":null,
            "7":null,
            "8":null
        };

        var onProfileSystemNotifyCallbacks= {
            "1" :null
        };

        var onMsgReadCallback = null;

        //普通长轮询
        var longPollingOn = false;//是否开启普通长轮询
        var isLongPollingRequesting = false;//是否在长轮询ing
        var notifySeq = 0;//c2c通知seq
        var noticeSeq = 0;//群消息seq

        //大群长轮询
        var onBigGroupMsgCallback = null;//大群消息回调
        var bigGroupLongPollingOn = false;//是否开启长轮询
        var bigGroupLongPollingStartSeq = 0;//请求拉消息的起始seq(大群长轮询)
        var bigGroupLongPollingHoldTime = 90;//客户端长轮询的超时时间，单位是秒(大群长轮询)
        var bigGroupLongPollingKey = null;//客户端加入群组后收到的的Key(大群长轮询)
        var bigGroupLongPollingMsgMap = {};//记录收到的群消息数


        var getLostGroupMsgCount = 0;//补拉丢失的群消息次数
        //我的群当前最大的seq
        var myGroupMaxSeqs = {};//用于补拉丢失的群消息

        var groupSystemMsgsCache = {};//群组系统消息缓存,用于判重

        //设置长轮询开关
        //isOn=true 开启
        //isOn=false 停止
        this.setLongPollingOn = function (isOn) {
            longPollingOn = isOn;
        };
        this.getLongPollingOn = function () {
            return longPollingOn;
        };

        //重置长轮询变量
        this.resetLongPollingInfo = function () {
            longPollingOn = false;
            notifySeq = 0;
            noticeSeq = 0;
        };

        //设置大群长轮询开关
        //isOn=true 开启
        //isOn=false 停止
        this.setBigGroupLongPollingOn = function (isOn) {
            bigGroupLongPollingOn = isOn;
        };
        //设置大群长轮询key
        this.setBigGroupLongPollingKey = function (key) {
            bigGroupLongPollingKey = key;
        };
        //重置大群长轮询变量
        this.resetBigGroupLongPollingInfo = function () {
            bigGroupLongPollingOn = false;
            bigGroupLongPollingStartSeq = 0;
            bigGroupLongPollingKey = null;
            bigGroupLongPollingMsgMap = {};
        };

        //设置群消息数据条数
        this.setBigGroupLongPollingMsgMap = function (groupId, count) {
            var bigGroupLongPollingMsgCount = bigGroupLongPollingMsgMap[groupId];
            if (bigGroupLongPollingMsgCount) {
                bigGroupLongPollingMsgCount = parseInt(bigGroupLongPollingMsgCount) + count;
                bigGroupLongPollingMsgMap[groupId] = bigGroupLongPollingMsgCount;
            } else {
                bigGroupLongPollingMsgMap[groupId] = count;
            }
        };

        //重置
        this.clear = function () {

            onGroupInfoChangeCallback = null;
            onGroupSystemNotifyCallbacks = {
                "1": null,//申请加群请求（只有管理员会收到）
                "2": null,//申请加群被同意（只有申请人能够收到）
                "3": null,//申请加群被拒绝（只有申请人能够收到）
                "4": null,//被管理员踢出群(只有被踢者接收到)
                "5": null,//群被解散(全员接收)
                "6": null,//创建群(创建者接收)
                "7": null,//邀请加群(被邀请者接收)
                "8": null,//主动退群(主动退出者接收)
                "9": null,//设置管理员(被设置者接收)
                "10": null,//取消管理员(被取消者接收)
                "11": null,//群已被回收(全员接收)
                "15": null,//群已被回收(全员接收)
                "255": null//用户自定义通知(默认全员接收)
            };
            onFriendSystemNotifyCallbacks = {
                "1": null,//好友表增加
                "2": null,//好友表删除
                "3": null,//未决增加
                "4": null,//未决删除
                "5": null,//黑名单增加
                "6": null,//黑名单删除
                "7": null,//未决已读上报
                "8": null//好友信息(备注，分组)变更
            };
            onProfileSystemNotifyCallbacks = {
                "1": null//资料修改
            };
            //重置普通长轮询参数
            onMsgCallback = null;
            longPollingOn = false;
            notifySeq = 0;//c2c新消息通知seq
            noticeSeq = 0;//group新消息seq

            //重置大群长轮询参数
            onBigGroupMsgCallback = null;
            bigGroupLongPollingOn = false;
            bigGroupLongPollingStartSeq = 0;
            bigGroupLongPollingKey = null;
            bigGroupLongPollingMsgMap = {};

            groupSystemMsgsCache = {};

            ipList = [];//文件下载地址
            authkey = null;//文件下载票据
            expireTime = null;//票据超时时间
        };

        //初始化文件下载ip和票据
        var initIpAndAuthkey = function (cbOk, cbErr) {
            proto_getIpAndAuthkey(function (resp) {
                    ipList = resp.IpList;
                    authkey = resp.AuthKey;
                    expireTime = resp.ExpireTime;
                    if (cbOk) cbOk(resp);
                },
                function (err) {
                    log.error("initIpAndAuthkey failed:" + err.ErrorInfo);
                    if (cbErr) cbErr(err);
                }
            );
        };

        //初始化我的群当前最大的seq，用于补拉丢失的群消息
        var initMyGroupMaxSeqs = function (cbOk, cbErr) {
            var opts = {
                'Member_Account': ctx.identifier,
                'Limit': 1000,
                'Offset': 0,
                'GroupBaseInfoFilter': [
                    'NextMsgSeq'
                ]
            };
            proto_getJoinedGroupListHigh(opts, function (resp) {
                    if (!resp.GroupIdList || resp.GroupIdList.length == 0) {
                        log.info("initMyGroupMaxSeqs: 目前还没有加入任何群组");
                        if (cbOk) cbOk(resp);
                        return;
                    }
                    for (var i = 0; i < resp.GroupIdList.length; i++) {
                        var group_id = resp.GroupIdList[i].GroupId;
                        var curMaxSeq = resp.GroupIdList[i].NextMsgSeq - 1;
                        myGroupMaxSeqs[group_id] = curMaxSeq;
                    }

                    if (cbOk) cbOk(resp);

                },
                function (err) {
                    log.error("initMyGroupMaxSeqs failed:" + err.ErrorInfo);
                    if (cbErr) cbErr(err);
                }
            );
        };

        //补拉群消息
        var getLostGroupMsgs = function (groupId, reqMsgSeq, reqMsgNumber) {
            getLostGroupMsgCount++;
            //发起一个拉群群消息请求
            var tempOpts = {
                'GroupId': groupId,
                'ReqMsgSeq': reqMsgSeq,
                'ReqMsgNumber': reqMsgNumber
            };
            //发起一个拉群群消息请求
            log.warn("第" + getLostGroupMsgCount + "次补齐群消息,参数=" + JSON.stringify(tempOpts));
            MsgManager.syncGroupMsgs(tempOpts);
        };

        //更新群当前最大消息seq
        var updateMyGroupCurMaxSeq=function(groupId,msgSeq){
            //更新myGroupMaxSeqs中的群最大seq
            var curMsgSeq=myGroupMaxSeqs[groupId]
            if(curMsgSeq){//如果存在，比较大小
                if(msgSeq>curMsgSeq){
                    myGroupMaxSeqs[groupId]=msgSeq;
                }
            }else{//不存在，新增
                myGroupMaxSeqs[groupId]=msgSeq;
            }
        };

        //添加群消息列表
        var addGroupMsgList = function (msgs, new_group_msgs) {
            for (var p in msgs) {
                var newGroupMsg = msgs[p];
                //发群消息时，长轮询接口会返回用户自己发的群消息
                //if(newGroupMsg.From_Account && newGroupMsg.From_Account!=ctx.identifier ){
                if (newGroupMsg.From_Account) {
                    //false-不是主动拉取的历史消息
                    //true-需要保存到sdk本地session,并且需要判重
                    var msg = handlerGroupMsg(newGroupMsg, false, true);
                    if (msg) {//不为空，加到新消息里
                        new_group_msgs.push(msg);
                    }
                    //更新myGroupMaxSeqs中的群最大seq
                    updateMyGroupCurMaxSeq(newGroupMsg.ToGroupId,newGroupMsg.MsgSeq);
                }
            }
            return new_group_msgs;
        };

        //处理收到的群普通和提示消息
        var handlerOrdinaryAndTipC2cMsgs = function (eventType, groupMsgArray) {
            var groupMsgMap = {};//保存收到的C2c消息信息（群号，最小，最大消息seq，消息列表）
            var new_group_msgs = [];
            var minGroupMsgSeq = 99999999;
            var maxGroupMsgSeq = -1;
            for (var j in groupMsgArray) {

                var groupMsgs = groupMsgMap[groupMsgArray[j].ToGroupId];
                if (!groupMsgs) {
                    groupMsgs = groupMsgMap[groupMsgArray[j].ToGroupId] = {
                        "min": minGroupMsgSeq,//收到新消息最小seq
                        "max": maxGroupMsgSeq,//收到新消息最大seq
                        "msgs": []//收到的新消息
                    };
                }
                //更新长轮询的群NoticeSeq
                if (groupMsgArray[j].NoticeSeq > noticeSeq) {
                    log.warn("noticeSeq=" + noticeSeq + ",msgNoticeSeq=" + groupMsgArray[j].NoticeSeq);
                    noticeSeq = groupMsgArray[j].NoticeSeq;
                }
                groupMsgArray[j].Event = eventType;
                groupMsgMap[groupMsgArray[j].ToGroupId].msgs.push(groupMsgArray[j]);//新增一条消息
                if (groupMsgArray[j].MsgSeq < groupMsgs.min) {//记录最小的消息seq
                    groupMsgMap[groupMsgArray[j].ToGroupId].min = groupMsgArray[j].MsgSeq;
                }
                if (groupMsgArray[j].MsgSeq > groupMsgs.max) {//记录最大的消息seq
                    groupMsgMap[groupMsgArray[j].ToGroupId].max = groupMsgArray[j].MsgSeq;
                }
            }

            for (var groupId in groupMsgMap) {
                var tempCount = groupMsgMap[groupId].max - groupMsgMap[groupId].min + 1;//收到的新的群消息数
                var curMaxMsgSeq = myGroupMaxSeqs[groupId];//获取本地保存的群最大消息seq
                if (curMaxMsgSeq) {//存在这个群的最大消息seq
                    //高并发情况下，长轮询可能存在丢消息，这时需要客户端通过拉取群消息接口补齐下
                    //1、如果收到的新消息最小seq比当前最大群消息seq大于1，则表示收到的群消息发生跳跃，需要补齐
                    //2、收到的新群消息seq存在不连续情况，也需要补齐
                    if (groupMsgMap[groupId].min - curMaxMsgSeq > 1 || groupMsgMap[groupId].msgs.length < tempCount) {
                        //发起一个拉群群消息请求
                        log.warn("发起一次补齐群消息请求,curMaxMsgSeq=" + curMaxMsgSeq + ", minMsgSeq=" + groupMsgMap[groupId].min + ", maxMsgSeq=" + groupMsgMap[groupId].max + ", msgs.length=" + groupMsgMap[groupId].msgs.length + ", tempCount=" + tempCount);
                        getLostGroupMsgs(groupId, groupMsgMap[groupId].max, groupMsgMap[groupId].max - curMaxMsgSeq);
                        //更新myGroupMaxSeqs中的群最大seq
                        updateMyGroupCurMaxSeq(groupId,groupMsgMap[groupId].max);
                    } else {
                        new_group_msgs = addGroupMsgList(groupMsgMap[groupId].msgs, new_group_msgs);
                    }
                } else {//不存在该群的最大消息seq
                    log.warn("不存在该群的最大消息seq，群id=" + groupId);
                    //高并发情况下，长轮询可能存在丢消息，这时需要客户端通过拉取群消息接口补齐下
                    //1、收到的新群消息seq存在不连续情况，也需要补齐
                    if (groupMsgMap[groupId].msgs.length < tempCount) {
                        //发起一个拉群群消息请求
                        log.warn("发起一次补齐群消息请求,minMsgSeq=" + groupMsgMap[groupId].min + ", maxMsgSeq=" + groupMsgMap[groupId].max + ", msgs.length=" + groupMsgMap[groupId].msgs.length + ", tempCount=" + tempCount);
                        getLostGroupMsgs(groupId, groupMsgMap[groupId].max, tempCount);
                        //更新myGroupMaxSeqs中的群最大seq
                        updateMyGroupCurMaxSeq(groupId,groupMsgMap[groupId].max);
                    } else {
                        new_group_msgs = addGroupMsgList(groupMsgMap[groupId].msgs, new_group_msgs);
                    }
                }
            }
            if (new_group_msgs.length) {
                MsgStore.updateTimeline();
            }
            if (onMsgCallback && new_group_msgs.length) onMsgCallback(new_group_msgs);

        };

        //处理收到的群普通和提示消息
        var handlerOrdinaryAndTipGroupMsgs = function (eventType, groupMsgArray) {
            var groupMsgMap = {};//保存收到的群消息信息（群号，最小，最大消息seq，消息列表）
            var new_group_msgs = [];
            var minGroupMsgSeq = 99999999;
            var maxGroupMsgSeq = -1;
            for (var j in groupMsgArray) {

                var groupMsgs = groupMsgMap[groupMsgArray[j].ToGroupId];
                if (!groupMsgs) {
                    groupMsgs = groupMsgMap[groupMsgArray[j].ToGroupId] = {
                        "min": minGroupMsgSeq,//收到新消息最小seq
                        "max": maxGroupMsgSeq,//收到新消息最大seq
                        "msgs": []//收到的新消息
                    };
                }
                //更新长轮询的群NoticeSeq
                if (groupMsgArray[j].NoticeSeq > noticeSeq) {
                    log.warn("noticeSeq=" + noticeSeq + ",msgNoticeSeq=" + groupMsgArray[j].NoticeSeq);
                    noticeSeq = groupMsgArray[j].NoticeSeq;
                }
                groupMsgArray[j].Event = eventType;
                groupMsgMap[groupMsgArray[j].ToGroupId].msgs.push(groupMsgArray[j]);//新增一条消息
                if (groupMsgArray[j].MsgSeq < groupMsgs.min) {//记录最小的消息seq
                    groupMsgMap[groupMsgArray[j].ToGroupId].min = groupMsgArray[j].MsgSeq;
                }
                if (groupMsgArray[j].MsgSeq > groupMsgs.max) {//记录最大的消息seq
                    groupMsgMap[groupMsgArray[j].ToGroupId].max = groupMsgArray[j].MsgSeq;
                }
            }

            for (var groupId in groupMsgMap) {
                var tempCount = groupMsgMap[groupId].max - groupMsgMap[groupId].min + 1;//收到的新的群消息数
                var curMaxMsgSeq = myGroupMaxSeqs[groupId];//获取本地保存的群最大消息seq
                if (curMaxMsgSeq) {//存在这个群的最大消息seq
                    //高并发情况下，长轮询可能存在丢消息，这时需要客户端通过拉取群消息接口补齐下
                    //1、如果收到的新消息最小seq比当前最大群消息seq大于1，则表示收到的群消息发生跳跃，需要补齐
                    //2、收到的新群消息seq存在不连续情况，也需要补齐
                    if (groupMsgMap[groupId].min - curMaxMsgSeq > 1 || groupMsgMap[groupId].msgs.length < tempCount) {
                        //发起一个拉群群消息请求
                        log.warn("发起一次补齐群消息请求,curMaxMsgSeq=" + curMaxMsgSeq + ", minMsgSeq=" + groupMsgMap[groupId].min + ", maxMsgSeq=" + groupMsgMap[groupId].max + ", msgs.length=" + groupMsgMap[groupId].msgs.length + ", tempCount=" + tempCount);
                        getLostGroupMsgs(groupId, groupMsgMap[groupId].max, groupMsgMap[groupId].max - curMaxMsgSeq);
                        //更新myGroupMaxSeqs中的群最大seq
                        updateMyGroupCurMaxSeq(groupId,groupMsgMap[groupId].max);
                    } else {
                        new_group_msgs = addGroupMsgList(groupMsgMap[groupId].msgs, new_group_msgs);
                    }
                } else {//不存在该群的最大消息seq
                    log.warn("不存在该群的最大消息seq，群id=" + groupId);
                    //高并发情况下，长轮询可能存在丢消息，这时需要客户端通过拉取群消息接口补齐下
                    //1、收到的新群消息seq存在不连续情况，也需要补齐
                    if (groupMsgMap[groupId].msgs.length < tempCount) {
                        //发起一个拉群群消息请求
                        log.warn("发起一次补齐群消息请求,minMsgSeq=" + groupMsgMap[groupId].min + ", maxMsgSeq=" + groupMsgMap[groupId].max + ", msgs.length=" + groupMsgMap[groupId].msgs.length + ", tempCount=" + tempCount);
                        getLostGroupMsgs(groupId, groupMsgMap[groupId].max, tempCount);
                        //更新myGroupMaxSeqs中的群最大seq
                        updateMyGroupCurMaxSeq(groupId,groupMsgMap[groupId].max);
                    } else {
                        new_group_msgs = addGroupMsgList(groupMsgMap[groupId].msgs, new_group_msgs);
                    }
                }
            }
            if (new_group_msgs.length) {
                MsgStore.updateTimeline();
            }
            if (onMsgCallback && new_group_msgs.length) onMsgCallback(new_group_msgs);

        };

        //处理新的群提示消息
        var handlerGroupTips = function (groupTips) {
            var new_group_msgs = [];
            for (var o in groupTips) {
                var groupTip = groupTips[o];
                //添加event字段
                groupTip.Event = LONG_POLLINNG_EVENT_TYPE.GROUP_TIP;
                //更新群消息通知seq
                if (groupTip.NoticeSeq > noticeSeq) {
                    noticeSeq = groupTip.NoticeSeq;
                }
                var msg = handlerGroupMsg(groupTip, false, true);
                if (msg) {
                    new_group_msgs.push(msg);
                }
            }
            if (new_group_msgs.length) {
                MsgStore.updateTimeline();
            }
            if (onMsgCallback && new_group_msgs.length) onMsgCallback(new_group_msgs);
        };

        //处理新的群系统消息
        //isNeedValidRepeatMsg 是否需要判重
        var handlerGroupSystemMsgs = function (groupSystemMsgs, isNeedValidRepeatMsg) {
            for (var k in groupSystemMsgs) {
                var groupTip = groupSystemMsgs[k];
                var groupReportTypeMsg = groupTip.MsgBody;
                var reportType = groupReportTypeMsg.ReportType;
                //当长轮询返回的群系统消息，才需要更新群消息通知seq
                if (isNeedValidRepeatMsg == false && groupTip.NoticeSeq && groupTip.NoticeSeq > noticeSeq) {
                    noticeSeq = groupTip.NoticeSeq;
                }
                var toAccount = groupTip.GroupInfo.To_Account;
                //过滤本不应该给自己的系统消息
                /*if (!toAccount || toAccount != ctx.identifier) {
                 log.error("收到本不应该给自己的系统消息: To_Account=" + toAccount);
                 continue;
                 }*/
                if (isNeedValidRepeatMsg) {
                    //var key=groupTip.ToGroupId+"_"+reportType+"_"+groupTip.MsgTimeStamp+"_"+groupReportTypeMsg.Operator_Account;
                    var key = groupTip.ToGroupId + "_" + reportType + "_" + groupReportTypeMsg.Operator_Account;
                    var isExist = groupSystemMsgsCache[key];
                    if (isExist) {
                        log.warn("收到重复的群系统消息：key=" + key);
                        continue;
                    }
                    groupSystemMsgsCache[key] = true;
                }

                var notify = {
                    "SrcFlag": 0,
                    "ReportType": reportType,
                    "GroupId": groupTip.ToGroupId,
                    "GroupName": groupTip.GroupInfo.GroupName,
                    "Operator_Account": groupReportTypeMsg.Operator_Account,
                    "MsgTime": groupTip.MsgTimeStamp,
                    "groupReportTypeMsg" : groupReportTypeMsg
                };
                switch (reportType) {
                    case GROUP_SYSTEM_TYPE.JOIN_GROUP_REQUEST://申请加群(只有管理员会接收到)
                        notify["RemarkInfo"] = groupReportTypeMsg.RemarkInfo;
                        notify["MsgKey"] = groupReportTypeMsg.MsgKey;
                        notify["Authentication"] = groupReportTypeMsg.Authentication;
                        notify["UserDefinedField"] = groupTip.UserDefinedField;
                        notify["From_Account"] = groupTip.From_Account;
                        notify["MsgSeq"] = groupTip.ClientSeq;
                        notify["MsgRandom"] = groupTip.MsgRandom;
                        break;
                    case GROUP_SYSTEM_TYPE.JOIN_GROUP_ACCEPT://申请加群被同意(只有申请人自己接收到)
                    case GROUP_SYSTEM_TYPE.JOIN_GROUP_REFUSE://申请加群被拒绝(只有申请人自己接收到)
                        notify["RemarkInfo"] = groupReportTypeMsg.RemarkInfo;
                        break;
                    case GROUP_SYSTEM_TYPE.KICK://被管理员踢出群(只有被踢者接收到)
                    case GROUP_SYSTEM_TYPE.DESTORY://群被解散(全员接收)
                    case GROUP_SYSTEM_TYPE.CREATE://创建群(创建者接收, 不展示)
                    case GROUP_SYSTEM_TYPE.INVITED_JOIN_GROUP_REQUEST://邀请加群(被邀请者接收)
                    case GROUP_SYSTEM_TYPE.QUIT://主动退群(主动退出者接收, 不展示)
                    case GROUP_SYSTEM_TYPE.SET_ADMIN://群设置管理员(被设置者接收)
                    case GROUP_SYSTEM_TYPE.CANCEL_ADMIN://取消管理员(被取消者接收)
                    case GROUP_SYSTEM_TYPE.REVOKE://群已被回收(全员接收, 不展示)
                        break;
                    case GROUP_SYSTEM_TYPE.READED://群消息已读同步
                        break;
                    case GROUP_SYSTEM_TYPE.CUSTOM://用户自定义通知(默认全员接收)
                        notify["MsgSeq"] = groupTip.MsgSeq;
                        notify["UserDefinedField"] = groupReportTypeMsg.UserDefinedField;
                        break;
                    default:
                        log.error("未知群系统消息类型：reportType=" + reportType);
                        break;
                }

                if (isNeedValidRepeatMsg) {
                    if (reportType == GROUP_SYSTEM_TYPE.JOIN_GROUP_REQUEST) {
                        //回调
                        if (onGroupSystemNotifyCallbacks[reportType]) onGroupSystemNotifyCallbacks[reportType](notify);
                    }
                } else {
                    //回调
                    if (onGroupSystemNotifyCallbacks[reportType]) {
                        if(reportType == GROUP_SYSTEM_TYPE.READED){
                            var arr = notify.groupReportTypeMsg.GroupReadInfoArray;
                            for(var i = 0 , l = arr.length; i < l ; i++){
                                var item = arr[i];
                                onGroupSystemNotifyCallbacks[reportType](item);
                            }
                        }else{
                            onGroupSystemNotifyCallbacks[reportType](notify);
                        }
                    }
                }
            }//loop
        };


        //处理新的好友系统通知
        //isNeedValidRepeatMsg 是否需要判重
        var handlerFriendSystemNotices = function (friendSystemNotices, isNeedValidRepeatMsg) {
            var friendNotice, type, notify;
            for (var k in friendSystemNotices) {
                friendNotice = friendSystemNotices[k];
                type = friendNotice.PushType;
                //当长轮询返回的群系统消息，才需要更新通知seq
                if (isNeedValidRepeatMsg == false && friendNotice.NoticeSeq && friendNotice.NoticeSeq > noticeSeq) {
                    noticeSeq = friendNotice.NoticeSeq;
                }
                notify = {'Type': type};
                switch (type) {
                    case FRIEND_NOTICE_TYPE.FRIEND_ADD://好友表增加
                        notify["Accounts"] = friendNotice.FriendAdd_Account;
                        break;
                    case FRIEND_NOTICE_TYPE.FRIEND_DELETE://好友表删除
                        notify["Accounts"] = friendNotice.FriendDel_Account;
                        break;
                    case FRIEND_NOTICE_TYPE.PENDENCY_ADD://未决增加
                        notify["PendencyList"] = friendNotice.PendencyAdd;
                        break;
                    case FRIEND_NOTICE_TYPE.PENDENCY_DELETE://未决删除
                        notify["Accounts"] = friendNotice.FrienPencydDel_Account;
                        break;
                    case FRIEND_NOTICE_TYPE.BLACK_LIST_ADD://黑名单增加
                        notify["Accounts"] = friendNotice.BlackListAdd_Account;
                        break;
                    case FRIEND_NOTICE_TYPE.BLACK_LIST_DELETE://黑名单删除
                        notify["Accounts"] = friendNotice.BlackListDel_Account;
                        break;
                    /*case FRIEND_NOTICE_TYPE.PENDENCY_REPORT://未决已读上报

                     break;
                     case FRIEND_NOTICE_TYPE.FRIEND_UPDATE://好友数据更新

                     break;
                     */
                    default:
                        log.error("未知好友系统通知类型：friendNotice=" + JSON.stringify(friendNotice));
                        break;
                }

                if (isNeedValidRepeatMsg) {
                    if (type == FRIEND_NOTICE_TYPE.PENDENCY_ADD) {
                        //回调
                        if (onFriendSystemNotifyCallbacks[type]) onFriendSystemNotifyCallbacks[type](notify);
                    }
                } else {
                    //回调
                    if (onFriendSystemNotifyCallbacks[type]) onFriendSystemNotifyCallbacks[type](notify);
                }
            }//loop
        };

        //处理新的资料系统通知
        //isNeedValidRepeatMsg 是否需要判重
        var handlerProfileSystemNotices = function (profileSystemNotices, isNeedValidRepeatMsg) {
            var profileNotice, type, notify;
            for (var k in profileSystemNotices) {
                profileNotice = profileSystemNotices[k];
                type = profileNotice.PushType;
                //当长轮询返回的群系统消息，才需要更新通知seq
                if (isNeedValidRepeatMsg == false && profileNotice.NoticeSeq && profileNotice.NoticeSeq > noticeSeq) {
                    noticeSeq = profileNotice.NoticeSeq;
                }
                notify = {'Type': type};
                switch (type) {
                    case PROFILE_NOTICE_TYPE.PROFILE_MODIFY://资料修改
                        notify["Profile_Account"] = profileNotice.Profile_Account;
                        notify["ProfileList"] = profileNotice.ProfileList;
                        break;
                    default:
                        log.error("未知资料系统通知类型：profileNotice=" + JSON.stringify(profileNotice));
                        break;
                }

                if (isNeedValidRepeatMsg) {
                    if (type == PROFILE_NOTICE_TYPE.PROFILE_MODIFY) {
                        //回调
                        if (onProfileSystemNotifyCallbacks[type]) onProfileSystemNotifyCallbacks[type](notify);
                    }
                } else {
                    //回调
                    if (onProfileSystemNotifyCallbacks[type]) onProfileSystemNotifyCallbacks[type](notify);
                }
            }//loop
        };

        //处理新的群系统消息(用于直播大群长轮询)
        var handlerGroupSystemMsg = function (groupTip) {
            var groupReportTypeMsg = groupTip.MsgBody;
            var reportType = groupReportTypeMsg.ReportType;
            var toAccount = groupTip.GroupInfo.To_Account;
            //过滤本不应该给自己的系统消息
            //if(!toAccount || toAccount!=ctx.identifier){
            //    log.error("收到本不应该给自己的系统消息: To_Account="+toAccount);
            //    continue;
            //}
            var notify = {
                "SrcFlag": 1,
                "ReportType": reportType,
                "GroupId": groupTip.ToGroupId,
                "GroupName": groupTip.GroupInfo.GroupName,
                "Operator_Account": groupReportTypeMsg.Operator_Account,
                "MsgTime": groupTip.MsgTimeStamp
            };
            switch (reportType) {
                case GROUP_SYSTEM_TYPE.JOIN_GROUP_REQUEST://申请加群(只有管理员会接收到)
                    notify["RemarkInfo"] = groupReportTypeMsg.RemarkInfo;
                    notify["MsgKey"] = groupReportTypeMsg.MsgKey;
                    notify["Authentication"] = groupReportTypeMsg.Authentication;
                    notify["UserDefinedField"] = groupTip.UserDefinedField;
                    notify["From_Account"] = groupTip.From_Account;
                    notify["MsgSeq"] = groupTip.ClientSeq;
                    notify["MsgRandom"] = groupTip.MsgRandom;
                    break;
                case GROUP_SYSTEM_TYPE.JOIN_GROUP_ACCEPT://申请加群被同意(只有申请人自己接收到)
                case GROUP_SYSTEM_TYPE.JOIN_GROUP_REFUSE://申请加群被拒绝(只有申请人自己接收到)
                    notify["RemarkInfo"] = groupReportTypeMsg.RemarkInfo;
                    break;
                case GROUP_SYSTEM_TYPE.KICK://被管理员踢出群(只有被踢者接收到)
                case GROUP_SYSTEM_TYPE.DESTORY://群被解散(全员接收)
                case GROUP_SYSTEM_TYPE.CREATE://创建群(创建者接收, 不展示)
                case GROUP_SYSTEM_TYPE.INVITED_JOIN_GROUP_REQUEST://邀请加群(被邀请者接收)
                case GROUP_SYSTEM_TYPE.QUIT://主动退群(主动退出者接收, 不展示)
                case GROUP_SYSTEM_TYPE.SET_ADMIN://群设置管理员(被设置者接收)
                case GROUP_SYSTEM_TYPE.CANCEL_ADMIN://取消管理员(被取消者接收)
                case GROUP_SYSTEM_TYPE.REVOKE://群已被回收(全员接收, 不展示)
                    break;
                case GROUP_SYSTEM_TYPE.CUSTOM://用户自定义通知(默认全员接收)
                    notify["MsgSeq"] = groupTip.MsgSeq;
                    notify["UserDefinedField"] = groupReportTypeMsg.UserDefinedField;
                    break;
                default:
                    log.error("未知群系统消息类型：reportType=" + reportType);
                    break;
            }
            //回调
            if (onGroupSystemNotifyCallbacks[reportType]) onGroupSystemNotifyCallbacks[reportType](notify);

        };

        //处理C2C EVENT 消息通道Array
        var handlerC2cNotifyMsgArray = function(arr){
            for(var i =0,l=arr.length; i<l ;i++){
                handlerC2cEventMsg(arr[i]);
            }
        }

        //处理C2C EVENT 消息通道Item
        var handlerC2cEventMsg = function (notify) {
            var subType = notify.SubMsgType;
            switch (subType) {
                case C2C_EVENT_SUB_TYPE.READED://已读通知
                    break;
                default:
                    log.error("未知C2c系统消息：reportType=" + reportType);
                    break;
            }
            // stopPolling = true;
            //回调onMsgReadCallback
            if(notify.ReadC2cMsgNotify.UinPairReadArray && onC2cEventCallbacks[subType]){
                for(var i = 0 ,l = notify.ReadC2cMsgNotify.UinPairReadArray.length; i < l ; i++){
                    var item = notify.ReadC2cMsgNotify.UinPairReadArray[i];
                    onC2cEventCallbacks[subType](item);
                }
            }
        };

        //长轮询
        this.longPolling = function (cbOk, cbErr) {


            var opts = {
                'Timeout': longPollingDefaultTimeOut/1000,
                'Cookie': {
                    'NotifySeq': notifySeq,
                    'NoticeSeq': noticeSeq
                }
            };
            if(LongPollingId){
                opts.Cookie.LongPollingId = LongPollingId;
                doPolling();
            }else{
                proto_getLongPollingId({},function(resp){
                    LongPollingId = opts.Cookie.LongPollingId = resp.LongPollingId;
                    //根据回包设置超时时间，超时时长不能>60秒，因为webkit手机端的最长超时时间不能大于60s
                    longPollingDefaultTimeOut = resp.Timeout > 60 ? longPollingDefaultTimeOut : resp.Timeout * 1000 ;
                    doPolling();
                });
            }

            function doPolling(){
                proto_longPolling(opts, function (resp) {
                    for (var i in resp.EventArray) {
                        var e = resp.EventArray[i];
                        switch (e.Event) {
                            case LONG_POLLINNG_EVENT_TYPE.C2C://c2c消息通知
                                //更新C2C消息通知seq
                                notifySeq = e.NotifySeq;
                                log.warn("longpolling: received new c2c msg");
                                //获取新消息
                                MsgManager.syncMsgs();
                                break;
                            case LONG_POLLINNG_EVENT_TYPE.GROUP_COMMON://普通群消息通知
                                log.warn("longpolling: received new group msgs");
                                handlerOrdinaryAndTipGroupMsgs(e.Event, e.GroupMsgArray);
                                break;
                            case LONG_POLLINNG_EVENT_TYPE.GROUP_TIP://（全员广播）群提示消息
                                log.warn("longpolling: received new group tips");
                                handlerOrdinaryAndTipGroupMsgs(e.Event, e.GroupTips);
                                break;
                            case LONG_POLLINNG_EVENT_TYPE.GROUP_SYSTEM://（多终端同步）群系统消息
                                log.warn("longpolling: received new group system msgs");
                                //false 表示 通过长轮询收到的群系统消息，可以不判重
                                handlerGroupSystemMsgs(e.GroupTips, false);
                                break;
                            case LONG_POLLINNG_EVENT_TYPE.FRIEND_NOTICE://好友系统通知
                                log.warn("longpolling: received new friend system notice");
                                //false 表示 通过长轮询收到的好友系统通知，可以不判重
                                handlerFriendSystemNotices(e.FriendListMod, false);
                                break;
                            case LONG_POLLINNG_EVENT_TYPE.PROFILE_NOTICE://资料系统通知
                                log.warn("longpolling: received new profile system notice");
                                //false 表示 通过长轮询收到的资料系统通知，可以不判重
                                handlerProfileSystemNotices(e.ProfileDataMod, false);
                                break;
                            case LONG_POLLINNG_EVENT_TYPE.C2C_COMMON://c2c消息通知
                                noticeSeq = e.C2cMsgArray[0].NoticeSeq;
                                //更新C2C消息通知seq
                                log.warn("longpolling: received new c2c_common msg",noticeSeq);
                                handlerOrdinaryAndTipC2cMsgs(e.Event, e.C2cMsgArray);
                                break;
                            case LONG_POLLINNG_EVENT_TYPE.C2C_EVENT://c2c已读消息通知
                                noticeSeq = e.C2cNotifyMsgArray[0].NoticeSeq;
                                log.warn("longpolling: received new c2c_event msg");
                                handlerC2cNotifyMsgArray(e.C2cNotifyMsgArray);
                                break;
                            default:
                                log.error("longpolling收到未知新消息类型: Event=" + e.Event);
                                break;
                        }
                    }
                    var successInfo = {
                        'ActionStatus': ACTION_STATUS.OK,
                        'ErrorCode': 0
                    };
                    updatecLongPollingStatus(successInfo);
                }, function (err) {
                    //log.error(err);
                    updatecLongPollingStatus(err);
                    if (cbErr) cbErr(err);
                });
            }
        };


        //大群 长轮询
        this.bigGroupLongPolling = function (cbOk, cbErr) {

            var opts = {
                'StartSeq': bigGroupLongPollingStartSeq,//请求拉消息的起始seq
                'HoldTime': bigGroupLongPollingHoldTime,//客户端长轮询的超时时间，单位是秒
                'Key': bigGroupLongPollingKey//客户端加入群组后收到的的Key
            };

            proto_bigGroupLongPolling(opts, function (resp) {

                var msgObjList = [];
                bigGroupLongPollingStartSeq = resp.NextSeq;
                bigGroupLongPollingHoldTime = resp.HoldTime;
                bigGroupLongPollingKey = resp.Key;

                if (resp.RspMsgList && resp.RspMsgList.length > 0) {
                    var msgCount = 0, msgInfo, event, msg;
                    for (var i = resp.RspMsgList.length - 1; i >= 0; i--) {
                        msgInfo = resp.RspMsgList[i];
                        //如果是已经删除的消息或者发送者帐号为空或者消息内容为空
                        //IsPlaceMsg=1
                        if (msgInfo.IsPlaceMsg || !msgInfo.From_Account || !msgInfo.MsgBody || msgInfo.MsgBody.length == 0) {
                            continue;
                        }

                        event = msgInfo.Event;//群消息类型
                        switch (event) {
                            case LONG_POLLINNG_EVENT_TYPE.GROUP_COMMON://群普通消息
                                log.info("bigGroupLongPolling: return new group msg");
                                msg = handlerGroupMsg(msgInfo, false, false);
                                msg && msgObjList.push(msg);
                                msgCount = msgCount + 1;
                                break;
                            case LONG_POLLINNG_EVENT_TYPE.GROUP_TIP://群提示消息
                            case LONG_POLLINNG_EVENT_TYPE.GROUP_TIP2://群提示消息
                                log.info("bigGroupLongPolling: return new group tip");
                                msg = handlerGroupMsg(msgInfo, false, false);
                                msg && msgObjList.push(msg);
                                //msgCount=msgCount+1;
                                break;
                            case LONG_POLLINNG_EVENT_TYPE.GROUP_SYSTEM://群系统消息
                                log.info("bigGroupLongPolling: new group system msg");
                                handlerGroupSystemMsg(msgInfo);
                                break;
                            default:
                                log.error("bigGroupLongPolling收到未知新消息类型: Event=" + event);
                                break;
                        }
                    } // for loop
                    if (msgCount > 0) {
                        MsgManager.setBigGroupLongPollingMsgMap(msgInfo.ToGroupId, msgCount);//
                        log.warn("current bigGroupLongPollingMsgMap: " + JSON.stringify(bigGroupLongPollingMsgMap));
                    }
                }
                curBigGroupLongPollingRetErrorCount = 0;
                //返回连接状态
                var successInfo = {
                    'ActionStatus': ACTION_STATUS.OK,
                    'ErrorCode': CONNECTION_STATUS.ON,
                    'ErrorInfo': 'connection is ok...'
                };
                ConnManager.callBack(successInfo);

                if (cbOk) cbOk(msgObjList);
                else if (onBigGroupMsgCallback) onBigGroupMsgCallback(msgObjList);//返回新消息

                //重新启动长轮询
                bigGroupLongPollingOn && MsgManager.bigGroupLongPolling();

            }, function (err) {
                //
                if (err.ErrorCode != longPollingTimeOutErrorCode) {
                    log.error(err.ErrorInfo);
                    //记录长轮询返回错误次数
                    curBigGroupLongPollingRetErrorCount++;
                }
                if (err.ErrorCode != longPollingKickedErrorCode) {
                    //登出
                    log.error("多实例登录，被kick");

                    if (onKickedEventCall) {onKickedEventCall();}
                    /*    return;
                    proto_logout(function(){
                        if (onKickedEventCall) {onKickedEventCall();}
                    });*/
                }
                //累计超过一定次数，不再发起长轮询请求
                if (curBigGroupLongPollingRetErrorCount < LONG_POLLING_MAX_RET_ERROR_COUNT) {
                    bigGroupLongPollingOn && MsgManager.bigGroupLongPolling();
                } else {
                    var errInfo = {
                        'ActionStatus': ACTION_STATUS.FAIL,
                        'ErrorCode': CONNECTION_STATUS.OFF,
                        'ErrorInfo': 'connection is off'
                    };
                    ConnManager.callBack(errInfo);
                }
                if (cbErr) cbErr(err);

            }, bigGroupLongPollingHoldTime * 1000);
        };

        //更新连接状态
        var updatecLongPollingStatus = function (errObj) {
            if (errObj.ErrorCode == 0 || errObj.ErrorCode == longPollingTimeOutErrorCode) {
                curLongPollingRetErrorCount = 0;
                longPollingOffCallbackFlag = false;
                var errorInfo;
                var isNeedCallback = false;
                switch (curLongPollingStatus) {
                    case CONNECTION_STATUS.INIT:
                        isNeedCallback = true;
                        curLongPollingStatus = CONNECTION_STATUS.ON;
                        errorInfo = "create connection successfully(INIT->ON)";
                        break;
                    case CONNECTION_STATUS.ON:
                        errorInfo = "connection is on...(ON->ON)";
                        break;
                    case CONNECTION_STATUS.RECONNECT:
                        curLongPollingStatus = CONNECTION_STATUS.ON;
                        errorInfo = "connection is on...(RECONNECT->ON)";
                        break;
                    case CONNECTION_STATUS.OFF:
                        isNeedCallback = true;
                        curLongPollingStatus = CONNECTION_STATUS.RECONNECT;
                        errorInfo = "reconnect successfully(OFF->RECONNECT)";
                        break;
                }
                var successInfo = {
                    'ActionStatus': ACTION_STATUS.OK,
                    'ErrorCode': curLongPollingStatus,
                    'ErrorInfo': errorInfo
                };
                isNeedCallback && ConnManager.callBack(successInfo);
                longPollingOn && MsgManager.longPolling();
            } else if( errObj.ErrorCode == longPollingKickedErrorCode){
                //登出
                log.error("多实例登录，被kick");
                    if (onKickedEventCall) {onKickedEventCall();}
                //     return;
                // proto_logout(function(){
                //     if (onKickedEventCall) {onKickedEventCall();}
                // });
            }else {
                //记录长轮询返回解析json错误次数
                curLongPollingRetErrorCount++;
                log.warn("longPolling接口第" + curLongPollingRetErrorCount + "次报错: " + errObj.ErrorInfo);
                //累计超过一定次数
                if (curLongPollingRetErrorCount <= LONG_POLLING_MAX_RET_ERROR_COUNT) {
                    setTimeout(startNextLongPolling, 100);//
                } else {
                    curLongPollingStatus = CONNECTION_STATUS.OFF;
                    var errInfo = {
                        'ActionStatus': ACTION_STATUS.FAIL,
                        'ErrorCode': CONNECTION_STATUS.OFF,
                        'ErrorInfo': 'connection is off'
                    };
                    longPollingOffCallbackFlag == false && ConnManager.callBack(errInfo);
                    longPollingOffCallbackFlag = true;
                    log.warn(longPollingIntervalTime + "毫秒之后,SDK会发起新的longPolling请求...");
                    setTimeout(startNextLongPolling, longPollingIntervalTime);//长轮询接口报错次数达到一定值，每间隔5s发起新的长轮询
                }
            }
        };

         //处理收到的普通C2C消息
        var handlerOrdinaryAndTipC2cMsgs = function (eventType, C2cMsgArray) {
                //处理c2c消息
                var notifyInfo = [];
                msgInfos = C2cMsgArray;//返回的消息列表
                // MsgStore.cookie = resp.Cookie;//cookies，记录当前读到的最新消息位置

                for (var i in msgInfos) {
                    var msgInfo = msgInfos[i];
                    var isSendMsg, id, headUrl;
                    if (msgInfo.From_Account == ctx.identifier) {//当前用户发送的消息
                        isSendMsg = true;
                        id = msgInfo.To_Account;//读取接收者信息
                        headUrl = '';
                    } else {//当前用户收到的消息
                        isSendMsg = false;
                        id = msgInfo.From_Account;//读取发送者信息
                        headUrl = '';
                    }
                    var sess = MsgStore.sessByTypeId(SESSION_TYPE.C2C, id);
                    if (!sess) {
                        sess = new Session(SESSION_TYPE.C2C, id, id, headUrl, 0, 0);
                    }
                    var msg = new Msg(sess, isSendMsg, msgInfo.MsgSeq, msgInfo.MsgRandom, msgInfo.MsgTimeStamp, msgInfo.From_Account);
                    var msgBody = null;
                    var msgContent = null;
                    var msgType = null;
                    for (var mi in msgInfo.MsgBody) {
                        msgBody = msgInfo.MsgBody[mi];
                        msgType = msgBody.MsgType;
                        switch (msgType) {
                            case MSG_ELEMENT_TYPE.TEXT:
                                msgContent = new Msg.Elem.Text(msgBody.MsgContent.Text);
                                break;
                            case MSG_ELEMENT_TYPE.FACE:
                                msgContent = new Msg.Elem.Face(
                                    msgBody.MsgContent.Index,
                                    msgBody.MsgContent.Data
                                );
                                break;
                            case MSG_ELEMENT_TYPE.IMAGE:
                                msgContent = new Msg.Elem.Images(
                                    msgBody.MsgContent.UUID
                                );
                                for (var j in msgBody.MsgContent.ImageInfoArray) {
                                    var tempImg = msgBody.MsgContent.ImageInfoArray[j];
                                    msgContent.addImage(
                                        new Msg.Elem.Images.Image(
                                            tempImg.Type,
                                            tempImg.Size,
                                            tempImg.Width,
                                            tempImg.Height,
                                            tempImg.URL
                                        )
                                    );
                                }
                                break;
                            case MSG_ELEMENT_TYPE.SOUND:
                                if (msgBody.MsgContent) {
                                    msgContent = new Msg.Elem.Sound(
                                        msgBody.MsgContent.UUID,
                                        msgBody.MsgContent.Second,
                                        msgBody.MsgContent.Size,
                                        msgInfo.From_Account,
                                        msgInfo.To_Account,
                                        msgBody.MsgContent.Download_Flag,
                                        SESSION_TYPE.C2C
                                    );
                                } else {
                                    msgType = MSG_ELEMENT_TYPE.TEXT;
                                    msgContent = new Msg.Elem.Text('[语音消息]下载地址解析出错');
                                }
                                break;
                            case MSG_ELEMENT_TYPE.LOCATION:
                                msgContent = new Msg.Elem.Location(
                                    msgBody.MsgContent.Longitude,
                                    msgBody.MsgContent.Latitude,
                                    msgBody.MsgContent.Desc
                                );
                                break;
                            case MSG_ELEMENT_TYPE.FILE:
                            case MSG_ELEMENT_TYPE.FILE + " ":
                                msgType = MSG_ELEMENT_TYPE.FILE;
                                if (msgBody.MsgContent) {
                                    msgContent = new Msg.Elem.File(
                                        msgBody.MsgContent.UUID,
                                        msgBody.MsgContent.FileName,
                                        msgBody.MsgContent.FileSize,
                                        msgInfo.From_Account,
                                        msgInfo.To_Account,
                                        msgBody.MsgContent.Download_Flag,
                                        SESSION_TYPE.C2C
                                    );
                                } else {
                                    msgType = MSG_ELEMENT_TYPE.TEXT;
                                    msgContent = new Msg.Elem.Text('[文件消息下载地址解析出错]');
                                }
                                break;
                            case MSG_ELEMENT_TYPE.CUSTOM:
                                try {
                                    var data = JSON.parse(msgBody.MsgContent.Data);
                                    if (data && data.userAction && data.userAction == FRIEND_WRITE_MSG_ACTION.ING) {//过滤安卓或ios的正在输入自定义消息
                                        continue;
                                    }
                                } catch (e) {
                                }

                                msgType = MSG_ELEMENT_TYPE.CUSTOM;
                                msgContent = new Msg.Elem.Custom(
                                    msgBody.MsgContent.Data,
                                    msgBody.MsgContent.Desc,
                                    msgBody.MsgContent.Ext
                                );
                                break;
                            default :
                                msgType = MSG_ELEMENT_TYPE.TEXT;
                                msgContent = new Msg.Elem.Text('web端暂不支持' + msgBody.MsgType + '消息');
                                break;
                        }
                        msg.elems.push(new Msg.Elem(msgType, msgContent));
                    }

                    if (msg.elems.length > 0 && MsgStore.addMsg(msg)) {
                        notifyInfo.push(msg);
                    }
                } // for loop
                if (notifyInfo.length > 0)
                    MsgStore.updateTimeline();
                if (notifyInfo.length > 0) {
                    if (onMsgCallback) onMsgCallback(notifyInfo);
                }
        };

        //发起新的长轮询请求
        var startNextLongPolling = function () {
            longPollingOn && MsgManager.longPolling();
        };

        //处理未决的加群申请消息列表
        var handlerApplyJoinGroupSystemMsgs = function (eventArray) {
            for (var i in eventArray) {
                var e = eventArray[i];
                switch (e.Event) {
                    case LONG_POLLINNG_EVENT_TYPE.GROUP_SYSTEM://（多终端同步）群系统消息
                        log.warn("handlerApplyJoinGroupSystemMsgs： handler new group system msg");
                        //true 表示 解决加群申请通知存在重复的问题（已处理的通知，下次登录还会拉到），需要判重
                        handlerGroupSystemMsgs(e.GroupTips, true);
                        break;
                    default:
                        log.error("syncMsgs收到未知的群系统消息类型: Event=" + e.Event);
                        break;
                }
            }
        };

        //拉取c2c消息(包含加群未决消息，需要处理)
        this.syncMsgs = function (cbOk, cbErr) {
            var notifyInfo = [];
            var msgInfos = [];
            //读取C2C消息
            proto_getMsgs(MsgStore.cookie, MsgStore.syncFlag, function (resp) {
                //拉取完毕
                if (resp.SyncFlag == 2) {
                    MsgStore.syncFlag = 0;
                }
                //处理c2c消息
                msgInfos = resp.MsgList;//返回的消息列表
                MsgStore.cookie = resp.Cookie;//cookies，记录当前读到的最新消息位置

                for (var i in msgInfos) {
                    var msgInfo = msgInfos[i];
                    var isSendMsg, id, headUrl;
                    if (msgInfo.From_Account == ctx.identifier) {//当前用户发送的消息
                        isSendMsg = true;
                        id = msgInfo.To_Account;//读取接收者信息
                        headUrl = '';
                    } else {//当前用户收到的消息
                        isSendMsg = false;
                        id = msgInfo.From_Account;//读取发送者信息
                        headUrl = '';
                    }
                    var sess = MsgStore.sessByTypeId(SESSION_TYPE.C2C, id);
                    if (!sess) {
                        sess = new Session(SESSION_TYPE.C2C, id, id, headUrl, 0, 0);
                    }
                    var msg = new Msg(sess, isSendMsg, msgInfo.MsgSeq, msgInfo.MsgRandom, msgInfo.MsgTimeStamp, msgInfo.From_Account);
                    var msgBody = null;
                    var msgContent = null;
                    var msgType = null;
                    for (var mi in msgInfo.MsgBody) {
                        msgBody = msgInfo.MsgBody[mi];
                        msgType = msgBody.MsgType;
                        switch (msgType) {
                            case MSG_ELEMENT_TYPE.TEXT:
                                msgContent = new Msg.Elem.Text(msgBody.MsgContent.Text);
                                break;
                            case MSG_ELEMENT_TYPE.FACE:
                                msgContent = new Msg.Elem.Face(
                                    msgBody.MsgContent.Index,
                                    msgBody.MsgContent.Data
                                );
                                break;
                            case MSG_ELEMENT_TYPE.IMAGE:
                                msgContent = new Msg.Elem.Images(
                                    msgBody.MsgContent.UUID
                                );
                                for (var j in msgBody.MsgContent.ImageInfoArray) {
                                    var tempImg = msgBody.MsgContent.ImageInfoArray[j];
                                    msgContent.addImage(
                                        new Msg.Elem.Images.Image(
                                            tempImg.Type,
                                            tempImg.Size,
                                            tempImg.Width,
                                            tempImg.Height,
                                            tempImg.URL
                                        )
                                    );
                                }
                                break;
                            case MSG_ELEMENT_TYPE.SOUND:
                                // var soundUrl = getSoundDownUrl(msgBody.MsgContent.UUID, msgInfo.From_Account);
                                if (msgBody.MsgContent) {
                                    msgContent = new Msg.Elem.Sound(
                                        msgBody.MsgContent.UUID,
                                        msgBody.MsgContent.Second,
                                        msgBody.MsgContent.Size,
                                        msgInfo.From_Account,
                                        msgInfo.To_Account,
                                        msgBody.MsgContent.Download_Flag,
                                        SESSION_TYPE.C2C
                                    );
                                } else {
                                    msgType = MSG_ELEMENT_TYPE.TEXT;
                                    msgContent = new Msg.Elem.Text('[语音消息]下载地址解析出错');
                                }
                                break;
                            case MSG_ELEMENT_TYPE.LOCATION:
                                msgContent = new Msg.Elem.Location(
                                    msgBody.MsgContent.Longitude,
                                    msgBody.MsgContent.Latitude,
                                    msgBody.MsgContent.Desc
                                );
                                break;
                            case MSG_ELEMENT_TYPE.FILE:
                            case MSG_ELEMENT_TYPE.FILE + " ":
                                msgType = MSG_ELEMENT_TYPE.FILE;
                                // var fileUrl = getFileDownUrl(msgBody.MsgContent.UUID, msgInfo.From_Account, msgBody.MsgContent.FileName);
                                if (msgBody.MsgContent) {
                                    msgContent = new Msg.Elem.File(
                                        msgBody.MsgContent.UUID,
                                        msgBody.MsgContent.FileName,
                                        msgBody.MsgContent.FileSize,
                                        msgInfo.From_Account,
                                        msgInfo.To_Account,
                                        msgBody.MsgContent.Download_Flag,
                                        SESSION_TYPE.C2C
                                    );
                                } else {
                                    msgType = MSG_ELEMENT_TYPE.TEXT;
                                    msgContent = new Msg.Elem.Text('[文件消息下载地址解析出错]');
                                }
                                break;
                            case MSG_ELEMENT_TYPE.CUSTOM:
                                try {
                                    var data = JSON.parse(msgBody.MsgContent.Data);
                                    if (data && data.userAction && data.userAction == FRIEND_WRITE_MSG_ACTION.ING) {//过滤安卓或ios的正在输入自定义消息
                                        continue;
                                    }
                                } catch (e) {
                                }

                                msgType = MSG_ELEMENT_TYPE.CUSTOM;
                                msgContent = new Msg.Elem.Custom(
                                    msgBody.MsgContent.Data,
                                    msgBody.MsgContent.Desc,
                                    msgBody.MsgContent.Ext
                                );
                                break;
                            default :
                                msgType = MSG_ELEMENT_TYPE.TEXT;
                                msgContent = new Msg.Elem.Text('web端暂不支持' + msgBody.MsgType + '消息');
                                break;
                        }
                        msg.elems.push(new Msg.Elem(msgType, msgContent));
                    }

                    if (msg.elems.length > 0 && MsgStore.addMsg(msg)) {
                        notifyInfo.push(msg);
                    }
                } // for loop

                //处理加群未决申请消息
                handlerApplyJoinGroupSystemMsgs(resp.EventArray);

                if (notifyInfo.length > 0)
                    MsgStore.updateTimeline();
                if (cbOk) cbOk(notifyInfo);
                else if (notifyInfo.length > 0) {
                    if (onMsgCallback) onMsgCallback(notifyInfo);
                }

            }, function (err) {
                log.error("getMsgs failed:" + err.ErrorInfo);
                if (cbErr) cbErr(err);
            });
        };


        //拉取C2C漫游消息
        this.getC2CHistoryMsgs = function (options, cbOk, cbErr) {

            if (!options.Peer_Account) {
                if (cbErr) {
                    cbErr(tool.getReturnError("Peer_Account is empty", -13));
                    return;
                }
            }
            if (!options.MaxCnt) {
                options.MaxCnt = 15;
            }
            if (options.MaxCnt <= 0) {
                if (cbErr) {
                    cbErr(tool.getReturnError("MaxCnt should be greater than 0", -14));
                    return;
                }
            }
            if (options.MaxCnt > 15) {
                if (cbErr) {
                    cbErr(tool.getReturnError("MaxCnt can not be greater than 15", -15));
                    return;
                }
                return;
            }
            if (options.MsgKey == null || options.MsgKey === undefined) {
                options.MsgKey = '';
            }
            var opts = {
                'Peer_Account': options.Peer_Account,
                'MaxCnt': options.MaxCnt,
                'LastMsgTime': options.LastMsgTime,
                'MsgKey': options.MsgKey
            };
            //读取c2c漫游消息
            proto_getC2CHistoryMsgs(opts, function (resp) {
                var msgObjList = [];
                //处理c2c消息
                msgInfos = resp.MsgList;//返回的消息列表
                var sess = MsgStore.sessByTypeId(SESSION_TYPE.C2C, options.Peer_Account);
                if (!sess) {
                    sess = new Session(SESSION_TYPE.C2C, options.Peer_Account, options.Peer_Account, '', 0, 0);
                }
                for (var i in msgInfos) {
                    var msgInfo = msgInfos[i];
                    var isSendMsg, id, headUrl;
                    if (msgInfo.From_Account == ctx.identifier) {//当前用户发送的消息
                        isSendMsg = true;
                        id = msgInfo.To_Account;//读取接收者信息
                        headUrl = '';
                    } else {//当前用户收到的消息
                        isSendMsg = false;
                        id = msgInfo.From_Account;//读取发送者信息
                        headUrl = '';
                    }
                    var msg = new Msg(sess, isSendMsg, msgInfo.MsgSeq, msgInfo.MsgRandom, msgInfo.MsgTimeStamp, msgInfo.From_Account);
                    var msgBody = null;
                    var msgContent = null;
                    var msgType = null;
                    for (var mi in msgInfo.MsgBody) {
                        msgBody = msgInfo.MsgBody[mi];
                        msgType = msgBody.MsgType;
                        switch (msgType) {
                            case MSG_ELEMENT_TYPE.TEXT:
                                msgContent = new Msg.Elem.Text(msgBody.MsgContent.Text);
                                break;
                            case MSG_ELEMENT_TYPE.FACE:
                                msgContent = new Msg.Elem.Face(
                                    msgBody.MsgContent.Index,
                                    msgBody.MsgContent.Data
                                );
                                break;
                            case MSG_ELEMENT_TYPE.IMAGE:
                                msgContent = new Msg.Elem.Images(
                                    msgBody.MsgContent.UUID
                                );
                                for (var j in msgBody.MsgContent.ImageInfoArray) {
                                    var tempImg = msgBody.MsgContent.ImageInfoArray[j];
                                    msgContent.addImage(
                                        new Msg.Elem.Images.Image(
                                            tempImg.Type,
                                            tempImg.Size,
                                            tempImg.Width,
                                            tempImg.Height,
                                            tempImg.URL
                                        )
                                    );
                                }
                                break;
                            case MSG_ELEMENT_TYPE.SOUND:

                                // var soundUrl = getSoundDownUrl(msgBody.MsgContent.UUID, msgInfo.From_Account);

                                if (msgBody.MsgContent) {
                                    msgContent = new Msg.Elem.Sound(
                                        msgBody.MsgContent.UUID,
                                        msgBody.MsgContent.Second,
                                        msgBody.MsgContent.Size,
                                        msgInfo.From_Account,
                                        msgInfo.To_Account,
                                        msgBody.MsgContent.Download_Flag,
                                        SESSION_TYPE.C2C
                                    );
                                } else {
                                    msgType = MSG_ELEMENT_TYPE.TEXT;
                                    msgContent = new Msg.Elem.Text('[语音消息]下载地址解析出错');
                                }
                                break;
                            case MSG_ELEMENT_TYPE.LOCATION:
                                msgContent = new Msg.Elem.Location(
                                    msgBody.MsgContent.Longitude,
                                    msgBody.MsgContent.Latitude,
                                    msgBody.MsgContent.Desc
                                );
                                break;
                            case MSG_ELEMENT_TYPE.FILE:
                            case MSG_ELEMENT_TYPE.FILE + " ":
                                msgType = MSG_ELEMENT_TYPE.FILE;
                                // var fileUrl = getFileDownUrl(msgBody.MsgContent.UUID, msgInfo.From_Account, msgBody.MsgContent.FileName);

                                if (msgBody.MsgContent) {
                                    msgContent = new Msg.Elem.File(
                                        msgBody.MsgContent.UUID,
                                        msgBody.MsgContent.FileName,
                                        msgBody.MsgContent.FileSize,
                                        msgInfo.From_Account,
                                        msgInfo.To_Account,
                                        msgBody.MsgContent.Download_Flag,
                                        SESSION_TYPE.C2C
                                    );
                                } else {
                                    msgType = MSG_ELEMENT_TYPE.TEXT;
                                    msgContent = new Msg.Elem.Text('[文件消息下载地址解析出错]');
                                }
                                break;
                            case MSG_ELEMENT_TYPE.CUSTOM:
                                msgType = MSG_ELEMENT_TYPE.CUSTOM;
                                msgContent = new Msg.Elem.Custom(
                                    msgBody.MsgContent.Data,
                                    msgBody.MsgContent.Desc,
                                    msgBody.MsgContent.Ext
                                );

                                break;
                            default :
                                msgType = MSG_ELEMENT_TYPE.TEXT;
                                msgContent = new Msg.Elem.Text('web端暂不支持' + msgBody.MsgType + '消息');
                                break;
                        }
                        msg.elems.push(new Msg.Elem(msgType, msgContent));
                    }
                    MsgStore.addMsg(msg);
                    msgObjList.push(msg);
                } // for loop

                MsgStore.updateTimeline();
                if (cbOk) {

                    var newResp = {
                        'Complete': resp.Complete,
                        'MsgCount': msgObjList.length,
                        'LastMsgTime': resp.LastMsgTime,
                        'MsgKey': resp.MsgKey,
                        'MsgList': msgObjList
                    };
                    sess.isFinished(resp.Complete);
                    cbOk(newResp);
                }

            }, function (err) {
                log.error("getC2CHistoryMsgs failed:" + err.ErrorInfo);
                if (cbErr) cbErr(err);
            });
        };

        //拉群历史消息
        //不传cbOk 和 cbErr，则会调用新消息回调函数
        this.syncGroupMsgs = function (options, cbOk, cbErr) {
            if (options.ReqMsgSeq <= 0) {
                if (cbErr) {
                    var errInfo = "ReqMsgSeq must be greater than 0";
                    var error = tool.getReturnError(errInfo, -16);
                    cbErr(error);
                }
                return;
            }
            var opts = {
                'GroupId': options.GroupId,
                'ReqMsgSeq': options.ReqMsgSeq,
                'ReqMsgNumber': options.ReqMsgNumber
            };
            //读群漫游消息
            proto_getGroupMsgs(opts, function (resp) {
                var notifyInfo = [];
                var group_id = resp.GroupId;//返回的群id
                var msgInfos = resp.RspMsgList;//返回的消息列表
                var isFinished = resp.IsFinished;

                if (msgInfos == null || msgInfos === undefined) {
                    if (cbOk) {
                        cbOk([]);
                    }
                    return;
                }
                for (var i = msgInfos.length - 1; i >= 0; i--) {
                    var msgInfo = msgInfos[i];
                    //如果是已经删除的消息或者发送者帐号为空或者消息内容为空
                    //IsPlaceMsg=1
                    if (msgInfo.IsPlaceMsg || !msgInfo.From_Account || !msgInfo.MsgBody || msgInfo.MsgBody.length == 0) {
                        continue;
                    }
                    var msg = handlerGroupMsg(msgInfo, true, true,isFinished);
                    if (msg) {
                        notifyInfo.push(msg);
                    }
                } // for loop
                if (notifyInfo.length > 0)
                    MsgStore.updateTimeline();
                if (cbOk) cbOk(notifyInfo);
                else if (notifyInfo.length > 0) {
                    if (onMsgCallback) onMsgCallback(notifyInfo);
                }

            }, function (err) {
                log.error("getGroupMsgs failed:" + err.ErrorInfo);
                if (cbErr) cbErr(err);
            });
        };

        //处理群消息(普通消息+提示消息)
        //isSyncGroupMsgs 是否主动拉取群消息标志
        //isAddMsgFlag 是否需要保存到MsgStore，如果需要，这里会存在判重逻辑
        var handlerGroupMsg = function (msgInfo, isSyncGroupMsgs, isAddMsgFlag ,isFinished) {
            if (msgInfo.IsPlaceMsg || !msgInfo.From_Account || !msgInfo.MsgBody || msgInfo.MsgBody.length == 0) {
                return null;
            }
            var isSendMsg, id, headUrl, fromAccountNick;
            var group_id = msgInfo.ToGroupId;
            var group_name = group_id;
            if (msgInfo.GroupInfo) {//取出群名称
                if (msgInfo.GroupInfo.GroupName) {
                    group_name = msgInfo.GroupInfo.GroupName;
                }
            }
            //取出成员昵称
            fromAccountNick = msgInfo.From_Account;
            if (msgInfo.GroupInfo) {
                if (msgInfo.GroupInfo.From_AccountNick) {
                    fromAccountNick = msgInfo.GroupInfo.From_AccountNick;
                }
            }
            if (msgInfo.From_Account == ctx.identifier) {//当前用户发送的消息
                isSendMsg = true;
                id = msgInfo.From_Account;//读取接收者信息
                headUrl = '';
            } else {//当前用户收到的消息
                isSendMsg = false;
                id = msgInfo.From_Account;//读取发送者信息
                headUrl = '';
            }
            var sess = MsgStore.sessByTypeId(SESSION_TYPE.GROUP, group_id);
            if (!sess) {
                sess = new Session(SESSION_TYPE.GROUP, group_id, group_name, headUrl, 0, 0);
            }
            if(typeof isFinished !=="undefined") {
                sess.isFinished(isFinished || 0);
            }
            var subType = GROUP_MSG_SUB_TYPE.COMMON;//消息类型
            //群提示消息,重新封装下
            if (LONG_POLLINNG_EVENT_TYPE.GROUP_TIP == msgInfo.Event || LONG_POLLINNG_EVENT_TYPE.GROUP_TIP2 == msgInfo.Event) {
                subType = GROUP_MSG_SUB_TYPE.TIP;
                var groupTip = msgInfo.MsgBody;
                msgInfo.MsgBody = [];
                msgInfo.MsgBody.push({
                        "MsgType": MSG_ELEMENT_TYPE.GROUP_TIP,
                        "MsgContent": groupTip
                    }
                );
            } else if (msgInfo.MsgPriority) {//群点赞消息
                if (msgInfo.MsgPriority == GROUP_MSG_PRIORITY_TYPE.REDPACKET) {
                    subType = GROUP_MSG_SUB_TYPE.REDPACKET;
                } else if (msgInfo.MsgPriority == GROUP_MSG_PRIORITY_TYPE.LOVEMSG) {
                    subType = GROUP_MSG_SUB_TYPE.LOVEMSG;
                }

            }
            var msg = new Msg(sess, isSendMsg, msgInfo.MsgSeq, msgInfo.MsgRandom, msgInfo.MsgTimeStamp, msgInfo.From_Account, subType, fromAccountNick);
            var msgBody = null;
            var msgContent = null;
            var msgType = null;
            for (var mi in msgInfo.MsgBody) {
                msgBody = msgInfo.MsgBody[mi];
                msgType = msgBody.MsgType;
                switch (msgType) {
                    case MSG_ELEMENT_TYPE.TEXT:
                        msgContent = new Msg.Elem.Text(msgBody.MsgContent.Text);
                        break;
                    case MSG_ELEMENT_TYPE.FACE:
                        msgContent = new Msg.Elem.Face(
                            msgBody.MsgContent.Index,
                            msgBody.MsgContent.Data
                        );
                        break;
                    case MSG_ELEMENT_TYPE.IMAGE:
                        msgContent = new Msg.Elem.Images(
                            msgBody.MsgContent.UUID
                        );
                        for (var j in msgBody.MsgContent.ImageInfoArray) {
                            msgContent.addImage(
                                new Msg.Elem.Images.Image(
                                    msgBody.MsgContent.ImageInfoArray[j].Type,
                                    msgBody.MsgContent.ImageInfoArray[j].Size,
                                    msgBody.MsgContent.ImageInfoArray[j].Width,
                                    msgBody.MsgContent.ImageInfoArray[j].Height,
                                    msgBody.MsgContent.ImageInfoArray[j].URL
                                )
                            );
                        }
                        break;
                    case MSG_ELEMENT_TYPE.SOUND:
                       if (msgBody.MsgContent) {
                            msgContent = new Msg.Elem.Sound(
                                msgBody.MsgContent.UUID,
                                msgBody.MsgContent.Second,
                                msgBody.MsgContent.Size,
                                msgInfo.From_Account,
                                msgInfo.To_Account,
                                msgBody.MsgContent.Download_Flag,
                                SESSION_TYPE.GROUP
                            );
                        } else {
                            msgType = MSG_ELEMENT_TYPE.TEXT;
                            msgContent = new Msg.Elem.Text('[语音消息]下载地址解析出错');
                        }
                        break;
                    case MSG_ELEMENT_TYPE.LOCATION:
                        msgContent = new Msg.Elem.Location(
                            msgBody.MsgContent.Longitude,
                            msgBody.MsgContent.Latitude,
                            msgBody.MsgContent.Desc
                        );
                        break;
                    case MSG_ELEMENT_TYPE.FILE:
                    case MSG_ELEMENT_TYPE.FILE + " ":
                        msgType = MSG_ELEMENT_TYPE.FILE;
                        var fileUrl = getFileDownUrl(msgBody.MsgContent.UUID, msgInfo.From_Account, msgBody.MsgContent.FileName);

                        if (msgBody.MsgContent) {
                            msgContent = new Msg.Elem.File(
                                msgBody.MsgContent.UUID,
                                msgBody.MsgContent.FileName,
                                msgBody.MsgContent.FileSize,
                                msgInfo.From_Account,
                                msgInfo.To_Account,
                                msgBody.MsgContent.Download_Flag,
                                SESSION_TYPE.GROUP
                            );
                        } else {
                            msgType = MSG_ELEMENT_TYPE.TEXT;
                            msgContent = new Msg.Elem.Text('[文件消息]地址解析出错');
                        }
                        break;
                    case MSG_ELEMENT_TYPE.GROUP_TIP:
                        var opType = msgBody.MsgContent.OpType;
                        msgContent = new Msg.Elem.GroupTip(
                            opType,
                            msgBody.MsgContent.Operator_Account,
                            group_id,
                            msgInfo.GroupInfo.GroupName,
                            msgBody.MsgContent.List_Account
                        );
                        if (GROUP_TIP_TYPE.JOIN == opType || GROUP_TIP_TYPE.QUIT == opType) {//加群或退群时，设置最新群成员数
                            msgContent.setGroupMemberNum(msgBody.MsgContent.MemberNum);
                        } else if (GROUP_TIP_TYPE.MODIFY_GROUP_INFO == opType) {//群资料变更
                            var tempIsCallbackFlag = false;
                            var tempNewGroupInfo = {
                                "GroupId": group_id,
                                "GroupFaceUrl": null,
                                "GroupName": null,
                                "OwnerAccount": null,
                                "GroupNotification": null,
                                "GroupIntroduction": null
                            };
                            var msgGroupNewInfo = msgBody.MsgContent.MsgGroupNewInfo;
                            if (msgGroupNewInfo.GroupFaceUrl) {
                                var tmpNGIFaceUrl = new Msg.Elem.GroupTip.GroupInfo(
                                    GROUP_TIP_MODIFY_GROUP_INFO_TYPE.FACE_URL,
                                    msgGroupNewInfo.GroupFaceUrl
                                );
                                msgContent.addGroupInfo(tmpNGIFaceUrl);
                                tempIsCallbackFlag = true;
                                tempNewGroupInfo.GroupFaceUrl = msgGroupNewInfo.GroupFaceUrl;
                            }
                            if (msgGroupNewInfo.GroupName) {
                                var tmpNGIName = new Msg.Elem.GroupTip.GroupInfo(
                                    GROUP_TIP_MODIFY_GROUP_INFO_TYPE.NAME,
                                    msgGroupNewInfo.GroupName
                                );
                                msgContent.addGroupInfo(tmpNGIName);
                                tempIsCallbackFlag = true;
                                tempNewGroupInfo.GroupName = msgGroupNewInfo.GroupName;
                            }
                            if (msgGroupNewInfo.Owner_Account) {
                                var tmpNGIOwner = new Msg.Elem.GroupTip.GroupInfo(
                                    GROUP_TIP_MODIFY_GROUP_INFO_TYPE.OWNER,
                                    msgGroupNewInfo.Owner_Account
                                );
                                msgContent.addGroupInfo(tmpNGIOwner);
                                tempIsCallbackFlag = true;
                                tempNewGroupInfo.OwnerAccount = msgGroupNewInfo.Owner_Account;
                            }
                            if (msgGroupNewInfo.GroupNotification) {
                                var tmpNGINotification = new Msg.Elem.GroupTip.GroupInfo(
                                    GROUP_TIP_MODIFY_GROUP_INFO_TYPE.NOTIFICATION,
                                    msgGroupNewInfo.GroupNotification
                                );
                                msgContent.addGroupInfo(tmpNGINotification);
                                tempIsCallbackFlag = true;
                                tempNewGroupInfo.GroupNotification = msgGroupNewInfo.GroupNotification;
                            }
                            if (msgGroupNewInfo.GroupIntroduction) {
                                var tmpNGIIntroduction = new Msg.Elem.GroupTip.GroupInfo(
                                    GROUP_TIP_MODIFY_GROUP_INFO_TYPE.INTRODUCTION,
                                    msgGroupNewInfo.GroupIntroduction
                                );
                                msgContent.addGroupInfo(tmpNGIIntroduction);
                                tempIsCallbackFlag = true;
                                tempNewGroupInfo.GroupIntroduction = msgGroupNewInfo.GroupIntroduction;
                            }

                            //回调群资料变化通知方法
                            if (isSyncGroupMsgs == false && tempIsCallbackFlag && onGroupInfoChangeCallback) {
                                onGroupInfoChangeCallback(tempNewGroupInfo);
                            }

                        } else if (GROUP_TIP_TYPE.MODIFY_MEMBER_INFO == opType) {//群成员变更
                            var memberInfos = msgBody.MsgContent.MsgMemberInfo;
                            for (var n in memberInfos) {
                                var memberInfo = memberInfos[n];
                                msgContent.addMemberInfo(
                                    new Msg.Elem.GroupTip.MemberInfo(
                                        memberInfo.User_Account, memberInfo.ShutupTime
                                    )
                                );
                            }
                        }
                        break;
                    case MSG_ELEMENT_TYPE.CUSTOM:
                        msgType = MSG_ELEMENT_TYPE.CUSTOM;
                        msgContent = new Msg.Elem.Custom(
                            msgBody.MsgContent.Data,
                            msgBody.MsgContent.Desc,
                            msgBody.MsgContent.Ext
                        );
                        break;
                    default :
                        msgType = MSG_ELEMENT_TYPE.TEXT;
                        msgContent = new Msg.Elem.Text('web端暂不支持' + msgBody.MsgType + '消息');
                        break;
                }
                msg.elems.push(new Msg.Elem(msgType, msgContent));
            }

            if (isAddMsgFlag == false) {//不需要保存消息
                return msg;
            }

            if (MsgStore.addMsg(msg)) {
                return msg;
            } else {
                return null;
            }
        };

        //初始化
        this.init = function (listeners, cbOk, cbErr) {
            if (!listeners.onMsgNotify) {
                log.warn('listeners.onMsgNotify is empty');
            }
            onMsgCallback = listeners.onMsgNotify;

            if (listeners.onBigGroupMsgNotify) {
                onBigGroupMsgCallback = listeners.onBigGroupMsgNotify;
            } else {
                log.warn('listeners.onBigGroupMsgNotify is empty');
            }

            if (listeners.onC2cEventNotifys) {
                onC2cEventCallbacks = listeners.onC2cEventNotifys;
            } else {
                log.warn('listeners.onC2cEventNotifys is empty');
            }
            if (listeners.onGroupSystemNotifys) {
                onGroupSystemNotifyCallbacks = listeners.onGroupSystemNotifys;
            } else {
                log.warn('listeners.onGroupSystemNotifys is empty');
            }
            if (listeners.onGroupInfoChangeNotify) {
                onGroupInfoChangeCallback = listeners.onGroupInfoChangeNotify;
            } else {
                log.warn('listeners.onGroupInfoChangeNotify is empty');
            }
            if (listeners.onFriendSystemNotifys) {
                onFriendSystemNotifyCallbacks = listeners.onFriendSystemNotifys;
            } else {
                log.warn('listeners.onFriendSystemNotifys is empty');
            }
            if (listeners.onProfileSystemNotifys) {
                onProfileSystemNotifyCallbacks = listeners.onProfileSystemNotifys;
            } else {
                log.warn('listeners.onProfileSystemNotifys is empty');
            }
            if (listeners.onKickedEventCall) {
                onKickedEventCall = listeners.onKickedEventCall;
            } else {
                log.warn('listeners.onKickedEventCall is empty');
            }

            if (listeners.onAppliedDownloadUrl) {
                onAppliedDownloadUrl = listeners.onAppliedDownloadUrl;
            } else {
                log.warn('listeners.onAppliedDownloadUrl is empty');
            }

            if (!ctx.identifier || !ctx.userSig) {
                if (cbOk) {
                    var success = {
                        'ActionStatus': ACTION_STATUS.OK,
                        'ErrorCode': 0,
                        'ErrorInfo': "login success(no login state)"
                    };
                    cbOk(success);
                }
                return;
            }

            //初始化
            initMyGroupMaxSeqs(
                function (resp) {
                    log.info('initMyGroupMaxSeqs success');
                    //初始化文件
                    initIpAndAuthkey(
                        function (initIpAndAuthkeyResp) {
                            log.info('initIpAndAuthkey success');
                            if (cbOk) {
                                log.info('login success(have login state))');
                                var success = {
                                    'ActionStatus': ACTION_STATUS.OK,
                                    'ErrorCode': 0,
                                    'ErrorInfo': "login success"
                                };
                                cbOk(success);
                            }
                            MsgManager.setLongPollingOn(true);//开启长轮询
                            longPollingOn && MsgManager.longPolling(cbOk);
                        }, cbErr);
                }, cbErr);
        };

        //发消息（私聊或群聊）
        this.sendMsg = function (msg, cbOk, cbErr) {
            proto_sendMsg(msg, function (resp) {
                //私聊时，加入自己的发的消息，群聊时，由于seq和服务器的seq不一样，所以不作处理
                if (msg.sess.type() == SESSION_TYPE.C2C) {
                    if (!MsgStore.addMsg(msg)) {
                        var errInfo = "sendMsg: addMsg failed!";
                        var error = tool.getReturnError(errInfo, -17);
                        log.error(errInfo);
                        if (cbErr) cbErr(error);
                        return;
                    }
                    //更新信息流时间
                    MsgStore.updateTimeline();
                }
                if (cbOk) cbOk(resp);
            }, function (err) {
                if (cbErr) cbErr(err);
            });
        };
    };

    //上传文件
    var FileUploader = new function () {
        this.fileMd5 = null;
        //获取文件MD5
        var getFileMD5 = function (file, cbOk, cbErr) {

            //FileReader pc浏览器兼容性
            //Feature   Firefox (Gecko) Chrome  Internet Explorer   Opera   Safari
            //Basic support 3.6 7   10                      12.02   6.0.2
            var fileReader = null;
            try {
                fileReader = new FileReader();//分块读取文件对象
            } catch (e) {
                if (cbErr) {
                    cbErr(tool.getReturnError('当前浏览器不支持FileReader', -18));
                    return;
                }
            }
            //file的slice方法，注意它的兼容性，在不同浏览器的写法不同
            var blobSlice = File.prototype.mozSlice || File.prototype.webkitSlice || File.prototype.slice;
            if (!blobSlice) {
                if (cbErr) {
                    cbErr(tool.getReturnError('当前浏览器不支持FileAPI', -19));
                    return;
                }
            }

            var chunkSize = 2 * 1024 * 1024;//分块大小，2M
            var chunks = Math.ceil(file.size / chunkSize);//总块数
            var currentChunk = 0;//当前块数
            var spark = new SparkMD5();//获取MD5对象

            fileReader.onload = function (e) {//数据加载完毕事件

                var binaryStr = "";
                var bytes = new Uint8Array(e.target.result);
                var length = bytes.byteLength;
                for (var i = 0; i < length; i++) {
                    binaryStr += String.fromCharCode(bytes[i]);//二进制转换字符串
                }
                spark.appendBinary(binaryStr);
                currentChunk++;
                if (currentChunk < chunks) {
                    loadNext();//读取下一块数据
                } else {
                    this.fileMd5 = spark.end();//得到文件MD5值
                    if (cbOk) {
                        cbOk(this.fileMd5);
                    }
                }
            };
            //分片读取文件
            function loadNext() {
                var start = currentChunk * chunkSize, end = start + chunkSize >= file.size ? file.size : start + chunkSize;
                //根据开始和结束位置，切割文件
                var b = blobSlice.call(file, start, end);
                //readAsBinaryString ie浏览器不兼容此方法
                //fileReader.readAsBinaryString(blobSlice.call(file, start, end));
                fileReader.readAsArrayBuffer(b);//ie，chrome，firefox等主流浏览器兼容此方法

            }

            loadNext();//开始读取
        };
        //提交上传图片表单(用于低版本IE9以下)
        this.submitUploadFileForm = function (options, cbOk, cbErr) {
            var errInfo;
            var error;
            var formId = options.formId;
            var fileId = options.fileId;
            var iframeNum = uploadResultIframeId++;
            var iframeName = "uploadResultIframe_" + iframeNum;
            var toAccount = options.To_Account;
            var businessType = options.businessType;

            var form = document.getElementById(formId);
            if (!form) {
                errInfo = "获取表单对象为空: formId=" + formId + "(formId非法)";
                error = tool.getReturnError(errInfo, -20);
                if (cbErr) cbErr(error);
                return;
            }

            var fileObj = document.getElementById(fileId);
            if (!fileObj) {
                errInfo = "获取文件对象为空: fileId=" + fileId + "(没有选择文件或者fileId非法)";
                error = tool.getReturnError(errInfo, -21);
                if (cbErr) cbErr(error);
                return;
            }
            //fileObj.type="file";//ie8下不起作用，必须由业务自己设置
            fileObj.name = "file";

            var iframe = document.createElement("iframe");
            iframe.name = iframeName;
            iframe.id = iframeName;
            iframe.style.display = "none";
            document.body.appendChild(iframe);

            var cmdName;
            if (isAccessFormalEnv()) {
                cmdName = 'pic_up';
            } else {
                cmdName = 'pic_up_test';
            }
            var uploadApiUrl = "https://pic.tim.qq.com/v4/openpic/" + cmdName + "?tinyid=" + ctx.tinyid + "&a2=" + ctx.a2 + "&sdkappid=" + ctx.sdkAppID + "&accounttype=" + ctx.accountType + "&contenttype=http";
            form.action = uploadApiUrl;
            form.method = 'post';
            //form.enctype='multipart/form-data';//ie8下不起作用，必须由业务自己设置
            form.target = iframeName;

            function createFormInput(name, value) {
                var tempInput = document.createElement("input");
                tempInput.type = "hidden";
                tempInput.name = name;
                tempInput.value = value;
                form.appendChild(tempInput);
            }

            createFormInput("App_Version", VERSION_INFO.APP_VERSION);
            createFormInput("From_Account", ctx.identifier);
            createFormInput("To_Account", toAccount);
            createFormInput("Seq", nextSeq().toString());
            createFormInput("Timestamp", unixtime().toString());
            createFormInput("Random", createRandom().toString());
            createFormInput("Busi_Id", businessType);
            createFormInput("PkgFlag", UPLOAD_RES_PKG_FLAG.RAW_DATA.toString());
            createFormInput("Auth_Key", authkey);
            createFormInput("Server_Ver", VERSION_INFO.SERVER_VERSION.toString());
            createFormInput("File_Type", options.fileType);


            //检测iframe.contentWindow.name是否有值
            function checkFrameName() {
                var resp;
                try {
                    resp = JSON.parse(iframe.contentWindow.name) || {};
                } catch (e) {
                    resp = {};
                }
                if (resp.ActionStatus) {//上传接口返回
                    // We've got what we need. Stop the iframe from loading further content.
                    iframe.src = "about:blank";
                    iframe.parentNode.removeChild(iframe);
                    iframe = null;

                    if (resp.ActionStatus == ACTION_STATUS.OK) {
                        cbOk && cbOk(resp);
                    } else {
                        cbErr && cbErr(resp);
                    }
                } else {
                    setTimeout(checkFrameName, 100);
                }
            }

            setTimeout(checkFrameName, 500);

            form.submit();//提交上传图片表单
        };
        //上传图片或文件(用于高版本浏览器，支持FileAPI)
        this.uploadFile = function (options, cbOk, cbErr) {

            var file_upload = {
                //初始化
                init: function (options, cbOk, cbErr) {
                    var me = this;
                    me.file = options.file;
                    //分片上传进度回调事件
                    me.onProgressCallBack = options.onProgressCallBack;
                    //停止上传图片按钮
                    if (options.abortButton) {
                        options.abortButton.onclick = me.abortHandler;
                    }
                    me.total = me.file.size;//文件总大小
                    me.loaded = 0;//已读取字节数
                    me.step = 1080 * 1024;//分块大小，1080K
                    me.sliceSize = 0;//分片大小
                    me.sliceOffset = 0;//当前分片位置
                    me.timestamp = unixtime();//当前时间戳
                    me.seq = nextSeq();//请求seq
                    me.random = createRandom();//请求随机数
                    me.fromAccount = ctx.identifier;//发送者
                    me.toAccount = options.To_Account;//接收者
                    me.fileMd5 = options.fileMd5;//文件MD5
                    me.businessType = options.businessType;//图片或文件的业务类型，群消息:1; c2c消息:2; 个人头像：3; 群头像：4;
                    me.fileType = options.fileType;//文件类型，不填为默认认为上传的是图片；1：图片；2：文件；3：短视频；4：PTT

                    me.cbOk = cbOk;//上传成功回调事件
                    me.cbErr = cbErr;//上传失败回调事件

                    me.reader = new FileReader();//读取文件对象
                    me.blobSlice = File.prototype.mozSlice || File.prototype.webkitSlice || File.prototype.slice;//file的slice方法,不同浏览器不一样

                    me.reader.onloadstart = me.onLoadStart;//开始读取回调事件
                    me.reader.onprogress = me.onProgress;//读取文件进度回调事件
                    me.reader.onabort = me.onAbort;//停止读取回调事件
                    me.reader.onerror = me.onerror;//读取发生错误回调事件
                    me.reader.onload = me.onLoad;//分片加载完毕回调事件
                    me.reader.onloadend = me.onLoadEnd;//读取文件完毕回调事件
                },
                //上传方法
                upload: function () {
                    var me = file_upload;
                    //读取第一块
                    me.readBlob(0);
                },
                onLoadStart: function () {
                    var me = file_upload;
                },
                onProgress: function (e) {
                    var me = file_upload;
                    me.loaded += e.loaded;
                    if (me.onProgressCallBack) {
                        me.onProgressCallBack(me.loaded, me.total);
                    }
                },
                onAbort: function () {
                    var me = file_upload;
                },
                onError: function () {
                    var me = file_upload;
                },
                onLoad: function (e) {
                    var me = file_upload;
                    if (e.target.readyState == FileReader.DONE) {
                        var slice_data_base64 = e.target.result;
                        //注意，一定要去除base64编码头部
                        var pos = slice_data_base64.indexOf(",");
                        if (pos != -1) {
                            slice_data_base64 = slice_data_base64.substr(pos + 1);
                        }
                        //封装上传图片接口的请求参数
                        var opt = {
                            'From_Account': me.fromAccount,
                            'To_Account': me.toAccount,
                            'Busi_Id': me.businessType,
                            'File_Type': me.fileType,
                            'File_Str_Md5': me.fileMd5,
                            'PkgFlag': UPLOAD_RES_PKG_FLAG.BASE64_DATA,
                            'File_Size': me.total,
                            'Slice_Offset': me.sliceOffset,
                            'Slice_Size': me.sliceSize,
                            'Slice_Data': slice_data_base64,
                            'Seq': me.seq,
                            'Timestamp': me.timestamp,
                            'Random': me.random
                        };

                        //上传成功的成功回调
                        var succCallback = function (resp) {
                            if (resp.IsFinish == 0) {
                                me.loaded = resp.Next_Offset;
                                if (me.loaded < me.total) {
                                    me.readBlob(me.loaded);
                                } else {
                                    me.loaded = me.total;
                                }
                            } else {

                                if (me.cbOk) {
                                    var tempResp = {
                                        'ActionStatus': resp.ActionStatus,
                                        'ErrorCode': resp.ErrorCode,
                                        'ErrorInfo': resp.ErrorInfo,
                                        'File_UUID': resp.File_UUID,
                                        'File_Size': resp.Next_Offset,
                                        'URL_INFO': resp.URL_INFO,
                                        'Download_Flag':resp.Download_Flag
                                    };
                                    if (me.fileType == UPLOAD_RES_TYPE.FILE) {//如果上传的是文件，下载地址需要sdk内部拼接
                                        tempResp.URL_INFO = getFileDownUrl(resp.File_UUID, ctx.identifier, me.file.name);
                                    }
                                    me.cbOk(tempResp);
                                }
                            }
                            Upload_Retry_Times = 0;
                        };
                        //上传失败的回调
                        var errorCallback = function(resp){
                            if(Upload_Retry_Times < Upload_Retry_Max_Times){
                                Upload_Retry_Times ++;
                                setTimeout(function(){
                                    proto_uploadPic(opt,succCallback,errorCallback);
                                },1000);
                            }else{
                                me.cbErr(resp);
                            }
                            //me.cbErr
                        };
                        //分片上传图片接口
                        proto_uploadPic(opt, succCallback,errorCallback);
                    }
                },
                onLoadEnd: function () {
                    var me = file_upload;
                },
                //分片读取文件方法
                readBlob: function (start) {
                    var me = file_upload;
                    var blob, file = me.file;
                    var end = start + me.step;
                    if (end > me.total) {
                        end = me.total;
                        me.sliceSize = end - start;
                    } else {
                        me.sliceSize = me.step;
                    }
                    me.sliceOffset = start;
                    //根据起始和结束位置，分片读取文件
                    blob = me.blobSlice.call(file, start, end);
                    //将分片的二进制数据转换为base64编码
                    me.reader.readAsDataURL(blob);
                },
                abortHandler: function () {
                    var me = file_upload;
                    if (me.reader) {
                        me.reader.abort();
                    }
                }
            };

            //读取文件MD5
            getFileMD5(options.file,
                function (fileMd5) {
                    log.info('fileMd5: ' + fileMd5);
                    options.fileMd5 = fileMd5;
                    //初始化上传参数
                    file_upload.init(options, cbOk, cbErr);
                    //开始上传文件
                    file_upload.upload();
                },
                cbErr
            );
        };
    };


    //web im 基础对象

    //常量对象

    //会话类型
    webim.SESSION_TYPE = SESSION_TYPE;

    webim.MSG_MAX_LENGTH = MSG_MAX_LENGTH;

    //c2c消息子类型
    webim.C2C_MSG_SUB_TYPE = C2C_MSG_SUB_TYPE;

    //群消息子类型
    webim.GROUP_MSG_SUB_TYPE = GROUP_MSG_SUB_TYPE;

    //消息元素类型
    webim.MSG_ELEMENT_TYPE = MSG_ELEMENT_TYPE;

    //群提示消息类型
    webim.GROUP_TIP_TYPE = GROUP_TIP_TYPE;

    //图片类型
    webim.IMAGE_TYPE = IMAGE_TYPE;

    //群系统消息类型
    webim.GROUP_SYSTEM_TYPE = GROUP_SYSTEM_TYPE;

    //好友系统通知子类型
    webim.FRIEND_NOTICE_TYPE = FRIEND_NOTICE_TYPE;

    //群提示消息-群资料变更类型
    webim.GROUP_TIP_MODIFY_GROUP_INFO_TYPE = GROUP_TIP_MODIFY_GROUP_INFO_TYPE;

    //浏览器信息
    webim.BROWSER_INFO = BROWSER_INFO;

    //表情对象
    webim.Emotions = webim.EmotionPicData = emotions;
    //表情标识符和index Map
    webim.EmotionDataIndexs = webim.EmotionPicDataIndex = emotionDataIndexs;

    //腾讯登录服务错误码(托管模式)
    webim.TLS_ERROR_CODE = TLS_ERROR_CODE;

    //连接状态
    webim.CONNECTION_STATUS = CONNECTION_STATUS;

    //上传图片业务类型
    webim.UPLOAD_PIC_BUSSINESS_TYPE = UPLOAD_PIC_BUSSINESS_TYPE;

    //最近联系人类型
    webim.RECENT_CONTACT_TYPE = RECENT_CONTACT_TYPE;

    //上传资源类型
    webim.UPLOAD_RES_TYPE = UPLOAD_RES_TYPE;


    /**************************************/

    //类对象
    //
    //工具对象
    webim.Tool = tool;
    //控制台打印日志对象
    webim.Log = log;

    //消息对象
    webim.Msg = Msg;
    //会话对象
    webim.Session = Session;
    //会话存储对象
    webim.MsgStore = {
        sessMap: function () {
            return MsgStore.sessMap();
        },
        sessCount: function () {
            return MsgStore.sessCount();
        },
        sessByTypeId: function (type, id) {
            return MsgStore.sessByTypeId(type, id);
        },
        delSessByTypeId: function (type, id) {
            return MsgStore.delSessByTypeId(type, id);
        },
        resetCookieAndSyncFlag: function () {
            return MsgStore.resetCookieAndSyncFlag();
        }
    };

    webim.Resources = Resources;

    /**************************************/

    // webim API impl
    //
    //基本接口
    //登录
    webim.login = webim.init = function (loginInfo, listeners, opts, cbOk, cbErr) {

        //初始化连接状态回调函数
        ConnManager.init(listeners.onConnNotify, cbOk, cbErr);

        //设置ie9以下浏览器jsonp回调
        if (listeners.jsonpCallback) jsonpCallback = listeners.jsonpCallback;
        //登录
        _login(loginInfo, listeners, opts, cbOk, cbErr);
    };
    //登出
    //需要传长轮询id
    //这样登出之后其他的登录实例还可以继续收取消息
    webim.logout = webim.offline = function (cbOk, cbErr) {
        return proto_logout('instance',cbOk, cbErr);
    };

    //登出
    //这种登出方式，所有的实例都将不会收到消息推送，直到重新登录
    webim.logoutAll = function (cbOk, cbErr) {
        return proto_logout('all',cbOk, cbErr);
    };


    //消息管理接口
    //发消息接口（私聊和群聊）
    webim.sendMsg = function (msg, cbOk, cbErr) {
        return MsgManager.sendMsg(msg, cbOk, cbErr);
    };
    //拉取未读c2c消息
    webim.syncMsgs = function (cbOk, cbErr) {
        return MsgManager.syncMsgs(cbOk, cbErr);
    };
    //拉取C2C漫游消息
    webim.getC2CHistoryMsgs = function (options, cbOk, cbErr) {
        return MsgManager.getC2CHistoryMsgs(options, cbOk, cbErr);
    };
    //拉取群漫游消息
    webim.syncGroupMsgs = function (options, cbOk, cbErr) {
        return MsgManager.syncGroupMsgs(options, cbOk, cbErr);
    };

    //上报c2c消息已读
    webim.c2CMsgReaded = function (options, cbOk, cbErr) {
        return MsgStore.c2CMsgReaded(options, cbOk, cbErr);
    };

    //上报群消息已读
    webim.groupMsgReaded = function (options, cbOk, cbErr) {
        return proto_groupMsgReaded(options, cbOk, cbErr);
    };

    //设置聊天会话自动标记已读
    webim.setAutoRead = function (selSess, isOn, isResetAll) {
        return MsgStore.setAutoRead(selSess, isOn, isResetAll);
    };

    //群组管理接口
    //
    //创建群
    webim.createGroup = function (options, cbOk, cbErr) {
        return proto_createGroup(options, cbOk, cbErr);
    };
    //创建群-高级接口
    webim.createGroupHigh = function (options, cbOk, cbErr) {
        return proto_createGroupHigh(options, cbOk, cbErr);
    };
    //申请加群
    webim.applyJoinGroup = function (options, cbOk, cbErr) {
        return proto_applyJoinGroup(options, cbOk, cbErr);
    };
    //处理加群申请(同意或拒绝)
    webim.handleApplyJoinGroupPendency = function (options, cbOk, cbErr) {
        return proto_handleApplyJoinGroupPendency(options, cbOk, cbErr);
    };

    //删除加群申请
    webim.deleteApplyJoinGroupPendency = function (options, cbOk, cbErr) {
        return proto_deleteC2CMsg(options, cbOk, cbErr);
    };

    //主动退群
    webim.quitGroup = function (options, cbOk, cbErr) {
        return proto_quitGroup(options, cbOk, cbErr);
    };
    //搜索群组(根据名称)
    webim.searchGroupByName = function (options, cbOk, cbErr) {
        return proto_searchGroupByName(options, cbOk, cbErr);
    };
    //获取群组公开资料(根据群id搜索)
    webim.getGroupPublicInfo = function (options, cbOk, cbErr) {
        return proto_getGroupPublicInfo(options, cbOk, cbErr);
    };
    //获取群组详细资料-高级接口
    webim.getGroupInfo = function (options, cbOk, cbErr) {
        return proto_getGroupInfo(options, cbOk, cbErr);
    };
    //修改群基本资料
    webim.modifyGroupBaseInfo = function (options, cbOk, cbErr) {
        return proto_modifyGroupBaseInfo(options, cbOk, cbErr);
    };
    //获取群成员列表
    webim.getGroupMemberInfo = function (options, cbOk, cbErr) {
        return proto_getGroupMemberInfo(options, cbOk, cbErr);
    };
    //邀请好友加群
    webim.addGroupMember = function (options, cbOk, cbErr) {
        return proto_addGroupMember(options, cbOk, cbErr);
    };
    //修改群成员资料
    webim.modifyGroupMember = function (options, cbOk, cbErr) {
        return proto_modifyGroupMember(options, cbOk, cbErr);
    };
    //删除群成员
    webim.deleteGroupMember = function (options, cbOk, cbErr) {
        return proto_deleteGroupMember(options, cbOk, cbErr);
    };
    //解散群
    webim.destroyGroup = function (options, cbOk, cbErr) {
        return proto_destroyGroup(options, cbOk, cbErr);
    };
    //转让群组
    webim.changeGroupOwner = function (options, cbOk, cbErr) {
        return proto_changeGroupOwner(options, cbOk, cbErr);
    };

    //获取我的群组列表-高级接口
    webim.getJoinedGroupListHigh = function (options, cbOk, cbErr) {
        return proto_getJoinedGroupListHigh(options, cbOk, cbErr);
    };
    //获取群成员角色
    webim.getRoleInGroup = function (options, cbOk, cbErr) {
        return proto_getRoleInGroup(options, cbOk, cbErr);
    };
    //设置群成员禁言时间
    webim.forbidSendMsg = function (options, cbOk, cbErr) {
        return proto_forbidSendMsg(options, cbOk, cbErr);
    };
    //发送自定义群系统通知
    webim.sendCustomGroupNotify = function (options, cbOk, cbErr) {
        return proto_sendCustomGroupNotify(options, cbOk, cbErr);
    };

    //进入大群
    webim.applyJoinBigGroup = function (options, cbOk, cbErr) {
        return proto_applyJoinBigGroup(options, cbOk, cbErr);
    };
    //退出大群
    webim.quitBigGroup = function (options, cbOk, cbErr) {
        return proto_quitBigGroup(options, cbOk, cbErr);
    };

    //资料关系链管理接口
    //
    //获取个人资料接口，可用于搜索用户
    webim.getProfilePortrait = function (options, cbOk, cbErr) {
        return proto_getProfilePortrait(options, cbOk, cbErr);
    };
    //设置个人资料
    webim.setProfilePortrait = function (options, cbOk, cbErr) {
        return proto_setProfilePortrait(options, cbOk, cbErr);
    };
    //申请加好友
    webim.applyAddFriend = function (options, cbOk, cbErr) {
        return proto_applyAddFriend(options, cbOk, cbErr);
    };
    //获取好友申请列表
    webim.getPendency = function (options, cbOk, cbErr) {
        return proto_getPendency(options, cbOk, cbErr);
    };
    //删除好友申请
    webim.deletePendency = function (options, cbOk, cbErr) {
        return proto_deletePendency(options, cbOk, cbErr);
    };
    //处理好友申请
    webim.responseFriend = function (options, cbOk, cbErr) {
        return proto_responseFriend(options, cbOk, cbErr);
    };
    //获取我的好友
    webim.getAllFriend = function (options, cbOk, cbErr) {
        return proto_getAllFriend(options, cbOk, cbErr);
    };
    //删除好友
    webim.deleteFriend = function (options, cbOk, cbErr) {
        return proto_deleteFriend(options, cbOk, cbErr);
    };
    //拉黑
    webim.addBlackList = function (options, cbOk, cbErr) {
        return proto_addBlackList(options, cbOk, cbErr);
    };
    //删除黑名单
    webim.deleteBlackList = function (options, cbOk, cbErr) {
        return proto_deleteBlackList(options, cbOk, cbErr);
    };
    //获取我的黑名单
    webim.getBlackList = function (options, cbOk, cbErr) {
        return proto_getBlackList(options, cbOk, cbErr);
    };

    //获取最近会话
    webim.getRecentContactList = function (options, cbOk, cbErr) {
        return proto_getRecentContactList(options, cbOk, cbErr);
    };

    //图片或文件服务接口
    //
    //上传文件接口（高版本浏览器）
    webim.uploadFile = webim.uploadPic = function (options, cbOk, cbErr) {
        return FileUploader.uploadFile(options, cbOk, cbErr);
    };
    //提交上传图片表单接口（用于低版本ie）
    webim.submitUploadFileForm = function (options, cbOk, cbErr) {
        return FileUploader.submitUploadFileForm(options, cbOk, cbErr);
    };
    //上传图片或文件(Base64)接口
    webim.uploadFileByBase64 = webim.uploadPicByBase64 = function (options, cbOk, cbErr) {
        //请求参数
        var opt = {
            'To_Account': options.toAccount,
            'Busi_Id': options.businessType,
            'File_Type': options.File_Type,
            'File_Str_Md5': options.fileMd5,
            'PkgFlag': UPLOAD_RES_PKG_FLAG.BASE64_DATA,
            'File_Size': options.totalSize,
            'Slice_Offset': 0,
            'Slice_Size': options.totalSize,
            'Slice_Data': options.base64Str,
            'Seq': nextSeq(),
            'Timestamp': unixtime(),
            'Random': createRandom()
        };
        return proto_uploadPic(opt, cbOk, cbErr);
    };

    //设置jsonp返回的值
    webim.setJsonpLastRspData = function (rspData) {
        jsonpLastRspData = typeof (rspData) == "string" ? JSON.parse(rspData) : rspData;
    };

    //获取长轮询ID
    webim.getLongPollingId = function (options, cbOk, cbErr) {
        return proto_getLongPollingId(options, cbOk, cbErr);
    };

    //获取下载地址
    webim.applyDownload = function (options, cbOk, cbErr) {
        return proto_applyDownload(options, cbOk, cbErr);
    };

    //获取下载地址
    webim.onDownFile = function (uuid) {
        window.open(Resources.downloadMap["uuid_"+uuid]);
    };

    //检查是否登录
    webim.checkLogin = function (cbErr, isNeedCallBack) {
        return checkLogin(cbErr, isNeedCallBack);
    };
})(webim);
