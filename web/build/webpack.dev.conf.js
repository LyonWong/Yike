'use strict'
const utils = require('./utils')
const webpack = require('webpack')
const config = require('../config')
const merge = require('webpack-merge')
const path = require('path')
const baseWebpackConfig = require('./webpack.base.conf')
const CopyWebpackPlugin = require('copy-webpack-plugin')
const HtmlWebpackPlugin = require('html-webpack-plugin')
const HtmlIncludeAssetsPlugin = require('html-webpack-include-assets-plugin')
const FriendlyErrorsPlugin = require('friendly-errors-webpack-plugin')
const portfinder = require('portfinder')
const express = require('express');
let app = express()
app.use('/mock', express.static('./mock'));

const HOST = process.env.HOST
const PORT = process.env.PORT && Number(process.env.PORT)
const APP_PAGE = utils.parseCommandArgv('page') || '*' // launch page(s)


console.log('App Page: : ', APP_PAGE)

let entry = {}
let historyRewrites = []
let htmlWebpackPlugins = [
  new HtmlIncludeAssetsPlugin({
    assets: [
      'static/css/global.css',
      'static/fonts/iconfont.css',
      'static/katex.css'
    ],
    append: false,
    hash: true
  })
]
utils.globNames(`./page/${APP_PAGE}.html`).forEach((page)=>{
  //page entry
  entry[page] = `./src/${page}/_.js`
  //url rewrites
  historyRewrites.push({
    from: new RegExp(`^/${page}/`),
    to: path.posix.join(config.dev.assetsPublicPath, `${page}.html`)
  })
  //html injection
  htmlWebpackPlugins.push(new HtmlWebpackPlugin({
      template: `./page/${page}.html`,
      filename: `${page}.html`,
      chunks: [page],
      inject: true
    })
  )
})

const devWebpackConfig = merge(baseWebpackConfig, {
  entry: entry,
  module: {
    rules: utils.styleLoaders({ sourceMap: config.dev.cssSourceMap, usePostCSS: true })
  },
  // cheap-module-eval-source-map is faster for development
  devtool: config.dev.devtool,

  // these devServer options should be customized in /config/index.js
  devServer: {
    clientLogLevel: 'warning',
    historyApiFallback: {
      rewrites: historyRewrites
    },
    hot: true,
    contentBase: false, // since we use CopyWebpackPlugin.
    compress: true,
    host: HOST || config.dev.host,
    port: PORT || config.dev.port,
    open: config.dev.autoOpenBrowser,
    overlay: config.dev.errorOverlay
      ? { warnings: false, errors: true }
      : false,
    publicPath: config.dev.assetsPublicPath,
    proxy: config.dev.proxyTable,
    quiet: true, // necessary for FriendlyErrorsPlugin
    watchOptions: {
      poll: config.dev.poll,
    }
  },
  plugins: [
    new webpack.DefinePlugin({
      'process.env': require('../config/dev.env')
    }),
    new webpack.HotModuleReplacementPlugin(),
    new webpack.NamedModulesPlugin(), // HMR shows correct file names in console on update.
    new webpack.NoEmitOnErrorsPlugin(),
    // https://github.com/ampedandwired/html-webpack-plugin
    new HtmlWebpackPlugin({
      template: 'index.html',
      inject: false
    }),
    ...htmlWebpackPlugins,
    // copy custom static assets
    new CopyWebpackPlugin([
      {
        from: path.resolve(__dirname, '../static'),
        to: config.dev.assetsSubDirectory,
        ignore: ['.*']
      },
      {
        from: path.resolve(__dirname, '../mock'),
        to: 'api',
        ignore: ['.*']
      }
    ])
  ]
})

module.exports = new Promise((resolve, reject) => {
  portfinder.basePort = process.env.PORT || config.dev.port
  portfinder.getPort((err, port) => {
    if (err) {
      reject(err)
    } else {
      // publish the new Port, necessary for e2e tests
      process.env.PORT = port
      // add port to devServer config
      devWebpackConfig.devServer.port = port

      // Add FriendlyErrorsPlugin
      devWebpackConfig.plugins.push(new FriendlyErrorsPlugin({
        compilationSuccessInfo: {
          messages: [`Your application is running here: http://${devWebpackConfig.devServer.host}:${port}`],
        },
        onErrors: config.dev.notifyOnErrors
        ? utils.createNotifierCallback()
        : undefined
      }))

      resolve(devWebpackConfig)
    }
  })
})
