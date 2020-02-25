import * as actions from './actions'
import * as getters from './getters'
import mutations from './mutations'

const state = {
  messageInfo: null,
  messageTypeMap: {},
};

export default{
  state,
  actions,
  getters,
  mutations
}
