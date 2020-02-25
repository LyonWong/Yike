import {
  FETCH_RANK_LIST,
  FETCH_RANK_LOCATE,
} from './mutation-type'

const mutations = {
  // 获取排行榜
  [FETCH_RANK_LIST](state, list) {
    state.rankList = list;
  },
  // 获取个人排名
  [FETCH_RANK_LOCATE](state, locate) {
    state.rankLocate = { ...locate };
  },
};
export default mutations;
