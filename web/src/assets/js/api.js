/**
 * Author: LyonWong
 * Date: 2018-05-16
 */

import app from './app'
import axios from 'axios'

let domLoading = document.querySelector('#loading')
let styLoading = domLoading ? domLoading.style : {}

let api = {
  loading: 0,
  request: function(method, url, data, options) {
    options = options || {}
    options.method = method
    options.url = url
    options.data = data
    api.loading ++;
    if (options.loading !== false) {
      styLoading.display = 'flex'
    }
    if (options.loading) {
      domLoading.innerHTML += '&nbsp'
      domLoading.append(options.loading)
    }
    return new Promise((resolve, reject) => {
      axios.request(options).then( (response) => {
        if (--api.loading === 0) {
          styLoading.display = 'none'
          domLoading.innerHTML = '<i></i>'
        }
        if (response.data.error === '0') {
          resolve(response.data)
        } else {
          reject && reject(response.data)
        }
      }).catch(console.error)
    })
  },
  get: function(path, params, options={}) {
    options.params = params
    return api.request('get', path, null, options)
  },
  post: function(path, data, options={}) {
    return api.request('post', path, data, options)
  },
  onErrorBase: function(res) {
    alert(res.message)
  },
  onErrorSign: function(res) {
    if (res.error === '0.1') {
      app.signIn()
    } else {
      api.onErrorBase(res)
    }
  }
}

export default api
