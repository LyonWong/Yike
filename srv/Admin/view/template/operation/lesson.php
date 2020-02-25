<?php namespace Admin; ?>
<div class="portlet box blue">
  <div class="portlet-title">
    <div class="caption">课程管理</div>
    <div class="actions">
    </div>
  </div>
  <div class="portlet-body portlet-compact">
    <div>
      <div class="datatable-hide-search">
        <table id="table-promote-rule" class="table table-bordered table-hover">
          <thead>
          <tr>
            <th>ID</th>
            <th>TSN</th>
            <th>标题</th>
            <th>标签</th>
            <th>权重</th>
            <th>时间</th>
            <th></th>
          </tr>
          </thead>
          <tbody>
          <tr>
            <form method="post" action="./lesson-create">
              <td></td>
              <td><input name="tsn"/></td>
              <td></td>
              <td>
                <input name="tag"/>
              </td>
              <td><input name="weight" type="number"/></td>
              <td></td>
              <td><input type="submit"></td>
            </form>
          </tr>
          <?php foreach ($this->data as $row) { ?>
            <tr>
              <form method="post" action="./lesson-modify">
              <td><?= $row['id'] ?></td>
              <td><input name="tsn" value="<?= $row['tsn'] ?>"/></td>
              <td><?= $row['title'] ?></td>
              <td><input name="tag" value="<?= $row['tag'] ?>"/></td>
              <td><input name="weight" value="<?= $row['weight'] ?>"/></td>
              <td><?= $row['tms'] ?></td>
              <td>
                <input class="btn btn-default" type="submit" value="修改"/>
              </td>
              </form>
            </tr>
          <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
