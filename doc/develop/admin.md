# 管理端手册


## 添加管理员

    `cmd/run Admin /cli/user-create --iType=1 --email=$email --password=$pasword`
    
## 权限管理

### 简介

- 由`scope/admin.ini`定义权限树结构
- 将权限结构导入到数据表`scope_admin`，生成权限节点ID，后续的管理是基于权限ID的
- 支持多个权限域`field`，如`admin`就是一个权限域，每个权限域对应一张权限域表
- 权限值`priv`按位划分，有`view=1` `edit=2` `exec=4`，可按位相加，特别的`full=-1`表示开启所有类型的权限
- 权限值挂载于权限树的节点`point`上，后缀`*`表示囊括下属分支，`!`表示当前叶子节点

### 设置

- 编辑权限菜单 `config//scope/admin.ini`
- 导入到数据库 `cmd/run Admin /cli/scope-load-admin`
- 查看权限结构 `cmd/run Admin /cli/scope-show-admin`
- 添加用户权限 `cmd/run Admin /cli/user-scope --uid=$uid --field=$field --point=$point --priv=$priv`

特别的，超级管理员的权限`auths`可设置为`{"admin": {"*": -1}}`, 表示拥有`admin`域下的所有权限

## 管理后台/admin.yike.local

### 首页管理

- 首页列表分为顶部`banner`和板块`block`两部分
- 【运营管理/板块分类】，中添加`home`分类，点击【配置】，选择该分类的`form`，并将课程、文章或系列的`sn`加入`list`中

### 课程管理
- 课程列表`/lesson/list`可以查看单课和文章列表
- 系列列表`/lesson/series-list`可查看系列课列表
- 讲师创建的课程，需要在`/lesson/review`中审核通过才可公开展示
- 讲师修改课程信息时，需要在`/ticket/lesson`中审核通过
- 用户的退款申请，如果超过无条件期限，需要在退款申请`/ticket/refundApply`或退款申诉`/ticket/refund`中审核通过