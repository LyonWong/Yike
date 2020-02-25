import {
  FETCH_EARNING_LIST,
  FETCH_EARNING_REVENUE,
} from './mutation-type'

import { convertToArray } from '@lib/js/mUtils';

const mutations = {
  // 改变列表
  [FETCH_EARNING_LIST](state, data) {
    state.earningList = data;
  },
  // 收益
  [FETCH_EARNING_REVENUE](state, data) {
    state.earningRevenue = data;
  },
};
export default mutations;
