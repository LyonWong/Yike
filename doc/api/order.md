## 订单

### inquiry

> 查询

`GET` /api/order-inquiry

参数

| name | type | default | comment |
| ---- | ---- | ---- | ---- |
| sn | string | - | 订单SN |

响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | [<订单信息>](./response#order-inquiry) |  |
| 1 | user not match | 不是用户自己的订单 |


### book

> 课程下单预定

`POST` /api/order-book-lesson

> 系列下单预定

`POST` /api/order-book-series

参数

| name | type | default | comment |
| ---- | ---- | ---- | ---- |
| sn | string | - | 课程SN/系列SN |
| origin | string | `_` | 来源渠道，默认为自然增长`_` |

响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | [<订单信息>|`null`](./response#order-inquiry) | 响应为`null`时为免费课程报名成功 |

### purchase

> 仅使用余额购买

`POST` /api/order-purchase

参数

| name | type | default | comment |
| ---- | ---- | ---- | ---- |
| sn | string | - | 订单SN |

响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | null | 购买成功 |

### prepay

#### prepay-wxm

> 微信公众号支付

`POST` /api/order-prepay-wxm

#### prepay-wxa

> 微信小程序支付

`POST` /api/order-prepay-wxa


参数

| name | type | default | comment |
| ---- | ---- | ---- | ---- |
| sn | string | - | 订单SN |

响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | <支付参数> | 客户端调起微信支付时使用 |


示例

```
{
    "error": "0",
    "message": "ok",
    "data": {
        "timeStamp": "1526610018",
        "nonceStr": "ba16943af12feb9cff09aceed2e900a8",
        "package": "prepay_id=wx181020180668562dc42a6edc0185559099",
        "signType": "MD5",
        "paySign": "8DA602EFF0E30E263D349897D11F0931"
    }
}
```