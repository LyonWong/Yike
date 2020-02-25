//index.js
//获取应用实例
const config    = require('../../../config')
const openIdUrl = config.openIdUrl
const loginUrl  = config.loginUrl
const hostUrl  = config.host
var app = getApp()

Page({
    data: {
    },
    onShow: function () {
        var that = this;
        // 是否不需要验证
        if(app.globalData.hasLogin){
          wx.setStorageSync('studentUrl', `${hostUrl}`);
          //
          return wx.navigateTo({
              url: '/page/component/index'
          });
        };
        // login
        wx.login({
            success: function (res) {
                var code = res.code; // 微信登录接口返回的 code 参数，下面注册接口需要用到
                wx.getUserInfo({
                    success: function (res) {
                        var iv = encodeURIComponent(res.iv);
                        var encryptedData = encodeURIComponent(res.encryptedData);
                        var studentUrl = loginUrl + '?iv=' + iv + '&encryptedData=' + encryptedData + '&code=' + code;
                        //
                        wx.setStorageSync('studentUrl', studentUrl);
                        // 已登录
                        app.globalData.hasLogin = true;
                        // 跳转
                        wx.navigateTo({
                          url: '/page/component/index'
                        });
                    }
                })
            }
        })
    }
})