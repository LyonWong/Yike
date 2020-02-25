## 系列

### profile

> 概况

`GET` /api/series-profile

参数

| name | type | default | comment |
| ---- | ---- | ---- | ---- |
| sn | string | - | 系列SN |

响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | [<课程简介>](./response#lesson-profile) |  |

### introduce

> 介绍

`GET` /api/series-introduce

参数

| name | type | default | comment |
| ---- | ---- | ---- | ---- |
| sn | string | - | 系列SN |

响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | <系列介绍> | markdown文本 |


### relative

> 相关课程

`GET` /api/series-relative

参数

| name | type | default | comment |
| ---- | ---- | ---- | ---- |
| sn | string | - | 系列SN |

响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | [[<课程简介>](./response#lesson-profile), ..] |  |