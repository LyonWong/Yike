/**
 * 配置文件模板，请根据自身环境，配置 config.js
 */



var local = {

  api: {
    default: 'http://we-class.local'
  },

  defaultUrl: `http://we-class.local`,
  studentUrl: `http://student.we-class.local/`,  
  storageUrl: `http://storage.sandbox.yike.fm`
};

var sandbox = {
  api: {
    default: 'https://sandbox.yike.fm'
  },

  defaultUrl: 'https://sandbox.yike.fm',
  webviewUrl: 'https://sandbox.yike.fm',
  studentUrl: 'https://student.sandbox.yike.fm/',
  storageUrl: 'https://storage.sandbox.yike.fm'
}

var production = {
  api: {
    default: 'https://yike.fm'
  },

  defaultUrl: 'https://yike.fm',
  webviewUrl: 'https://yike.fm',
  studentUrl: 'https://student.yike.fm/',
  storageUrl: 'https://storage.yike.fm'
}

module.exports = sandbox
