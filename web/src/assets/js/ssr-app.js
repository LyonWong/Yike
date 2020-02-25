/**
 * Author: LyonWong
 * Date: 2018-03-24
 */

const config = require('../../config')

const app = {
  env: () => {
    return undefined
  },
  os: () => {
      return undefined
  },
  config: config,
  cookie: {},
  signIn: () => {
    // let callback = encodeURIComponent(window.location.href)
    //   window.location.href = '/sign-in?callbackURI=' + callback
    // }
  },
  setTitle: (title) => {
    console.log(title)
  },
  linkToAssets: (path) => {
    let prefix = config.assetsPreUrl.indexOf('http') === 0 ? '' : ''
    return prefix + config.assetsPreUrl + path
  },
  linkToStudent: (path) => {
    return config.studentPreUrl+path
  },
  linkToTeacher: (path) => {
    return config.teacherPreUrl+path
  }
};

export default app
