## 讲师

### datum

> 资料

`GET` /api/teacher-datum

参数

| name | type | default | comment |
| ---- | ---- | ---- | ---- |
| usn | string | - | 讲师用户SN |

响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | <讲师资料> | |

<讲师资料>

- `sn`:string 讲师用户SN
- `name`:string 讲师用户名
- `avatar`:url 讲师头像
- `about`:string 讲师介绍
- `followed`:bool|null 是否关注，未登录状态返回`null`