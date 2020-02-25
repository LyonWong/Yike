# 说明

- 接口参数分为`必填参数`和`可选参数`
- 参数类型有`string` `int`等，特别的：
    - `string.csv`表示`,`分隔的简单列表，如`foo,bar`
- `<` `>`表示动态值
- [`meta`, ..]表示数组

## 地址

### 公共

- 正式环境 http://yike.fm
- 沙盒环境 http://sandbox.yike.fm

### 学员

- 正式环境 http://student.yike.fm
- 沙盒环境 http://student.sandbox.yike.fm

### 讲师

- 正式环境 http://teacher.yike.fm
- 沙盒环境 http://teacher.sandbox.yike.fm

## 格式

接口返回均为JSON格式`{"error":?, "message":"...", "data": mixed}`

- error: 错误码，string，`0`为成功
- message: 描述帖子，string
- data: 接口返回数据，mixed

基本响应：

| error | message | data | comment |
| ---- | ------- | ---- | ------- |
| 1.0 | illegal session `<reason>` | null | 非法会话 |
| -1 | `<reson>` | null | 未定义错误 |

## 会话

建立会话后，接受两种验证方式

- 在cookie中以`sess`携带
- 在header中`X-SESS`携带

