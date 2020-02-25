// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import Ssr from './ssr'
import {createRouter} from './router/ssr'
import axios from 'axios'

import ssrapp from '../assets/js/ssr-app'
import api from '../assets/js/ssr-api'

Vue.prototype.api = api
Vue.prototype.app = ssrapp

/* eslint-disable no-new */

export function createApp() {
  const router = createRouter()
  const app = new Vue({
    el: '#app',
    router,
    axios,
    // api,
    render: h => h(Ssr)
  });
  return {app, router}
}
