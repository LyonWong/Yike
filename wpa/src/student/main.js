// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue';
import VueRouter from 'vue-router';
import vueResource from 'vue-resource';
import FastClick from 'fastclick';
import App from './App';
import store from './vuex/store';
import routes from './router';
import { Vueinterceptors, VueFilterMoment, VueFilterSpecKey } from '@student/assets/js/middleware';
import VueLazyload from 'vue-lazyload';
import VueScroller from 'vue-scroller';
import SetWechatTitle from '@lib/js/setWechatTitle';
import { StrFilter, NumFilter } from "@lib/js/filter";
import '@lib/js/rem';
import '@lib/css/font.styl';
import 'normalize.css';
// pop window style
import 'sweetalert/dist/sweetalert.css';
import '@lib/css/sweetalert.styl';
import '@lib/css/sweetalert-mobile.styl';
import '@lib/css/markdown.styl';

/*
 * fastclick
 */
if ('addEventListener' in document) {
  document.addEventListener('DOMContentLoaded', function() {
    FastClick.attach(document.body);
  }, false);
}

/* register compoent */
Vue.use(VueRouter);
Vue.use(vueResource);
/* set vue interceptors */
Vueinterceptors();
/* set vue moment */
VueFilterMoment();
/* lazy load */
Vue.use(VueLazyload, {
  //error: 'dist/error.png',
  loading: `${process.env.ASSETS_HOST?process.env.ASSETS_HOST:'https://assets.sandbox.yike.fm/'}static/student/_static/student/img/lazy-loading.gif`,
  try: 3 // default 1
});
/* scroll load middleware */
Vue.use(VueScroller);
/* set vue spec key */
Vue.use(VueFilterSpecKey);

Vue.use(StrFilter);
Vue.use(NumFilter);

/* bind router */
const router = new VueRouter({
  'linkActiveClass': 'active',
  //mode:'history',
  routes,
});

/* change title */
router.afterEach((transition) => {
  let title = transition.meta.pageTitle;
  SetWechatTitle(title);
});

/* el app */
new Vue({
  router,
  store,
  template: '<App/>',
  components: { App },
}).$mount('#app');
