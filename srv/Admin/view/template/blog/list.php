<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">
          <span> 文章列表 </span>
          <a href="./edit" class="btn default">+新建</a>
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
                        <td>SN</td>
                        <td>标题</td>
                        <td>分类</td>
                        <td>权重</td>
                        <td>创建时间</td>
                        <td>更新时间</td>
                        <td></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($this->list as $item) { ?>
                        <tr>
                            <td width="5%"> <?= $item['id'] ?> </td>
                          <td width="5%"> <?= $item['sn'] ?> </td>
                            <td width="20%">
                              <?= $item['title'] ?>
                            </td>
                            <td>
                              <?= $this->category[$item['category']]??$item['category']?>
                            </td>
                            <td>
                              <?= $item['weight']?>
                            </td>
                            <td>
                              <?= $item['tms_create']?>
                            </td>
                            <td>
                              <?= $item['tms_update']?>
                            </td>
                            <td>
                              <a href="./edit?id=<?=$item['id']?>">
                                编辑
                              </a>
                              <a href="<?="$this->preview/blog-view-$item[sn]"?>" target="_blank">
                                预览
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



