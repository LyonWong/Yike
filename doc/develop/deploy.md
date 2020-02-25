## 部署

### 服务端

代码包 `yike/srv`，环境要求及框架说明参见`yike/srv/README.md`

#### 配置文件

`_` `Admin` `Student` `Teacher` 各模块的配置文件独立，`config/basic`为默认配置模板，`config/local`为本地配置文件，两者结构完全相同，详细说明参见[doc/deploy/config](./config.md)

#### 初始化
- 建立资源目录软链接
  ```
    cmd/mklink _
    cmd/mklink Admin
    cmd/mklink Student
    cmd/mklink Teacher
  ```
- 数据库
  ```
    source yike/srv/_/file/database.sql;
  ```
- 渠道来源
  ```
    cmd/run _ /cli/init-origin
  ```
- 管理后台菜单
  ```
    cmd/run Admin /cli/scope-load-admin
  ```
- 管理员账号
  ```
    # 创建管理员账号，成功则显示创建的uid
    cmd/run Admin /cli/user-create --email=admin@domain --password=YKpass
    # 设置管理员权限 `field=admin`执行权限域为管理后台，`point=*`表示所有权限范围，`priv=-1`表示所有权限级别
    cmd/run Admin /cli/user-scope --uid=$uid --field=admin --point=* --priv=-1
  ```  
- 守护进程
  ```
  cmd/daemon -c start
  ```
- 定时任务
  ```
  #crontab
  * * * * * /usr/local/yike/release/cmd/cron _ 1>/var/log/yike/cron.log 2>&1
  ```

#### WEB服务器

分为主域`yike.local`, 管理后台`admin.yike.local`， 讲师后台`teacher.yike.local`，学员端`student.yike.local`四部分，`yike.local/_/`目录下为内部调用服务，不对外开放，具体配置参见[doc/deploy/nginx][./config.md]


> - 访问 http://yike.local 即可访问微课首页
> - 访问 http://admin.yike.local , 输入管理员账号密码可登录管理后台

### 用户端

代码包 `yike/web`，需要node>=6.0.0

#### 安装
```
npm install
```

#### 配置
`yike/web/config/local.js`
```
'use strict'
module.exports = {
  env: 'dev', // 运行环境dev|sandbox|live
  host: 'yike.local',
  port: 8080,
  mta:  {}, // 百度统计参数
  subDir: 'source', // 资源子目录
  api: 'http://yike.local', // api转发
  assets: 'http://assets.yike.local/', // 资源域名
  studentPreUrl: 'http://student.yike.local', // 旧学员端域名
  teacherPreUrl: 'http://teacher.yike.local' // 讲师端域名
}
```
#### 运行
多入口页设计，页面入口模板位于`yike/web/page`

- 开发模式
  ```
    # 运行所有页面
    npm run dev 
    # 运行指定页面
    npm run dev -- --define page=lesson
  ```
  访问入口为`yike.local:8080/{page}/`
- 生产部署
  ```
    npm run build
    # 链接入口页面
    ln -s yike/web/dist/entry yike/src/_/view/template
    # 链接静态资源,将dist/source下的资源文件部署到资源域名下
    ln -s yike/web/dist/source yike/src/_/public
  ```
### 旧用户端

代码包`yike/wpa`，直播间的讨论区实时聊天服务绑定了腾讯云通讯

#### 安装
```
npm install 
```

#### 配置
- `yike/wpa/config/dev.env.js`
  ```
  // 开发环境配置
  module.exports = {
    NODE_ENV: '"development"',
    SDK_APP_ID: ???, // 腾讯云通讯apppid
    PORT: 8080, // 本地开发调试端口
    HOST: "http://dev.yike.local", // 本地开发调试域名
    API_TARGET: { // API转发地址
      "live": "http://yike.local",
      "teacher": "http://teacher.yike.local",
      "student": "http://student.yike.local",
    }  
  }
  ```
- `yike/wpa/config/build.env.js`
  ```
  // 编译配置
   module.exports = {
    NODE_ENV: '"production"',
    SDK_APP_ID: ???, // 腾讯云通讯apppid
    LIVE_HOST: '"http://yike.domain"', // 直播模块编译部署域名
    TEACHER_HOST: '"http://teacher.yike.local"', // 讲师模块编译部署域名
    STUDENT_HOST: '"http://student.yike.local"', // 学员模块编译部署域名
    ASSETS_HOST: '"http://assets.yike.local"', // 静态资源获取地址
  }
  ```  

#### 运行
- 开发模式
  - 运行`npm run dev-$modual` 即可通过浏览器访问`HOST:PORT`实时预览
    - `npm run dev-live`运行直播间
    - `npm run dev-teacher`运行讲师端
    - `npm run dev-student`运行学员端
- 编译部署  
  - 运行`npm run build`编译
  - 将`dist/static`目录发布到资源域名，使之能通过`http://assets.yike.local/static/`访问
  - `dist/*.html`是入口文件模板，有变动需手动修改对应的php文件
    - 直播间 `live.html` => `yike/srv/_/view/template/live.php` 
    - 学员端`student.html` => `yike/srv/Student/view/template/idx.php`
    - 讲师端`t
    - eacher.html` =>  `yike/srv/Teacher/view/template/idx.php`

### 小程序

代码包`yike/wxa`

#### 配置
参照`yike/wxa/config-default.js`配置`yike/wxa/config.js`
```
// 本地开发
var local = {
  api: { // API接口地址
    default: 'http://yike.local'
  },

  defaultUrl: 'http://yike.local', //主地址
  webviewUrl: 'http://yike.local', //嵌入webview的地址
  studentUrl: 'http://student.yike.local/', //旧学员端地址
  storageUrl: 'http://storage.yike.local' //文件存储地址
}

// 线上生产
var productin = {...}

// 根据需要在不同的环境配置之间切换
module.exports = local
```