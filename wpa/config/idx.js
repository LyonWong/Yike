// see http://vuejs-templates.github.io/webpack for documentation.
var path = require('path');
var publicPath = process.env.ASSETS_HOST ? process.env.ASSETS_HOST : '/';

module.exports = {
  build: {
    env: require('./product.env'),
    index: path.resolve(__dirname, '../dist/index.html'),
    assetsRoot: path.resolve(__dirname, '../dist'),
    assetsSubDirectory: 'static',
    assetsPublicPath: publicPath,
    productionSourceMap: false,
    productionGzip: false,
    productionGzipExtensions: ['js', 'css'],
    bundleAnalyzerReport: process.env.npm_config_report
  },
  dev: {
    env: require('./develop.env'),
    port: 8181,
    host: 'yike.local',
    autoOpenBrowser: false,
    assetsSubDirectory: 'static',
    assetsPublicPath: '/',
    proxyTable: {
      '/api':{
        target: 'http://yike.local',
        secure: false,
        changeOrigin: true,

      }
    },
    cssSourceMap: false
  }
}
