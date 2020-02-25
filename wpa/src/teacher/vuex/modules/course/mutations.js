import {
  FETCH_COURSE_LIST,
  FETCH_COURSE_DETAIL,
  FETCH_OPEN_INFO,
  FETCH_EVALUATE_LIST,
  FETCH_COURSE_EDIT,
} from './mutation-type'

import { convertToArray } from '@lib/js/mUtils';

const mutations = {
  // 改变列表
  [FETCH_COURSE_LIST](state, data) {
    state.courseList = convertToArray([], data);
  },
  // 改变详情
  [FETCH_COURSE_DETAIL](state, data) {
    state.courseDetail = { ...state.courseDetail, ...data };
  },
  // 获得开课信息
  [FETCH_OPEN_INFO](state, data) {
    state.openInfo = { ...state.openInfo, data };
  },
  // 获得评价列表信息
  [FETCH_EVALUATE_LIST](state, data) {
    // state.evaluateList = convertToArray(state.evaluateList, data);
    state.evaluateList = data;
  },
  // 改变编辑详情
  [FETCH_COURSE_EDIT](state, data) {
    state.courseEdit = { ...state.courseEdit, ...data };
  },
};
export default mutations;
