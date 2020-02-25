## 学员-系列课/student-series

### series-conf

> 课程配置

`GET` /series-conf.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| series_sn | string | 系列课串码 |

返回响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | `<menu,activity>` | 系列配置 |

返回例子

```json
{
    "error": "0",
    "message": "success",
    "data": {
        "menu": [
            {
                "text": "课程列表",
                "href": "/"
            },
            {
                "text": "优惠活动",
                "href": "/promote?target_sn=S8i491ks1kkssf"
            }
        ],
        "activity": {
            "text": "分享有奖",
            "href": "/promote-card?target_sn=S8i491ks1kkssf"
        }
    }
}
```

### series-order

> 系列课下单

`POST` /series-order

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| series_sn | string | 课程串码 |

可选参数 

| name | type | default | comment |
| ---- | ---- | ------- | ------- |

返回响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | 订单信息 |  |

返回例子

```
{
    "error": "0",
    "message": "success",
    "data": {
    	"order_total": 300, // 单课价格总和
        "order_price": 150, // 优惠前的订单价格        
        "order_amount": 150, // 实际订单金额
        "margin": -101, // 余额充足度
        "charge": 49, // 用余额支付的部分
        "deduct": 0, // 优惠额度
        "surplus": 101, // 需要补充支付的部分
        "lessons": [
            {
                "participants": 0,
                "sn": "L59798d91070e5",
                "cover": null,
                "teacher": {
                    "sn": "U598bc53aa656a",
                    "name": "root",
                    "avatar": "http://oorfbrtmt.bkt.clouddn.com/user/U598bc53aa656a/avatar!avatar?1conh9q"
                },
                "title": "foo",
                "price": 1
            },
            {
                "participants": 0,
                "sn": "L59e8539381ef7",
                "cover": "http://oorfbrtmt.bkt.clouddn.com/null",
                "teacher": {
                    "sn": "U59a12ae68a2b9",
                    "name": "轩辕 ･亮",
                    "avatar": "http://oorfbrtmt.bkt.clouddn.com/user/U59a12ae68a2b9/avatar!avatar?1cr7e60"
                },
                "title": "bar",
                "price": 2
            }
        ],
        "order": "sn": "UO59ed5e5a0466a"
    }
}
```

### series-purchase
 
>账户余额确认支付

`POST` /series-purchase

| name | type | comment |
| ---- | ---- | ------- |
| sn | string | 系列订单串码 |

返回响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | null | 购买成功 |
