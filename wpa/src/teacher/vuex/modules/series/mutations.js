import {
  UPDATE_SERIES_LIST,
} from './mutation-type'

const mutations = {
  // 改变loading状态
  [UPDATE_SERIES_LIST](state, data) {
    state.seriesList = [ ...data ];
  },
};
export default mutations;
