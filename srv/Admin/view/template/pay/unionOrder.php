<?php namespace Admin;
/**
 * @var unitQueryUnionOrder
 */
use Core\library\Http;

$query = $this->query;
?>
<style>
  tbody td {
    white-space: nowrap;
  }
</style>
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
        <?= wdgtForm::select('状态', 'status', $query->status, array_merge(['*'], $this->status_map))?>
        <?= wdgtForm::input('联合订单号', 'unionOrderSn', $query->unionOrderSn, '联合订单号')?>

        <?= wdgtForm::input('支付单号', 'paySn', $query->paySn, '第三方支付单')?>
        <?= wdgtForm::submit() ?>
    </form>
</div>

<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">联合订单列表</div>
        <div class="actions">
            <div class="input-group hide" style="width:300px">
                <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                <input id="filter" class="form-control" placeholder="Filter"/>
            </div>
          <a href="<?=Http::makeURL('./unionOrder-export.csv', $_GET)?>" class="btn btn-default" target="_blank">导出CSV</a>
        </div>
    </div>
    <div class="portlet-body portlet-compact">
        <div>
            <div class="datatable-hide-search">
                <table id="table-order" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <td>ID</td>
                        <td>SN</td>
                        <td>订单类型</td>
                        <td>用户</td>
                        <td>订单金额</td>
                        <td>订单来源</td>
                        <td>支付状态</td>
                        <td>全系列单课总价(勾选)</td>
                        <td>全系列打包价</td>
                        <td>订单单课总价</td>
                        <td>订单加权价格</td>
                        <td>优惠抵扣价</td>
                        <td>代金券抵扣</td>
                        <td>余额抵扣</td>
                        <td>优惠券</td>
                        <td>支付方式</td>
                        <td>支付单号</td>
                        <td>支付金额</td>
                        <td>系列课SN</td>
                        <td>创建时间</td>
                        <td>子课程ID</td>
                        <td>子订单ID</td>
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
            "ajax": './unionOrder-data?<?=$this->query->toString()?>'
        });
        $('#filter').keyup(function () {
            table.search($(this).val()).draw();
        });
    });

</script>

