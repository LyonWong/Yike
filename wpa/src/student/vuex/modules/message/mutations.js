import {
  CHANGE_MESSAGE_INFO,
  CHANGE_MESSAGE_TYPE,
} from './mutation-type'

const mutations = {
  // 改变loading状态
  [CHANGE_MESSAGE_INFO](state, info) {
    state.messageInfo = info;
  },
  // 改变type-map
  [CHANGE_MESSAGE_TYPE](state, data) {
    state.messageTypeMap = { ...state.messageTypeMap, ...data };
  },
};
export default mutations;
