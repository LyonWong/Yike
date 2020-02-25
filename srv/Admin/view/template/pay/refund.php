<?php namespace Admin;
/**
 * @var unitQueryRefund
 */
$query = $this->query;
?>
<div class="page-bar">
    <form class="form-inline" role="form">
        <?= wdgtPicker::dateRange($query->dateStart, $query->dateEnd) ?>
        <?= wdgtPicker::fields('用户', [
            'name' => 'userField',
            'value' => $query->userField,
            'options' => ['id' => 'ID', 'sn' => 'SN', 'name' => '名',]
        ],
            ['name' => 'userValue', 'value' => $query->userValue]
        ) ?>
        <?= wdgtPicker::fields('订单ID', [
            'name' => 'orderField',
            'value' => $query->orderField,
            'options' => ['id' => 'ID', 'sn' => 'SN']
        ],
            ['name' => 'orderValue', 'value' => $query->orderValue]
        )?>
        <?= wdgtPicker::fields('课程', [
            'name' => 'lessonField',
            'value' => $query->lessonField,
            'options' => ['id' => 'ID', 'sn' => 'SN', 'name' => '名']
        ],
            ['name' => 'lessonValue', 'value' => $query->lessonValue]
        )?>
        <?= wdgtForm::select('状态', 'status', $query->status, array_merge(['*'], $this->status_map))?>
        <?= wdgtForm::submit() ?>
    </form>
</div>
<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">退款订单列表</div>
        <div class="actions">
            <div class="input-group" style="width:300px">
                <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                <input id="filter" class="form-control" placeholder="Filter"/>
            </div>
        </div>
    </div>
    <div class="portlet-body portlet-compact">
        <div class="datatable-hide-search">
            <table class="table table-bordered table-hover" id="table-refund">
                <thead>
                <tr>
                    <td>SN</td>
                    <td>订单ID</td>
                    <td>课程</td>
                    <td>用户</td>
                    <td>金额</td>
                    <td>状态</td>
                    <td>创建时间</td>
                    <td>更新时间</td>
                    <td>结束时间</td>

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
            var table = $('#table-refund').DataTable({
                paging: true,
                info: true,
                lengthMenu: [20],
                ordering: false,
                "processing": true,
                "serverSide": true,
                "ajax": './refund-data?<?=$this->query->toString()?>'
            });
            $('#filter').keyup(function () {
                table.search($(this).val()).draw();
            });
        });
//        $(function () {
//            $('#table-refund').DataTable({
//                paging: true,
//                lengthMenu: [20],
//                info: true
//            })
//        });
    </script>

