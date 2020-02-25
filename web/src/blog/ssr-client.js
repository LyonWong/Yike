/**
 * Author: LyonWong
 * Date: 2018-07-26
 */
import { createApp } from './_ssr.js'
const { app, router } = createApp()
// 因为可能存在异步组件，所以等待router将所有异步组件加载完毕，服务器端配置也需要此操作
router.onReady(() => {
  app.$mount('#app')
})
