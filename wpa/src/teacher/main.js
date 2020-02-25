// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue';
import VueRouter from 'vue-router';
import vueResource from 'vue-resource';
import FastClick from 'fastclick';
import App from './App';
import store from './vuex/store';
import routes from './router';
import { Vueinterceptors, VueFilterMoment, VueFilterSpecKey } from '@teacher/assets/js/middleware';
import VeeValidate, { Validator } from 'vee-validate';
import SetWechatTitle from '@lib/js/setWechatTitle';
import VueLazyload from 'vue-lazyload';
import mavonEditor from 'mavon-editor';
import '@lib/css/font.styl';
import 'normalize.css';
import 'sweetalert/dist/sweetalert.css';
import '@lib/css/sweetalert.styl';
import 'mavon-editor/dist/css/index.css';
import '@lib/css/markdown.styl';

/*
 * fastclick
 */
if ('addEventListener' in document) {
  document.addEventListener('DOMContentLoaded', function() {
    FastClick.attach(document.body);
  }, false);
}

/*
 * vue config
 */
const vueConfig = {
  errorBagName: 'errors', // change if property conflicts.
  delay: 0,
  locale: 'zh_CN',
  messages: null,
  strict: true
};

/* register compoent */
Vue.use(VueRouter);
Vue.use(vueResource);
Vue.use(VeeValidate, vueConfig);
/* set mavon editor */
Vue.use(mavonEditor);
/* set vue interceptors */
Vueinterceptors();
/* set vue moment */
Vue.use(VueFilterMoment);
/* set vue spec key */
Vue.use(VueFilterSpecKey);
/* lazy load */
Vue.use(VueLazyload, {
  //error: 'dist/error.png',
  loading: `${process.env.ASSETS_HOST?process.env.ASSETS_HOST:'https://assets.sandbox.yike.fm/'}static/student/_static/student/img/loading.gif`,
  try: 3, // default 1
});

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
