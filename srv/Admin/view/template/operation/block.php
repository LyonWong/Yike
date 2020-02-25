<?php namespace Admin; ?>
<div class="portlet box blue">
  <div class="portlet-title">
    <div class="caption">版块分类</div>
    <div class="actions">
<!--      <a href="#" class="btn btn-default btn-release" data-mode="server" data-toggle="modal" data-target="#myModal">创建优惠券</a>-->
    </div>
  </div>
  <div class="portlet-body portlet-compact">
    <div>
      <div class="datatable-hide-search">
        <table id="table-promote-rule" class="table table-bordered table-hover">
          <thead>
          <tr>
            <th>ID</th>
            <th>KEY</th>
            <th>名称</th>
            <th>类型</th>
            <th>权重</th>
            <th></th>
          </tr>
          </thead>
          <tbody>
          <tr>
            <form method="post" action="./block-create">
              <td></td>
              <td><input name="key"/></td>
              <td><input name="name"/></td>
              <td>
                <select name="type">
                    <?=wdgtForm::options(array_combine($this->types, $this->types), 'default')?>
                </select>
              </td>
              <td><input name="weight" type="number"/></td>
              <td><input type="submit"></td>
            </form>
          </tr>
          <?php foreach ($this->data as $row) { ?>
            <tr>
              <form method="post" action="./block-modify">

              <td><?= $row['id'] ?></td>
              <td><input name='key' value="<?= $row['key'] ?>"/></td>
              <td><input name='name' value="<?= $row['name'] ?>"/></td>
              <td>
                <select name="type" value="<?=$row['type']?>">
                  <?= wdgtForm::options(array_combine($this->types, $this->types), $row['type'])?>
                </select>
              </td>
              <td><input name='weight' value="<?= $row['weight'] ?>"/></td>
              <td>
                <input class="btn btn-default" type="submit" value="修改"/>
                <?php if ($row['type'] == 'home') {?>
<!--                    <button>首页配置</button>-->
                  <a href="#" class="btn btn-default btn-release" data-mode="server" data-toggle="modal"
                     data-target="#modal-home-edit" data-extra='<?=json_encode($row['extra'])?>' data-key="<?=$row['key']?>">
                    配置
                  </a>
                <?php } ?>
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

<div class="modal fade" id="modal-home-edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">首页编辑</h4>
      </div>
      <div class="modal-body">
        <form>
          <input type="hidden" id="home-key"/>
          <div class="form-group">
            <label for="recipient-name" class="control-label">form</label>
            <input type="text" class="form-control input-inline" id="home-form" placeholder="展示形式:banner|block">
          </div>
          <label>list<i class="btn fa fa-plus" id="add-home-list"></i></label>
          <div class="form-group" id="home-list">
          </div>

        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-primary" id="save-home-extra">保存</button>
      </div>

    </div>
  </div>
</div>

<script type="text/javascript">
  $('#modal-home-edit').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var modal = $(this);
    var extra = button.data('extra');
    $('#home-key').val(button.data('key'));
    $('#home-form').val(extra.form);
    var homeList = $('#home-list')
    extra.list = extra.list || []
    var html = '';
    for (var item of extra.list) {
      html += ' <div class="input-group">' +
        '<input type="text" class="form-control home-list-item" value="'+ item +'">' +
        '<span class="input-group-addon">'+extra.name[item]+'</span>'+
        '<span class="btn input-group-addon add-home-item"> <i class="fa fa-plus"></i></span>' +
        '<span class="btn input-group-addon del-home-item"> <i class="fa fa-minus"></i></span>' +
        '</div>';
    }
    homeList.html(html)
  });
  $('#save-home-extra').click(function () {
    var extra = {
      form: $('#home-form').val(),
      list: []
    };
    $('.home-list-item').each(function(i,v){
      if (v.value) {
        extra.list.push(v.value)
      }
    });
    var data = {
      key: $('#home-key').val(),
      extra: JSON.stringify(extra)
    };
    $.post('./block-modify', data, function() {
      location.reload()
    });
  });
  $('#add-home-list').click(function(){
    $('#home-list').prepend(' <div class="input-group">' +
      '<input type="text" class="form-control home-list-item" placeholder="添加课程或系列课SN">'+
      '<span class="btn input-group-addon add-home-item"> <i class="fa fa-plus"></i></span>'+
      '<span class="btn input-group-addon del-home-item"> <i class="fa fa-minus"></i></span></div>'
    )
  });
  $('#home-list').on('click', '.add-home-item', function() {
    $(this).parent().after(' <div class="input-group">' +
      '<input type="text" class="form-control home-list-item" placeholder="添加课程或系列课SN">'+
      '<span class="btn input-group-addon add-home-item"> <i class="fa fa-plus"></i></span>'+
      '<span class="btn input-group-addon del-home-item"> <i class="fa fa-minus"></i></span></div>'
    )
  });
  $('#home-list').on('click', '.del-home-item', function() {
    $(this).parent().remove();
  })
</script>

<style>
  .home-list-item {
    font-family: monospace;
  }
</style>
