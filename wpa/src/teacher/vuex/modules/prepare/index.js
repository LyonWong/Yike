import * as actions from './actions'
import * as getters from './getters'
import mutations from './mutations'

const state = {
  curSeqno: 0,
  sending: false,
  recording: false,
  blobRecord: null,
  prepareList: [],
  cancleRecord: false,
  recorderTimer: '0:01',
  recorderStatus: false,
  audioCompressComplete: false,
  assetsHost: (process.env.ASSETS_HOST ? process.env.ASSETS_HOST : 'https://assets.sandbox.yike.fm/'),
};

export default{
  state,
  actions,
  getters,
  mutations
}
