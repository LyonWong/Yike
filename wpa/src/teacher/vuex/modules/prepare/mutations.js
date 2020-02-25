import {
  ADD_PREPARE_LIST,
  UPDATE_CURSEQNO,
  UPDATE_SENDING,
  UPDATE_RECORDING,
  UPDATE_PREPARE_LIST,
  UPDATE_RECORDER_TIMER,
  UPDATE_RECORDER_STATUS,
  UPDATE_AUDIO_COMPLETE,
  UPDATE_BLOB_RECORDING,
  UPDATE_CANCLE_RECORD,
} from './mutation-type'

import { convertToArray } from '@lib/js/mUtils';

const mutations = {
  // 改变录音
  [UPDATE_RECORDING](state, show){
    state.recording = show;
  },
  // 录音时间
  [UPDATE_RECORDER_TIMER](state, time){
    state.recorderTimer = time;
  },
  // 录音状态
  [UPDATE_RECORDER_STATUS](state, stop){
    state.recorderStatus = stop;
  },
  // 录音完成
  [UPDATE_AUDIO_COMPLETE](state, status){
    state.audioCompressComplete = status;
  },
  // 录音数据
  [UPDATE_BLOB_RECORDING](state, blob){
    state.blobRecord = blob;
  },
  // 正在播放的audio
  [UPDATE_CANCLE_RECORD](state, record){
    state.cancleRecord = record;
  },
  // 发送状态
  [UPDATE_SENDING](state, show){
    state.sending = show;
  },
  // 当前游标
  [UPDATE_CURSEQNO](state, cursor){
    state.curSeqno = cursor;
  },
  // 备课列表
  [UPDATE_PREPARE_LIST](state, list){
    state.prepareList = [...list];
  },
  // 备课列表
  [ADD_PREPARE_LIST](state, data){
    state.prepareList.push(data);
  },
};
export default mutations;
