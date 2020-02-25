import {
  FETCH_HANDLE_LIST,
} from './mutation-type'

import { convertToArray } from '@lib/js/mUtils';

const mutations = {
  // 改变列表
  [FETCH_HANDLE_LIST](state, data) {
    state.handleList = data;
  },
};
export default mutations;
