<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">《<?= $this->lesson['title'] ?>》 当前在线: <?= $this->count ?></div>
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
                        <td>用户</td>
                        <td>进入时间</td>
                        <td></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($this->list as $item) { ?>
                        <tr>
                            <td>
                                <a href="/user/detail?uid=<?= $item['uid'] ?>">
                                    <?= $item['user']['name'] ?>
                                </a>
                            </td>
                            <td>
                                <?= $item['tms'] ?>
                            </td>
                            <td>
                                <a id="forbidSendMsg-<?= $item['id'] ?>"
                                   onclick="forbidSendMsg('<?= $this->lesson['sn'] ?>','<?= $item['user']['sn'] ?>')">禁言</a>
                                <a id="deleteGroupMember-<?= $item['id'] ?>"
                                   onclick="deleteGroupMember('<?= $this->lesson['sn'] ?>',<?= $item['user']['sn'] ?>')">踢出</a>
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
<script type="text/javascript">
    $(function () {
        var table = $('#lesson-table').DataTable();
        $('#filter').keyup(function () {
            table.search($(this).val()).draw();
        });
    });
    function forbidSendMsg(lesson_sn,usn) {
        $.post('./lesson-forbidSendMsg', {
            lesson_sn: lesson_sn,
            usn: usn
        }, function () {
            location.reload()
        })
    }
    function deleteGroupMember(lesson_sn,usn) {
        $.post('./lesson-deleteGroupMember', {
            lesson_sn: lesson_sn,
            usn: usn
        }, function () {
            location.reload()
        })
    }

</script>



