// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue';
import VueRouter from 'vue-router';
import vueResource from 'vue-resource';
import FastClick from 'fastclick';
import App from './App';
import store from './vuex/store';
import routes from './router';
import { VueNoSleep } from '@live/assets/js/middleware';
import '@lib/js/rem';
import '@lib/css/font.styl';
import 'normalize.css';
import 'sweetalert/dist/sweetalert.css';
import '@lib/css/sweetalert.styl';
import '@lib/css/sweetalert-mobile.styl';


// import videojs from 'video.js';
// window.videojs = videojs;
// import VueVideoPlayer from 'vue-video-player';
// import 'vue-video-player/src/custom-theme.css';
// require('videojs-contrib-hls/dist/videojs-contrib-hls');
// require('video.js/dist/video-js.css');
// require('vue-video-player/src/custom-theme.css');
// Vue.use(VueVideoPlayer);

/*
 * fastclick
 */
if ('addEventListener' in document) {
  document.addEventListener('DOMContentLoaded', function() {
    FastClick.attach(document.body);
  }, false);
}

/* register compoent */
// Vue.use(infiniteScroll);
Vue.use(VueRouter);
Vue.use(vueResource);
/* set vue interceptors */
// Vueinterceptors();
/* set no sleep */
VueNoSleep();

/* bind router */
const router = new VueRouter({
  'linkActiveClass': 'active',
  //mode:'history',
  routes,
});

/* el app */
new Vue({
  el: '#app',
  store,
  router,
  template: '<App/>',
  components: { App },
});
