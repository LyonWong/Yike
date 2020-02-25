<?php namespace Admin;
/**
 * @var unitQuerySeries
 */
$query = $this->query;
?>
<div class="page-bar">
    <form class="form-inline" role="form">
        <?= wdgtPicker::dateRange($query->dateStart, $query->dateEnd) ?>
        <?= wdgtPicker::fields('讲师', [
            'name' => 'userField',
            'value' => $query->userField,
            'options' => ['id' => 'ID', 'sn' => 'SN', 'name' => '名',]
        ],
            ['name' => 'userValue', 'value' => $query->userValue]
        ) ?>
        <?= wdgtPicker::fields('系列课', [
            'name' => 'seriesField',
            'value' => $query->seriesField,
            'options' => ['id' => 'ID', 'sn' => 'SN', 'name' => '名']
        ],

            ['name' => 'seriesValue', 'value' => $query->seriesValue]
        )?>
        <?= wdgtForm::submit() ?>
    </form>
</div>
<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">系列课列表</div>
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
            <table id="series-table" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <td>ID</td>
                    <td>SN</td>
                    <td>讲师</td>
                    <td>标题</td>
                    <td>创建时间</td>
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

            var table = $('#series-table').DataTable({
                paging: true,
                info: true,
                lengthMenu: [20],
                ordering: false,
                "processing": true,
                "serverSide": true,
                "ajax": '/lesson/series-data?<?=$this->query->toString()?>'
            });
//            $('#filter').keyup(function () {
//                table.search($(this).val()).draw();
//            });
        });

    </script>
