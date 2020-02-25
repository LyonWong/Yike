<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">课程列表</div>
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
                <table id="lesson-table" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <td>ID</td>
                        <td>SN</td>
                        <td>标题</td>
                        <td>开课时间</td>
                        <td>讲师</td>
                        <td>形式</td>
                        <td>是否公开</td>
                        <td>价格</td>
                        <td>阶段</td>
                        <td>创建时间</td>
                        <td></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($this->list as $item) { ?>
                        <tr>
                            <td width="5%"> <?= $item['id'] ?> </td>
                            <td width="10%"><?= $item['sn'] ?> </td>
                            <td width="20%">
                                <a href="./detail?lesson_sn=<?=$item['sn']?>">
                                    <?= $item['title'] ?>
                                </a>
                            </td>
                            <td>
                                <a href="/teacher/detail?tusn=<?=$item['teacher']['sn']?>">
                                    <?= $item['teacher']['name'] ?>
                                </a>
                            </td>
                            <td><?= $this->form_map[$item['i_form']] ?></td>
                            <td><?= $item['isPublic']? '公开':'隐身' ?></td>
                            <td>￥<?= $item['price'] ?></td>
                            <td>
                                <a data-type="select2" data-pk="1" data-name='<?=$item['sn']?>' data-value="<?=$item['i_step']?>"
                                   data-url="./list-step.api"
                                   data-original-title="Select groups"
                                   class="step editable editable-click"
                                   style="background-color: rgba(0, 0, 0, 0);">
                                     <?= $this->step_map[$item['i_step']] ?>
                                </a>
                            </td>
                            <td>
                                <a href="./online?lesson_sn=<?=$item['sn']?>" target="_blank">
                                    在线
                                </a>
                                <a href="./detail-inspect?lesson_sn=<?=$item['sn']?>" target="_blank">
                                    课堂
                                </a>
                                <a href="./share?lesson_sn=<?=$item['sn']?>" target="_blank">
                                    邀请
                                </a>
                                <a href="/lesson/log-teacher?lesson_sn=<?=$item['sn']?>" target="_blank">
                                    操作日志
                                </a>
                                <a href="/lesson/log-student?lessonField=sn&lessonValue=<?=$item['sn']?>" target="_blank">
                                    学员日志
                                </a>
<!--                                <a href="/lesson/detail-conf?lesson_sn=--><?//=$item['sn']?><!--">配置</a>-->
                                <a href="/lesson/prepare?lesson_sn=<?=$item['sn']?>" target="_blank">备课内容</a>
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
        var steps = [];
        $.each(<?=json_encode($this->step_map)?>, function(k, v) {
            steps.push({id: k, text: v})
        });
        console.log(steps);
        $('.step').editable({
            inputclass: 'form-control input-medium',
            source: steps
        })
    });
</script>


