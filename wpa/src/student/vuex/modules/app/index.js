import * as actions from './actions'
import * as getters from './getters'
import mutations from './mutations'

const state = {
  userInfo: {},
  loading: false,
  footer: true,
  assetsHost: (process.env.ASSETS_HOST?process.env.ASSETS_HOST:'https://assets.sandbox.yike.fm/'),
}

export default{
  state,
  actions,
  getters,
  mutations
}
