/**
 * Author: LyonWong
 * Date: 2018-04-13
 */
import wx  from 'weixin-js-sdk'

export default {
    login: () => {
      let target = encodeURIComponent(window.location.pathname + window.location.search)
      wx.miniProgram.reLaunch({url: `/page/web/boot?target=${target}&action=sign`})
    }
}
