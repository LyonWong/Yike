<?php $profiles = $this->profiles;
$data = $this->data ?>
<div class="portlet box blue">
  <div class="portlet-title">
    <div class="caption">
      <a href="./lesson">课程统计</a> - 来源 |
        <?php
        $crumbs = [];
        foreach ($this->tier as $item) {
            $crumbs[] = "<a href='./lesson-origin?lesson_sn={$this->query['lesson_sn']}&origin_id={$item['id']}'>$item[name]</a>";
        }
        echo implode(' > ', $crumbs);
        ?>
    </div>
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
          <th>渠道</th>
          <th>报名人数</th>
          <th>听课人数</th>
          <th>退款人数</th>
          <th>评价人数</th>
          <th>课程评分</th>
          <th>课程收入</th>
          <th>分成收入</th>
        </tr>
        </thead>
        <tbody>
        <tr>
          <td><?= $data['total']['origin']['name'] ?></td>
          <td><?= $data['total']['data']['lesson.enroll.unique'] ?? null ?></td>
          <td><?= $data['total']['data']['lesson.access.unique'] ?? null ?></td>
          <td><?= $data['total']['data']['lesson.refund.unique'] ?? null ?></td>
          <td><?= $data['total']['data']['lesson.rate.count'] ?? null ?></td>
          <td><?= $data['total']['data']['lesson.rate.avg'] ?? null ?></td>
          <td><?= $data['total']['data']['lesson.income.sum'] ?? null ?></td>
          <td><?= $data['total']['data']['lesson.payoff.sum'] ?? null ?></td>
        </tr>
        <?php foreach ($data['subs'] as $id => $item) { ?>
          <tr>
            <td>├
              <a
                href="./lesson-origin?<?= "lesson_sn={$this->query['lesson_sn']}&origin_id={$item['origin']['id']}" ?>">
                  <?= $item['origin']['name'] ?>
              </a>
            </td>
            <td><?= $item['data']['lesson.enroll.unique'] ?? null ?></td>
            <td><?= $item['data']['lesson.access.unique'] ?? null ?></td>
            <td><?= $item['data']['lesson.refund.unique'] ?? null ?></td>
            <td><?= $item['data']['lesson.rate.count'] ?? null ?></td>
            <td><?= $item['data']['lesson.rate.avg'] ?? null ?></td>
            <td><?= $item['data']['lesson.income.sum'] ?? null ?></td>
            <td><?= $item['data']['lesson.payoff.sum'] ?? null ?></td>
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
    var table = $('#datatable').DataTable({
      "order": [[1, "desc"]]
    });
    $('#filter').keyup(function () {
      table.search($(this).val()).draw();
    });
  });
</script>


