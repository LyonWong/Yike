import * as actions from './actions'
import * as getters from './getters'
import mutations from './mutations'

const state = {
  seriesList: '欢迎您',
  seriesDetail: null,
  seriesOrder: null,
};

export default{
  state,
  actions,
  getters,
  mutations
}
