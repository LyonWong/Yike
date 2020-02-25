### lesson-profile

> <课程简介>

- `sn`:string  课程SN
- `type`:string  类型
> - 'lesson' 单课
> - 'series' 系列

- `title` 课程标题
- `cover` 封面URL地址
- `series` 系列信息，可能为空`{}`
	- `sn` 系列SN
	- `name` 系列名
- `teacher`
	- `sn` 讲师用户SN
	- `name` 讲师名
	- `avatar` 讲师头像URL地址
- `progress`:array(2) 开课进度
> [总节数, 已开节数]
- `status`:string 课程状态
> - 'opened' 开放中
> - 'onlive' 直播中
> - 'repose' 讨论中
> - 'finish' 可观看
> - 'closed' 已下架

- `form`:string 授课形式
> - 'im' IM直播间

- `enrollment`:int 报名人数
- `price`:string 课程价格（元）
- `plan`
	- `duration`:string
	- `dtm_start`:string
	- `countdown`:int
	- `dtm_now`:string


示例

```
{
    "sn": "L5af6479c312f2",
    "type": "lesson",
    "title": "2",
    "cover": null,
    "series": {
        "sn": "S5af647783aed1",
        "name": "ces321312"
    },
    "teacher": {
        "sn": "U5a0befdc7da0d",
        "name": "易灵微课小助手",
        "avatar": "https://storage.sandbox.yike.fm/user/U5a0befdc7da0d/avatar-avatar?1dfchhs"
    },
    "progress": [
        1,
        1
    ],
    "status": "opened",
    "form": "im",
    "enrollment": 2,
    "price": 2,
    "plan": {
        "duration": "0.5",
        "dtm_start": "2018-05-09 09:46",
        "countdown": -712029,
        "dtm_now": "2018-05-17 15:33:09"
    }
}
```

### lesson-list

[<课程列表项>, ..]

> <课程列表项>

- `tsn`:string 课程/系列SN
- `title`:string 课程标题
- `profile`:<课程简介>
- `tag`:string 课程标签
- `tms`:string 变更时间


### lesson-rating

> <课程评价>

- `cursor`:int
- `score`:int
- `remark`:string
- `reply`:string
- `tms`:string
- `tms_reply`:string
- `user` 讲师<用户信息>

示例

```
{
    "cursor": 49,
    "score": 4,
    "remark": "1",
    "reply": null,
    "tms": "2018-04-26 18:00",
    "tms_reply": "2018-04-26 18:00:23",
    "user": {
        "sn": "U5acdd14c33dd6",
        "name": "伊多",
        "avatar": "https://storage.sandbox.yike.fm/user/U5acdd14c33dd6/avatar!avatar?1dfkft5"
    }
}
```

### order-inquriy

> <订单信息>

- `sn`:string 订单SN
- `title`:string 订单标题
- `order_total`:number 订单原价
- `order_amount`:number 订单金额
- `decuct`:number 优惠抵扣金额
- `charge`:number 余额支付金额
- `surplus`: number 需要补充支付金额(order_amount-charge)
- `status` 订单状态
> - `book` 下单
> - `paid` 已支付
> - `firm` 已确认（不可自由退款）
> - `refund` 已退款

- `list`:array [{`title`: 课程标题, `price`: 课程价格}, ..]

示例

```
{
    "sn": "O5af41a0cd0c59",
    "title": "某一节课程",
    "order_total": 10,
    "order_amount": 8.5,
    "charge": 5,
    "surplus": 3.5,
    "status": "paid",
    "list": [
        {
            "title": "某一节课程",
            "price": 10
        }
    ]
}
```
