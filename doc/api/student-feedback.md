## 学员-反馈/student-feedback

### feedback-submit

> 提交反馈

> `POST` /feedback-submit

必填参数

| name | type | comment |
| ---- | ---- | ------- |
| text | string | 反馈文本 |

可选参数

| name | type | comment |
| ---- | ---- | ------- |
| image| string.base64 | 反馈图片 |

返回响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | null | |