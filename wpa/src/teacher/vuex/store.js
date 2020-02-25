/**
 * Created by zhengguorong on 16/6/22.
 */
import Vue from 'vue'
import Vuex from 'vuex'
import home from './modules/home'
import course from './modules/course'
import handle from './modules/handle'
import earning from './modules/earning'
import prepare from './modules/prepare'
import series from './modules/series'
import data from './modules/data'
import app from './modules/app'

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    home,
    course,
    handle,
    earning,
    prepare,
    series,
    data,
    app,
  },
  strict: process.env.NODE_ENV !== 'production'
})
