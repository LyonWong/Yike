<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">反馈列表</div>
        <div class="actions">
        </div>
    </div>
    <div class="portlet-body portlet-compact">
        <div class="table-scrollable">
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <td>ID</td>
                    <td>用户</td>
                    <td>描述</td>
                    <td>图片</td>
                    <td>状态</td>
                    <td>备注</td>
                    <td>创建时间</td>
                    <td>更新时间</td>

                </tr>
                </thead>
                <tbody>
                <?php foreach ($this->list as $item) { ?>
                    <tr>
                        <td><?= $item['id'] ?></td>
                        <td><?= $item['name'] ?></td>
                        <td><?= $item['text'] ?></td>
                        <td><?php if ($item['image']) { ?>
                                <img src="<?= view::upload($item['image']) . '?imageView2/0/w/400' ?>">
                            <?php } ?>
                        </td>
                        <td><?= $this->status_map[$item['i_status']] ?></td>
                        <td><?= $item['remark'] ?></td>
                        <td><?= $item['tms_create'] ?></td>
                        <td><?= $item['tms_update'] ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

