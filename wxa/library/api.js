const config = require('../config')
const Http = require('./http')

class API {
  constructor(target = 'default', signed = false) {
    this.target = target
    this.signed = signed
  }

  request(method, path, data = {}, options = {}) {
    let the = this
    let url = config.api[this.target] + path
    options.header = options.header || {}
    options.header['X-SESS'] = wx.getStorageSync('session')
    return new Promise((success, fail, complete) => {
      let _ = {
        method: method,
        url: url,
        data: data,
        options: options,
        success: success,
        fail: fail,
        complete: complete
      }
      let _success = (res) => {
        console.log('api-res', res, success)
        if (res.error === '0') {          
          success(res)
        } else if (res.error === '0.1' && this.signed === true) {
          the.login().then(() => {
            the.request(method, path, data, options).then(success, fail, complete)
          })
        } else {
          fail(res, _)
        }
      }

      Http.request(method, url, data, options)
        .then(_success, fail, complete)
    })
  }

  GET(path, data={}, options={}) {
    return this.request('GET', path, data, options)
  }

  POST(path, data={}, options={}) {
    return this.request('POST', path, data, options)
  }

  login() {
    return new Promise((success, fail) => {
      wx.login({
        success: (loginRes) => {
          wx.getUserInfo({
            success: (infoRes) => {
              // 成功获取到用户信息
              Http.POST(config.api.default + '/sign-wxa.api', {
                code: loginRes.code,
                userInfo: infoRes.userInfo
              }).then((signRes) => {
                if (signRes.error === '0') {                  
                  wx.setStorageSync('session', signRes.data.token)
                  success && success()
                } else {
                  fail && fail(signRes)
                }
              })
            },
            fail: () => {
              // 获取用户信息失败，以匿名用户身份登录
              Http.POST(config.api.default + '/sign-wxa.api', {
                code: loginRes.code
              }).then((signRes) => {
                if (signRes.error === '0') {
                  wx.setStorageSync('session', signRes.data.token)
                  success && success()
                } else {
                  fail && fail(signRes)
                }
              })
            }
          })
        }        
      })
    })
  }
}

function init(target = 'default', signed = false) {
  let instances = {}
  let key = `${target}|${signed})`
  if (!instances[key]) {
    instances[key] = new API(target, signed)
  }
  return instances[key]
}



module.exports = init