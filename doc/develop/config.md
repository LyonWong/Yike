### 基础配置
- `yike/srv/{_,Admin,Student,Teacher}/config/local/boot.ini`
  ```
  [system]
    ; 运行环境，线上生产|沙盒|开发
    env = LIVE|SANDBOX|DEV
  [public]
    ; 填写对应的域名
    domain = {www,student,teacher}.yike.local
    ; 静态资源域名
    assets = http://assets.yike.local
    ; 上传文件域名
    upload = http://storage.yike.local
  ```
- `yike/srv/{_,Admin,Student,Teacher}/config/local/mysql.ini`
  ```
  [yike]
    dsn = "mysql:host=localhost;dbname=yike"
    dbname = yike
    username = $username
    password = $password
  ```
- `yike/srv/{_,Admin,Student,Teacher}/config/local}/redis.ini`
  ```
  [yike]
    host = 127.0.0.1
    port = 6379
    timeout = 0.0
    
  # 利用redis的Key过期事件做定时器，对应的redis实例需要开启`notify-keyspace-events Ex`
  [notify]
    host = 127.0.0.1
    port = 6380
    timeout = 0.0
  ```
- `yike/srv/_/config/local/option.ini`
  ```
  ; 白名单
  [allowed]
  ; 内部IP
  internal.IPs[] = 127.0.0.1

- `yike/srv/Admin/config/local/option.ini`
  ```
  ; 白名单
  [allowed]
    register.IPs[] = 127.0.0.1
    white.IPs[] = 127.0.0.1

  [manager]
    ; 管理员邮箱
    emails[] = developer@gamebegin.com
    ; 管理员的微信公众号openId，用于推送通知
    openIds[] = 
  ```

### 第三方服务
- `yike/srv/_/config/local/weixin.ini`
  ```
  [base]
    ; 内部获取微信access token
    FetchAccessAddress = http://yike.local/_/weixin-access
    FetchAccessSecrets[] = thRGkRdLK0WZUYidZHPKnhKIf0islTMOaDvvvIhfJdfDbyRbfHVDYFleGDH1wtQH
  ;微信网页应用
  [web]
    AppID = 
    AppSecret = 
  ;微信公众号
  [mp]
    AppID = 
    AppSecret = 
  ;微信小程序
  [wxa]
    AppId = 
    AppSecret = 
  ;登录回调地址  
  [callback]
    teacherLogin = 'http://teacher.yike.local' // 讲师端回调
    studentLogin = 'http://student.yike.fm' // 旧学员端回调
    indexLogin = 'http://yike.local';
    webLogin = 'http://yike.local/callback/weixin/op-webLogin-index' // web回调
    mpLogin = 'http://yike.local/callback/weixin/mp-login' //公众号回调
  ```
- `yike/srv/_/config/local/pay.ini`
  ```
  ; 微信公众号支付
  [wxm]
    ; 应用ID
    appId = 
    ; 商户ID
    mchId = 
    ; 签名KEY
    signKey = s
    signType = MD5
    ; 证书路径
    certPath = apiclient_cert.pem
    ; key路径
    keyPath = apiclient_key.pem 
    ; 接收微信支付通知回调
    notifyUrl = https://yike.local/callback/weixin/mp-notify
    log = /tmp/debug-pay.log

  ; 微信小程序支付
  [wxa]
    appId = wx46dd76c08122dbd1
    mchId = 1455794802
    signKey = 8loMRJhqENJqlDKE3jWupLgmHH3DgPZp
    signType = MD5
    certPath = apiclient_cert.pem
    keyPath =  apiclient_key.pem
    notifyUrl = https://yike.local/callback/weixin/pay-wxa
    log = /tmp/debug-pay.log

  ; 微信扫码支付
  [wxs]
    appId = 
    mchId = 
    signKey = 
    signType = MD5
    tradeType = NATIVE
    certPath = apiclient_cert.pem
    keyPath =  apiclient_key.pem
    notifyUrl = https://yike.local/callback/weixin/mp-notify
    log = /tmp/debug-pay.log
  ```
- `yike/srv/_/config/local/qiniu.ini`
  ```
  ; 对象存储
  [os]
    ; 空间名称
    Bucket  =
    ; 上传域名
    Upload =
    ; 源站地址 
    Source =
    AccessKey =
    SecretKey =
  ; 智能多媒体
  [dora]
    ; 多媒体队列
    mps =
  ```
- `yike/srv/_/config/local/tencent.ini`
  ```
  ;腾讯云通信
  [im]
    ; 云通讯appid
    SdkAppId = 
    AccountType = 12098
    ; 云通信管理员账号
    AccountAdmin =
    ; 云通讯公钥证书
    PublicKey =
    ; 云同学私钥证书
    PrivateKey =
  ```
- `yike/srv/_/config/local/email.ini`
  ```
  ; 系统邮件
  [noreply]
    Host = smtp.emaildomain
    Port = 
    Username = noreply@yike.local
    Password = 
    From = noreply@yike.local
    FromName = 易课
    WordWrap = 50
    SMTPAuth = true
    SMTPSecure = ssl
    SMTPDebug = false
    Debugoutput = error_log
    CharSet = UTF-8
    isSMTP[] = ''
    isHTML[] = true
  ```
- `yike/srv/_/config/local/sms.ini`
  ```
  ; 此处使用腾讯云的短信服务
  [tencent]
    AppId = 
    AppKey = 
  ```
- `yike/srv/_config/local/zsxq.ini`
  ```
  ; API应用KEY
  [channel]
    ; 接口密钥
    secret =
    ; 入口地址
    entryUrl =
  ```