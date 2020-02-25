<?php namespace Admin;
/**
 * @var unitQueryMoney
 */
$query = $this->query;
?>
<div class="page-bar">
    <form class="form-inline" role="form">
        <?= wdgtPicker::dateRange($query->dateStart, $query->dateEnd) ?>
        <?= wdgtForm::input('用户ID', 'uid', $query->uid, '用户ID')?>
        <?= wdgtForm::select('收支', 'item', $query->item, array_merge(['*'], $this->item_map))?>

        <?= wdgtForm::submit() ?>
    </form>
</div>
<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">余额详情列表</div>
        <div class="actions">
            <div class="input-group" style="width:300px">
                <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                <input id="filter" class="form-control" placeholder="Filter"/>
            </div>
        </div>
    </div>
    <div class="portlet-body portlet-compact">
        <div class="datatable-hide-search">
            <table class="table table-bordered table-hover" id="table-detail-list">
                <thead>
                <tr>
                    <td>ID</td>
                    <td>类型</td>
                    <td>金额</td>
                    <td>余额</td>
                    <td>相关</td>
                    <td>时间</td>

                </tr>
                </thead>
                <tbody>
                <?php foreach ($this->list as $item) { ?>
                    <tr>
                        <td><?= $item['id'] ?></td>
                        <td><?= wdgtLang::dict($this->item_map[$item['i_item']]) ?></td>
                        <td><?= $item['amount'] ?></td>
                        <td><?= $item['balance'] ?></td>
                        <td>
                            <?= $item['args']['info'] ?  'info:'.$item['args']['info'] : '订单id:'.$item['args']['order_id']?></td>
                        <td><?= $item['tms']?></td>

                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?= \view::js('table'); ?>
    <script type="text/javascript">
        $(function () {
            $('#table-detail-list').DataTable({
                paging: true,
                lengthMenu: [20],
                info: true
            });
            var table = $('#table-detail-list').DataTable();
            $('#filter').keyup(function () {
                table.search($(this).val()).draw();
            });
        });
    </script>

