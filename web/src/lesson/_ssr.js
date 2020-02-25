// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
// import Ssr from './index'
import Ssr from './ssr'
import {createRouter} from './router/ssr'
import axios from 'axios'

// import ssrapp from '../assets/js/ssr-app'
// import api from '../assets/js/ssr-api'
// import wxa from '../assets/js/wxa'
// import wx from 'weixin-js-sdk'
// import tool from '../assets/js/tool'
//   const window = {}
//   console.log('win', window)
// const tool = require('../assets/js/tool')

// console.log(tool)

/* eslint-disable no-new */

export function createApp() {
  const router = createRouter()
  // Vue.prototype.wx = wx
  // Vue.prototype.api = api
  // Vue.prototype.wxa = wxa
  // Vue.prototype.app = ssrapp
  const app = new Vue({
    el: '#app',
    router,
    axios,
    // api,
    render: h => h(Ssr)
  });
  return {app, router}
}
