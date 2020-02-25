import {
  CHANGE_IS_NOTICE,
  FETCH_USER_INFO,
  START_LOADING,
  FINISH_LOADING,
  CHANGE_CALENDAR
} from './mutation-type'

const mutations = {
  // 获取用户信息状态
  [FETCH_USER_INFO](state, data) {
    state.userInfo = { ...data }
  },
  [CHANGE_IS_NOTICE](state, isNotice) {
    state.isNotice = isNotice
  },
  [START_LOADING](state) {
    state.loading = true
  },
  [FINISH_LOADING](state) {
    state.loading = false
  },
  [CHANGE_CALENDAR](state, data) {
    state.calendar = { ...state.calendar, ...data }
  }
};

export default mutations
