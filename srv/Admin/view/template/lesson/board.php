<?php namespace Admin;
/**
 * @var unitQueryBoard
 */

$query = $this->query;
?>
<div class="page-bar">
    <form class="form-inline" role="form">
        <?= wdgtPicker::dateRange($query->dateStart, $query->dateEnd) ?>
        <?= wdgtPicker::fields('用户', [
            'name' => 'userField',
            'value' => $query->userField,
            'options' => ['id' => 'ID', 'sn' => 'SN', 'name' => '名',]
        ],
            ['name' => 'userValue', 'value' => $query->userValue]
        ) ?>
        <?= wdgtPicker::fields('课程', [
            'name' => 'lessonField',
            'value' => $query->lessonField,
            'options' => ['id' => 'ID', 'sn' => 'SN', 'name' => '名']
        ],
            ['name' => 'lessonValue', 'value' => $query->lessonValue]
        )?>
        <?= wdgtForm::select('类型', 'type', $query->type, array_merge($this->type_map,['*']))?>
        <?= wdgtForm::submit() ?>
    </form>
</div>
<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">留言板列表</div>
        <div class="actions">
            <div class="input-group" style="width:300px">
                <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                <input id="filter" class="form-control" placeholder="Filter"/>
            </div>
        </div>
    </div>
    <div class="portlet-body portlet-compact">
        <div class="datatable-hide-search">
            <table class="table table-bordered table-hover" id="table-order">
                <thead>
                <tr>
                    <td>Id</td>
                    <td>所属课程</td>
                    <td>用户</td>
                    <td>内容</td>
                    <td>点赞数</td>
                    <td>权重</td>
                    <td>回复留言Id</td>
                    <td>子留言Id总数</td>
                    <td>创建时间</td>
                    <td>操作</td>

                </tr>
                </thead>
                <tbody>
                <?php foreach ($this->list as $item) { ?>
                    <tr>
                        <td><?= $item['id'] ?></td>
                        <td>
                            <a href="/lesson/detail?lesson_sn=<?=$item['lesson']['sn']?>">
                                <?= $item['lesson']['title'] ?>
                            </a>
                        </td>
                        <td>
                            <a href="/user/detail?uid=<?=$item['uid']?>">
                                <?= $item['uname'] ?>
                            </a>
                        </td>
                        <td><?= $item['message']['text'].'<br>' ?>

                            <?php if(isset($item['message']['image'])){foreach ($item['message']['image'] as $url) {$fullUrl = \view::upload($url);echo '<a target="_blank" href="'.$fullUrl.'">'.$url.'</a>'.'<br>';} }?></td>
                        <td><?= $item['stats']['liked'] ?? 0 ?></td>
                        <td><?= $item['weight'] ?? 0 ?></td>
                        <td><?= $item['_id'] ?? ''?></td>
                        <td><?= $item['count_id'] ?? 0?></td>
                        <td><?= $item['tms_create'] ?></td>
                        <td>
                            <?php if(!$item['isDelete']) {?>
                            <input type="button" value="删除" onclick="deleteBoard('<?=$item['id']?>')"/>
                            <?php }?>
                            <?php if($item['isDelete']) {?>
                                已删除
                            <?php }?>

                        </td>
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
        Table.filter('#table-order',{
            paging: true,
            lengthMenu: [20],
            info: true
        })
    });

    function deleteBoard(boardId) {
        $.post('./board-delete', {
            boardId: boardId,
            reason: 'sadsadsa'
        }, function (res) {
            if (res.error == 0) {
                alert('删除成功');
                location.reload()
            } else {
                alert('删除失败');
            }
        })
    }
</script>

