<div class="portlet box blue util-btn-group-margin-bottom-5">
    <div class="portlet-title">
        <div class="caption">
            <div class="caption">二维码</div>
        </div>
    </div>
    <div class="portlet-body form portlet-empty">
        <form role="form" class="form-horizontal">
            <div class="form-body">
            <div class="form-group">
                <label class="col-md-2 control-label" for="remark">URL</label>
                <div class="col-md-5">
                    <input id='url' type="text" class="form-control" name="url" value=""/>
                </div>
                <div class="col-md-offset-1">
                    <button type="button" class="btn green" id="act-build">点击生成二维码</button>
                </div>
            </div>
<!--                <div class="form-group short_url" style="display: none">-->
<!--                    <label class="col-md-2 control-label" for="remark">短网址</label>-->
<!--                    <div class="col-md-5">-->
<!--                        <span class="form-control short_url_info"></span>-->
<!--                    </div>-->
<!--                </div>-->
                <div class="form-group qrcode" style="display: none">
                    <label class="col-md-2 control-label">二维码</label>
                    <div class="col-md-2">
                        <img src="" width="200px" class="qrcode_url" style="border:1px solid #e5e5e5"/>

                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    jQuery(document).ready(function () {
        $('#act-build').click(function () {
            $(".qrcode_url").attr('src','/develop/qrcode-build?url=' + encodeURIComponent($("#url").val()));
            $(".qrcode").attr("style","display:block;");

        });
    });
</script>