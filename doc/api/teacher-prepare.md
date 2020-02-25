## 讲师-备课/teacher-prepare

### prepare-slice

> 备课列表切片

`GET` /prepare-slice.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串号 |

可选参数

| name | type | default | comment |	
| ---- | ---- | ------- | ------- |
| cursor | float | 0 | 游标 |
| toward | string | next | 方向，`next` `prev` `fore` `hind` `self`之一 |
| limit | int | 1 | 条数 |

- `next`: 从游标往后，不包括自身
- `prev`: 从后边往前，不包括自身
- `self`: 游标对应的当前记录
- `fore`: `self`+`next`
- `hind`: `prev`+`self`

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | `[<备课记录>]` | |

返回例子

```json
{
    "error": "0",
    "message": "success",
    "data": [
        {
            "content": "fairytale",
            "seqno": 2,
            "tms": "2017-08-22 15:22:01",
            "type": "text",
            "note": "comment"
        }
    ]
}
``` 

### prepare-create

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串号 |
| content | string | 消息内容 |

可选参数

| name | type | default | comment |
| ---- | ---- | ------- | ------- |
| note | string | '' | 消息备注 |
| insert | float | 0 | 插入点，在此前插入记录 |
| update | float | 0 | 替换点，替换原来的记录 |

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | `<备课记录>` | 成功创建的记录 |


> 创建文本记录

`POST` /prepare-create-text.api

- `content` 为文本内容

> 创建图片记录

`POST` /prepare-create-image.api

- 需要先请求draft接口获取token
- `content` 为图片`key`

> 创建音频记录

`POST` /prepare-create-audio.api

- 需要先请求draft接口获取token
- `content` 为音频`key`
- `note` 音频备注

> 创建视频记录

`POST` /prepare-create-video.api

- 需要先请求draft接口获取token
- `content` 为视频`key`

> 创建书签

`POST` /prepare-create-mark.api
- `content` 为书签文字

### prepare-delete

> 删除指定备课记录

`POST` /prepare-delete.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串号 |
| cursor | float | 消息游标 |

### prepare-swap 

> 交换两条备课记录的顺序

`POST` /prepare-swap.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串号 |
| cursor_a | float | 消息游标A |
| cursor_b | float | 消息游标B |

### prepare-jump

> 将一条记录插到另一条记录前

`POST` /prepare-jump.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串号 |
| cursor | float | 目标记录游标 |
| before | float | 被插入记录游标 |

返回响应

| error | message | data | comment |
| 0 | success | `seqno` | 调整后的记录游标 |

### prepare-draft

> 获取草稿授权

`GET` /prepare-draft.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串号 |

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | `<上传授权>` |  |


返回例子

```json
{
    "error": "0",
    "message": "success",
    "data": {
        "upload": "http://upload.qiniu.com",
        "token": "9c5z3ArNUTOFTCMz27igGq0OH2o78gciu3Qkc2-i:mEd5CyK7rPDWjwrujBTey5BI1vY=:eyJzY29wZSI6Inlpa2UtdGVzdDpkcmFmdFwvY292ZXJcLzU5MzYxOWM2MmI2NmQiLCJkZWFkbGluZSI6MTQ5NjcyMTM2NiwidXBIb3N0cyI6WyJodHRwOlwvXC91cC5xaW5pdS5jb20iLCJodHRwOlwvXC91cGxvYWQucWluaXUuY29tIiwiLUggdXAucWluaXUuY29tIGh0dHA6XC9cLzE4My4xMzYuMTM5LjE2Il19",
        "key": "draft/prepare/593619c62b66d"
    }
}
```
-----

## 参数列表

### 备课记录

- `type`
    - `text` 文本
    - `image` 图片
    - `audio` 语音
- `content` 消息内容
- `note` 消息备注
- `seqno` 消息序列号
- `tms` 消息时间
