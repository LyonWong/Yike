import {
  FETCH_DATA_COURSE,
  FETCH_DATA_ORIGIN,
} from './mutation-type'

import { convertToArray } from '@lib/js/mUtils';

const mutations = {
  // 改变列表
  [FETCH_DATA_COURSE](state, data) {
    state.dataCourse = convertToArray([], data);
  },
  // 改变列表
  [FETCH_DATA_ORIGIN](state, data) {
    state.dataOrigin = data;
  },
};
export default mutations;
