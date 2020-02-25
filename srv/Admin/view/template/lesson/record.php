<div class="portlet box blue">
  <div class="portlet-title">
    <div class="caption">课程内容</div>
    <div class="actions">
      <div class="btn-group">
        <a class="btn btn-default btn-sm dropdown-toggle" href="#" data-toggle="dropdown" data-hover="dropdown"
           data-close-others="true" aria-expanded="false">
          新建 <i class="fa fa-angle-down"></i>
        </a>
        <ul class="dropdown-menu pull-right">
          <li>
            <a href="./record-edit?form=view&type=markdown&lesson_sn=<?=$this->lessonSn?>" target="_blank">markdown</a>
            <a href="./record-edit?form=view&type=image&lesson_sn=<?=$this->lessonSn?>" target="_blank">Image</a>
            <a href="./record-edit?form=view&type=audio&lesson_sn=<?=$this->lessonSn?>" target="_blank">Audio</a>
            <a href="./record-edit?form=view&type=video&lesson_sn=<?=$this->lessonSn?>" target="_blank">Video</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="portlet-body portlet-compact">
    <div>
      <div class="datatable-hide-search">
        <table id="lesson-table" class="table table-bordered table-hover">
          <thead>
          <tr>
            <td>ID</td>
            <td>形式</td>
            <td>来自</td>
            <td>内容</td>
            <td>时间</td>
            <td></td>
          </tr>
          </thead>
          <tbody>
          <?php foreach ($this->list as $item) { ?>
            <tr>
              <td> <?= $item['id'] ?> </td>
              <td> <?= $this->types[$item['i_type']] ?> </td>
              <td><a href="/teacher/detail?tuid=<?= $item['from_uid'] ?>"
                     target="_blank"><?= $item['user']['name'] ?> </a></td>
              <td>
                <textarea class="form-control" id="data-<?= $item['id'] ?>" rows="4"><?= $item['content'] ?></textarea>
              </td>
              <td>
                  <?= $item['tms'] ?>
              </td>
              <td>
                <a href="./record-edit?id=<?=$item['id']?>&lesson_sn=<?=$this->lessonSn?>" target="_blank">编辑</a>
                <a href="javascript:void(0);" onclick="modifyR(<?= $item['id'] ?>)">更改</a>
                <a href="javascript:void(0);" onclick="deleteR(<?= $item['id'] ?>)">删除</a>
              </td>
            </tr>
          <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">

  function deleteR(recordId) {
    var sure = confirm("确定删除该条课程内容吗？");
    if (sure) {
      $.post('./record-delete', {
        recordId: recordId,
      }, function (res) {
        if (res.error == '0') {
          toastr['success']('删除成功')
          location.reload()
        } else {
          toastr['warning']('删除失败')
        }
      });
    }
  }

  function modifyR(recordId) {
    $.post('./record-modify', {
      recordId: recordId,
      content: $("#data-" + recordId).val(),
    }, function (res) {
      if (res.error == '0') {
        toastr['success']('更改成功')
        location.reload()
      } else {
        toastr['warning']('更改失败')
      }
    });
  }
</script>


