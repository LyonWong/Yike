## 个人

### lesson

> 个人课程信息

`GET` /api/individual-lesson

参数

| name | type | default | comment |
| ---- | ---- | ---- | ---- |
| sn | string | - | 课程SN |

响应

| error | message | data | comment |
| ----- | ------- | ---- | ------- |
| 0 | success | <个人课程状态> | |

<个人课程状态>

- `status`
> - `fresh` 无记录
> - `enroll` 已报名
> - `access` 已听课
> - `refund` 已退款
> - `reset` 重置

- `refund`:string|bool
> - `freely` 可自由退款
> - `apply`  向讲师申请退款
> - `appeal` 向平台申诉退款
> - `false` 不可退款

- `promote`
	- `sn`:string 优惠码
	- `discount`:float(2) 优惠抵扣额度

### series

> 个人系列信息
