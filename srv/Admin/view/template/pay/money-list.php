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
        <?= wdgtForm::submit() ?>
    </form>
</div>
<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">用户余额列表</div>
        <div class="actions">
            <div class="input-group" style="width:300px">
                <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                <input id="filter" class="form-control" placeholder="Filter"/>
            </div>
        </div>
    </div>

    <div class="portlet-body portlet-compact">
        <div class="datatable-hide-search">
            <table class="table table-bordered table-hover" id="table-money-list">
                <thead>
                <tr>
                    <td>ID</td>
                    <td>用户</td>
                    <td>当前余额</td>
                    <td>总收入</td>
                    <td>总支出</td>
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
            var table = $('#table-money-list').DataTable({
                paging: true,
                info: true,
                lengthMenu: [20],
                ordering: false,
                "processing": true,
                "serverSide": true,
                "ajax": './money-data?<?=$this->query->toString()?>'
            });
            $('#filter').keyup(function () {
                table.search($(this).val()).draw();
            });
        });
    </script>

