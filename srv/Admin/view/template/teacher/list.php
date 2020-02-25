<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">讲师列表</div>
        <div class="actions">
            <div class="input-group" style="width:300px">
                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                <input name="email" class="form-control" placeholder="Invite User"/>
                <span class="input-group-btn" id="invite-teacher">
                    <button type="submit" class="btn btn-default">+</button>
                </span>
            </div>
        </div>
    </div>
    <div class="portlet-body portlet-compact">
        <div class="table-scrollable">
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <td>TUID</td>
                    <td>TUSN</td>
                    <td>昵称</td>
                    <td>状态</td>
                    <td>操作</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($this->list as $item) { ?>
                    <tr>
                        <td><?= $item['tuid'] ?></td>
                        <td><?= $item['profile']['sn'] ?></td>
                        <td><a href="./detail?tuid=<?= $item['tuid'] ?>"><?= $item['profile']['name'] ?></a></td>
                        <td>
                            <a data-type="select2" data-pk="1" data-name='<?=$item['profile']['sn']?>' data-value="<?=$item['i_status']?>"
                               data-url="./list-status.api"
                               data-original-title="Select groups"
                               class="step editable editable-click"
                               style="background-color: rgba(0, 0, 0, 0);">
                                <?= $this->status_map[$item['i_status']] ?>
                            </a>
                        </td>
                        <td>
                            <a href="./certificate-detail?tuid=<?= $item['tuid'] ?>">认证</a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
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
    $(function(){
        var steps = [];
        $.each(<?=json_encode($this->status_map)?>, function(k, v) {
            steps.push({id: k, text: v})
        });
        console.log(steps);
        $('.step').editable({
            inputclass: 'form-control input-medium',
            source: steps
        });
        $('#invite-teacher').click(function(){
            var email = $(this).prev('input[name=email]').val();
            $.post('./list-invite.api', {
                email: email
            }, function(res) {
                if (res.error == 0) {
                    toastr['success']('Invitation send to ' + email, 'Success');
                } else  {
                    toastr['warning']('Failed to send invitation to ' + email, "Failure");
                }
            })
        });

    })

</script>

