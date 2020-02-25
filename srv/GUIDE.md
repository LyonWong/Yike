# 易灵微课服务端开发指南

## 业务结构

### 主站
> _
- `api` 供HTML5和小程序调用的通用接口
  - `lesson`
  - `series`
  - `order`
  - `promote`
  - `teacher`
  - `my`
  - `individual`
- `callback` 回调接口
  - `weixin` 微信回调
    - `mp` 公众号
    - `op` 开放平台
    - `pay` 支付
    - `work` 企业微信
  - `tim` 腾讯云通信
  
### 管理后台
> Admin
### 讲师端
> Teacher
### 学员端
> Student

## 功能模块

### 登录注册
### 购买/退款
### 用户分享
在下单时固定订单渠道
在支付时计算分成金额
