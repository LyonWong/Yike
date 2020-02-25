<?php namespace Admin;?>
<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">课程操作日志</div>
        <div class="actions">
            <div class="input-group" style="width:300px">
                <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                <input id="filter" class="form-control" placeholder="Filter"/>
            </div>
        </div>
    </div>
    <div class="portlet-body portlet-compact">
        <div>
            <div class="portlet-body form portlet-empty">
                <form role="form" class="form-horizontal">
            <div class="form-body">
            <div class="form-group">
                <label class="col-md-2 control-label">课程</label>
                <div class="col-md-5">
                    <span class="form-control"> <a href="/lesson/detail?lesson_sn=<?=$this->lesson['sn']?>"><?= $this->lesson['title']?> </a> </span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">讲师</label>
                <div class="col-md-5">
                    <span class="form-control"> <a href="/teacher/detail?tusn=<?=$this->lesson['teacher']['sn']?>"><?= $this->lesson['teacher']['name']?> </a> </span>
                </div>
            </div>
            </div>
                </form>
            </div>
            <div class="datatable-hide-search">
                <table id="lesson-table" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <td>操作</td>
                        <td>时间</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($this->list as $item) { ?>
                        <tr>
                            <td> <?= wdgtLang::dict($item['i_event_map']) ?> </td>
<!--                            <td> --><?//= $item['i_event_map'] ?><!-- </td>-->
                            <td><?= $item['tms'] ?> </td>
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


