## 学员-支付/student-pay

> 学员支付

### pay-order-create

> 创建支付订单

`POST` /pay/order-create.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lsn | string | 课程串码 |

返回响应

```json
{
	"error": "0",
	"message": "success",
	"data": {
		"sn": "58f8b33642fa7",
		"i_type": 1,
		"uid": 31,
		"lesson_id": 1,
		"origin_id": 0,
		"order_amount": 1,
		"i_pay_way": 1,
		"body": "\u53ea\u8981\u4e00\u5757\u94b1"
	}
}
```


### pay-order-confirm

> 确认支付

`POST` /pay/order-confirm.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| order_sn | string | 订单串码 |
| body | string | 订单描述 |

返回响应

```json
{
	"error": "0",
	"message": "success",
	"data": {
		"appId": "wxd767009104b89c74",
		"nonceStr": "672fde09733b8f8",
		"package": "prepay_id=wx2017042109573996d8084aac0227228077",
		"signType": "MD5",
		"timeStamp": "1492693815",
		"paySign": "F9CF269D9FE61126DFD30E3C514B3ACB"
	}
}
```

### pay-refund-apply

> 退款申请

`POST` /pay/refund-apply.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 订单串码 |


### pay-money-balance

> 账户余额

`GET` /pay/money-balance.api

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

### pay-money-bill

> 账单记录

`GET` /pay/money-bill.api

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
                "tms": "2017-06-01 17:31:09"
            }
        ],
        "cursor": 3
    }
}
```

### pay-money-debit

> 余额变动记录

`GET` /pay/money-debit.api

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



### pay-group-create

> 创建团体订单

`POST` /pay/group-create.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| target | string | 课程SN/系列SN |

可选参数

| name | type | default | comment |
| ---- | ---- | ------- | ------- |
| notice | string |  | 报名须知 |
| origin | string |  | 报名来源 |


返回响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | 团体报名订单SN |  |

返回例子

```json
{
	"error": "0",
	"message": "success",
	"data": {
		"unionSn": "UG767009104b89c74"		
	}
}
```

### pay-group-list

> 获取团体订单列表

`GET` /pay/group-list.api

可选参数

| name | type | default | comment |
| ---- | ---- | ------- | ------- |
| target | string | | 课程SN/系列SN，不填则返回当前用户所有团体订单 |

返回响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | [{profile: [`团体订单信息概况`](#group-enlist-profile), settle: [`团体订单结算信息`](#group-enlist-settle), status: [`团体订单状态`](#group-enlist-status)}, ..] | 

返回例子

```json
{
	"error": "0",
	"message": "success",
	"data": {
		[
			{
				"profile":{
					"type": "series",
					"sn": "Sxxxxxx",
					"title": "系列课名",
					"cover": "课程封面地址",
					"teacher": {
						"sn": "Uxxxxxx",
						"name": "讲师名",
						"avatar": "讲师头像地址"
					}
				},
				"settle":{
					"member": 20,
					"amount": 200,
					"refund": 0
				},
				"status": "enlist"
			}
		]
	}
}
```

### pay-group-detail

> 获取团体订单详情

`GET` /pay/group-detail.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| unionSn | string | 团体订单SN |

返回响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | {profile: [`团体订单信息概况`](#group-enlist-profile), settle: [`团体订单结算信息`](#group-enlist-settle), status: [`团体订单状态`](#group-enlist-status), enlist: [[`团体订单成员信息`](#group-enlist-member), ..]} | 

返回例子

```json
{
	"error": "0",
	"message": "success",
	"data": {
		[
			{
				"profile":{
					"type": "series",
					"sn": "Sxxxxxx",
					"title": "系列课名",
					"cover": "课程封面地址",
					"teacher": {
						"sn": "Uxxxxxx",
						"name": "讲师名",
						"avatar": "讲师头像地址"
					}
				},
				"settle":{
					"member": 20,
					"amount": 200,
					"refund": 0
				},
				"status": "enlist",
				"enlist": [
					{
						"user":{
							"sn": "Uxxxxx",
							"name": "用户名",
							"avatar": "用户头像地址"
							},
						"remark": "报名备注信息"
						"tags":[
							{
								"type": "green",
								"text": "已听课1/4"
							}
						],
						"status": "attend",
						"subdetail": true
					}
				]
			}
		]
	}
}
```


### pay-group-join

`GET` /pay/group-join.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| unionSn | string | 团体订单SN |

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| unionSn | string | 团体订单SN |

> 参与团体订单

`POST` /pay/group-join.api 

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| unionSn | string | 团体订单SN |

可选参数

| name | type | default | comment |
| ---- | ---- | ------- | ------- |
| remark | string | | 备注 |

返回响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | 参团SN |  |

### pay-group-subdetail

> 参团订单自明细

`GET` pay-group-subdetail.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| joinSn | string | 团体订单SN |

返回响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | [[`参团成员子明细`](#group-enlist-subdetail), ..] |  |

返回例子

```json
{
	"error": "0",
	"message": "success",
	"data": [
		{
			"title": "课程一",
			"status": "已听课"
		},
		{			
			"title": "课程二",
			"status": "已退款"		
		}
	]
}
```


### pay-group-cancel

> 退出团体报名

`POST` /pay/group-cancel.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| joinSn | string | 参与报名SN |

返回响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | 申请报名状态 |  |

### pay-group-accept

> 同意团体报名申请

`POST` /pay/group-accept.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| joinSn | string | 参团SN |

返回响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | {settle: `团队订单结算信息`, status: 申请报名状态} |  |

### pay-group-reject

> 拒绝团体报名申请

`POST` /pay/group-reject.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| joinSn | string | 参团SN |

返回响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | 申请报名状态 |  |

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

- `tms`: 订单时间
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


### group-enlist-profile


- `type`
> - `lesson`: 单课
> - `series`: 系列课
- `sn`: 课程串号
- `title`: 课程标题
- `cover`: 课程封面
- `teacher` 讲师信息
	- `sn`: 讲师串号
	- `name` 讲师名称
	- `avatar` 讲师头像

### group-enlist-settle

- `member` 成员数量
- `amount` 支付金额
- `refund` 退款金额

### group-enlist-status

>- `enlist` 报名中
>- `finish` 已结单	
	

### group-enlist-member

> 团体订单成员信息

- `user` 用户信息
	- `sn` 用户串号
	- `name` 用户昵称
	- `avatar` 用户头像
- `remark` 参团备注
- `tags`:[array] 标签
>	- `type`
>	- `text`
- `status` 申请状态
> - `attend` 参加
> - `accept` 通过
> - `reject` 拒绝
> - `cancel` 退出
- `subdetail`:[boolean] 是否有子明细

### group-enlist-subdetail

> 团体订单成员子明细

- `title` 课程标题
- `status` 听课状态
>	- `enroll` 已报名
>	- `access` 已听课
>	- `refund` 已退款



