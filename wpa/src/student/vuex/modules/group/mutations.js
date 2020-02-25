import {
  UPDATE_GROUP_LOADING,
} from './mutation-type'

const mutations = {
  // 改变loading状态
  [UPDATE_GROUP_LOADING](state, load) {
    state.groupLoading = load;
  },
};
export default mutations;
