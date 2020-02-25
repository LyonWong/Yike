/**
 * Created by lcr on 17/4/1.
 */
import Vue from 'vue';
import Vuex from 'vuex';
import home from './modules/home';
import course from './modules/course';
import user from './modules/user';
import refund from './modules/refund';
import series from './modules/series';
import message from './modules/message';
import group from './modules/group';
import rank from './modules/rank';
import app from './modules/app';

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    home,
    user,
    course,
    refund,
    series,
    message,
    group,
    rank,
    app,
  },
  strict: process.env.NODE_ENV !== 'production'
})
