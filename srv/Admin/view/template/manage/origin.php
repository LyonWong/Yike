<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">渠道来源 |
            <?php
            $tier = [];
            foreach ($this->tier as $i => $item) {
                if ($i == 0) {
                    $tier = ['key'=>[], 'name'=>[], 'crumbs' => []];
                } else {
                    $tier['key'][] = $item['key'];
                    $tier['name'][] = $item['name'];
                }
                $tier['crumbs'][] = "<a href='./origin?oid={$item['id']}'>$item[name]</a>";
            }
            echo implode(' > ', $tier['crumbs']);
            ?>
        </div>
        <div class="actions">
            <span> <?=implode('-', $tier['key']).' : '.implode('-', $tier['name'])?> </span>
            <a href='#new' class="btn green" id="btn-new" data-toggle="modal"><i class="fa fa-plus"></i> 新增</a>
        </div>
    </div>
    <div class="portlet-body portlet-compact">
        <div class="table-scrollable">
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Key</th>
                    <th>Name</th>
                    <th>Time</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($this->list as $item) { ?>
                    <tr>
                        <td><?= $item['id'] ?></td>
                        <td>
                            <a href="./origin?oid=<?= $item['id'] ?>">
                                <?= $item['key'] ?>
                            </a>
                        </td>
                        <td>
                            <a data-type="text" data-pk="1" data-original-title="Name"
                               data-url="./origin-name-<?= $item['id'] ?>"
                               class="origin-name editable editable-click">
                                <?= $item['name'] ?> </a>
                        </td>
                        <td><?= $item['tms_create'] ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade in" id="new" tabindex="-1" role="new" aria-hidden="false" ><div class="modal-backdrop fade in"></div>
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="./origin-new" class="form-horizontal" id="form">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">新建来源</h4>
                </div>
                <div class="modal-body">
                    <div class="form-body">
                        <div class="form-group">
                            <label class=" col-md-3 control-label">KEY</label>
                            <div class="col-md-9">
                                <input type="text" name="key" class="form-control input-inline input-medium" value="<?=implode('-', $tier['key'])?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class=" col-md-3 control-label">NAME</label>
                            <div class="col-md-9">
                                <input type="text" name="name" class="form-control input-inline input-medium" value="<?=implode('-', $tier['name'])?>"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">取消</button>
                    <button type="button" class="btn blue" id="btn-form-submit">提交</button>
                </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>

<script type="text/javascript">
    $(function(){
        $('.origin-name').editable();
        $('#btn-form-submit').click(function(){
            $('#form').submit();
        });
    })
</script>

