import {
  CHANGE_HOME_INFO,
} from './mutation-type'

const mutations = {
  // 改变loading状态
  [CHANGE_HOME_INFO](state, info) {
    state.homeInfo.msg = info;
  },
};
export default mutations;
