<?php $data = $this->data; ?>
<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">
            <div class="caption">课程分享</div>
        </div>
    </div>
    <div class="portlet-body form portlet-empty">
        <form role="form" class="form-horizontal">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-2 control-label">讲师姓名</label>
                    <div class="col-md-5">
                        <span class="form-control"><?= $data['teacher']['name'] ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">课程标题</label>
                    <div class="col-md-5">
                        <div class="form-control">
                            <?= $data['title'] ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">课程封面</label>
                    <div class="col-md-5">
                        <img src="<?= $data['cover'] . '?imageView2/0/w/400' ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">课程简介</label>
                    <div class="col-md-5">
                        <textarea class="form-control" rows="5" readonly><?= $data['brief'] ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">课程价格</label>
                    <div class="col-md-5">
                        <div class="form-control">
                            <?= $data['price'] ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">开课时间</label>
                    <div class="col-md-5">
                        <div class="form-control">
                            <?= $data['plan']['dtm_start'] ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">预计课时</label>
                    <div class="col-md-5">
                        <div class="form-control">
                            <?= $data['plan']['duration'] ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label" for="remark">originKey</label>
                    <div class="col-md-5">
                        <input id='originKey' type="text" class="form-control" name="originKey" value="platform-" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label" for="remark">originName</label>
                    <div class="col-md-5">
                        <input id='originName' type="text" class="form-control" name="originName" value="平台推广-"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label" for="remark">ad</label>
                    <div class="col-md-5">
                        <input id='ad' type="text" class="form-control" name="ad" value=""/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-offset-2">
                        <button type="button" class="btn green" id="act-build">点击生成邀请卡</button>
                    </div>
                </div>
                <div class="form-actions ">

                    <div class="form-group qrcode" style="display: none">
                        <label class="col-md-2 control-label">邀请链接</label>
                        <div class="col-md-9">
                            <div class="form-control">
                                <span class="invite_url"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group qrcode" style="display: none">
                        <label class="col-md-2 control-label">短网址</label>
                        <div class="col-md-5">
                            <div class="form-control">
                                <span class="short_url_info"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group qrcode" style="display: none">
                        <label class="col-md-2 control-label">课程二维码</label>
                        <div class="col-md-5">
                            <img src="" class="qrcode_url" width="200px"/>

                        </div>
                    </div>
                    <div class="form-group qrcode" style="display: none">
                        <label class="col-md-2 control-label">课程邀请卡</label>
                        <div class="col-md-5">
                            <img src="" class="card_url" width="300px"/>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <script>
            jQuery(document).ready(function () {
                $('#act-build').click(function () {
                    $.post('./share-invite', {
                        lesson_sn: '<?=$this->data['sn']?>',
                        origin_key:$("#originKey").val(),
                        origin_name:$("#originName").val(),
                        ad:$("#ad").val()
                    }, function (res) {
                        if(res.error==0){
                            $(".qrcode").attr("style","display:block;");
                            $(".qrcode_url").attr('src',res.data.qrcode);
                            $(".card_url").attr('src',res.data.card);
                            $(".invite_url").html(res.data.share_url);
                            $(".short_url_info").html(res.data.short_url);
                        } else {
                            alert('生成邀请卡失败')
                        }
                    })
                });
            });
        </script>
    </div>
</div>
