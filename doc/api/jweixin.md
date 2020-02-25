## 微信JS-SDK

### config

> 初始化配置

`GET` /api/jweixin-config

参数

| name | type | default | comment |
| ---- | ---- | ---- | ---- |
| url | string | [当前页面URL] | 需要调用微信JS-SDK的页面URL |

响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | <配置签名> | 配置签名数据 |

- `debug`:bool 是否开启SDK调试
- `appId`:string 公众号APP_ID
- `timestamp`:int 时间戳
- `nonceStr`:string 随机字符串
- `signature`:string 签名字符串

```
{
    "error": "0",
    "message": "success",
    "data": {
        "debug": false,
        "appId": "wxd767009104b89c74",
        "timestamp": 1527237499,
        "nonceStr": "UrmvPZgIm9wgkKO8",
        "signature": "51284b6dfc6df1b0b0d492d98f60f07aa32567d3"
    }
}
```