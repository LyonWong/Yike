## 讲师-课程/teacher-lesson

### lesson-list

> 课程列表

`GET` /lesson-list.api

可选参数

| name | type | comment |
| ---- | ---- | ------- |
| tusn | string | 讲师串码 |

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | `[<课程>, ..]` | 创建成功 |


```json
{
    "error": "0",
    "message": "success",
    "data": [
        {
            "sn": "58f43c8e14e5c",
            "title": "lesson1",
            "category": "",
            "tags": "",
            "i_form": 1,
            "price": 0,
            "quota": 10000,
            "plan": {
                "duration": "3",
                "dtm_start": "2017-05-18 12:00"
            },
            "step": "onlive",            
            "cover": "http://oorfbrtmt.bkt.clouddn.com/lesson/58f43c8e14e5c/cover?1chq22q",
            "revenue": 0,
            "stats": {}        
        },
        {
            "sn": "58f45e003d331",
            "title": "lesson2",
            "category": "",
            "tags": "",
            "i_form": 1,
            "price": 0,
            "quota": 10000,
            "plan": {
                "duration": "3",
                "dtm_start": "2017-05-18 12:00"
            },
            "step": "opened",
            "cover": "http://oorfbrtmt.bkt.clouddn.com/lesson/58f43c8e14e5c/cover?1chq22q",
            "revenue": 0
        }
    ]
}
```

### lesson-create
>
`GET` /lesson-cover_draft.api

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | `<封面草稿>` |  |

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
        "key": "draft/cover/593619c62b66d"
    }
}
```

> 创建课程

`POST` /lesson-create.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| title | string | 课程标题 |
| brief | string | 课程简介 |
| cover | strinb.base64 | 课程封面 |
| price | float(9,2) | 课程费用(元) |
| dtm_start | string | Y-m-d H:i |
| duration | | 预计课时 |
| quota | int | 名额限制，最大9999 |

可选参数

| name | type | comment |
| ---- | ---- | ------- |
| category | string | 课程分类 |
| tags | string | 课程标签 |
| form | int | 授课形式 |

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | `<课程>` | 创建成功 |

返回例子

```json
{
    "error": "0",
    "message": "success",
    "data": {
        "lesson_sn": "L58f45e003d331"
    }
}
```

### lesson-modify

> 课程编辑

`POST` /lesson-modify.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串码 |


可选参数

| name | type | comment |
| ---- | ---- | ------- |
| category | string | 课程分类 |
| tags | string | 课程标签 |
| form | int | 授课形式 |
| title | string | 课程标题 |
| brief | string | 课程简介 |
| cover | strinb.base64 | 课程封面 |
| price | float(9,2) | 课程费用(元) |
| dtm_start | string | Y-m-d H:i |
| duration | | 预计课时 |
| quota | int | 名额限制，最大9999 |

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | null | 创建成功 |

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
| 0 | success | `<课程详情>` |

返回例子

```json
{
    "error": "0",
    "message": "success",
    "data": {
        "participants": 2,
        "sn": "L59536c4a54b5a",
        "cover": "https://storage.sandbox.yike.fm/draft/cover/59536c3d10163",
        "title": "从前有条河",
        "brief": "从前油条和",
        "category": "",
        "tags": "",
        "form": "im",
        "price": 1,
        "quota": 0,
        "step": "onlive",
        "plan": {
            "duration": "1",
            "dtm_start": "&times",
            "countdown": -1498706665,
            "dtm_now": "2017-06-29 11:24:25"
        },
        "teacher": {
            "sn": "U590087e173788",
            "name": "轩辕•亮",
            "avatar": "https://storage.sandbox.yike.fm/user/U590087e173788/avatar!avatar?1cididf",
            "about": "请开始你的表演"
        },
        "tms_update": "2017-06-28 17:25:01",
        "stats": {
            "lesson.enroll.unique": 2,
            "lesson.access.count": 9,
            "lesson.access.unique": 0,
            "lesson.income.unique": 3,
            "lesson.income.sum": 1,
            "lesson.refund.unique": 1,
            "lesson.refund.sum": 100,
            "lesson.rate.count": 1,
            "lesson.rate.sum": 4,
            "lesson.payoff.sum": 6,
            "lesson.rate.avg": 4
        }
    }
}
```

### lesson-open

> 开启课程

`POST` /lesson-open.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串码 |

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | `<teach, discuss>` | 授课区和讨论区群ID |
| 1 | This is not your lesson | null | 不是该讲师的课程 |
| 2 | It's too early to open lesson | 最多允许提前15分钟开课 |

返回例子

```json
{
    "error": "0",
    "message": "success",
    "data": {
        "teach": "58f43c8e14e5c-T",
        "discuss": "58f43c8e14e5c-D"
    }
}
```

### lesson-rate

> 获取评论列表

`GET` /lesson-rating-list.api


必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串号 |

可选参数

| name | type | default | comment |
| ---- | ---- | ------- | ------- |
| page | int | 1 | 页码 |
| limit | int | 10 | 条数 |


返回响应
```json
{
    "error": "0",
    "message": "success",
    "data": {
        "total": 1,
        "page": 1,
        "pages": [
            {
                "cursor": 3,
                "score": 5,
                "remark": "河蚌",
                "reply": null,
                "tms": "2017-07-28 09:55",
                "tms_reply": "2017-08-03 09:37:21",
                "user": {
                    "sn": "U5969c94a79dbc",
                    "name": "Movooc",
                    "avatar": "https://storage.sandbox.yike.fm/user/U5969c94a79dbc/avatar!avatar?1cmtegn"
                }
            }
        ],
        "totalPage": 1
    }
}
```

> 回复评论

`POST` /lesson-rating-reply.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| cursor | int | 评论游标 |
| text | string | 回复内容 |

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | | 成功 |


----

## 参数列表

- `sn` 课程串码
- `title` 课程标题
- `category` 课程分类
- `tags` 课程标签
- `i_form` 授课形式
- `price` 课程价格
- `quota` 名额限制

