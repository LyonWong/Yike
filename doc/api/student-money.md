## 学员-资金/student-money

### money-balance

> 账户余额

`GET` /money-balance.api

返回响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | 账户余额 | 单位：元 |

返回例子

```json
{
    "error": "0",
    "message": "success",
    "data": 1.68
}
```

### money-bill

> 账单记录

`GET` /money-bill.api

可选参数

| name | type | default | comment |
| ---- | ---- | ------- | ------- |
| cursor | int | 0 | 游标 | 二次查询时应携带上一次的游标 |
| limit | int | 10 | 记录数 |

返回响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | {list:[[`账单条目`](#账单条目),..],cursor:?} |  |

返回例子

```json
{
    "error": "0",
    "message": "success",
    "data": {
        "list": [
            {
                "type": "enroll",
                "desc": "很长很长很长很长的标题标题标题",
                "order_amount": 1,
                "paid_amount": 1,
                "balance_var": 0,
                "pay_way": null,
                "status": "firm",
                "tms_update": "2017-06-01 17:31:09"
            }
        ],
        "cursor": 3
    }
}
```

### money-debit

> 余额变动记录

`GET` /money-debit.api

可选参数

| name | type | default | comment |
| ---- | ---- | ------- | ------- |
| cursor | int | 0 | 游标 | 二次查询时应携带上一次的游标 |
| limit | int | 10 | 记录数 |

返回响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | {list:[[`余额条目`](#余额条目),..],cursor:?} |  |

```json
{
    "error": "0",
    "message": "success",
    "data": {
        "list": [
            {
                "item": "purchase",
                "amount": 1,
                "tms": "2017-06-01 16:00:49",
                "desc": "购买-xxx课程"
            },
            {
                "item": "return",
                "amount": 1,
                "tms": "2017-06-01 17:31:09",
                "desc": "返还-xxx课程"
            }
        ],
        "cursor": 2
    }
}
```
----

### 账单条目

- `type`: 账单类型
>	- `enroll` 课程报名

- `order_amount`: 订单金额
- `paid_amount`: 实际付款金额
- `balance_var`: 余额变动
- `pay_way`: 支付方式
>	- `weixin` 微信支付

- `status`:
>	- `book`: 下单
>	- `paid`: 已支付
>	- `firm`: 已确认(放弃退款)
>	- `refund` 已退款

- `tms_update`: 更新时间
- `desc`：描述

### 余额条目

- `item`: 变动项目
>	- `lesson_income` 课程收入
>	- `topup` 充值
>	- `rebate` 返利
>	- `return` 返还
>	- `drawcash` 提现
>	- `lesson_refund` 课程退款
>	- `purchase` 购买

- `amount`: 变动金额（元）
- `tms`: 时间
- `desc`: 描述