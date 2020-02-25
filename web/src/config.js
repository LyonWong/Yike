const local = require('../config/local')
const index = require('../config/index')

let conf = {}
switch (process.env.NODE_ENV) {
  case 'development':
    conf = index.dev
    break
  case 'production':
    conf = index.build
    break
}

const config = {
  env: local.env,
  api: local.api,
  mta: local.mta,
  mpProfile: 'https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=MzUyMDAzNDYxNw==#wechat_redirect',
  assetsPreUrl: conf.assetsPublicPath + local.subDir,
  studentPreUrl: local.studentPreUrl,
  teacherPreUrl: local.teacherPreUrl
}

module.exports = config
