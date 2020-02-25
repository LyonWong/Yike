var Http = {
  methods:   ['GET', 'POST', 'PUT', 'DELETE', 'HEAD'],
  request: (method, url, data = {}, options = {}) => {
    return new Promise((success, fail, complete) => {

      wx.request({
        method: method,
        url: url,
        data: data,
        header: options.header || {},
        success: (res) => {success(res.data)} ,
        fail: fail,
        complete: complete
      })
    })
  }
}

Http.methods.forEach((method) => {
  Http[method] = (url, data = {}, options = {}) => {
    return Http.request(method, url, data, options)
  }
})
 

// var api = {}
module.exports = Http
