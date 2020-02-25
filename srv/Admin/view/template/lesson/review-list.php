<?php namespace Admin; $query = $this->query?>
<div class="page-bar">
    <form class="form-inline">
        <?= wdgtPicker::dateRange($query->dateStart, $query->dateEnd) ?>
        <?= wdgtPicker::fields('讲师', [
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
        <div class="caption">课程列表</div>
        <div class="actions hide">
            <div class="input-group" style="width:300px">
                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                <input  name="email" class="form-control" placeholder="Invite User"/>
                <span class="input-group-btn" id="invite-user">
                    <button type="submit" class="btn btn-default">+</button>
                </span>
            </div>
        </div>
    </div>
    <div class="portlet-body portlet-compact">
        <div class="datatable-hide-search">
            <table id="lesson-create-review" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <td>工单Id</td>
                    <td>课程SN</td>
                    <td>讲师</td>
                    <td>标题</td>
                    <td>创建时间</td>
                    <td>备注</td>
                    <td>状态</td>
                    <td>操作</td>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <?= \view::js('table'); ?>
    <script type="text/javascript">
        $(function () {
            var table = $('#lesson-create-review').DataTable({
                paging: true,
                info: true,
                lengthMenu: [20],
                ordering: false,
                "processing": true,
                "serverSide": true,
                "ajax": './review-data?<?=$this->query->toString()?>'
            });
            $('#filter').keyup(function () {
                table.search($(this).val()).draw();
            });
        });
    </script>
