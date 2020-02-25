// page/pay/index.js
var app = getApp()
var api = require('../../library/api.js')('default', true)
var thisPage = {

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
    this.setData({ order_sn: options.order })
    
    api.GET('/api/order-inquiry.api', {
      sn: options.order
    }).then( (res) => {
      console.log(res)
      if (res.error === '0') {
        the.setData(res.data)
      }
    })
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

  },
  purchase: function () {
    api.POST('/api/order-prepay-wxa', { sn: this.data.order_sn })
    .then( (res) => {      
      res.data.success = (res) => {        
        wx.showToast({
          title: '支付成功',
        })        
      }
      res.data.fail = (res) => {
        console.log('fail', res)
        if (res.errMsg === 'requestPayment:fail cancel') {
          wx.showToast({
            title: '取消支付',
          })
        } else {
          wx.showModal({
            title: '支付失败',
            content: res.errMsg,
            showCancel: false
          })
        }        
      }
      wx.requestPayment(res.data)      
    }, (res) => {
      wx.showModal({
        title: '下单失败',
        content: res.message,
        showCancel: false
      })
    })
  }
}
Page(thisPage)
