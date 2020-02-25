import {
  FETCH_SERIES_LIST,
  FETCH_SERIES_ORDER,
  FETCH_SERIES_DETAIL,
} from './mutation-type'

import { convertToArray } from '@lib/js/mUtils';

const mutations = {
  // 改变列表
  [FETCH_SERIES_LIST](state, data) {
    state.seriesList = convertToArray(state.seriesList, data);
  },
  // 改变详情
  [FETCH_SERIES_DETAIL](state, data) {
    state.seriesDetail = { ...data };
  },
  // 改变详情
  [FETCH_SERIES_ORDER](state, data) {
    state.seriesOrder = { ...data };
  },
};
export default mutations;
