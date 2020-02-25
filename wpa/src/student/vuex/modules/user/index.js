import * as actions from './actions'
import * as getters from './getters'
import mutations from './mutations'

const state = {
  enrollList: [],
  moneyBalance: null,
  moneyBill: null,
  moneyDebit: null,
};

export default{
  state,
  actions,
  getters,
  mutations
}
