<?php namespace Admin;
/**
 * @var unitQueryOrder
 */
use Core\library\Http;

$query = $this->query;
?>
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
        <?= wdgtPicker::fields('课程', [
                'name' => 'lessonField',
            'value' => $query->lessonField,
            'options' => ['id' => 'ID', 'sn' => 'SN', 'name' => '名']
        ],
            ['name' => 'lessonValue', 'value' => $query->lessonValue]
        )?>
        <?= wdgtForm::select('状态', 'status', $query->status, array_merge(['*'], $this->status_map))?>
        <?= wdgtForm::input('支付单号', 'paySn', $query->paySn, '第三方支付单')?>
        <?= wdgtForm::submit() ?>
    </form>
</div>

<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">订单列表</div>
        <div class="actions">
            <div class="input-group hide" style="width:300px">
                <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                <input id="filter" class="form-control" placeholder="Filter"/>
            </div>
          <a href="<?=Http::makeURL('./order-export.csv', $_GET)?>" class="btn btn-default" target="_blank">导出CSV</a>
        </div>
    </div>
    <div class="portlet-body portlet-compact">
        <div>
            <div class="datatable-hide-search">
                <table id="table-order" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <td>SN</td>
                        <td>订单类型</td>
                        <td>课程</td>
                        <td>用户</td>
                        <td>订单金额</td>
                        <td>订单来源</td>
                        <td>支付方式</td>
                        <td>支付单号</td>
                        <td>支付金额</td>
                        <td>支付状态</td>
                        <td>讲师分成</td>
                        <td>创建时间</td>
                        <td>操作</td>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= \view::js('table'); ?>
<script type="text/javascript">
    $(function () {
        var table = $('#table-order').DataTable({
            paging: true,
            info: true,
            lengthMenu: [20],
            ordering: false,
            "processing": true,
            "serverSide": true,
            "ajax": './order-data?<?=$this->query->toString()?>'
        });
        $('#filter').keyup(function () {
            table.search($(this).val()).draw();
        });
    });

//    $(function () {
//        Table.filter('#table-order',{
//            paging: true,
//            lengthMenu: [20],
//            info: true
//        })
//    });
</script>

