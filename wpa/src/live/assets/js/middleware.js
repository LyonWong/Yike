import Vue from 'vue';
import { getCookie } from '@lib/js/mUtils';
import NoSleep from 'nosleep.js/dist/NoSleep.min.js';

export const Vueinterceptors = function () {
  Vue.http.interceptors.push(function(request, next) {
    // add
    Vue.http.headers.common['X-SESS'] = getCookie('sess');

    next(function (response) {
      //console.log(response);
    });
  });
};
// 启动抗休眠
export const VueNoSleep = function () {
  let noSleep = new NoSleep();
  noSleep.enable();
};

