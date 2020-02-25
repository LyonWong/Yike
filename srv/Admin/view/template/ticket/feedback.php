<?php namespace Admin; $query = $this->query?>
<div class="page-bar">
    <form class="form-inline">
        <?= wdgtPicker::dateRange($query->dateStart, $query->dateEnd) ?>
        <?= wdgtPicker::fields('用户', [
            'name' => 'userField',
            'value' => $query->userField,
            'options' => ['id' => 'ID', 'sn' => 'SN', 'name' => '名',]
        ],
            ['name' => 'userValue', 'value' => $query->userValue]
        ) ?>
        <?= wdgtForm::select('状态', 'status', $query->status, array_merge($this->status_map,['*']))?>
        <?= wdgtForm::submit() ?>
    </form>
</div>
<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">反馈列表</div>
        <div class="actions hide">
            <div class="input-group" style="width:300px">
                <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                <input id="filter" class="form-control" placeholder="Filter"/>
            </div>
        </div>
    </div>
    <div class="portlet-body portlet-compact">
        <div class="datatable-hide-search">
            <table id="feedback-table" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <td>ID</td>
                    <td>用户</td>
                    <td>描述</td>
                    <td>图片</td>
                    <td>备注</td>
                    <td>创建时间</td>
                    <td>状态</td>
                    <th>处理</th>

                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="text-align: center">
        <div class="modal-dialog" role="document" style="display: inline-block; width: auto;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <img src="" id="image_b">
                </div>
            </div>
        </div>
    </div>
    <?= \view::js('table'); ?>
    <script type="text/javascript">
        $('#myModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var recipient = button.data('imgsrc');
            console.log(button);
            document.getElementById("image_b").src = recipient;
        });
        $(function () {
            var table = $('#feedback-table').DataTable({
                paging: true,
                info: true,
                lengthMenu: [20],
                ordering: false,
                "processing": true,
                "serverSide": true,
                "ajax": './feedback-data?<?=$this->query->toString()?>'
            });
            $('#filter').keyup(function () {
                table.search($(this).val()).draw();
            });
        });

        function closeFeedBack(id) {
            remark = prompt('反馈内容');
            if(remark == null) {
                return false;
            }
            send = confirm('是否向用户发送反馈消息？');
            if(!send) {
                send = 0;
            } else {
                send = 1;
            }
            $.post('./feedback-close', {
                ticket_id: id,
                remark:remark,
                send:send
            }, function () {
                location.reload()
            })
        }

    </script>
