<?php namespace Admin;
$lesson = $this->lesson;
?>

<div class="page-bar">
    <form class="form-inline">
        <?= wdgtForm::input('课程SN', 'lesson_sn', $this->lesson['sn'] ?? null) ?>
        <?= wdgtForm::submit() ?>
    </form>
</div>

<?php if ($this->lesson) { ?>
    <div class="portlet box blue">
        <div class="portlet-title">
            <div class="caption">
                <div class="caption">课程信息</div>
            </div>
        </div>
        <div class="portlet-body form portlet-empty">
            <div class="datatable-hide-search">
                <table class="table table-bordered table-hover" id="table-lesson-info">
                    <tbody>
                    <tr>
                        <th>标题</th>
                        <td><?= $lesson['title'] ?></td>
                    </tr>
                    <tr>
                        <th>讲师</th>
                        <td><?= $lesson['teacher']['name'] ?></td>
                    </tr>
                    <tr>
                        <th>价格</th>
                        <td><?= $lesson['price'] ?></td>
                    </tr>
                    <tr>
                        <th>报名人数</th>
                        <td><?= $lesson['stats']['lesson.enroll.unique'] ?></td>
                    </tr>
                    <tr>
                        <th>听课人数</th>
                        <td><?= $lesson['stats']['lesson.access.unique'] ?></td>
                    </tr>
                    <tr>
                        <th>课程收入</th>
                        <td><?= $lesson['stats']['lesson.income.sum'] ?> </td>
                    </tr>
                    <tr>
                        <th>
                            返现条件
                        </th>
                        <td>
                            <form>
                                <input hidden name="lesson_sn" value="<?= $lesson['sn'] ?>"/>
                                报名截止日期<input name="deadline" value="<?= $this->deadline?>" placeholder="Y-m-d H:i:s"/>
                                返现比例<input id="percent" name="percent" value="<?= $this->percent ?>"/>%
                                <button id="btn-review" class="btn green">预览</button>
                            </form>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php } ?>

<?php if ($this->preview) { ?>
    <div class="portlet box blue">
        <div class="portlet-title">
            <div class="caption">
                <div class="caption">返现确认
                </div>
            </div>
            <div class="actions">
                <div class="input-group" style="width:300px">
                    <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                    <input id="filter" class="form-control" placeholder="Filter"/>
                </div>
            </div>
        </div>
        <div class="portlet-body form portlet-empty">
            <div class="note note-success">
                总人数: <?=$this->preview['count']?>　总金额: ￥<?=$this->preview['total']?>
                信息：<input id="info" value="《<?=$lesson['title']?>》" class="input-large"/>
                <a class="btn green" id="btn-rebate">确认发放</a>
            </div>
            <div class="datatable-hide-search">
                <table class="table table-bordered table-hover" id="table-lesson-rebate">
                    <thead>
                    <tr>
                        <th>订单SN</th>
                        <th>用户SN</th>
                        <th>用户名</th>
                        <th>返现金额</th>
                        <th>订单金额</th>
                        <th>支付金额</th>
                        <th>讲师分成</th>
                        <th>订单状态</th>
                        <th>下单时间</th>
                        <td>更新时间</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($this->preview['list'] as $row) { ?>
                        <tr>
                            <td><?= $row['sn'] ?></td>
                            <td><?= $row['user']['sn'] ?></td>
                            <td><?= $row['user']['name'] ?></td>
                            <td><span class="red"><?= $row['rebate_amount'] ?></span></td>
                            <td><?= $row['order_amount'] ?></td>
                            <td><?= $row['paid_amount'] ?></td>
                            <td><?= $row['payoff_amount'] ?></td>
                            <td><?= wdgtLang::dict($row['status']) ?></td>
                            <td><?= $row['tms_create'] ?></td>
                            <td><?= $row['tms_update'] ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php } ?>

<?= \view::js('table'); ?>
<script type="text/javascript">
    $(function () {
        Table.filter('#table-lesson-rebate', {
            paging: true,
            lengthMenu: [20],
            info: true
        });
        $('#btn-rebate').click(function () {
            $.post('./rebate-lesson', {
                lesson_sn: '<?=$lesson['sn']?>',
                percent: '<?=$this->percent?>',
                deadline: '<?=$this->deadline?>',
                info: $('#info').val()
            }, function (res) {
                if (res.error == '0') {
                    toastr['success']('向' + res.data.count + '名学员发放返现共计' + res.data.total);
                } else {
                    toastr['error'](res.message);
                }
            })
        })
    })
</script>


