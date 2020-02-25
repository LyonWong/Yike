<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">
          <span> 设置列表 </span>
          <a href="./settings-edit" class="btn default">+新建</a>
        </div>
        <div class="actions" style="display: flex">
            <div class="input-group" style="width:300px">
                <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                <input id="filter" class="form-control" placeholder="Filter"/>
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
                        <td>类型</td>
                        <td>项目</td>
                        <td>备注</td>
                        <td>时间</td>
                        <td></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($this->list as $row) { ?>
                        <tr>
                            <td width="5%"> <?= $row['id'] ?> </td>
                            <td width="20%">
                              <?= $this->dict[$this->types[$row['i_type']]] ?>
                            </td>
                            <td>
                              <?= $row['item']?>
                            </td>
                            <td>
                              <?= $row['remark']?>
                            </td>
                            <td>
                              <?= $row['tms']?>
                            </td>
                            <td>
                              <a href="./settings-edit?id=<?=$row['id']?>">
                                编辑
                              </a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= \view::js('table'); ?>
<?=\view::js('resource/plugins/select2-3.5.1/select2')?>
<?=\view::css(['resource/plugins/select2-3.5.1/' => [
    'select2',
    'select2-bootstrap'
]])?>
<script type="text/javascript">
    $(function () {
        var table = $('#lesson-table').DataTable();
        $('#filter').keyup(function () {
            table.search($(this).val()).draw();
        });
    });
</script>



