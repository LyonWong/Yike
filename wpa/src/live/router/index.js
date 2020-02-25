// require.ensure 是 Webpack 的特殊语法，用来设置 组件到底路径
/**
 * 1.定义路由，每个路由应该映射一个组件
 * path : 浏览器的显示的路径
 * name ： 路由的名字
 * component : 路由的组件路径
 */
if(process.env.NODE_ENV == 'production'){
  //
  var hello = r => require.ensure([], () => r(require('../components/Hello.vue')), 'live/live-hello');
}else{
  //
  var hello = require('../components/Hello.vue');
}

export default [{
  path: '/',
  name: 'index',
  component: hello
}];

