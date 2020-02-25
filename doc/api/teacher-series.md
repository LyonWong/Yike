## 讲师-系列课/teacher-series
### series-list

> 系列课列表

`GET` /series-list.api

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | `[<课程>, ..]` | |


```json
{
	"error": "0",
	"message": "success",
	"data": [{
		"sn": "S59db29b4cfceb",
		"name": "my series2",
		"introduce": {
			"type": 0,
			"cover": "http:\/\/7xq0jh.com1.z0.glb.clouddn.com\/sdfdsfdsf\/ssss",
			"content": "sfssdfsdfssss",
			"price": 0
		},
		"scheme": {
			"price": "100",
			"share": "510"
		}
	}, {
		"sn": "S59db29e93feb3",
		"name": "my series2",
		"introduce": {
			"type": 0,
			"cover": "http:\/\/7xq0jh.com1.z0.glb.clouddn.com\/sdfdsfdsf\/ssss",
			"content": "sfssdfsdfs",
			"price": 0
		},
		"scheme": {
			"price": "10",
			"share": "50"
		}
	}]
}
```

### series-listLesson

> 系列课课程列表

`GET` /series-listLesson.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| series_sn | string | 系列课串码 |

返回例子
```json
{
	"error": "0",
	"message": "success",
	"data": [{
		"participants": 2,
		"sn": "58f5e26822252",
		"cover": "http:\/\/7xq0jh.com1.z0.glb.clouddn.com\/dsdasdasdsadsa",
		"teacher": {
			"sn": "58df77b3dd38",
			"name": "antic",
			"avatar": "http:\/\/7xq0jh.com1.z0.glb.clouddn.com\/user\/58df77b3dd38\/avatar!avatar?1cs3iia"
		},
		"title": "\u77e5\u8bc6\u661f\u7403\u7684\u96f6\u5230\u767e\u4e07\u2014\u2014\u4ee5\u53ca\u5f52\u96f6\u91cd\u542fssadsadasdasd",
		"brief": null,
		"category": "S59db3ef4c4506",
		"categoryInfo": {
			"sn": "S59db3ef4c4506",
			"uid": 3,
			"name": "mmm",
			"introduce": {
				"type": 0,
				"cover": "http:\/\/7xq0jh.com1.z0.glb.clouddn.com\/sdfdsfdsf\/ssss",
				"content": "sfssdfsdfs"
			},
			"scheme": {
				"price": 0.1,
				"share": "50"
			},
			"teacher": {
				"sn": "58df77b3dd38",
				"name": "antic",
				"avatar": "http:\/\/7xq0jh.com1.z0.glb.clouddn.com\/user\/58df77b3dd38\/avatar!avatar?1cs3iia",
				"about": "dcsadsadsascsac"
			}
		},
		"tags": "",
		"form": null,
		"price": 20,
		"quota": 0,
		"homework": null,
		"plan": {
			"duration": "1",
			"dtm_start": "2017-06-06 10:58:13",
			"countdown": -11053783,
			"dtm_now": "2017-10-12 09:27:56"
		},
		"step": "opened",
		"extra": "{\"cover\": \"dsdasdasdsadsa\"}",
		"tms_step": "2017-04-13 17:56:15",
		"tms_create": "2017-04-13 17:56:15",
		"tms_update": "2017-10-10 14:45:47"
	}]
}
```
### series-create
>
`GET` /series-draft.api

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | `<封面草稿>` |  |

封面草稿

- `upload` 上传host
- `token` 上传token，过期时间3600s
- `key` 目标文件路径

返回例子
```json
{
    "error": "0",
    "message": "success",
    "data": {
        "upload": "http://upload.qiniu.com",
        "token": "9c5z3ArNUTOFTCMz27igGq0OH2o78gciu3Qkc2-i:mEd5CyK7rPDWjwrujBTey5BI1vY=:eyJzY29wZSI6Inlpa2UtdGVzdDpkcmFmdFwvY292ZXJcLzU5MzYxOWM2MmI2NmQiLCJkZWFkbGluZSI6MTQ5NjcyMTM2NiwidXBIb3N0cyI6WyJodHRwOlwvXC91cC5xaW5pdS5jb20iLCJodHRwOlwvXC91cGxvYWQucWluaXUuY29tIiwiLUggdXAucWluaXUuY29tIGh0dHA6XC9cLzE4My4xMzYuMTM5LjE2Il19",
        "key": "draft/cover/593619c62b66d"
    }
}
```

> 创建系列课

`POST` /series-create.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| name | string | 系列课标题 |
| content | string | 系列课简介 |
| cover | string | 课程封面 |
| price | float(9,2) | 课程费用(元) |
| share | string | 分享比例 |


可选参数

| name | type | comment |
| ---- | ---- | ------- |
|type | string | 简介分类，默认text |


返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | `<课程>` | 创建成功 |

返回例子

```json
{
    "error": "0",
    "message": "success",
    "data": {
        "series_sn": "L58f45e003d331"
    }
}
```
### lesson-modify

> 课程编辑

`POST` /series-modify.api

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| series_sn | string | 系列课串码 |


可选参数

| name | type | comment |
| ---- | ---- | ------- |
| name | string | 系列课标题 |
| content | string | 系列课简介 |
| cover | string | 课程封面 |
| price | float(9,2) | 课程费用(元) |
| share | string | 分享比例 |

返回响应

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 0 | success | null | 创建成功 |