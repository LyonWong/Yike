/**
 * store
 */
import Vue from 'vue';
import Vuex from 'vuex';
import * as actions from './actions';
import * as getters from './getters';

/* register middleware */
Vue.use(Vuex);

/* init state */
const state = {
  headerTitle: '授课区',
  loadShow: true,
  menuShow: false,
  isOwner : false,
  commentShow: false,
  commentType: 1,
  refundShow: false,
  evaluateShow: false,
  isHeader: true,
  userInfo: null,
  lessonInfo: null,
  teacherInfo: null,
  messageInfo: [],
  commentMessageInfo: [],
  recording: false,
  audioPause: false,
  sending: false,
  sendWidth: '0',
  loadingImg: 'static/_static/live/img/loading.gif',
  userAvatar: null,
  playingAudio: null,
  cancleRecord: false,
  blobRecord: null,
  isEvaluate: false,
  boxMoreShow: false,
  recorderStatus: false,
  recorderTimer: '0:01',
  statsInfo: {},
  prepareList: [],
  recorderFold: false,
  videoPlaying: false,
  audioCompressComplete: false,
  footerConf: null,
  fullMember: false,
  bookmarkList: [],
  liveHost: (process.env.NODE_ENV=='production'?process.env.LIVE_HOST.replace(/\/$/,''):'/api'),
  studentHost:(process.env.NODE_ENV=='production'?process.env.STUDENT_HOST.replace(/\/$/,'?'):'/student.html?'),
  teacherHost:(process.env.NODE_ENV=='production'?process.env.TEACHER_HOST.replace(/\/$/,''):'/teacher.html'),
  assetsHost:(process.env.ASSETS_HOST ? process.env.ASSETS_HOST : 'https://assets.sandbox.yike.fm/'),
};

/* mutations serials */
const mutations = {
  // update title
  UPDATE_TITLE(state, title){
    state.headerTitle = title;
  },
  //
  UPDATE_LOADING(state, show){
    state.loadShow = show;
  },
  UPDATE_RECORDING(state, show){
    state.recording = show;
  },
  UPDATE_BLOB_RECORDING(state, blob){
    state.blobRecord = blob;
  },
  UPDATE_SENDING(state, show){
    state.sending = show;
  },
  UPDATE_SENDING_WIDTH(state, width){
    state.sendWidth = width;
  },
  //
  UPDATE_MESSAGE(state, data){
    console.log('update message', data)
    if(Array.isArray(data)){
      for (let item of data) {
       if (item.content[0].type == 'TIMCustomElem' && item.content[0].custom[0].type == 'MARK') {
        item.isSystem = true
        item.bookmark = item.content[0].custom[0];
        if (item.bookmark) {
          state.bookmarkList.push(item.bookmark);
        }
       }
       if (item.content[0].type == 'TIMCustomElem' && item.content[0].custom[0].type == 'ADMIRE') {
         item.isSystem = true
         item.admire = item.content[0].custom[0]
       }
      }
      console.log('array', data)
      state.messageInfo = [...state.messageInfo, ...data];
    }else if(Object.prototype.toString.call(data) == '[object Object]'){
      if (data.content[0].type == 'TIMCustomElem' && data.content[0].custom[0].type == 'MARK') {
        data.isSystem = true
        data.bookmark = data.content[0].custom[0];
        if (data.bookmark) {
          state.bookmarkList.push(data.bookmark);
        }
      }
      if (data.content[0].type == 'TIMCustomElem' && data.content[0].custom[0].type == 'ADMIRE') {
        data.isSystem = true
        data.admire = data.content[0].custom[0]
      }
      console.log('object', data)
      state.messageInfo = [...state.messageInfo, data];
    }
  },
  //
  UPDATE_HISTORY_MESSAGE(state, data){
    console.log('update history message', data)
    if(Array.isArray(data)){
      console.log('foo')
      for (let item of data) {
        if (item.content[0].type == 'TIMCustomElem' && item.content[0].custom[0].type == 'MARK') {
          item.isSystem = true
          item.bookmark = item.content[0].custom[0];
          if (item.bookmark) {
            state.bookmarkList.push(item.bookmark);
          }
        }
      }
      state.messageInfo = [...data, ...state.messageInfo];
    }else if(Object.prototype.toString.call(data) == '[object Object]'){
      console.log('bar')
      if (data.content[0].type == 'TIMCustomElem' && data.content[0].custom[0].type == 'MARK') {
        data.isSystem = true
        data.bookmark = data.content[0].custom[0];
        if (data.bookmark) {
          state.bookmarkList.push(data.bookmark);
        }
      }
      state.messageInfo = [data, ...state.messageInfo];
    } else {
      console.log('cuz')
    }
  },
  //
  UPDATE_COMMENT_MESSAGE(state, data){
    if(Array.isArray(data)){
      state.commentMessageInfo = [...state.commentMessageInfo, ...data];
    }else if(Object.prototype.toString.call(data) == '[object Object]'){
      state.commentMessageInfo = [...state.commentMessageInfo, data];
    }
  },
  //
  UPDATE_HISTORY_COMMENT_MESSAGE(state, data){
    if(Array.isArray(data)){
      state.commentMessageInfo = [...data, ...state.commentMessageInfo];
    }else if(Object.prototype.toString.call(data) == '[object Object]'){
      state.commentMessageInfo = [data, ...state.commentMessageInfo];
    }
  },
  RESET_COMMENT_MESSAGE(state, data){
    state.commentMessageInfo = [...data];
  },
  //
  UPDATE_MENUSHOW(state, data){
    state.menuShow = data;
  },
  // update comment show
  UPDATE_COMMETN_SHOW(state, show){
    state.commentShow = show;
  },
  UPDATE_COMMETN_TYPE(state, type){
    state.commentType = type;
  },
  UPDATE_REFUND_SHOW(state, show){
    state.refundShow = show;
  },
  // 是否是管理员
  UPDATE_ISOWNER(state, isOwner){
    state.isOwner = isOwner;
  },
  UPDATE_USERINFO(state, data){
    state.userInfo = { ...state.userInfo, ...data };
  },
  UPDATE_LESSONINFO(state, data){
    state.lessonInfo = { ...state.lessonInfo, ...data };
  },
  UPDATE_TEACHERINFO(state, data){
    state.teacherInfo = { ...state.teacherInfo, ...data };
  },
  UPDATE_AVATAR(state, avatar){
    state.loadingImg = avatar;
  },
  UPDATE_USER_AVATAR(state, avatars){
    state.userAvatar = { ...state.userAvatar, ...avatars };
  },
  // 正在播放的audio
  UPDATE_PLAYER(state, player){
    state.playingAudio = player;
  },
  // 正在播放的audio
  UPDATE_CANCLE_RECORD(state, record){
    state.cancleRecord = record;
  },
  UPDATE_EVALUATE_SHOW(state, show){
    state.evaluateShow = show;
  },
  UPDATE_IS_EVALUATE(state, eva){
    state.isEvaluate = eva;
  },
  UPDATE_BOX_MORE(state, show){
    state.boxMoreShow = show;
  },
  UPDATE_RECORDER_STATUS(state, stop){
    state.recorderStatus = stop;
  },
  UPDATE_RECORDER_TIMER(state, time){
    state.recorderTimer = time;
  },
  UPDATE_STATSINFO(state, info){
    state.statsInfo = { ...info };
  },
  UPDATE_AUDIO_COMPLETE(state, status){
    state.audioCompressComplete = status;
  },
  UPDATE_PREPARE_LIST(state, data){
    state.prepareList = [...data];
  },
  UPDATE_RECORDER_FOLD(state, fold){
    state.recorderFold = fold;
  },
  UPDATE_VIDEO_STATUS(state, play){
    state.videoPlaying = play;
  },
  UPDATE_FOOTER_CONF(state, conf){
    state.footerConf = conf;
  },
  UPDATE_FULL_MEMBER(state, full){
    state.fullMember = full;
  },
};

// 整合初始状态和变更函数，我们就得到了我们所需的 store
// 至此，这个 store 就可以连接到我们的应用中
export default new Vuex.Store({
  state,
  mutations,
  actions,
  getters,
});
