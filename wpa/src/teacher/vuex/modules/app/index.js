import * as actions from './actions'
import * as getters from './getters'
import mutations from './mutations'

const state = {
  userInfo: {},
  isNotice: true,
  loading: false,
  calendar: {
    value: '',
    show: false
  },
}

export default{
  state,
  actions,
  getters,
  mutations
}
