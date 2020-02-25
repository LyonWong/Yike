<?php $profiles = $this->profiles; ?>
<div class="portlet box blue">
  <div class="portlet-title">
    <div class="caption">课程统计</div>
    <div class="actions">
      <div class="input-group" style="width:300px">
        <span class="input-group-addon"><i class="fa fa-filter"></i></span>
        <input id="filter" class="form-control" placeholder="Filter"/>
      </div>
    </div>
  </div>
  <div class="portlet-body portlet-compact">
    <div class="table-scrollable">
      <table class="table table-bordered table-hover" id="datatable">
        <thead>
        <tr>
          <th>SN</th>
          <th>讲师</th>
          <th>标题</th>
          <th>报名人数</th>
          <th>听课人数</th>
          <th>退款人数</th>
          <th>评价人数</th>
          <th>课程评分</th>
          <th>课程收入</th>
          <th>分成收入</th>
          <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($this->stats as $id => $item) { ?>
          <tr>
            <td><?= $profiles[$id]['sn']?></td>
            <td><?= $profiles[$id]['teacher']['name'] ?></td>
            <td style="max-width: 100px; overflow: hidden" title="<?= $profiles[$id]['title'] ?>">
                <?= $profiles[$id]['title'] ?>
            </td>
            <td><?= $item['lesson.enroll.unique'] ?? null ?></td>
            <td><?= $item['lesson.access.unique'] ?? null ?></td>
            <td><?= $item['lesson.refund.unique'] ?? null ?></td>
            <td><?= $item['lesson.rate.count'] ?? null ?></td>
            <td><?= $item['lesson.rate.avg'] ?? null ?></td>
            <td><?= $item['lesson.income.sum'] ?? null ?></td>
            <td><?= $item['lesson.payoff.sum'] ?? null ?></td>
            <td>
              <a href="./lesson-origin?lesson_sn=<?= $profiles[$id]['sn'] ?>">
                来源
              </a>
            </td>
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
        var table = $('#datatable').DataTable();
        $('#filter').keyup(function () {
            table.search($(this).val()).draw();
        });
    });
</script>
