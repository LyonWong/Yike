<div class="portlet box blue util-btn-group-margin-bottom-5">
    <div class="portlet-title">
        <div class="caption">
            <div class="caption">课程配置</div>
        </div>
    </div>
    <div class="portlet-body form portlet-empty">
        <form role="form" class="form-horizontal">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-2 control-label" for="remark">菜单配置</label>
                    <div class="col-md-8">
                        <textarea class="form-control" warp="virtual"  id="conf" rows="15"></textarea>
                    </div>
                </div>
                <div class="col-md-offset-2">
                    <button type="button" class="btn green" id="conf_submit">保存</button>
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
                <div class="form-group">
                    <label class="col-md-2 control-label" for="remark">活动配置解析</label>
                    <div class="col-md-8">
                        <textarea class="form-control" warp="virtual"  id="activity_conf" rows="20" readonly>
                配置内容是一个json格式。
                data里面为卡片配置，每一张都是单独配置；
                 /*
                 * 0-背景图   图片名  位置(0,0) 图片尺寸
                 *
                 * 1-图片  图片名  位置 图片尺寸
                 * 1.1 头像
                 * 1.2 二维码
                 *
                 * 2-文字（文字一般放最后）内容  大小  颜色 位置 对齐方式(left center right)
                 * 2.1 title
                 * 2.2 昵称
                 */
                 menu_icon为滚动按钮小图标配置，有几张就配置几个，如：
                 "menu_icon": [
                    "qqq.png",
                    "qqq.png"
                 ],。
 事例如下：
{
  "data": [
    [
      [
        0,
        "qukuailian.5.jpg",
        0,
        0,
        640,
        1008
      ],
      [
        1.1,
        "",
        65,
        820,
        90,
        90
      ],
      [
        1.2,
        "",
        463,
        800,
        116,
        116
      ],
      [
        2.2,
        "",
        25,
        "#333",
        210,
        860,
        "left"
      ]
    ],
    [
      [
        0,
        "qukuailian.4.jpg",
        0,
        0,
        640,
        1008
      ],
      [
        1.1,
        "",
        65,
        820,
        90,
        90
      ],
      [
        1.2,
        "",
        463,
        800,
        116,
        116
      ],
      [
        2.2,
        "",
        25,
        "#333",
        214,
        860,
        "left"
      ]
    ]
  ],
  "menu_icon": [
    "qqq.png",
    "qqq.png"
  ],
  "discountRatio": 0,
  "commissionRatio": 0.3
}
                        </textarea>
                    </div>
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

        $('#conf_submit').click(function(){
            $.post('./detail-conf', {
                conf: $("#conf").val(),
                lesson_sn:'<?=$this->lesson_sn?>'
            }, function(res){
                if (res.error == '0') {
                    toastr['success']('修改成功')
                } else {
                    toastr['warning'](res.message)
                }
            })
        });

        $('#activity_submit').click(function(){
            $.post('./detail-activityConf', {
                conf: $("#activity_conf").val(),
                lesson_sn:'<?=$this->lesson_sn?>'
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