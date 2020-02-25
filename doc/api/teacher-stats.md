## 讲师-统计/teacher-stats

### stats-overview

> 讲师概览

`GET` /stats-overview.api

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | `<讲师概览>` | |

响应例子
```json
{
    "error": "0",
    "message": "success",
    "data": { 
        "teacher.lesson.count": 2,
        "teacher.access.count": 3,
        "teacher.access.unique": 2
    }
}
```

### stats-lesson

> 课程统计

`GET` /stats-lesson.api


返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | `[{<课程概况>, <课程数据>}, ..]` | |


响应例子
```json
{
    "error": "0",
    "message": "success",
    "data": [
        {
            "lesson": {
                "participants": 2,
                "sn": "L5934f9851ef79",
                "cover": "https://storage.sandbox.yike.fm/lesson/L5934f9851ef79/cover",
                "title": "One More Thing",
                "price": 0,
                "step": "closed",
                "teacher": {
                    "sn": "U5932183f154ef",
                    "name": "lyon.wong@outlook.com",
                    "avatar": "https://storage.sandbox.yike.fm/user/U5932183f154ef/avatar!avatar?1cj461v"
                },
                "plan": {
                    "duration": "1",
                    "dtm_start": "2017-06-05 14:24:00",
                    "countdown": -1280930,
                    "dtm_now": "2017-06-20 10:12:50"
                },
                "tms_update": "2017-06-13 15:44:58"
            },
            "data": {
                "lesson.enroll.unique": 2,
                "lesson.access.count": 2,
                "lesson.access.unique": 0
            }
        },
        {
            "lesson": {
                "participants": 1,
                "sn": "L59321a8609110",
                "cover": "https://storage.sandbox.yike.fm/",
                "title": "从前有条河",
                "price": 0,
                "step": "onlive",
                "teacher": {
                    "sn": "U5932183f154ef",
                    "name": "lyon.wong@outlook.com",
                    "avatar": "https://storage.sandbox.yike.fm/user/U5932183f154ef/avatar!avatar?1cj461v"
                },
                "plan": {
                    "duration": "1",
                    "dtm_start": "2017-06-03 11:00:00",
                    "countdown": -1465970,
                    "dtm_now": "2017-06-20 10:12:50"
                },
                "tms_update": "2017-06-17 15:46:50"
            },
            "data": {
                 "lesson.enroll.unique": 2,
                    "lesson.access.count": 9,
                    "lesson.access.unique": 0,
                    "lesson.income.unique": 3,
                    "lesson.income.sum": 100,
                    "lesson.refund.unique": 1,
                    "lesson.refund.sum": 100,
                    "lesson.rate.count": 1,
                    "lesson.rate.sum": 4,
                    "lesson.payoff.sum": 6,
                    "lesson.rate.avg": 4
            }
        }
    ]
}
```

### stats-origin

> 来源统计
`GET` /stats-origin.api

必填参数

| name | type | comment |
| ---- | ------- | ---- |
| lesson_sn | string | 课程串号 |

可选参数

| name | type | default | comment |
| ---- | ------- | ---- | ------- |
| origin_id | int | `0` | 来源ID |

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | `{"lesson":<课程概况>, "tier":<来源层级>, "origin":<来源数据>, "subs":[<来源数据>, ..]}` | |

返回例子
```json
{
    "error": "0",
    "message": "success",
    "data": {
        "lesson": {
            "participants": 1,
            "sn": "L59321a8609110",
            "cover": "https://storage.sandbox.yike.fm/",
            "title": "从前有条河",
            "price": 0,
            "step": "onlive",
            "teacher": {
                "sn": "U5932183f154ef",
                "name": "lyon.wong@outlook.com",
                "avatar": "https://storage.sandbox.yike.fm/user/U5932183f154ef/avatar!avatar?1cj461v"
            },
            "plan": {
                "duration": "1",
                "dtm_start": "2017-06-03 11:00:00",
                "countdown": -1486372,
                "dtm_now": "2017-06-20 15:52:52"
            },
            "tms_update": "2017-06-17 15:46:50"
        },
        "tier": [
            {
                "id": 0,
                "key": "*",
                "name": "※",
                "depth": 0
            },
            {
                "id": 5,
                "_id": 0,
                "ids": "5",
                "key": "platform",
                "name": "platform",
                "depth": 1,
                "tags": "",
                "tms_create": "2017-06-15 15:39:38"
            }
        ],
        "total": {
            "origin": {
                "id": 5,
                "ids": "5",
                "key": "platform",
                "name": "platform"
            },
            "data": {
                 "lesson.enroll.unique": 2,
                    "lesson.access.count": 9,
                    "lesson.access.unique": 0,
                    "lesson.income.unique": 3,
                    "lesson.income.sum": 100,
                    "lesson.refund.unique": 1,
                    "lesson.refund.sum": 100,
                    "lesson.rate.count": 1,
                    "lesson.rate.sum": 4,
                    "lesson.payoff.sum": 6,
                    "lesson.rate.avg": 4
            }
        },
        "subs": [            
            {
                "origin": {
                    "id": 3,
                    "ids": "2-3",
                    "key": "A",
                    "name": "A"
                },
                "data": {
                    "lesson.enroll.unique": 1
                }
            },
            {
                "origin": {
                    "id": 4,
                    "ids": "2-4",
                    "key": "B",
                    "name": "B"
                },
                "data": {
                    "lesson.enroll.unique": 1
                }
            }
        ]
    }
}
```

----

### 讲师概览

> 数据键由 主体、事件、算子 三段组成

- `teacher.lesson.count` 课程总数
- `teacher.access.count` 听课次数
- `teacher.access.unique` 听课人数

### 课程数据

> 数据键由 主体、事件、算子 三段组成

- `lesson.enroll.unique` 报名人数
- `lesson.access.unique` 听课人数
- `lesson.refund.unique` 退款人数
- `lesson.rate.count`    评价次数
- `lesson.rate.sum`      评价总分
- `lesson.rate.avg`      评价评分
- `lesson.income.sum`    课程收入
- `lesson.payoff.sum`    分成收入

### 来源层级

- `id` 来源ID
- `key` 来源键
- `name` 来源名
- `depth` 层级深度

### 来源数据

- `origin` 来源概况
	- `id` 来源ID
	- `ids` 来源ID串
	- `key` 来源键
	- `name` 来源名
- `data` `<课程数据>`
