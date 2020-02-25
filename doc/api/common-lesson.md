## 课程/lesson

### lesson-profile

> 课程基本信息

`GET` /lesson-profile.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串码 |

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | `<课程概况>` |  |

返回例子

```json
{
    "error": "0",
    "message": "success",
    "data": {
        "title": "a",
        "brief": "b",
        "tms_update": "2017-04-25 14:44:15",
        "cover": "http://oorfbrtmt.bkt.clouddn.com/lesson/L58fef03f22293/cover?1cfts1v",
        "teacher": {
            "sn": "58f32e4251fda",
            "name": "teacher",
            "avatar": "http://oorfbrtmt.bkt.clouddn.com/user/58f32e4251fda/avatar?1cf6bjc"
        }
    }
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
| 0 | success | `<课程详情>` |

返回例子

```json
{
    "error": "0",
    "message": "success",
    "data": {
        "id": 4,
        "sn": "L58fef03f22293",
        "tuid": 3,
        "title": "a",
        "brief": "b",
        "category": "",
        "tags": "",
        "i_form": 1,
        "price": 1,
        "quota": 0,
        "homework": "{}",
        "plan": {
            "duration": "3",
            "dtm_start": "2"
        },
        "i_step": 1,
        "extra": null,
        "tms_step": "2017-04-25 14:44:15",
        "tms_create": "2017-04-25 14:44:15",
        "tms_update": "2017-04-25 14:44:15",
        "teacher": {
            "tusn": "58f32e4251fda",
            "name": "test",
            "avatar": "http://oorfbrtmt.bkt.clouddn.com//teacher/58f32e4251fda/avatar"
        }
    }
}
```

### lesson-repose

> 进入课后交流

`POST` /lesson-repose.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串码 |

可选参数
| name | type | default | comment |
| ---- | ---- | ------- | ------- |
| duration | int | `24` | 交流持续小时数 |

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- 
| 0 | success | null | 成功 |

### lesson-finish

> 课程详情

`POST` /lesson-finish.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| lesson_sn | string | 课程串码 |

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | null | 成功 |