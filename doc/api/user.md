## 用户

### profile

> 简介

`GET` /api/user-profile

参数

| name | type | default | comment |
| ---- | ---- | ---- | ---- |
| sn | string | 当前用户SN | 用户SN |

响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | <用户简介> | |

<用户简介>

- `usn` 用户SN
- `name` 用户昵称
- `avatar` 用户头像