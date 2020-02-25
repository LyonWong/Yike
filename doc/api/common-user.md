## 用户/user

### user-profile

> 用户基本信息

`GET` /user-profile.api

可选参数

| name | type | comment |
| ---- | ---- | ------- |
| usn | string | 用户串码，不填默认为当前用户 |

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success |  |  |

返回例子

```json
{
    "error": "0",
    "message": "success",
    "data": {        
        "sn": "58d38c63bc3f9",
        "name": "root",
        "avatar": "http://oorfbrtmt.bkt.clouddn.com/user/58d38c63bc3f9/avatar"
    }
}
```