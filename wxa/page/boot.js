// page/boot.js
var app = getApp()
var api = require('../library/api.js')()


Page({

  /**
   * 页面的初始数据
   */
  data: {

  },

  onLoad: function(options) {
    console.log('boot', options)
  },

  /**
   * 生命周期函数--监听页面加载
   * mode: native|webview
   */
  _onLoad: function (options) {
    var the = this
    var mode = options.mode || 'native'
    var target = options.target
    console.log('boot', options)
    the.dispath(mode, target)
    return
    api.GET('/sess-check.api', null, function (res) {
      console.info('sess-check', res)
      if (res.error === '0') {
        the.dispatch(mode, target)
      } else {
        wx.login({
          success: function (res) {
            wx.getUserInfo({
              success: function (_res) {
                api.POST('/sign-wxa', {
                  code: res.code,
                  userInfo: _res.userInfo
                }, function (_response) {
                  console.info('sign-wxa', _response)
                  if (_response.error === '0') {
                    app.session = _response.data.token
                    api.header['X-SESS'] = app.session
                    wx.setStorage({
                      key: 'session',
                      data: app.session,
                    })
                    the.dispatch(mode, target)
                  } else {
                    console.error(_response.message)
                    //todo 失败处理
                  }
                })
              },
              fail: function () {
                //todo 
              }
            })
          },
          fail: function () {
            //todo
          }
        })
      }
    })
  },
  dispatch: function (mode, target) {
    var the = this
    switch (mode) {
      case 'native':
        wx.reLaunch({ url: target })
        break;
      case 'webview':
        target = target || app.config.studentUrl
        api.POST('/sess-cipher', null, function (res) {
          console.log('sess-cipher', res)
          if (res.error === '0') {
            console.log('target', target)
            wx.reLaunch({ url: '/page/web/view?target=' + target + '&cipher=' + res.data })
          } else {
            console.error(res.message)
            //todo
          }
        })
        break;
    }
  },


  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})
