//index.js
//获取应用实例
var app = getApp()

Page({
  data: {
    studentUrl: '',
  },
  onLoad: function (option) {
  	console.log('welcome index');
  	console.log(option.query);
  	//
    this.setData({
      studentUrl: wx.getStorageSync('studentUrl')
    })
  }
})