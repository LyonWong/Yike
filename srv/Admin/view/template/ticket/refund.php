<?php namespace Admin; $query = $this->query?>
<div class="page-bar">
    <form class="form-inline">
        <?= wdgtPicker::dateRange($query->dateStart, $query->dateEnd) ?>
        <?= wdgtPicker::fields('用户', [
            'name' => 'userField',
            'value' => $query->userField,
            'options' => ['id' => 'ID', 'sn' => 'SN', 'name' => '名',]
        ],
            ['name' => 'userValue', 'value' => $query->userValue]
        ) ?>
        <?= wdgtForm::select('状态', 'status', $query->status, array_merge($this->status_map,['*']))?>
        <?= wdgtForm::submit() ?>
    </form>
</div>
<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">退款申诉</div>
        <div class="actions hide">
            <div class="input-group" style="width:300px">
                <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                <input id="filter" class="form-control" placeholder="Filter"/>
            </div>
        </div>
    </div>
    <div class="portlet-body portlet-compact">
        <div class="datatable-hide-search">
            <table id="refund-table" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>用户</th>
                    <th>相关课程课程</th>
                    <th>申请理由</th>
                    <th>订单金额</th>
                    <th>提交申请时间</th>
                    <th>讲师</th>
                    <th >讲师拒绝理由</th>
                    <th>老师处理时间</th>
                    <th>申诉内容</th>
                    <th>提交申诉时间</th>
                    <th>后台理由</th>
                    <th>后台处理时间</th>
                    <th>状态</th>
                    <th>处理</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= \view::js('table'); ?>
<script type="text/javascript">

    $(function () {
        var table = $('#refund-table').DataTable({
            paging: true,
            info: true,
            lengthMenu: [20],
            ordering: false,
            "processing": true,
            "serverSide": true,
            "ajax": './refund-data?<?=$this->query->toString()?>'
        });
        $('#filter').keyup(function () {
            table.search($(this).val()).draw();
        });
    });
    function operate(operate, id) {
        remark = '';
        if(operate == -1) {
            remark = prompt('拒绝理由');
        }
        if(remark == null) {
            return false;
        }
        $.post('./refund-operate', {
            operate: operate,
            ticket_id: id,
            remark:remark

        }, function () {
            location.reload()
        })
    }
</script>




