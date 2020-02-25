// page/web/boot.js
var app = getApp()
var api = require('../../library/api.js')('default', true)

Page({

  /**
   * 页面的初始数据
   */
  data: {

  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    console.log('web/boot', options)
    let websrc = app.config.webBootUrl
    if (options.target) {
      websrc += decodeURIComponent(options.target)
    }
    switch (options.action) {
      case 'sign':
        api.POST('/sess-cipher.api').then( (res) => {          
          let s = websrc.indexOf('?') == -1 ? '?' : '&'
          websrc += s + `cipher=${res.data}`
          console.log('websrc', websrc)
          this.setData({ 'websrc': websrc })
        })
        break;
      default:
        console.log('websrc', websrc)
        this.setData({ 'websrc': websrc })
        break;
    }
    
  },  
  onReady: function () {
    wx.setNavigationBarColor({
      frontColor: '#ffffff',
      backgroundColor: '#333333',
    })
  },
  onShareAppMessage(options) {
    var frag = options.webViewUrl.split('#')
    var encodeURI = encodeURIComponent(`path=` + encodeURIComponent(frag[0]) + `&hash=` + encodeURIComponent(frag[1]))
    var data = {
      title: '易灵微课',
      path: `/page/web/boot?target=${app.config.defaultUrl}/sess-link&encodeURI=${encodeURI}`
    }
    console.log(data)
    return data
  },
  bindMessage(e) {
    console.log('message', e)
  }

})