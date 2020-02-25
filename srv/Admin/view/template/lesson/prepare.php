<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">备课内容</div>

    </div>
    <div class="portlet-body portlet-compact">
        <div>
            <div class="datatable-hide-search">
                <table id="lesson-table" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <td>NUM</td>
                        <td>type</td>
                        <td>内容</td>
                        <td>时间</td>
                        <td></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($this->list as $item) { ?>
                        <tr>
                            <td> <?= $item['seqno'] ?> </td>
                            <td ><?= $item['type'] ?> </td>
                            <td >
                                <?php switch ($item['type']) {
                                    case 'text':
                                        echo '<textarea id="data-'.$item['seqno'].'" class="form-control" rows="2">'.$item['content'].'</textarea>';
                                        break;
                                    case 'image':
                                        echo '<img  src="'.$item['content'] .'" style="width: 250px"/>';
                                        break;
                                    case 'audio':
                                        echo '<audio src="'.$item['content'].'" controls="controls"> </audio>'.$item['note'];
                                        break;
                                    case 'video':
                                        echo '<video src="'.$item['content'].'" controls="controls" width="300px"></video>';
                                        break;
                                }?>
                            </td>
                            <td>
                               <?=$item['tms']?>
                            </td>
                            <td>
                                <?php if($item['type'] == 'text') echo '<a  href="javascript:void(0);" onclick="modifyP('.$item['seqno'].')">更改 </a>';?>
                                <a  href="javascript:void(0);" onclick="deleteP(<?=$item['seqno']?>)">删除</a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    function deleteP(cursor) {
        var sure = confirm("确定删除该条备课内容吗？");
        if(sure) {
            $.post('./prepare-delete', {
                cursor: cursor,
                lesson_sn: '<?=$this->lesson_sn?>',
            }, function (res) {
                if (res.error == '0') {
                    toastr['success']('删除成功')
                    location.reload()
                } else {
                    toastr['warning']('删除失败')
                }
            });
        }
    }
    function modifyP(cursor) {
        $.post('./prepare-modify', {
            cursor: cursor,
            lesson_sn: '<?=$this->lesson_sn?>',
            text:$("#data-"+cursor).val(),
        }, function (res) {
            if (res.error == '0') {
                toastr['success']('更改成功')
                location.reload()
            } else {
                toastr['warning']('更改失败')
            }
        });
    }
</script>


