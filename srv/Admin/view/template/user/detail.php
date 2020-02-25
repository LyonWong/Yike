<?php
$profile = $this->profile;
$detail = $this->detail;
$info = $detail['info'];
?>
<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">
            <div class="caption">学生详情</div>
        </div>
    </div>
    <div class="portlet-body form portlet-empty">
        <form role="form" class="form-horizontal">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-2 control-label">ID</label>
                    <div class="col-md-5">
                        <span class="form-control"><?= $detail['id'] ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">SN</label>
                    <div class="col-md-5">
                        <span class="form-control"><?= $profile['sn'] ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">昵称</label>
                    <div class="col-md-5">
                        <span class="form-control"><?= $profile['name'] ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">头像</label>
                    <div class="col-md-5">
                        <a href="<?= $profile['avatar'] ?>" target="_blank">
                            <img class="img-s" src="<?= $profile['avatar'] ?>"/>
                        </a>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">邮箱</label>
                    <div class="col-md-5">
                        <span class="form-control"> <?= $info['email']??'' ?> </span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">手机</label>
                    <div class="col-md-5">
                        <div class="form-control"> <?= $info['telephone']??'' ?> </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">关注公众号</label>
                    <div class="col-md-5">
                        <div class="form-control"> <?= ($info['subscribe']??0) ? '是' : '否'?> </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">来源</label>
                    <div class="col-md-5">
                        <div class="form-control"> <?= $info['originName']??''?> </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">加入时间</label>
                    <div class="col-md-5">
                        <div class="form-control"> <?= $detail['tms_create']?> </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">信息更新时间</label>
                    <div class="col-md-5">
                        <div class="form-control"> <?= $detail['tms_update']?> </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">账户余额</label>
                    <div class="col-md-5">
                        <div class="form-control"> <a href="/pay/money?userField=sn&userValue=<?=$detail['sn']?>">￥<?= $detail['balance']?> </a></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">设置</label>
                    <div class="col-md-5">
                        <div class="form-control" style="height: 100px;overflow:auto">
                            <ul>
                                <?php if(isset($this->detail['setting']['notice'])) {
                                    foreach ($this->detail['setting']['notice'] as $k=>$v) {
                                        echo '<li>'.\Admin\wdgtLang::dict($k).' => '.$v.'</li>';
                                    }

                                }?>
                                <?php if(isset($this->detail['setting']['auto_refund'])) {
                                    echo '<li>'.\Admin\wdgtLang::dict('auto_refund').' => '.$this->detail['setting']['auto_refund'].'</li>';

                                }?>

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">操作</label>
                    <div class="col-md-5">
                        <div class="form-control">
                            <a href="/lesson/log-student?userField=id&userValue=<?=$detail['id']?>">课程统计 </a>
                            <input style="text-shadow: none; color: #5b9bd1;border: none" type="button" data-toggle="modal" data-target="#myModal"  value="发送微信消息">
<!--                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">发送微信消息</button>-->
                            <a href="/user/mock?usn=<?=$profile['sn']?>" target="_blank">模拟登录</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">New message</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">first:</label>
                            <input type="text" class="form-control" id="first" placeholder="您好，您的问题已经接收">
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">类型:</label>
                            <input type="text" class="form-control" id="type" placeholder="反馈意见">
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">提交日期:</label>
                            <input type="text" class="form-control" id="time" value="<?=date('Y年m月d日 H:i')?>">
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">remark:</label>
                            <input type="text" class="form-control" id="remark" placeholder="我们的工作人员会尽快跟进您的问题，请耐心等候。">
                        </div>
                    </form>

                    <label for="recipient-name" class="control-label">示例:</label>
                    <div class="row" style="padding-left: 30px">
                        您好，您的问题已经提交。<br>
                        信息类型：反馈意见 <br>
                        提交日期：2015年6月29日 10:55 <br><br>
                        我们的工作人员会尽快跟进您的问题，请耐心等候。<br>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary" id="send">发送</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#myModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var name = '<?= $profile['name'] ?>' // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this)
        modal.find('.modal-title').text('向 ' + name + ' 发送微信消息')
//        modal.find('.modal-body input').val(name)
    });

    $('#send').click(function(){
        document.getElementById("first").style.border = '';
        document.getElementById("type").style.border = '';
        document.getElementById("time").style.border = '';
        document.getElementById("remark").style.border = '';
        if($("#first").val() == '') {
            document.getElementById("first").style.border = "1px solid red";
            return false;
        }
        if($("#type").val() == '') {
            document.getElementById("type").style.border = "1px solid red";
            return false;
        }
        if($("#time").val() == '') {
            document.getElementById("time").style.border = "1px solid red";
            return false;
        }
        if($("#remark").val() == '') {
            document.getElementById("remark").style.border = "1px solid red";
            return false;
        }
        $.post('./detail-sendMsg', {
            uid: '<?=$detail['id']?>',
            first:$("#first").val(),
            type:$("#type").val(),
            time:$("#time").val(),
            remark:$("#remark").val()
        }, function (res) {
            if (res.error == '0') {
                toastr['success']('发送成功')
            } else {
                toastr['warning']('发送失败')
            }
        })
    })
</script>