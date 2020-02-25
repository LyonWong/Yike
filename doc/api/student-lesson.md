## 学员-课程/student-lesson

### lesson-conf

> 课程配置

`GET` /lesson-conf.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串码 |

返回响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | `<menu,activity>` | 课程配置 |

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
                "href": "/promote?target_sn=L8i491ks1kkssf"
            }
        ],
        "activity": {
            "text": "分享有奖",
            "href": "/promote-card?target_sn=L8i491ks1kkssf"
        }
    }
}
```

### lesson-profile

> 课程概况

`GET` /lesson-profile.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串码 |

返回响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | [`课程概况`](#课程概况) | 课程列表 |

返回例子


```json
{
    "error": "0",
    "message": "success",
    "data": {
    	"sn": "L58f43c8e14e5c",
    	"cover": "http://storage.sandbox.yike.fm/lessson/L58f43c8e14e5c/cover",        
        "title": "lesson1",        
        "price": 0,
        "step": "onlive",
        "teacher": {
            "sn": "L58f32e4251fda",
            "name": "王老师",
            "avatar": "http://storage.sandbox.yike.fm/teacher/L58f32e4251fda/avatar"
        },
        "plan": {            
            "dtm_start": "2017-04-01 15:00",
            "duration": "1.5",
            "countdown": 78
        }
    }
}
```

### lesson-list

> 课程列表

`GET` /lesson-list.api

可选参数

| name | type | comment |
| ---- | ---- | ------- |
| tusn | string | 讲师串码 |

返回响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | [[`课程概况`](#课程概况), ..] | 课程列表 |


返回例子

```json
{
    "error": "0",
    "message": "success",
    "data": [
        {
            "participants": 0,
            "sn": "L59fffde99cac7",
            "cover": null,
            "title": "创建课程测试",
            "price": 1,
            "category": "",
            "categoryInfo": false,
            "extra": {
                "cover": ""
            },
            "step": "opened",
            "teacher": {
                "sn": "U597196c9d5d8f",
                "name": "鱼",
                "avatar": "https://storage.sandbox.yike.fm/user/U597196c9d5d8f/avatar!avatar?1d02np7"
            },
            "plan": {
                "duration": "0.25",
                "dtm_start": "2017-11-08 14:10",
                "countdown": 82042,
                "dtm_now": "2017-11-07 15:22:38"
            },
            "tms_update": "2017-11-06 15:15:51",
            "type": "single"
        },
        {
            "sn": "S59ffd194a6744",
            "name": "系列优惠",
            "scheme": {
                "price": 4,
                "share": 20,
                "total": 3,
                "opened": 1
            },
            "teacher": {
                "sn": "U597982a817efe",
                "name": "ティーチャー",
                "avatar": "https://storage.sandbox.yike.fm/user/U597982a817efe/avatar!avatar?1cur6n9",
                "about": "God in his heaven"
            },
            "cover": "",
            "type": "series"
        },
    ]
}
```

### lesson-detail

> 课程详情

`GET` /lesson-detail.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串码 |

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | [`课程详情`](#课程详情) | |

返回例子

```json
{
    "error": "0",
    "message": "success",
    "data": {        
        "sn": "L58fef03f22293",
        "cover": "http://storage.sandbox.yike.fm/lessson/L58fef03f22293/cover",
        "title": "lesson1",
        "brief": "My first lesson",
        "category": "",
        "tags": "",
        "event":"enroll",
        "form": "tim",
        "price": 0,
        "quota": 1000,
        "homework": "{}",
        "plan": {            
            "dtm_start": "2017-04-01 15:00",
            "duration": "1.5",
            "countdown": 78
        },
        "step": "opened",
        "extra": null,
        "tms_step": "2017-04-25 14:44:15",
        "tms_create": "2017-04-25 14:44:15",
        "tms_update": "2017-04-25 14:44:15",
        "teacher": {
            "sn": "L58f32e4251fda",
            "name": "王老师",
            "avatar": "http://storage.sandbox.yike.fm/teacher/L58f32e4251fda/avatar",
            "about": "I'm a teacher"
        },
        "participants": 18
    }
}
```

## lesson-rating

> 获取课程评价列表

`GET` /lesson-rating-list.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串码 |

可选参数

| name | type | default | comment |
| ---- | ---- | ------- | ------- |
| cursor | int | `0` | 游标 |
| limit | int | `10` | 幅度 |
| toward | string | `next` | 方向 |

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | [[`课程评价`](#课程评价), ..] | 课程评价列表 |

返回例子

```json
{
    "error": "0",
    "message": "success",
    "data": [
    	{
    		"cursor":1,
    		"score": 4,
    		"remark": "nice",
            "reply": "thanks",
    		"tms": "2017-04-01 12:01:02",
            "tms_reply": "2017-04-01 13:01:02"
    	},
    	{
    		"cursor":2,
    		"score": 3,
    		"remark": "good",
            "reply": "yeah",
    		"tms": "2017-04-01 12:05:02",
            "tms": "2017-04-01 14:01:02"
    	}
    ]
}
```

> 获取当前用户自己的评价

`GET` /lesson-rating-self.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串码 |

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | [`课程评价`](#课程评价) | 课程评价 |

返回例子

```json
{
    "error": "0",
    "message": "success",
    "data": {
    	"cursor":1,
    	"score": 4,
    	"remark": "nice",
    	"tms": "2017-04-01 12:01:02"
    }
}
```

> 获取当前课程的评论统计

`GET` /lesson-rating-count.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串码 |

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | [`课程评价`](#课程评价) | 课程评价 |

返回例子

```json
{
    "error": "0",
    "message": "success",
    "data": {
    	"avg_score":3.7,
    	"rated_count": 4
    }
}
```

> 发表课程评价

`POST` /lesson-rating.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串码 |
| score | int | 评价分数 1~5分 |

可选参数

| name | type | comment |
| ---- | ---- | ------- ||
| remark | string | 评价文字，限200字节 |

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | null | |
| 1 | cannot rate before access | null | 未听课不能评价 |
| 2 | cannot rate again | null | 不能重复评价 |

### lesson-enroll


> 课程报名

`POST` /lesson-enroll.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串码 |

可选参数

| name | type | comment |
| ---- | ---- | ------- |
| origin | string | 来源标记 |

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | [`报名信息`](#报名信息)| 课堂ID |
| 1 | not available | null | 课程未开放 |

返回例子

```json
//免费课程
{
	"error": "0",
	"message": "success",
	"data": {
        "order": null,
		"price": 0,		
        "margin": null,
        "subscribed": 1
	}
}

//付费课程
{
	"error": "0",
	"message": "success",
	"data": {
        "order": "O591a784aec354",
		"price": 100,		
        "margin": -90, // 小于0表示需要额外支付
        "deduct": 0, // 优惠抵扣的部分
        "charge": 10, // 余额支付的部分
        "surplus": 90, // 补充支付的部分
        "subscribed": 1, // 是否订阅公众号
		"pay_data": {
			"appId": "wxd767009104b89c74",
			"nonceStr": "17966282b487a19",
			"package": "prepay_id=wx201705161155556779aa68eb0156737666",
			"signType": "MD5",
			"timeStamp": "1494906955",
			"paySign": "B0DCFD159B106621E48178FBB25E93C9"
		}
	}
}
```

### lesson-purchase

> 课程购买（余额支付）

`POST` /lesson-purchase.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| order | string | 报名订单号 |

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | | |


### lesson-access

> 进入课程，检测用户是否拥有课程权限

`GET` /lesson-access.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串码 |


返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | [`课堂信息`](#课堂信息)|  |
| 1 | access denied | null | 拒绝进入 |

返回例子

```json
{
    "error": "0",
    "message": "success",
    "data": {
        "teach": "L58fef03f22293-T",
        "discuss": "L58fef03f22293-D"
    }
}
```
### lesson-refund-freely

> 学员退款

`POST` /lesson-refund-freely.api    一小时内自由退款

`POST` /lesson-refund-apply.api    向老师申请退款

`POST` /lesson-refund-appeal.api   向平台申诉退款

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串码 |

选填参数

| name | type | comment |
| ---- | ---- | ------- |
| reason | string | 退款理由 |

### lesson-history

> 学员课程历史

`GET` /lesson-history.api

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | [[`课程事件`](#课程事件), ..]|  |

返回例子

```json
{
    "error": "0",
    "message": "success",
    "data": {
        "event": "access",
        "args": [],
        "tms": "2017-05-10 15:25:25",
        "lesson": {
            "sn": "58f43c8e14e5c",
            "cover": "http://oorfbrtmt.bkt.clouddn.com/lesson/58f43c8e14e5c/cover?1cfrs96",
            "title": "test",
            "price": 0,
            "step": "onlive",
            "teacher": {
                "sn": "58f32e4251fda",
                "name": "teacher",
                "avatar": "http://oorfbrtmt.bkt.clouddn.com/user/58f32e4251fda/avatar?1cf6bjc"
            },
            "plan": {            
            	"dtm_start": "2017-04-01 15:00",
            	"duration": "1.5",
            	"countdown": 78
        	}
        }
    }
}
```

----

### 课程概况

- `sn`: 课程串码
- `cover`: 课程封面
- `title`: 课程标题
- `price`: 课程价格
- `step`: 课程阶段
- `teacher`: 讲师信息
	- `sn`: 讲师用户串号
	- `name`: 讲师昵称
	- `avatar`: 讲师头像
- `plan`: 课程计划
	- `dtm_start` 开课时间
	- `duration` 预计用时
	- `countdown` 开课倒计时，开始后会以负数继续
- `participants`: 参与人数


### 课程详情

- `sn`: 课程串码
- `type`: 类型
>	- `single` 单课
>	- `series` 系列课
- `cover`: 课程封面
- `title`: 课程标题
- `brief`: 课程简介
- `category`: 课程分类
	- `key`: 分类标识
	- `name`: 分类名称
- `tags`: 课程标签
	- `key`: 标签标识
	- `name`: 标签名称
- `form`: 授课形式 
> 	- `tim` 腾讯云IM群聊，图文语音
- `name` 系列名
- `scheme` 系列计划
	- `price` 价格
	- `total` 总节数
	- `opened` 已开节数

- `price`: 课程价格
- `quota`: 名额限制
- `step`: 课程阶段
>	-  `submit`：审核中
>	-  `denied`：审核不通过
>	-  `closed`：关闭下架
>	-  `opened`：开放中
>	-  `onlive`: 授课中
>	-  `repose`: 交流中
>	-  `finish`: 已结束

- `plan`: 课程计划
	- `dtm_start` 开课时间
	- `duration` 预计用时
	- `countdown` 开课倒计时，开始后会以负数继续
- `teacher`: 讲师信息
	- `sn`: 讲师用户串号
	- `name`: 讲师昵称
	- `avatar`: 讲师头像
	- `about`: 讲师简介
- `participants`: 参与人数
- `tms_update`: 更新时间

### 报名信息

- `price`: 课程价格，单位分
- `order`: 课程订单号，免费课程为`null`
- `margin`: 报名费和余额的差额
- `deduct`: 优惠抵扣的部分
- `charge`: 余额支付的部分
- `surplus`: 需要额外支付的部分
- `subscribed`: 是否订阅公众号

### 课堂信息

- `teach`: 授课区房间号
- `discuss`: 讨论区房间号

### 课程评价

- `cursor`: 评价游标
- `score`: 评价分数
- `remark`: 评价文字

### 课程事件

- `event`: 事件名

>	- `browse`: 浏览课程
>	- `enroll`: 报名预约
>	- `access`: 进入课程
>	- `cancel`: 退出课程
>	- `refund`: 退款

- `args`: 事件参数
- `tms`: 事件发生时间
- `lesson`: [课程概况](#课程概况)