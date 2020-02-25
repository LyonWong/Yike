// page/web/view.js
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
    var the = this
    var target = options.target || app.config.studentUrl
    api.POST('/sess-cipher.api')
    .then( (res) => {
      var websrc = `${app.config.defaultUrl}/sign-wxa?cipher=${res.data}&target=${target}`
      console.log('web/view:websrc', websrc)
      the.setData({websrc: websrc})
    })
    
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
    wx.getSystemInfo({
      success: function(res) {console.log(res)},
    })
    wx.setNavigationBarColor({
      frontColor: '#ffffff',
      backgroundColor: '#333333',
    })
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