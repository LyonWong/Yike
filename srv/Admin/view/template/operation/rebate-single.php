<?php namespace Admin;
$user = $this->user;
?>

<div class="page-bar">
    <form class="form-inline">
        <?= wdgtForm::input('用户SN', 'usn', $user['sn'] ?? null) ?>
        <?= wdgtForm::submit() ?>
    </form>
</div>

<?php if ($this->user) { ?>
    <div class="portlet box blue">
        <div class="portlet-title">
            <div class="caption">
                <div class="caption">信息确认</div>
            </div>
        </div>
        <div class="portlet-body form portlet-empty">
            <div class="datatable-hide-search">
                <table class="table table-bordered table-hover" id="table-lesson-rebate">
                    <tbody>
                    <tr>
                        <th>用户</th>
                        <td><?= $user['name'] ?></td>
                    </tr>
                    <tr>
                        <th>返现金额</th>
                        <td><input id="amount" type="number"/> 元</td>
                    </tr>
                    <tr>
                        <th>返现说明</th>
                        <td><input id="info"/></td>
                    </tr>
                    <tr>
                        <th>确认返现</th>
                        <td>
                            <button id="btn-rebate" class="btn green">发放返现</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(function () {
            $('#btn-rebate').click(function () {
                $.post('./rebate-single', {
                    usn: '<?=$user['sn']?>',
                    amount: $('#amount').val(),
                    info: $('#info').val()
                }, function (res) {
                    if (res.error == '0') {
                        toastr['success']('发放成功');
                    } else {
                        toastr['error'](res.message);
                    }
                })
            })
        })
    </script>
<?php } ?>
