## 学员-留言板/student-board

### board-init

> 初始化

`GET` /board-init

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串号 |

返回响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | 初始化数据 | `type_map`类型字典, `type_now`当前类型 |

```
{
    "error": "0",
    "message": "success",
    "data": {
        "type_map": {
            "argue": "讨论"
        },
        "type_now": "argue"
    }
}
```

### board-slice

> 分段记录

`GET` /board-slice

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串号 |
| type | string | 分类，讨论`argue` |

可选参数 

| name | type | default | comment |
| ---- | ---- | ------- | ------- |
| sort | string | weight | 排序，综合`weight`,时间正序`time_asc`,时间倒序`time_desc` |
| cursor | string | -- | 起始游标 |
| limit | int | 10 | 记录数 |

返回响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | 留言列表 |  |

返回例子

```
{
    "error": "0",
    "message": "success",
    "data": [
        {
            "type": "before",
            "user": {
                "sn": "U598bc53aa656a",
                "name": "root",
                "avatar": "http://oorfbrtmt.bkt.clouddn.com/user/U598bc53aa656a/avatar!avatar?1cvo9js",
                "lebel": "teacher"
            },
            "refer": null,
            "message": {
                "text": "text",
                "image": ["a.jpg", "b.jpg"],
                "audio": "audio.mp3"
            },
            "stats": {
                "liked": 1
            },
            "tms_create": "2017-11-07 15:05:50",
            "cursor": "1-1d02mqe-1",
            "self": {
                "isLike": true
            },
			"menu": ["chain", "assoc", "delete", "tipoff"]
        }
    ]
}
```

### board-focus

> 指定聚焦

`GET` /board-focus

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串号 |
| target | string | 留言游标 |


返回响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | 留言数据 |  |

返回例子

```
{
    "error": "0",
    "message": "success",
    "data": {
        "type": "argue",
        "user": {
            "sn": "U598bc53aa656a",
            "name": "root",
            "avatar": "http://oorfbrtmt.bkt.clouddn.com/user/U598bc53aa656a/avatar-avatar?1d1a6l2",
            "label": "teacher"
        },
        "refer": {
            "user": {
                "sn": "U598bc53aa656a",
                "name": "root",
                "avatar": "http://oorfbrtmt.bkt.clouddn.com/user/U598bc53aa656a/avatar-avatar?1d1a6l2"
            },
            "message": {
                "text": "bar"
            },
            "tms_create": "2017-12-23 14:48:25"
        },
        "message": {
            "text": "bar"
        },
        "stats": {
            "liked": 0
        },
        "tms_create": "2017-12-23 14:48:36",
        "cursor": "17-1d3rv24-0",
        "self": null
    }
}
```

### board-refer

> 引用记录

`GET` /board-refer

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串号 |
| target | string | 被引用留言游标 |

可选参数

| name | type | default | comment |
| ---- | ---- | ------- | ------- |
| cursor | string | -- | 游标 |
| limit | int | 10 | 记录数 |

返回响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | 留言列表 |  |

### board-chain

> 对话链

`GET` /board-chain

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串号 |
| target | string | 留言游标 |

可选参数

| name | type | default | comment |
| ---- | ---- | ------- | ------- |
| cursor | string | -- | 游标 |
| limit | int | 10 | 记录数 |

返回响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | 留言列表 |  |


### board-assoc

> 相关留言

`GET` /board-assoc

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串号 |
| target | string | 留言游标 |

可选参数

| name | type | default | comment |
| ---- | ---- | ------- |
| cursor | string | -- | 游标 |
| limit | int | 10 | 记录数 |

返回响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | 留言列表 |  |

### board-comment

> 发布留言

`POST` /board-comment

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串号 |
| type | string | 分类，讨论`argue` |
| text | string | 文字 |
| image | array | 图片，最多允许三张，`image[0]` `image[1]` `image[2]` |
| audio | string | 音频 |

返回响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | `cursor` | 发布的留言游标 |
| 1 | 请先报名 | null | 报名后才能留言 |

### board-reply

> 回复留言

`POST` /board-reply 

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| cursor | string | 所回复的留言游标 |
| text | string | 文字 |
| image | array | 图片，最多允许三张，`image[0]` `image[1]` `image[2]` |
| audio | string | 音频 |

返回响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | `cursor` | 发布的留言游标 |
| 1 | 请先报名 | null | 报名后才能回复 |

### board-like

> 点赞留言

| name | type | comment |
| ---- | ---- | ------- |
| cursor | string | 所点赞的留言游标 |

返回响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | 点赞结果 | `isLike`是否为点赞状态,`liked`总点赞数 |
| 1 | 请先报名 | null | 报名后才能点赞 |

返回例子

```
{
    "error": "0",
    "message": "success",
    "data": {
        "isLike": true,
        "liked": 1
    }
}
```

### board-delete

> 删除自己留言

`POST` /board-delete

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| cursor | string | 要删除的留言游标 |

选填参数

| name | type | comment |
| ---- | ---- | ------- |
| reason | string | 删除理由|

返回响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | 
| 1 | error | 无权删除

### board-tipoff

> 举报留言

`POST` /board-tipoff

| name | type | comment |
| ---- | ---- | ------- |
| cursor | string | 所回复的留言游标 |
| text | string | 文字 |
| image | array | 图片，最多允许三张，`image[0]` `image[1]` `image[2]` |
| audio | string | 音频 |

返回响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success |  |  |
