/**
 * 配置编译环境和线上环境之间的切换
 *
 * baseUrl: 域名地址
 * routerMode: 路由模式
 * imgBaseUrl: 图片所在域名地址
 *
 */
let baseUrl;
let routerMode;
const imgBaseUrl = 'https://xx.cn';

if (process.env.NODE_ENV == 'development') {
	baseUrl = '';
	routerMode = 'hash'
}else{
	baseUrl = 'https://xx.cn';
	routerMode = 'hash'
}

export {
	baseUrl,
	routerMode,
	imgBaseUrl
}
