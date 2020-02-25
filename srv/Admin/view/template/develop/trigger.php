<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">事件触发</div>
        <div class="actions">
            <div class="input-group" style="width:300px">
                <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                <input id="filter" class="form-control" placeholder="Filter"/>
            </div>
        </div>
    </div>
    <div class="portlet-body portlet-compact">
        <div>
            <div class="datatable-hide-search">
                <table id="table-trigger-list" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>Tag</th>
                        <th>Args</th>
                        <th
                        <th>TTL</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($this->list as $item) { ?>
                        <tr>
                            <td><?=$item['tag']?></td>
                            <td>
                                <ul>

                                <?php foreach ($item['args'] as $key => $val) { ?>
                                    <li><?=$key?>=<?=$val?> | <?=$item['info'][$key]?></li>
                                <?php } ?>
                                </ul>
                            </td>
                            <td><?=$item['ttl']?></td>
                            <td data-key="<?=$item['key']?>">
                                <a class="ignite">触发</a>
                                <a class="delete">清除</a>
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
<script type="text/javascript">
    $(function () {
        var table = $('#table-trigger-list').DataTable();
        $('#filter').keyup(function () {
            table.search($(this).val()).draw();
        });
        $('.ignite').click(function(){
            var self = $(this);
            $.post('./trigger-ignite', {
                key: self.parent().data('key')
            }, function(res){
                if (res.error == 0) {
                    toastr['success']('Ignited successfully.');
                    self.parents('tr').remove()
                } else {
                    toastr['warning']('Ignite failed: ' + res.message);
                }
                console.log(res)
            })
        });
        $('.delete').click(function(){
            var self = $(this);
            $.post('./trigger-delete', {
                key: self.parent().data('key')
            }, function(res){
                if (res.error == 0) {
                    toastr['success']('Deleted successfully.');
                    self.parents('tr').remove()
                } else {
                    toastr['warning']('Ignited failed: ' + res.message);
                }
                console.log(res)
            })
        });
    });
</script>
