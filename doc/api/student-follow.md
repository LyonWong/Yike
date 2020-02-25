## 学员-关注讲师/student-follow

### follow-follow

> 提交反馈

> `POST` /follow-follow

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| tusn | string | 讲师usn|


返回响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | null | |
返回例子

```
{
    "error": "0",
    "message": "success",
    "data": {
        "isFollow": true,
    }
}
```

### follow-list

> 关注列表

> `GET` /follow-list

可选参数 

| name | type | default | comment |
| ---- | ---- | ------- | ------- |
| cursor | int | -- | 起始游标 |
| limit | int | 10 | 记录数 |



返回响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | null | |
返回例子

```
{
	"error": "0",
	"message": "success",
	"data": [{
		"sn": "58df77b3dd38",
		"name": "antic",
		"avatar": "http:\/\/7xq0jh.com1.z0.glb.clouddn.com\/user\/58df77b3dd38\/avatar-avatar?1d4mhd7",
		"about": "bb",
		"stats": [],
		"cursor": 1
	}, {
		"sn": "58fac96b86e1b",
		"name": "antic88",
		"avatar": "http:\/\/7xq0jh.com1.z0.glb.clouddn.com\/user\/58fac96b86e1b\/avatar-avatar?1d4rl9f",
		"about": "xxbb",
		"stats": [],
		"cursor": 2
	}]
}
```