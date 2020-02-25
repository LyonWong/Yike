## 讲师-收益/teacher-revenue

### revenue-overview

> 收益概览

`GET` /revenue-overview.api

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | `<收益概览>` | |

响应例子
```json
{
    "error": "0",
    "message": "success",
    "data": {
        "gross": 0.02,
        "remain": 0.01
    }
}
```

### revenue-record

> 收益记录

`GET` /revenue-record.api

可选参数

| name | type | default | comment |
| ---- | ---- | ------- | ------- |
| page | int | 1 | 页码 |
| limit | int | 10 | 条数 |

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | `[<资金记录>]` | |

响应例子
```json
{
    "error": "0",
    "message": "success",
    "data": {
        "total": 5,
        "page": 1,
        "pages": [
            {
                "tms": "2017-08-11 10:40:35",
                "amount": 0.01,
                "args": null,
                "item": "lesson_income",
                "desc": "课程收入"
            },
            {
                "tms": "2017-08-11 10:38:19",
                "amount": 0,
                "args": null,
                "item": "drawcash",
                "desc": "提现"
            }
        ],
        "totalPage": 1
    }
}
```

> 提现记录

`GET` /revenue-record-drawcash.api

同上

### 自助提现

`POST` /revenue-drawcash.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| amount | float(,2) | 提现金额，单位元，不小于1 |

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | `<收益概览>` | 成功后返回剩余资金 |

响应例子
```json
{
    "error": "0",
    "message": "success",
    "data": {
        "gross": 0.02,
        "remain": 0.01
    }
}
```

----

### 收益概览

- `gross` 累计分成
- `remain` 可提现金额

### 资金记录

- `tms` 记录时间
- `amount` 金额，单位元
- `args` 附加参数
- `item` 资金项目
- `desc` 项目描述