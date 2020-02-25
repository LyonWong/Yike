## 课程

### home

> 课程首页

`GET` /api/lesson-home

响应

响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | [<首页分区>, ..] |  |

<首页分区>

- `form`:string 展示形式
> - banner 顶部banner
> - block 课程板块

- `title`:string 分区标题
- `tag`:string 课程标签，以空格` `分隔
- `list`:array [[<课程列表>](./response#lesson-list)]


### list

> 课程列表

`GET` /api/lesson-list

参数

| name | type | default | comment |
| ---- | ---- | ---- | ---- |
| tag | string | `` | 课程标签 |
| cursor | string | `--` | 定位游标 |
| limit | int | 10 | 条数 |


响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | [<课程列表>](./response#lesson-list) |  |

### profile

> 概况

`GET` /api/lesson-profile

参数

| name | type | default | comment |
| ---- | ---- | ---- | ---- |
| sn | string | - | 课程SN |

响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | [<课程简介>](./response#lesson-profile) |  |

### introduce

> 介绍

`GET` /api/lesson-introduce

参数

| name | type | default | comment |
| ---- | ---- | ---- | ---- |
| sn | string | - | 课程SN |

响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | <课程介绍> | markdown文本 |


### rating

> 评价

参数

| name | type | default | comment |
| ---- | ---- | ---- | ---- |
| sn | string | - | 课程SN |

响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | <评价摘要> |  |

<评价摘要>

- `stats`
	- `score`:int 课程评分
	- `turnout`:int 评价人数
- `list`:array [[<课程评价>](./response#lesson-rating), ..]

示例

```
 {
 	"stats": {
        "score": 3,
        "turnout": 1
    },
    "list": [
        {
            "cursor": 49,
            "score": 4,
            "remark": "1",
            "reply": null,
            "tms": "2018-04-26 18:00",
            "tms_reply": "2018-04-26 18:00:23",
            "user": {
                "sn": "U5acdd14c33dd6",
                "name": "伊多",
                "avatar": "https://storage.sandbox.yike.fm/user/U5acdd14c33dd6/avatar!avatar?1dfkft5"
            }
        },
        {
            "cursor": 48,
            "score": 3,
            "remark": "123123",
            "reply": "234",
            "tms": "2018-04-26 15:18",
            "tms_reply": "2018-04-26 15:19:20",
            "user": {
                "sn": "U5acdd14c33dd6",
                "name": "伊多",
                "avatar": "https://storage.sandbox.yike.fm/user/U5acdd14c33dd6/avatar!avatar?1dfkft5"
            }
        }
    ]
}
```

### relative

> 相关课程

`GET` /api/lesson-relative

参数

| name | type | default | comment |
| ---- | ---- | ---- | ---- |
| sn | string | - | 课程SN |

响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | [[<课程简介>](./response#lesson-profile), ..] |  |
