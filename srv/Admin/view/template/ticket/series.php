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
        <div class="caption">系列课工单</div>
        <div class="actions hide">
            <div class="input-group" style="width:300px">
                <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                <input id="filter" class="form-control" placeholder="Filter"/>
            </div>
        </div>
    </div>
    <div class="portlet-body portlet-compact">
        <div class="datatable-hide-search">
            <table id="lesson-table" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>工单Id</th>
                    <th>系列课</th>
                    <th>讲师</th>
                    <th>提交时间</th>
                    <th>备注</th>
                    <th>状态</th>
                    <th>处理</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= \view::js('table'); ?>
<script type="text/javascript">
    $(function () {
        var table = $('#lesson-table').DataTable({
            paging: true,
            info: true,
            lengthMenu: [20],
            ordering: false,
            "processing": true,
            "serverSide": true,
            "ajax": './series-data?<?=$this->query->toString()?>'
        });
        $('#filter').keyup(function () {
            table.search($(this).val()).draw();
        });
    });
</script>




