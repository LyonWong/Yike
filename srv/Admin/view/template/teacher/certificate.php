<?php
$profile = $this->profile;
$datum = $this->datum;
$info = $this->info;
?>
<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">
            <div class="caption">讲师认证</div>
        </div>
    </div>
    <div class="portlet-body form portlet-empty">
        <form role="form" class="form-horizontal">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-2 control-label">UID</label>
                    <div class="col-md-5">
                        <span class="form-control"><?= $this->tuid ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">TUSN</label>
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
                        <span class="form-control"> <?= $info['email'] ?> </span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">手机</label>
                    <div class="col-md-5">
                        <div class="form-control"> <?= $info['telephone'] ?> </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">简介</label>
                    <div class="col-md-5">
                        <textarea class="form-control" rows="5" readonly><?= $datum['about'] ?></textarea>
                    </div>
                </div>
            </div>
            <div class="form-actions ">

                <div class="form-group">
                    <label class="col-md-2 control-label" for="remark">备注</label>
                    <div class="col-md-5">
                        <input id='remark' type="text" class="form-control" name="remark" placeholder="拒绝请填写理由"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-offset-2">
                        <button type="button" class="btn green" id="act-accept">通过</button>
                        <button type="button" class="btn red" id="act-reject">拒绝</button>
                    </div>
                </div>
            </div>
        </form>
        <script>
            jQuery(document).ready(function () {
                $('#act-accept').click(function () {
                    $.post('./certificate-accept', {
                        tuid: <?=$this->tuid?>
                    }, function () {
                        location.reload()
                    })
                });
                $('#act-reject').click(function () {
                    $.post('./certificate-reject', {
                        tuid: <?=$this->tuid?>
                    }, function () {
                        location.reload()
                    })
                })
            });
        </script>
    </div>
</div>