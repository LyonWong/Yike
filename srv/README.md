
## 版本要求
- PHP >= 7.0
- Mysql >= 5.7
- Redis >= 2.8

## 框架结构
- 由基本模块和项目模块组成
- 基本模块提供核心路由、基本类库支持
- 项目模块可以有多个，每个项目有独立入口

### 基本模块
- `Core` 框架核心
- `cmd` 命令行控制
- `library` 公共类库
- `resource` 公共资源
- `document` 文档

### 项目模块
- `_` 框架管理
- `Admin` 管理端
- `Student` 学员端
- `Teacher` 讲师端

## 项目结构

- `basic` 公共基础
- `config` 配置文件，分为basic(全局)和local(本地)两部分
- `control` 控制器
- `service` 业务逻辑
- `data` 数据操作
- `view` 表现层，分为`assets`（资源）、`template`（模板）`widget`（部件）三部分
- `file` 项目文件
- `public` 对外入口

## 安装配置

### WebServer 

#### Nginx

```nginxconfig
server {
    listen 80;
    server_name <domain>;
    index index.php index.html
    location /assets/ {} //静态资源
    location / {
        try_files $uri $uri/ /index.php?$query_string
    }
    location ~ index.php {
        fastcgi_pass   127.0.0.1:9000;
        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include        fastcgi_params;
    } 
}
```

#### 目录连接

为项目静态文件建立软连接

```
public/assets -> view/assets
view/assets/resource -> ../resource
```

### 项目配置文件
- boot.ini
- mysql.ini
- redis.ini


## 执行流程
- 由web入口`public`下的的`index.php`文件启动
    - CGI模式：用于WEB启动
    - CLI模式：用于命令行执行
    - TEST模式：用于单元测试
- 设定模块后，根据传入的`URI`定位`controller`
- 由Core/bootstrap依次执行`runBefore` `run` `runBehind` 方法
    - 如果`runBefore` `runBehind` 返回`false`，将退出执行
    - `run` 方法为执行主方法，并接收`URI`中的控制参数
- 采用 MVCS 层级划分，将传统的 MVC 中的model，拆分成 `service` 和 `data`
    - `controller` 为控制器，负责参数接收和响应控制
    - `service` 处理业务层逻辑
    - `data` 处理数据操作
    - `view` 负责渲染显示

### 路由
- 默认路由 domain/path[-args]
    - domain -> App prefix
    - path -> controller 的 Namespace
    - {args} -> `controller::run` 的参数
- rewrite规则
    - 如存在{app}/router.php文件，则优先使用所定义的规则
    - `router::attr` 添加需要捕获的属性
    - `router::rewrite` 设置rewrite规则

### 自动加载
- 根据命名空间定位文件路径，大小写敏感
- `routemap`映射：如果类名前缀和`routemap`匹配，则视为其处在对应模块下

### 配置
- 分为本地`local`和全局`global`两类，本地配置的优先级更高
- 可通过`bin/make-conf`预解析配置文件
- `boot.ini`为启动配置文件
    - `system.env` => 系统运行环境, `LIVE`:线上; `DEV`:开发
    - `public.assets` => 静态资源文件路径
    - `debug.mask` => 调试掩码，只有通过此掩码的调试记录才会输出
    - `debug.report` => 调试报告输出形式,可按位叠加，`STD`:1 (标准输出); `LOG`:2 (调试日志); `JSC`:4 (JS控制台)
 - `cron.ini`为cron配置文件
     `* * * * * bin/cron --prefix=Demo 1>>cron.log 2>&1`
    

### 模板
- 原生PHP标签写法，不引入新的模板
- 使用`bin/make-vmap`，根据最后修改时间生成静态资源的版本号
- 通过部件`widget`实现模块复用

### 异常处理
- 异常抛出都应继承自`coreException`
- 在`index`入口做统一捕获，并决定如何处理异常