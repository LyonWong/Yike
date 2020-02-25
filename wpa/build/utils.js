var path = require('path');
var config = require('../config');
var glob = require('glob');
var ExtractTextPlugin = require('extract-text-webpack-plugin');
var assetsHost = process.env.ASSETS_HOST ? process.env.ASSETS_HOST : 'https://assets.sandbox.yike.fm/';

exports.assetsPath = function (_path) {
  var assetsSubDirectory = process.env.NODE_ENV === 'production'
    ? config.build.assetsSubDirectory
    : config.dev.assetsSubDirectory
  return path.posix.join(assetsSubDirectory, _path)
};

exports.cssLoaders = function (options) {
  options = options || {}

  var cssLoader = {
    loader: 'css-loader',
    options: {
      minimize: process.env.NODE_ENV === 'production',
      sourceMap: options.sourceMap
    }
  }

  // generate loader string to be used with extract text plugin
  function generateLoaders (loader, loaderOptions) {
    var loaders = [cssLoader]
    if (loader) {
      loaders.push({
        loader: loader + '-loader',
        options: Object.assign({}, loaderOptions, {
          sourceMap: options.sourceMap
        })
      })
    }

    // Extract CSS when that option is specified
    // (which is the case during production build)
    if (options.extract) {
      return ExtractTextPlugin.extract({
        use: loaders,
        fallback: 'vue-style-loader'
      })
    } else {
      return ['vue-style-loader'].concat(loaders)
    }
  }

  // https://vue-loader.vuejs.org/en/configurations/extract-css.html
  return {
    css: generateLoaders(),
    postcss: generateLoaders(),
    less: generateLoaders('less'),
    sass: generateLoaders('sass', { indentedSyntax: true }),
    scss: generateLoaders('sass'),
    stylus: generateLoaders('stylus', { define: {userCenterImg:`${assetsHost}static/student/_static/student/img/center.png`, labelImg:`${assetsHost}static/live/_static/live/img/label.png`, labelBgImg:`${assetsHost}static/live/_static/live/img/label-bg.png`} }),
    styl: generateLoaders('stylus', { define: {userCenterImg:`${assetsHost}static/student/_static/student/img/center.png`, labelImg:`${assetsHost}static/live/_static/live/img/label.png`, labelBgImg:`${assetsHost}static/live/_static/live/img/label-bg.png`} })
  }
};

// Generate loaders for standalone style files (outside of .vue)
exports.styleLoaders = function (options) {
  var output = []
  var loaders = exports.cssLoaders(options)
  for (var extension in loaders) {
    var loader = loaders[extension]
    output.push({
      test: new RegExp('\\.' + extension + '$'),
      use: loader
    })
  }
  return output
};

// Generate entry
exports.getEntries = function (globPath) {
  var entries = {};
  /**
   * 读取src目录,并进行路径裁剪
   */
  glob.sync(globPath).forEach(function (entry) {
    // ***************begin***************
    // slice 从已有的数组中返回选定的元素, -3 倒序选择，即选择最后三个
    var tmp = entry.split('/').splice(-3);
    var pathname = tmp.splice(1, 1); // splice(0, 1)取tmp数组中第一个元素
    // ***************end***************
    entries[pathname] = entry;
   // console.log(tmp)
  });
  // console.log(entries);
  return entries;
};
