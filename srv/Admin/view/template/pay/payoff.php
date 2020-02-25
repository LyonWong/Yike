<?php namespace Admin;
/**
 * @var unitQueryOrder
 */
$query = $this->query;
?>

<div class="page-bar">
  <form class="form-inline">
      <?= wdgtForm::input('订单ID', 'orderId', $query['orderId'])?>
      <?= wdgtForm::input('用户ID', 'uid', $query['uid'])?>
      <?= wdgtForm::submit() ?>
  </form>
</div>

<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">分成金额</div>
        <div class="actions">
            <div class="input-group" style="width:300px">
                <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                <input id="filter" class="form-control" placeholder="Filter"/>
            </div>
        </div>
    </div>
    <div class="portlet-body portlet-compact">
        <div class="datatable-hide-search">
            <table class="table table-bordered table-hover" id="table-order">
                <thead>
                <tr>
                    <th>订单ID</th>
                    <th>订单状态</th>
                    <th>用户</th>
                    <th>项目</th>
                    <th>金额</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($this->list as $item) { ?>
                    <tr>
                        <td><?=$item['order_id']?></td>
                        <td><?= wdgtLang::dict($item['status'])?></td>
                        <td><?=$item['uid']?>#<?=$item['user']['name']?></td>
                        <td><?= wdgtLang::dict($item['item'])?></td>
                        <td><?=$item['amount']/100?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= \view::js('table'); ?>
<script type="text/javascript">
    $(function () {
        Table.filter('#table-order',{
            paging: true,
            lengthMenu: [20],
            info: true
        })
    });
</script>

