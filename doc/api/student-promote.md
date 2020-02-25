## 学员-推广/student-promote

### promote-rank

> 个人排名

`GET` /promote-rank-locate.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| target | string | 课程/系列串码 |


返回响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | <rank, score> | <排名, 分数> |

返回例子

```json
{
    "error": "0",
    "message": "success",
    "data": {
        "rank": 2,
        "score": 5
    }
}

```

> 榜单数据

`GET` /prmote-rank-slice.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| target | string | 课程/系列串码 |

可选参数

| name | type | default | comment |
| ---- | ---- | ------- | ------- |
| cursor | string | 0 | 游标 |
| limit | int | 10 | 记录数 |

返回响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | <用户信息> + < rank, score, cursor > | <用户信息>+<排名, 分数, 游标> |

```json
{
    "error": "0",
    "message": "success",
    "data": [
        {
            "sn": "U59a12ae68a2b9",
            "name": "轩辕 ･亮",
            "avatar": "http://oorfbrtmt.bkt.clouddn.com/user/U59a12ae68a2b9/avatar!avatar?1cr7e60",
            "score": 9,
            "cursor": 1,
            "rank": 1
        },
        {
            "sn": "U598bc53aa656a",
            "name": "root",
            "avatar": "http://oorfbrtmt.bkt.clouddn.com/user/U598bc53aa656a/avatar!avatar?1d4rnug",
            "score": 5,
            "cursor": 2,
            "rank": 2
        }
    ]
}
```