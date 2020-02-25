<div class="portlet box blue util-btn-group-margin-bottom-5">
    <div class="portlet-title">
        <div class="caption">
            <div class="caption">系列课配置</div>
        </div>
    </div>
    <div class="portlet-body form portlet-empty">
        <form role="form" class="form-horizontal">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-2 control-label" for="remark">配置</label>
                    <div class="col-md-8">
                        <textarea class="form-control" warp="virtual"  id="conf" rows="15"></textarea>
                    </div>
                </div>
                <div class="col-md-offset-2">
                    <button type="button" class="btn green" id="submit">保存</button>
                </div>
            </div>
        </form>
    </div>
    <div class="portlet-body form portlet-empty">
        <form role="form" class="form-horizontal">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-2 control-label" for="remark">活动配置</label>
                    <div class="col-md-8">
                        <textarea class="form-control" warp="virtual"  id="activity_conf" rows="15"></textarea>
                    </div>
                </div>
                <div class="col-md-offset-2">
                    <button type="button" class="btn green" id="activity_submit">保存</button>
                </div>
            </div>
        </form>

    </div>
</div>

<script>
    jQuery(document).ready(function () {
<?php $conf = str_replace("\n", '\n', $this->conf);$activityconf = str_replace("\n", '\n', $this->activity_conf);?>
var conf = '<?=$conf?>';
var activity_conf = '<?=$activityconf?>';

if(conf) {
    conf = JSON.stringify(JSON.parse(conf),null,2);
}
if(activity_conf) {
    activity_conf = JSON.stringify(JSON.parse(activity_conf),null,2);
}

        $("#conf").val(conf);
        $("#activity_conf").val(activity_conf);
        console.log(conf);

        $('#submit').click(function(){
            $.post('./series-conf', {
                conf: $("#conf").val(),
                series_sn:'<?=$this->series_sn?>'
            }, function(res){
                if (res.error == '0') {
                    toastr['success']('修改成功')
                } else {
                    toastr['warning'](res.message)
                }
            })
        });
        $('#activity_submit').click(function(){
            $.post('./series-activityConf', {
                conf: $("#activity_conf").val(),
                series_sn:'<?=$this->series_sn?>'
            }, function(res){
                if (res.error == '0') {
                    toastr['success']('修改成功')
                } else {
                    toastr['warning'](res.message)
                }
            })
        });

    });

</script>