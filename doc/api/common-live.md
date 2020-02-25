## 直播/live

### live-TencentIM

> 腾讯TIM

#### live-tim-user_sig

`GET` /live-tim-user_sig.api

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | `<user_sig>` | |

#### live-record

> 获取TIM直播记录

`GET` live-record-tim

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串号 |

可选参数

| name | type | default | comment |
| ---- | ---- | ------- | ------- |
| type | string | `tim` | 记录类型 |

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | `<record>` | 课程记录 |
| 1 | no access to lesson | 没有听课许可 |

返回例子
```json
{
    "error": "0",
    "message": "success",
    "data": [
        {
            "content": {
                "Text": "red packet"
            },
            "tms": "2017-04-25 18:07:39",
            "from_account": "58f32e4251fda"
        },
        {
            "content": [
                {
                    "MsgType": "TIMTextElem",
                    "MsgContent": {
                        "Text": "red packet"
                    }
                }
            ],
            "tms": "2017-04-25 20:06:02",
            "from_account": "58f32e4251fda"
        }
    ]
}
```

### lesson-slice

> 获取TIM记录切片

`GET` /live-slice-tim.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串号 |

可选参数

| name | type | default | comment |
| ---- | ---- | ------- | ------- |
| cursor | string | `` | 切片游标 |
| toward | string | `next` | 切片方向，`next`,`prev`
| limit  | int | 10 | 切片长度 |

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | `<record>` | 课程记录 |
| 1 | no access to lesson | 没有听课许可 |

接口说明

- 切片记录从游标处开始（不包括游标自身），返回指定长度，无记录时返回空数组`[]`
- 切片方向`toward`有两个可选值`next`和`prev`
  - `next`加载新记录
  - `prev`加载之前的记录
- 直播状态，应以`cursor`为空，`toward=prev` 的参数获取最新的直播记录
- 回放状态，应以`cursor`为空，`toward=next` 的参数获取开始的直播记录


返回例子

```json
{
    "error": "0",
    "message": "success",
    "data": [
        {
            "lesson_id": 112,
            "i_type": 1,
            "content": [
                {
                    "MsgType": "TIMTextElem",
                    "MsgContent": {
                        "Text": "haah"
                    }
                }
            ],
            "tms": "2017-09-13 11:34:31",
            "cursor": "822-1crh9q7",
            "from_account": "U5969cc2a4a9cc",
            "accountNick": "轩辕·亮",
            "sess": {
                "_impl": {
                    "id": "L59a92ad996115-T"
                }
            }
        },
        {
            "lesson_id": 112,
            "i_type": 1,
            "content": [
                {
                    "MsgType": "TIMTextElem",
                    "MsgContent": {
                        "Text": "啊"
                    }
                }
            ],
            "tms": "2017-09-13 11:48:24",
            "cursor": "833-1crhak8",
            "from_account": "U59a61b2e2d995",
            "accountNick": "zhuchanglin",
            "sess": {
                "_impl": {
                    "id": "L59a92ad996115-T"
                }
            }
        }
    ]
}
```

### lesson-audio

> 获取音频草稿地址

`GET` /live-audio_draft.api

返回响应


| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | `<音频草稿>` |  |

封面草稿

- `upload` 上传host
- `token` 上传token，过期时间3600s
- `key` 目标文件路径

返回例子
```json
{
    "error": "0",
    "message": "success",
    "data": {
        "upload": "http://upload.qiniu.com",
        "token": "9c5z3ArNUTOFTCMz27igGq0OH2o78gciu3Qkc2-i:mEd5CyK7rPDWjwrujBTey5BI1vY=:eyJzY29wZSI6Inlpa2UtdGVzdDpkcmFmdFwvY292ZXJcLzU5MzYxOWM2MmI2NmQiLCJkZWFkbGluZSI6MTQ5NjcyMTM2NiwidXBIb3N0cyI6WyJodHRwOlwvXC91cC5xaW5pdS5jb20iLCJodHRwOlwvXC91cGxvYWQucWluaXUuY29tIiwiLUggdXAucWluaXUuY29tIGh0dHA6XC9cLzE4My4xMzYuMTM5LjE2Il19",
        "key": "draft/audio/593619c62b66d",
        "preview": "http://storage.qiniu.com/lesson/record/593619c62b66d.mp3"
    }
}
```

> 检查音频处理状态

`GET` /live-audio_check.api


必填参数

| name | type | comment |
| ---- | ---- | ------- |
| pid | string | 上传草稿后返回的persistentId |

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | | 处理成功 |
| 1 | xxxxxxx | | 等待处理 |
| 2 | xxxxxxx | | 正在处理 |
| 3 | xxxxxxx | | 处理失败 |
| 4 | xxxxxxx | | 通知提交失败 |

### live-delete

删除制定记录

`POST` /live-delete.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串号 |
| cursor | string | 记录游标 |

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | | 成功 |


### live-quote

将讨论区发言引入授课区

> 引用文本消息

`POST` /live-quote-text.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串号 |
| usn | string | 用户串号 |
| text | string | 文本信息 |

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | | 成功 |
| 1 | illegal quote type | null | 非法引用类型 |
| 2 | no speark permission | null | 没有发言权限 |
| 3 | failed to quote message | 引用失败 |

### live-forbid_speak

> 禁言并删除用户消息

`POST` /live-forbid_speak.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串号 |
| usn | string | 用户串号 |

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | | 成功 |

### live-prepare

> 获取备课记录

`GET` /live-prepare-slice.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串号 |

可选参数

| name | type | default | comment |
| ---- | ---- | ------- | ------- |
| cursor | float | 0 | 游标 |
| toward | string | next | 方向，`next` `prev` `fore` `hind` `self`之一 |
| limit | int | 10 | 条数 |

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | `<备课记录>` | |

返回例子

```json
{
    "error": "0",
    "message": "success",
    "data": [
        {
            "content": "fairytale",
            "note": "comment",
            "seqno": 2,
            "tms": "2017-08-22 15:22:01",
            "type": "text"
        }
    ]
}
``` 

> 发送备课记录

`POST` /live-prepare-send.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串号 |
| cursor | float | 记录序号 |

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | | |

### live-invite

> 邀请嘉宾

`POST` /live-invite.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串号 |

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | 邀请地址 | |

返回例子

```json
{
    "error": "0",
    "message": "success",
    "data": "https://sandbox.yike.fm/live-invite?lesson_sn=L59a92ad996115&token=59b8aa192dd73"
}
```
> 直播间配置接口

`POST` /live-conf.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串号 |

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | 地址 | |

返回例子

```json
{
	error: "0",
	message: "success",
	data: {
		menu: [{
				key: "home",
				text: "回到首页",
				href: "https://student.sandbox.yike.fm"
			},
			{
				key: "rate",
				text: "评价课程",
				href: "https://student.sandbox.yike.fm/weixin-courseTask?lesson_sn=L59f1b8251b31f"
			},
			{
				key: "refund",
				text: "申请退款",
				href: "https://student.sandbox.yike.fm/weixin-refundPage?lesson_sn=L59f1b8251b31f&mode=apply"
			}
		],
		footer: [{
				key: "rate",
				text: "去评价",
				href: "https://student.sandbox.yike.fm/weixin-courseTask?lesson_sn=L59f1b8251b31f"
			},
			{
				key: "next",
				text: "前往下一节",
				href: "https://student.sandbox.yike.fm/live?isOwner=no&lesson_sn=L59f2cdc5b75ee&teach=L59f2cdc5b75ee-T&discuss=L59f2cdc5b75ee-D#/"
			}
		]
	}
}
```