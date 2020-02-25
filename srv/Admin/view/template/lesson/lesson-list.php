<?php namespace Admin;
/**
 * @var unitQueryLessonList
 */
$query = $this->query;
?>
<div class="page-bar">
    <form class="form-inline" role="form">
        <?= wdgtPicker::dateRange($query->dateStart, $query->dateEnd) ?>
        <?= wdgtPicker::fields('讲师', [
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
        <?= wdgtPicker::fields('所属系列', [
            'name' => 'seriesField',
            'value' => $query->seriesField,
            'options' => ['id' => 'ID', 'sn' => 'SN', 'name' => '名']
        ],

            ['name' => 'seriesValue', 'value' => $query->seriesValue]
        )?>
        <?= wdgtForm::select('阶段', 'step', $query->step, array_merge($this->step_map,['*']))?>
        <?= wdgtForm::submit() ?>
    </form>
</div>
<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">课程列表</div>


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
                        <td>所属系列</td>
                        <td>开课时间</td>
                        <td>讲师</td>
                        <td>形式</td>
                        <td>是否公开</td>
                        <td>价格</td>
                        <td>阶段</td>
                        <td>操作</td>
                        <td>创建时间</td>
                    </tr>
                    </thead>
                    <tbody>
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

        var table = $('#lesson-table').DataTable({
            paging: true,
            info: true,
            lengthMenu: [20],
            ordering: false,
            "processing": true,
            "serverSide": true,
            "ajax": '/lesson/list-data?<?=$this->query->toString()?>'
        });
        $('#filter').keyup(function () {
            table.search($(this).val()).draw();
        });
    });

    function edit() {
        var steps = [];
        $.each(<?php $maps = $this->step_map;foreach( $maps as &$map) {$map=wdgtLang::dict($map);} echo  json_encode($maps)?>, function(k, v) {
            steps.push({id: k, text: v})
        });
        console.log(steps);
        $('.step').editable({
            inputclass: 'form-control input-medium',
            source: steps
        });
    }
</script>



