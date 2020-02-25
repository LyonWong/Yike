// page/lesson/play.js
var app = getApp()
var api = require('../../library/sapi.js')()
var TIM = require('../../library/TIM.js')
var thisPage = {
  _view_: {
    edge: {}
  },
  data: {
    storageUrl: app.config.storageUrl,
    profile: null,
    records: [],
    states: {},
    images: [],
    audios: [],
    videos: [],
	avdios: [],
    cursor: '',
    dynamic: {
      rolling: null,
      playing: null
    }
  },

  onLoad: function (options) {
    var the = this
    console.log('options', options)
    api.GET('/lesson-profile.api', {
      lesson_sn: options.lesson_sn
    }, function (res) {
      console.log('profile.api', res)
      switch (res.error) {
        case '0':
          the.setData({
            profile: res.data
          })
          wx.setNavigationBarTitle({ title: res.data.title })
          var states = wx.getStorageSync(res.data.sn + '-states') || '{}'
          the.setData({ states: JSON.parse(states) })
          if (res.data.step == 'onlive') {
            the.setData({ 'dynamic.rolling': !states.offset })
            the.polling()
          }
          the.slice(function () {
            the.scrollToOffset(the.data.states.offset)
          })
          break;
        case '0.1':
          // var target = `/page/lesson/play?lesson_sn=${options.lesson_sn}`
          wx.reLaunch({
            url: `/page/boot`,
          })
          break;
      }
    }
    )
  },

  onReady: function () {
    var the = this
  	wx.createSelectorQuery()
	.select('#edge')
	.boundingClientRect(function(res){
	  the._view_.edge = res
	}).exec()
  },

  onShow: function () {

  },

  onHide: function () {
    var the = this
    wx.setStorage({
      key: the.data.profile.sn + '-states',
      data: JSON.stringify(the.data.states)
    })
  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },

  onPullDownRefresh: function () {
  },

  onReachBottom: function () {
    this.slice()
  },

  onShareAppMessage: function () {

  },

  slice: function (callback) {
    var the = this
    api.GET('/live-slice-tim.api', {
      lesson_sn: the.data.profile.sn,
      cursor: the.data.cursor,
      limit: 10
    }, function (res) {
      // console.log('slice.api', res)
      if (res.error === '0') {
        var cursor = the.append(res.data)
        callback && callback(cursor)
      }
    })
  },

  append: function (data) {
    var the = this
    var len = the.data.records.length
    var lenImages = the.data.images.length
	var lenAvdios = the.data.avdios.length
    var idx, _key, _data, _content, _image, _avdio
    for (var i in data) {
      _data = {}

      _content = TIM.parse(data[i].content[0])

      switch (_content.type) {
        case 'image':
          _image = {}
          _image[`images[${lenImages}]`] = _content.data
          the.setData(_image)
          lenImages++
          break;
        case 'audio':
		case 'video':
		  _avdio = {}
		  _content.avpos = lenAvdios
          _avdio[`avdios[${lenAvdios}]`] = Object.assign({cursor: data[i].cursor},_content)
          the.setData(_avdio)
          lenAvdios++
          break;
      }

      idx = len + parseInt(i)
      _key = `records[${idx}]`
      _data[_key] = {
        'user': {
          'sn': data[i].from_account,
          'name': data[i].accountNick,
          'avatar': `${app.config.storageUrl}/user/${data[i].from_account}/avatar`
        },
        'tms': data[i].tms,
        'content': _content,
        'cursor': data[i].cursor
      }
      console.log('_data', _data)
      the.setData(_data)
    }

    var cursor = (i && data[i].cursor) || the.data.cursor
    the.setData({ 'cursor': cursor })
    return cursor  
},

  scrollToCursor: function (cursor) {
    var the = this
    wx.createSelectorQuery()
      .select('#record-' + cursor)
      .boundingClientRect(function (rect) {
        wx.pageScrollTo({
          scrollTop: rect.top
        })
        the.setData({ 'states.offset': rect.top })
      }).exec()
  },
  scrollToOffset: function (offset) {
    var the = this
    wx.pageScrollTo({
      scrollTop: offset
    })
    wx.createSelectorQuery()
      .selectViewport()
      .scrollOffset(function (res) {
        console.log('scrollToOffset', offset, res)
        if (res.scrollTop < offset) {
          setTimeout(function () {
            the.scrollToOffset(offset)
          }, 300)
        }
      }).exec()
  },

  polling: function (opt) {
    var the = this
    setInterval(function () {
      the.slice(the.data.dynamic.rolling ? the.scrollToCursor : null)
      // the.polling()
    }, 5000)
  },
  playing: function (opt) {
  },

  bindImagePreview: function (e) {
    var the = this
    wx.previewImage({
      urls: the.data.images,
      current: e.currentTarget.dataset.url
    })
  },
  bindRecordsTouch: function (e) {
    var the = this
	wx.createSelectorQuery()
	.select('#records')
	.boundingClientRect(function(res) {
	  the.setData({'dynamic.rolling': res.bottom - the._view_.edge.bottom < 250})
      console.log('rolling', the.data.dynamic.rolling)
	}).exec()
  },
  bindAudioPlay: function(e) {
    this.pauseAvPlay()
	var data = {
	  'dynamic.playing.type': 'audio',
	  'dynamic.playing.cursor': e.currentTarget.id,
	  'dynamic.playing.avpos': e.currentTarget.dataset.avpos
	}
    this.setData(data)
  },
  bindAudioEnd: function(e) {
    var avpos = e.currentTarget.dataset.avpos
    var next = this.data.avdios[avpos+1]
	if (!next) {
		return
	}
	switch (next.type) {
		case 'audio':
			wx.createAudioContent('#av-'+next.cursor).play()
			break;
		case 'video':
			wx.createVideoContent('#av-'+next.cursor).play()
			break;
	}
  },
  pauseAvPlay: function(target) {
    target = target || this.data.dynamic.playing || {}
	switch (target.type) {
		case 'audio':
		wx.createAudioContent('#av-'+target.cursor).pause()
		break;
		case 'video':
		wx.createVideoConent('#av-'+target.cursor).pause()
		break;
	}
  }

}
Page(thisPage)
