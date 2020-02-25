<?php
namespace Admin;
$query = $this->query;
$typeDict = [
    'coupon' => '折扣券(共同承担)',
    'voucher' => '抵用券(平台承担)',
    'haggle' => '砍价券(平台承担)',
    'reward' => '奖励券(平台承担)',
    'audition' => '试听券(限时试听)',
];
?>

<div class="page-bar">
    <form class="form-inline">
        <?= wdgtPicker::dateRange($query->dateStart, $query->dateEnd) ?>
        <?= wdgtForm::input('PSN', 'psn', $query->psn)?>
        <?= wdgtForm::input('TSN', 'tsn', $query->tsn, '课程或系列SN')?>
        <?= wdgtPicker::fields('用户', [
            'name' => 'userField',
            'value' => $query->userField,
            'options' => ['id' => 'ID', 'sn' => 'SN', 'name' => '名',]
        ],
            ['name' => 'userValue', 'value' => $query->userValue]
        ) ?>
        <?= wdgtForm::submit() ?>
    </form>
</div>

<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">优惠规则</div>
        <div class="actions">
            <a href="#" class="btn btn-default btn-release" data-mode="server" data-toggle="modal" data-target="#myModal">创建优惠券</a>
        </div>
    </div>
    <div class="portlet-body portlet-compact">
        <div>
            <div class="datatable-hide-search">
                <table id="table-promote-rule" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>PSN</th>
                        <th>备注</th>
                        <th>类型</th>
                        <th>状态</th>
                        <th>发行</th>
                        <th>来源</th>
                        <th>折扣</th>
                        <th>佣金</th>
                        <th>过期</th>
                        <th>创建时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">创建优惠券</h4>
            </div>
            <div class="modal-body">
                <form>

                    <div class="form-group">
                        <label for="recipient-name" class="control-label">类型:</label>
                        <select class="form-control" id="type">
                            <?php foreach ($this->type_map as $type) {
                                echo '<option value="'.$type.'">' . $typeDict[$type] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">*发行者UID/uid:</label>
                        <input type="text" class="form-control" id="uid">
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">渠道ID/oid:</label>
                        <input type="text" class="form-control" id="oid">
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">课程SN/tsn:</label>
                        <input type="text" class="form-control" id="tsn">
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">折扣(分)/discount:</label>
                        <input type="text" class="form-control" id="discount">
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">佣金(分)/commission:</label>
                        <input type="text" class="form-control" id="commission">
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">讲师分成(分)/payoff:</label>
                        <input type="text" class="form-control" id="payoff">
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">数量/quantity:</label>
                        <input type="text" class="form-control" id="quantity">
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">过期时间/expire:</label>
                        <input type="text" class="form-control" id="expire">
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">领取后有效期(秒)/duration:</label>
                        <input type="text" class="form-control" id="duration" placeholder="默认三天">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" id="send">创建</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">创建临时券</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">*规则码/psn:</label>
                        <input type="text" class="form-control" id="psn">
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">可领取次数/quantity:</label>
                        <input type="text" class="form-control" id="quantity_">
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">优惠券张数/batch:</label>
                        <input type="text" class="form-control" id="batch">
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">过期时间/expire:</label>
                        <input type="text" class="form-control" id="expire_">
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">prefix:</label>
                        <input type="text" class="form-control" id="prefix">
                    </div>

                    <div class="control-label" id="quota-text">
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" id="send-quota">创建</button>
            </div>

        </div>
    </div>
</div>
<script>
    $('#myModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var modal = $(this);
    });
    $('#myModal2').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var modal = $(this);
        modal.find('#psn').val(button.data('psn'))
    });
    $('#send').click(function(){
        document.getElementById("uid").style.border = '';
        if($("#uid").val() == '') {
            document.getElementById("uid").style.border = "1px solid red";
            return false;
        }
        $.post('./promote-create', {
            type:$("#type").val(),
            uid: $("#uid").val(),
            tsn:$("#tsn").val(),
            oid:$("#oid").val(),
            discount:$("#discount").val(),
            commission:$("#commission").val(),
            payoff:$("#payoff").val(),
            quantity:$("#quantity").val(),
            expire:$("#expire").val(),
            duration:$("#duration").val(),
        }, function (res) {
            if (res.error == '0') {
                toastr['success']('创建成功');
                location.reload();
            } else {
                toastr['warning']('创建失败')
            }
        })
    });
    $('#send-quota').click(function(){
        document.getElementById("psn").style.border = '';
        if($("#psn").val() == '') {
            document.getElementById("psn").style.border = "1px solid red";
            return false;
        }
        $.post('./promote-setQuota', {
            psn:$("#psn").val(),
            quantity: $("#quantity_").val(),
            batch:$("#batch").val(),
            expire:$("#expire_").val(),
            prefix:$("#prefix").val()
        }, function (res) {
            if (res.error == '0') {
                toastr['success']('创建成功');
                $("#quota-text").html(res.data);
            } else {
                toastr['warning']('创建失败')
            }
        })
    });
</script>

<?= \view::js('table'); ?>
<script type="text/javascript">
    $(function () {
        var table = $('#table-promote-rule').DataTable({
            paging: true,
            info: true,
            lengthMenu: [20],
            ordering: false,
            "processing": true,
            "serverSide": true,
            "ajax": './promote-rule?<?=$this->query->toString()?>'
        });
        $('#filter').keyup(function () {
            table.search($(this).val()).draw();
        });
    });
</script>
