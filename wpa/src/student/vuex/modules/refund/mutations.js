import {
  CHANGE_REFUND_INFO,
} from './mutation-type'

const mutations = {
  // 改变loading状态
  [CHANGE_REFUND_INFO](state, info) {
    state.refund = info;
  },
};
export default mutations;
