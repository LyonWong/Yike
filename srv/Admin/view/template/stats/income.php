<?php namespace Admin;
$groups = [
    'date' => '日期',
    'lesson' => '课程',
];
$total = $this->data['TOTAL'];
?>

<div class="page-bar ">
    <form class="form-inline" role="form">
        <?= wdgtForm::select('聚合', 'groupBy', $this->query['groupBy'], array_keys($groups))?>
        <?= wdgtPicker::dateRange($this->query['dateStart'], $this->query['dateEnd']) ?>
        <?= wdgtForm::input('TSN', 'tsn', $this->query['tsn'])?>
        <?= wdgtForm::submit() ?>
    </form>
</div>

<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">收入统计</div>
    </div>
    <div class="portlet-body portlet-compact">
      <div class="note note-info">
        <ul>
          <li>用户报名费 = <?=$total['order_amount']?></li>
          <li>讲师分成 = <?=$total['payoff_amount']?></li>
          <li>平台技术服务费 = <?=$total['service_fee']?></li>
          <li>微信支付手续费 = <?=$total['weixin_fee']?></li>
          <li>平台渠道分成 = <?=$total['share_amount']?></li>
          <li>分销渠道佣金 = <?=$total['commission']?></li>
          <li>平台服务费 = 平台技术服务费 + 微信支付手续费 = <?=($total['service_fee'] + $total['weixin_fee'])?></li>
          <li>平台分成 = 平台渠道分成 + 分销渠道分成 = <?=($total['share_amount'] + $total['commission'])?></li>
          <li>平台收入 = 平台服务费 + 平台分成 = <?=($total['order_amount'] - $total['payoff_amount'])?></li>
          <li>平台毛利 = 平台收入 - 微信支付手续费 - 分销渠道佣金 = <?=($total['service_fee'] + $total['share_amount'])?></li>
        </ul>
      </div>

      <div class="table-scrollable">
            <table class="table table-bordered table-hover datatable">
                <thead>
                <tr>
                    <th><?=$groups[$this->query['groupBy']]?></th>
                    <th>报名费</th>
                    <th>讲师分成</th>
                    <th>平台技术服务费</th>
                    <th>微信支付手续费</th>
                    <th>平台渠道分成</th>
                    <th>分销渠道佣金</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($this->data as $key => $item) {?>
                    <tr>
                        <td><?=$item['KEY']??'TOTAL'?></td>
                        <td><?=$item['order_amount']?></td>
                        <td><?=$item['payoff_amount']?></td>
                        <td><?=$item['service_fee']?></td>
                        <td><?=$item['weixin_fee']?></td>
                        <td><?=$item['share_amount']?></td>
                        <td><?=$item['commission']?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
