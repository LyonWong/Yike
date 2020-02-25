<div class="portlet box blue util-btn-group-margin-bottom-5">
    <div class="portlet-title">
        <div class="caption">
            <div class="caption">微信公众号菜单</div>
        </div>
    </div>
    <div class="portlet-body form portlet-empty">
        <form role="form" class="form-horizontal">
            <div class="form-body">
            <div class="form-group">
                <label class="col-md-2 control-label" for="remark">配置</label>
                <div class="col-md-5">
                    <textarea class="form-control" warp="virtual"  id="button" rows="30"></textarea>
                </div>

            </div>
                <div class="col-md-offset-2">
                    <button type="button" class="btn green" id="submit">保存并发布</button>
                </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    jQuery(document).ready(function () {
<?php $button = str_replace("\n", '\n', $this->button);?>

var detail = '<?=$button?>';
if(detail) {
     detail = JSON.stringify(JSON.parse(detail),null,2);
}

        $("#button").val(detail);
        console.log(detail);

        $('#submit').click(function(){
            $.post('./mp-change', {
                button: $("#button").val()
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