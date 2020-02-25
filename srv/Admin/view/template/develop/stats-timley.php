<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">实时统计</div>
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
                        <th>Id</th>
                        <th>Dom</th>
                        <th>Idx</th>
                        <th>Val</th>
                        <th>Tms</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($this->data as $item) { ?>
                        <tr>
                            <td><?=$item['id']?></td>
                            <td><?=$item['dom']?></td>
                            <td><?=$item['idx']?></td>
                            <td><?=$item['val']?></td>
                            <td><?=$item['tms']?></td>
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
        Table.filter('#table-trigger-list');
    });
</script>
