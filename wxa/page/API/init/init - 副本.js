//index.js
//获取应用实例
const config    = require('../../../config')
const openIdUrl = config.openIdUrl
const loginUrl  = config.loginUrl
var app = getApp()

Page({
  data: {
  },
  onLoad: function () {
  	var that = this;
    wx.login({
      success: function (res) {
        var code = res.code; // 微信登录接口返回的 code 参数，下面注册接口需要用到
        wx.getUserInfo({
          success: function (res) {
            var iv = res.iv;
            var encryptedData = encodeURIComponent(res.encryptedData);
            var studentUrl = loginUrl + '?iv=' + iv + '&encryptedData=' + encryptedData + '&code=' + code;
            //
            // app.globalData.hasLogin = true;
            wx.setStorageSync('studentUrl', studentUrl);
            // wx.navigateTo({
            //   url: '/page/component/index'
            // })
            // 下面开始调用注册接口
            wx.request({
              method: 'post',
              url: loginUrl,
              header: {
                'content-type': 'application/x-www-form-urlencoded'
              },
              data: {code:code,encryptedData:encryptedData,iv:iv}, // 设置请求的 参数
              success: (res) =>{
                if(res.data && res.data.error == 0) {
                  wx.setStorageSync('studentUrl', res.data.data);
                  wx.navigateTo({
                    url: '/page/component/index'
                  })
                }
                wx.hideLoading();
              },
              error: (err) => {
                console.log(err);
              }
            })
          }
        })
      }
    })
  }
})