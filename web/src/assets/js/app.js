/**
 * Author: LyonWong
 * Date: 2018-03-24
 */

import wx from 'weixin-js-sdk'
import cookie from 'js-cookie'
import qs from 'qs'

const config = require('../../config')

const app = {
  env: (len) => {
    if (window.__wxjs_environment === 'miniprogram') {
      return 'wxa'.substr(0, len)
    }
    if (navigator.userAgent.match(/WindowsWechat/)) {
      return 'wxw'.substr(0, len)
    }
    if (navigator.userAgent.match(/Mobile.*MicroMessenger/)) {
      return 'wxm'.substr(0, len)
    }
    return undefined
  },
  os: () => {
    let res = navigator.userAgent.match(/(Windows|Macintosh|Android|iOS|iPhone|iPad)/)
    if (res) {
      if (res[1] === 'iPhone' || res[1] === 'iPad') {
        return 'iOS'
      } else {
        return res[1]
      }
    } else {
      return undefined
    }
  },
  config: config,
  cookie: cookie,
  signIn: () => {
    let callback = window.location.href
    if (callback.indexOf('origin=') === -1 && localStorage.getItem('source')) {
      callback += (callback.indexOf('?') === -1 ? '?' : '&') + 'origin=' + localStorage.getItem('source')
    }
    callback = encodeURIComponent(callback)
    if (app.env() === 'wxa') {
      wx.miniProgram.navigateTo({
        url: `/page/login/index?callback=${callback}`
      })
    } else {
      window.location.href = '/sign-in?callbackURI=' + callback
    }
  },
  setTitle: (title) => {
    document.title = `易灵微课-${title}`
  },
  linkToAssets: (path) => {
    let prefix = config.assetsPreUrl.indexOf('http') === 0 ? '' : location.origin
    return prefix + config.assetsPreUrl + path
  },
  linkToStudent: (path) => {
    return config.studentPreUrl+path
  },
  linkToTeacher: (path) => {
    return config.teacherPreUrl+path
  },
  disableBodyScroll: () => {
    document.body.style.overflow = 'hidden'
    document.documentElement.style.overflow='hidden'
  },
  enableBodyScroll: () => {
    document.body.style.overflow = 'visible'
    document.documentElement.style.overflow='visible'
  },
  previewImageOne: (src) => {
    if (app.env()) {
      wx.previewImage({
        current: src,
        urls: [src]
      })
    } else {
      window.open(src)
    }
  },
  previewImages: (urls, key) => {
    if (app.env()) {
      wx.previewImage({
        current: key,
        urls: urls
      })
    } else {
      window.open(key)
    }
  },
  init: () => {
    (function () {
      let mta = document.createElement("script");
      mta.src = "https://pingjs.qq.com/h5/stats.js?v2.0.4";
      mta.setAttribute("name", "MTAH5");
      mta.setAttribute("sid", app.config.mta.AppId);
      let s = document.getElementsByTagName("script")[0];
      s.parentNode.insertBefore(mta, s);
    })();

    (function() {
      let hm = document.createElement("script");
      hm.src = "https://hm.baidu.com/hm.js?0a0aac37343b546ea47c4b07f07a1426";
      let s = document.getElementsByTagName("script")[0];
      s.parentNode.insertBefore(hm, s);
    })();

    (function() {
      /*
      let match = document.referrer.match(/https?:\/\/([\w.]*\.)?(\w+\.\w+)\//)
      if (match) {
        let origin = `site-${match[2]}`
        if (match[1]) {
          origin += '-'+match[1].replace(/\.$/, '')
        }
        app.cookie.set('origin', origin)
      }
      */
      let query = qs.parse(location.search.slice(1))
      if (query.source) {
        localStorage.setItem('source', query.source)
      }
    })();
  }
};

export default app
