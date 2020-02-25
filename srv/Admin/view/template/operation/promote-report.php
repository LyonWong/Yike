<?php namespace Admin;
$query = $this->query;
?>

<style>
  .avatar {
    width: 20px;
    height: 20px;
  }
</style>

<div class="page-bar">
    <form class="form-inline">
        <?= wdgtForm::input('PSN', 'psn', $query['psn'])?>
        <?= wdgtForm::submit() ?>
    </form>
</div>

<div class="portlet box blue">
  <div class="portlet-title">
    <div class="caption">优惠券使用情况 [ <?=implode('->', array_column($this->origin, 'name'))?> ]</div>
  </div>
  <div class="portlet-body portlet-compact">
    <div>
      <div class="datatable-hide-search">
        <table id="table-promote-rule" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>用户</th>
              <th>领券</th>
              <th>报名</th>
              <th>听课</th>
              <th>退款</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>总计</td>
              <td><?=$this->total['receive']??null?></td>
              <td><?=$this->total['enroll']??null?></td>
              <td><?=$this->total['access']??null?></td>
              <td><?=$this->total['refund']??null?></td>
            </tr>
          <?php foreach ($this->data as $row) { ?>
              <tr>
                <td>
                  <img class='avatar' src="<?=$row['user']['avatar']?>"/>
                  <a href="/user/detail?usn=<?=$row['user']['sn']?>"><?=$row['user']['name']?></a>
                </td>
                <td><?=($row['receive']['args']['ip']??null).'@'.$row['receive']['tms']?></td>
                <td><?=$row['enroll']['tms']?></td>
                <td><?=$row['access']['tms']?></td>
                <td><?=$row['refund']['tms']?></td>
              </tr>
          <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


